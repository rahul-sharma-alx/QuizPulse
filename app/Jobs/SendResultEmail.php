<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Attempts;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class SendResultEmail implements ShouldQueue
{
    use Queueable, SerializesModels, Dispatchable, InteractsWithQueue;
    public $attempt;

    /**
     * Create a new job instance.
     */
    public function __construct(Attempts $attempts)
    {
        $this->attempt = $attempts;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = $this->attempt->user;
        $quiz = $this->attempt->quiz;

        // Send mail (make sure you have mail config set)
        Mail::to($user->email)->send(new SendResultEmail($this->attempt));
    }
}
