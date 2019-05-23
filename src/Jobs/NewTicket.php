<?php

namespace Chernogolov\Fogcms\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Chernogolov\Fogcms\Exchange1C;

class NewTicket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        if(!isset($this->data['Ref_Key']) || strlen($this->data['Ref_Key'])<1 )
            Exchange1C::netTicket($this->data);
//        else
            //update_ticket;
    }
}
