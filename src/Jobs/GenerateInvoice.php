<?php

namespace Chernogolov\Fogcms\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Chernogolov\Fogcms\ExchangeErc;

class GenerateInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $account;
    protected $month;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($account, $month)
    {
        //
        $this->account = $account;
        $this->month = $month;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        ExchangeErc::getInvoice($this->account, $this->month);
    }
}
