<?php

namespace App\Services;

use App\Models\Business;
use App\Models\User;
use App\Models\PayItem;
use App\Helpers\PayItemHelper;
use App\Integrations\AcmeIntegration as AcmeIntegration;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class PayItemService
 */
class PayItemService
{    

    /**
     * @param $value
     * @return mixed|string
     */
    public static function syncForBusiness(Business $business, $vendor = NULL)
    {                  
        $vendor = $vendor ?: self::_payItemVendor();

        DB::beginTransaction();

        try {
            $result = self::_syncForBusiness($business, $vendor);            
        } catch (\Exception $e) {
            $result = ['success' => false, 'errorMessage' => $e->getMessage()];
        }

        if ($result['success'] != true) {
            DB::rollBack();
            self::_logFailedResult($result);
            return $result;
        }

        DB::commit();

        return $result;
    }    

    private static function _syncForBusiness(Business $business, $vendor) {
        $processedExternalIDs = [];

        $page = 1;
        while ($page) {
            $result = $vendor->fetchPayItemsForBusiness($business->external_id, $page);
            
            if ($result['success'] != true) {
                return $result;
            }

            foreach($result['payItems'] as $row) {
                $user = User::where('external_id', $row['user_external_id'])
                            ->with('businesses')
                            ->whereHas('businesses', function ($query) use ($business) {
                                $query->where('business_user.business_id', $business->id);
                            })
                            ->first();

                if (!$user) {
                    continue;
                }          

                $findAttributes = [
                    'business_id' => $business->id, 
                    'user_id' => $user->id, 
                    'external_id' => $row['external_id'],
                ];

                $updateAttributes = [
                    'amount_paid_in_cents' => PayItemHelper::calcAmountToPayInCents($row['time_worked'], $row['hourly_rate_in_cents'], $business->deduction),
                    'time_worked' => $row['time_worked'],
                    'hourly_rate_in_cents' => $row['hourly_rate_in_cents'],
                    'paid_at' => $row['paid_at'],
                ];
                
                $payItem = PayItem::updateOrCreate($findAttributes, $updateAttributes);

                $processedExternalIDs[] = $payItem->external_id;
            }            

            if ($result['isLastPage'] == true) {
                break;
            }
            
            $page++;
        }

        $missingPayItems = PayItem::where(['business_id' => $business->id])->whereNotIn('external_id', $processedExternalIDs)->get();
        foreach($missingPayItems as $missingPayItem) {
            $missingPayItem->delete();
        }

        return $result;
    }  

    private static function _logFailedResult($result) {
        switch ($result['code'] ?? null) {
            case 401:
                Log::alert($result['errorMessage']);
                break;
            case 404:
                Log::critical($result['errorMessage']);
                break;
        };
    }

    private static function _payItemVendor() {
        $gateway = new AcmeIntegration\Gateway(
            apiKey: config('services.acme.key'), 
            strategy: config('services.acme.strategy'),
        );

        return $gateway->up();
    }
}