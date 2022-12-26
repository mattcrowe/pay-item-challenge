<?php

namespace Tests\Unit\Services;

use Tests\TestCase;

use App\Models\Business;
use App\Models\PayItem;
use App\Models\User;
use App\Helpers\PayItemHelper;
use App\Services\PayItemService;
use App\Integrations\AcmeIntegration\FakeCommunicator;

use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class PayItemServiceTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_syncForBusiness_error401()
    {   
        Config::set('services.acme.key', 'some-invalid-key');

        Log::shouldReceive('alert')
            ->once()
            ->with(FakeCommunicator::ERROR_MESSAGES[401]);

        $business = Business::factory()->create();
        $result = PayItemService::syncForBusiness($business);
        
        $this->assertFalse($result['success']);
    }

    public function test_syncForBusiness_error404()
    {   
        Log::shouldReceive('critical')
            ->once()
            ->with(FakeCommunicator::ERROR_MESSAGES[404]);

        $business = Business::factory()->invalidExternalId()->create();
        $result = PayItemService::syncForBusiness($business);
        
        $this->assertFalse($result['success']);
    }

    public function test_syncForBusiness_noUsers()
    {           
        $business = Business::factory()->create();        

        $this->assertDatabaseEmpty('pay_items');
        
        PayItemService::syncForBusiness($business);
        
        $this->assertDatabaseEmpty('pay_items');
    }

    public function test_syncForBusiness_addsPayItemForUser()
    {           
        $business = Business::factory()->create();
        $user = User::factory()
                ->hasAttached([$business])
                ->create(['external_id' => 'pay-item-user-external-id-10']);

        $search_attributes = [
            'business_id' => $business->id,
            'user_id' => $user->id,
        ];

        $this->assertDatabaseMissing('pay_items', $search_attributes);
        
        PayItemService::syncForBusiness($business);
        
        $this->assertDatabaseHas('pay_items', $search_attributes);
    }

    public function test_syncForBusiness_updatesPayItem()
    {           
        $business = Business::factory()->create();
        $user = User::factory()
                ->hasAttached([$business])
                ->create(['external_id' => 'pay-item-user-external-id-10']);

        $payItemSource = FakeCommunicator::mapPayItem(FakeCommunicator::PAY_ITEMS[0]);

        $payItem = PayItem::factory()->create([
            'external_id' => $payItemSource['external_id'],
            'business_id' => $business->id,
            'user_id' => $user->id,
            'amount_paid_in_cents' => 1,
            'time_worked' => 1,
            'hourly_rate_in_cents' => 1,
            'paid_at' => '2022-10-11',
        ]);

        $expected_updated_attributes = [
            'amount_paid_in_cents' => PayItemHelper::calcAmountToPayInCents($payItemSource['time_worked'], $payItemSource['hourly_rate_in_cents'], $business->deduction),
            'time_worked' => $payItemSource['time_worked'],
            'hourly_rate_in_cents' => $payItemSource['hourly_rate_in_cents'],
            'paid_at' => $payItemSource['paid_at'],
        ];
        
        PayItemService::syncForBusiness($business);

        $actual_updated_attributes = Arr::only(
            $payItem->refresh()->attributesToArray(),
            array_keys($expected_updated_attributes)
        );
        
        $this->assertEquals($expected_updated_attributes, $actual_updated_attributes);
    }

    public function test_syncForBusiness_deleteMissingPayItem()
    {           
        $business = Business::factory()->create();
        $user = User::factory()
                ->hasAttached([$business])
                ->create(['external_id' => 'pay-item-user-external-id-10']);
                
        $payItem = PayItem::factory()->create([
            'business_id' => $business->id,
            'user_id' => $user->id,
        ]);

        $search_attributes = [
            'business_id' => $business->id,
            'external_id' => $payItem->external_id,
        ];
        
        $this->assertDatabaseHas('pay_items', $search_attributes);
        
        PayItemService::syncForBusiness($business);
        
        $this->assertDatabaseMissing('pay_items', $search_attributes);
    }
    
    public function test_syncForBusiness_rollbackOnError()
    {            
        $business = Business::factory()->create();

        $mockCommunicator = Mockery::mock(FakeCommunicator::class);
        $mockCommunicator->shouldReceive('fetchPayItemsForBusiness')->andThrow(new \Exception('oh dang!'));

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();
        DB::shouldNotReceive('commit');
        
        $result = PayItemService::syncForBusiness($business, $mockCommunicator);
        
        $this->assertFalse($result['success']);        
        $this->assertEquals($result['errorMessage'], 'oh dang!');        
    }
}
