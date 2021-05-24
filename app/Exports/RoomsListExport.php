<?php

namespace App\Exports;

use App\PedidoGeral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\BeforeExport;

class RoomsListExport implements FromView
{
    protected $pedido;

    function __construct($pedido) {
            $this->pedido = $pedido;
    }

    public function collection()
    {
        $pedido = PedidoGeral::all();
    }

    public function properties(): array
    {
        return [
            'creator'        => 'OSB',
            'lastModifiedBy' => 'OSB',
            'title'          => 'Room List',
            'description'    => 'Room List',
            'category'       => 'List',
            'manager'        => 'OSB',
            'company'        => 'OSB',
        ];
    }
    public static function beforeExport(BeforeExport $event)
    {
        $event->writer->getProperties()->setCreator('OSB');
    }


    public function view(): View
    {

        return view('Admin.profile.Excel.roomlist',['pedido' => $this->pedido->get(), 'usuario' => Auth::user()]);

    }
}

