<?php

namespace App\Integrations\AcmeIntegration;

use App\Models\Business;

class Gateway
{       

  const FAKE = 'fake';
  const REAL = 'real';
  const INVALID_STRATEGY_MESSAGE = 'Invalid Strategy for AcmeIntegration';

  public function __construct(
    public string $apiKey, 
    public string $strategy,
  )
  {
      $this->apiKey = $apiKey;
      $this->strategy = $strategy;
  }

  public function up()
  {
    switch ($this->strategy) {
      case self::FAKE:
          return new FakeCommunicator(apiKey: $this->apiKey);
      case self::REAL:
          return new RealCommunicator(apiKey: $this->apiKey);
    }

    throw new \InvalidArgumentException(self::INVALID_STRATEGY_MESSAGE);
  }    
}