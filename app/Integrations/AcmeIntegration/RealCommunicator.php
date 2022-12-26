<?php

namespace App\Integrations\AcmeIntegration;

use Illuminate\Support\Arr;

class RealCommunicator
{      
    const ENDPOINT = 'https://some-partner-website.com/clair-pay-item-sync/'; 

    const ERROR_MESSAGES = [
        401 => 'Invalid apiKey',
        404 => 'Invalid externalID',
    ];
    
    public function __construct(
        public string $apiKey, 
    ){}

    public function fetchPayItemsForBusiness($externalID, $page = 1)
    {     
        # build endpoint, ie ENDPOINT/{$externalID}?page={$page}
        # make curl request
        # return parsed results...
    }

    public static function mapPayItem($payItem) {
        return [
            'external_id' => $payItem['id'],
            'user_external_id' => $payItem['employeeId'],
            'time_worked' => gmdate("H:i:s", $payItem['hoursWorked'] * 60 * 60),
            'hourly_rate_in_cents' => intval($payItem['payRate'] * 100),
            'paid_at' => $payItem['date'],
        ];
    }

    public static function mapPayItems($payItems) {
        return Arr::map($payItems, function ($payItem) {
            return self::mapPayItem($payItem);
        });
    }

    protected function _buildSuccessfulResponse($payItems, $isLastPage){
        return [
            'success' => true,
            'code' => 200,
            'payItems' => self::mapPayItems($payItems),
            'isLastPage' => $isLastPage,
            'rawResponse' => ['payItems' => $payItems],
        ];
    }

    protected function _buildFailedResponse($code){
        return [
            'success' => false,
            'code' => $code,
            'errorMessage' => self::ERROR_MESSAGES[$code],
        ];
    }  
}