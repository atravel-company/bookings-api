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

    public function __construct($mailData, $pedido, $prod)
    {
        $this->mailData = $mailData;
        $this->pedido = $pedido;
        $this->prod = $prod;
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

        Mail::send(
            'Admin.emails.product',
            $mailData,
            function ($message) use ($pedido, $prod) {
                $message
                    ->from('noreply@atsportugal.com', 'Ats Travel Reservation request')
                    ->to('sales@atravel.pt')
                    ->cc(Auth::user()->email)
                    // ->cc('henrique@oseubackoffice.com')
                    ->subject('Reservation request Nº: ' . $pedido->referencia . ' / ' . $pedido->lead_name . ' / ' . $prod['nome']);
            }
        );
    }
}
