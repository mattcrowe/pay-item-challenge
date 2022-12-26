<?php

namespace App\Jobs;

use App\Models\Business;
use App\Services\PayItemService;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncBusinessPayItemsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The business instance.
     *
     * @var \App\Models\Business
     */
    public $business;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Business $business)
    {
        $this->business = $business;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $result = PayItemService::syncForBusiness($this->business);

        if ($result['success'] != true) {
            $this->fail(new \Exception($result['errorMessage'] ?: 'failed SyncBusinessPayItemsJob'));
        }
    }
}
