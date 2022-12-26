<?php

namespace Tests\Unit\Integrations\AcmeIntegration;

use PHPUnit\Framework\TestCase;

use App\Integrations\AcmeIntegration\FakeCommunicator;

class FakeCommunicatorTest extends TestCase
{    
    /**
     * @covers \App\Integrations\AcmeIntegration\FakeCommunicator::fetchPayItemsForBusiness
     */
    public function test_missingApiKey()
    {
        $communicator = new FakeCommunicator(apiKey: "");
        $result = $communicator->fetchPayItemsForBusiness(FakeCommunicator::VALID_EXTERNAL_ID);

        $this->assertEquals($result, [
            'success' => false,
            'code' => 401,
            'errorMessage' => FakeCommunicator::ERROR_MESSAGES[401],
        ]);
    }

    /**
     * @covers \App\Integrations\AcmeIntegration\FakeCommunicator::fetchPayItemsForBusiness
     */
    public function test_invalidApiKey()
    {
        $communicator = new FakeCommunicator(apiKey: "invalid key");
        $result = $communicator->fetchPayItemsForBusiness(FakeCommunicator::VALID_EXTERNAL_ID);

        $this->assertEquals($result, [
            'success' => false,
            'code' => 401,
            'errorMessage' => FakeCommunicator::ERROR_MESSAGES[401],
        ]);
    }

    /**
     * @covers \App\Integrations\AcmeIntegration\FakeCommunicator::fetchPayItemsForBusiness
     */
    public function test_invalidExternalId()
    {
        $communicator = new FakeCommunicator(apiKey: FakeCommunicator::VALID_API_KEY);
        $result = $communicator->fetchPayItemsForBusiness("invalid external id");

        $this->assertEquals($result, [
            'success' => false,
            'code' => 404,
            'errorMessage' => FakeCommunicator::ERROR_MESSAGES[404],
        ]);
    }

    /**
     * @covers \App\Integrations\AcmeIntegration\FakeCommunicator::fetchPayItemsForBusiness
     */
    public function test_validExternalIdPage1()
    {
        $communicator = new FakeCommunicator(apiKey: FakeCommunicator::VALID_API_KEY);
        $result = $communicator->fetchPayItemsForBusiness(FakeCommunicator::VALID_EXTERNAL_ID);
        
        $expectedRawPayItems = [FakeCommunicator::PAY_ITEMS[0], FakeCommunicator::PAY_ITEMS[1]];
        $expectedMappedPayItems = FakeCommunicator::mapPayItems($expectedRawPayItems);        

        $this->assertEquals($result['success'], true);
        $this->assertEquals($result['code'], 200);
        $this->assertEquals($result['isLastPage'], false);
        $this->assertEquals($result['payItems'], $expectedMappedPayItems);
        $this->assertEquals($result['rawResponse']['payItems'], $expectedRawPayItems);
    }

    /**
     * @covers \App\Integrations\AcmeIntegration\FakeCommunicator::fetchPayItemsForBusiness
     */
    public function test_validExternalIdPage2()
    {
        $communicator = new FakeCommunicator(apiKey: FakeCommunicator::VALID_API_KEY);
        $result = $communicator->fetchPayItemsForBusiness(FakeCommunicator::VALID_EXTERNAL_ID, 2);

        $expectedRawPayItems = [FakeCommunicator::PAY_ITEMS[2], FakeCommunicator::PAY_ITEMS[3]];
        $expectedMappedPayItems = FakeCommunicator::mapPayItems($expectedRawPayItems);        

        $this->assertEquals($result['success'], true);
        $this->assertEquals($result['code'], 200);
        $this->assertEquals($result['isLastPage'], false);
        $this->assertEquals($result['payItems'], $expectedMappedPayItems);
        $this->assertEquals($result['rawResponse']['payItems'], $expectedRawPayItems);
    }

    /**
     * @covers \App\Integrations\AcmeIntegration\FakeCommunicator::fetchPayItemsForBusiness
     */
    public function test_validExternalIdPage3()
    {
        $communicator = new FakeCommunicator(apiKey: FakeCommunicator::VALID_API_KEY);
        $result = $communicator->fetchPayItemsForBusiness(FakeCommunicator::VALID_EXTERNAL_ID, 3);

        $expectedRawPayItems = [FakeCommunicator::PAY_ITEMS[4], FakeCommunicator::PAY_ITEMS[5]];
        $expectedMappedPayItems = FakeCommunicator::mapPayItems($expectedRawPayItems);        

        $this->assertEquals($result['success'], true);
        $this->assertEquals($result['code'], 200);
        $this->assertEquals($result['isLastPage'], true);
        $this->assertEquals($result['payItems'], $expectedMappedPayItems);
        $this->assertEquals($result['rawResponse']['payItems'], $expectedRawPayItems);
    }

    /**
     * @covers \App\Integrations\AcmeIntegration\FakeCommunicator::fetchPayItemsForBusiness
     */
    public function test_validExternalIdPageN()
    {
        $communicator = new FakeCommunicator(apiKey: FakeCommunicator::VALID_API_KEY);
        $result = $communicator->fetchPayItemsForBusiness(FakeCommunicator::VALID_EXTERNAL_ID, 4);

        $this->assertEquals($result['success'], true);
        $this->assertEquals($result['code'], 200);
        $this->assertEquals($result['payItems'], []);
        $this->assertEquals($result['isLastPage'], true);
    }
}
