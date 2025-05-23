<?php

namespace App\Console;

use Log;
use App\PedidoGeral;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\TruncateAllTables::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->call(function () {
            $options = [
            'cluster' => 'eu','useTLS' => true
            ];

            $pusher = new \Pusher\Pusher('37abc5efe9e26526a020','64f7e508246daad1422e','856252',$options);

            if(config('app.env') == 'local'){
                $host = "http://localhost:8000/profile/search?";
            }else if(config('app.env') == 'development'){
                $host = "https://dev.atsportugal.com/admin/profile/search?";
            }else{
                $host = "https://atsportugal.com/admin/profile/search?";
            }

            $pedidos = PedidoGeral::pendings()->get();
            foreach($pedidos as $p){
                $status = str_replace(" ", "+", $p->status);
                $callback_url[$p->id]['url'] = $host."in=&out=&tipo={$status}&operator_id=0&lead={$p->lead_name}";
                $callback_url[$p->id]['text'] = "Pedido: ".$p->referencia;
            }

            if($pedidos->count() > 0){
            $data  = [
                'message' => 'Você possui <b class="badge badge-pill badge-primary">'.$pedidos->count(). '</b> pedidos pendentes',
                'total' => $pedidos->count(),
                'callbacks' => $callback_url
            ];
            }else{
                $data  = [
                    'message' => 'Você possui '.$pedidos->count(). ' pedidos pendentes',
                    'total' => 0,
                ];
            }
            $channel = config('app.pusherChannel');
            $pusher->trigger( $channel, 'get-proform-pendenting', $data);
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
