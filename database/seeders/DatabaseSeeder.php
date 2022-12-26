<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\PayItem;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * php artisan db:seed
     *
     * @return void
     */
    public function run()
    {
        $valid_business = Business::factory()->create();        
        User::factory()->hasAttached([$valid_business])->create(['external_id' => 'pay-item-user-external-id-40']);
        
        Business::factory()->invalidExternalId()->create();        
    }
}
