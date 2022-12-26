<?php

namespace App\Console\Commands;

use App\Models\Business;
use App\Jobs\SyncBusinessPayItemsJob;

use Illuminate\Console\Command;

class SyncPayItemsForBusiness extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pay-items:sync-for-business {business_external_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sync pay-items for business';

    /**
     * Execute the console command.
     *
     * @return int
     */    
    public function handle()
    {
        $business_external_id = $this->argument('business_external_id');
        $business = Business::where('external_id', $business_external_id)->first();
        if (empty($business)) {
            return Command::FAILURE;
        }

        SyncBusinessPayItemsJob::dispatch($business);

        return Command::SUCCESS;
    }
}
