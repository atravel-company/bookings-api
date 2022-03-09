<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SendMailNovaReservaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $mailData;
    private $pedido;
    private $prod;

    public function __construct($mailData, $pedido, $prod, $destinationEmail = null)
    {
        $this->mailData = $mailData;
        $this->pedido = $pedido;
        $this->prod = $prod;
        $this->destinationEmail = $destinationEmail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mailData = $this->mailData;
        $pedido = $this->pedido;
        $prod = $this->prod;
        $destinationEmail = $this->destinationEmail;

        if ($destinationEmail == null) {
            $destinationEmail = Auth::user()->email;
        }

        Mail::send(
            'Admin.emails.product',
            $mailData,
            function ($message) use ($pedido, $prod, $destinationEmail) {
                $message
                    ->from('noreply@atsportugal.com', 'Ats Travel Reservation request')
                    ->to('sales@atravel.pt')
                    ->cc($destinationEmail)
                    ->subject('Reservation request Nº: ' . $pedido->referencia . ' / ' . $pedido->lead_name . ' / ' . $prod['nome']);
            }
        );
    }
}
