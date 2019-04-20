<?php

namespace Chernogolov\Fogcms\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Chernogolov\Fogcms\ExchangeErc;

class LoadErcData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $account;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($account)
    {
        //
        $this->account = $account;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        ExchangeErc::getDevices($this->account);
        ExchangeErc::getMeterValues($this->account);
    }
}
