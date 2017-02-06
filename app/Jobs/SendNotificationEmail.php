<?php

namespace App\Jobs;

use App\Mail\TestMail;
use App\Propose;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendNotificationEmail implements ShouldQueue {
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $propose, $content, $recipients, $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($recipients, $email, Propose $propose)
    {
        $this->propose = $propose;
        $this->recipients = $recipients;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        $flow_status = $this->propose->flowStatus()->orderBy('id', 'desc')->first();
//        if($flow_status->status_code === 'VA')
//        {
//            $members = $this->propose->member()->get();
//            $recipients = [];
//            foreach($members as $key => $member)
//            {
//                dd($member);
//                $recipients[$key] = $member->lecturer()->first()->email;
//            }
//        }
        if ($this->recipients != '' && $this->recipients != null)
        {
            Mail::to($this->recipients)->send(new TestMail($this->email));
        }
    }
}
