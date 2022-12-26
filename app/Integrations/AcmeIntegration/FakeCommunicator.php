<?php

namespace App\Integrations\AcmeIntegration;

class FakeCommunicator extends RealCommunicator
{
    const VALID_API_KEY = 'CLAIR-ABC-123';
    const VALID_EXTERNAL_ID = 'a-valid-fake-business-external-id';

    const PAY_ITEMS = [
        [
            'id' => 'pay-item-external-id-1',
            'employeeId' => 'pay-item-user-external-id-10',
            'hoursWorked' => 8.5,
            'payRate' => 12.5,
            'date' => '2021-10-18',
        ],
        [
            'id' => 'pay-item-external-id-2',
            'employeeId' => 'pay-item-user-external-id-20',
            'hoursWorked' => 10,
            'payRate' => 8,
            'date' => '2021-10-18'
        ],
        [
            'id' => 'pay-item-external-id-3',
            'employeeId' => 'pay-item-user-external-id-10',
            'hoursWorked' => 10,
            'payRate' => 12.5,
            'date' => '2021-10-19'
        ],
        [
            'id' => 'pay-item-external-id-4',
            'employeeId' => 'pay-item-user-external-id-20',
            'hoursWorked' => 10,
            'payRate' => 8,
            'date' => '2021-10-19'
        ],
        [
            'id' => 'pay-item-external-id-5',
            'employeeId' => 'pay-item-user-external-id-30',
            'hoursWorked' => 8,
            'payRate' => 9,
            'date' => '2021-10-20'
        ],
        [
            'id' => 'pay-item-external-id-6',
            'employeeId' => 'pay-item-user-external-id-40',
            'hoursWorked' => 8,
            'payRate' => 9,
            'date' => '2021-10-20'
        ],
    ];    
    
    public function fetchPayItemsForBusiness($externalID, $page = 1)
    {     
        if (empty($this->apiKey) || $this->apiKey != self::VALID_API_KEY) {
            return $this->_buildFailedResponse(401);
        }
        if ($externalID != self::VALID_EXTERNAL_ID) {
            return $this->_buildFailedResponse(404);
        }

        $isLastPage = true;
        $payItems = [];
        
        switch ($page) {
            case 1:
                $isLastPage = false;
                $payItems = [self::PAY_ITEMS[0], self::PAY_ITEMS[1]];
                break;
            case 2:
                $isLastPage = false;
                $payItems = [self::PAY_ITEMS[2], self::PAY_ITEMS[3]];
                break;
            case 3:
                $payItems = [self::PAY_ITEMS[4], self::PAY_ITEMS[5]];
                break;
        }

        return $this->_buildSuccessfulResponse($payItems, $isLastPage);
    }
}