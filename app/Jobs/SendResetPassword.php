<?php

namespace App\Jobs;

use App\Mail\ResetPasswordEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendResetPassword implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $recipient, $email;

    public function __construct($recipient, $email)
    {
        $this->recipient = $recipient;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->recipient != '' && $this->recipient != null)
        {
            Mail::to($this->recipient)->send(new ResetPasswordEmail($this->email));
        }
    }
}
