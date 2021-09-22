<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SendEmailServicosApiTGJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $mailData;

    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mailData = $this->mailData;

        Mail::send('Admin.emails.enviaprodutoapi', $mailData, function ($message) {
            $message
                ->from('noreply@atsportugal.com', 'Ats Travel - API service')
                ->to('sales@atravel.pt')
                ->cc('transfers@atravel.pt')
                // ->cc('henrique@oseubackoffice.com')
                // ->cc('verificaspam@amen.pt')
                ->subject('Services send to API Transfergest');
        });
    }
}
