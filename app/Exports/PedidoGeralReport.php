<?php

namespace App\Exports;

use App\PedidoGeral;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use \Maatwebsite\Excel\Sheet;


Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

class PedidoGeralReport implements FromView, WithProperties, WithTitle, ShouldAutoSize, WithEvents
{
    protected $pedidos;
    private $ats;

    function __construct($pedidos, $ats)
    {
        $this->pedidos = $pedidos;
        $this->ats = $ats;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

            }
        ];
    }

    public function view(): View
    {
        try {
            if ($this->ats == 1) {
                return view('Admin.reports.pedidosreportsv2.Excel.reportWithAts', [
                    'pedidos' => $this->pedidos
                ]);
            }

            return view('Admin.reports.pedidosreportsv2.Excel.report', [
                'pedidos' => $this->pedidos
            ]);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function properties(): array
    {
        return [];

        return [
            'creator'        => 'OSB',
            'lastModifiedBy' => 'OSB',
            'title'          => 'Pedidos report',
            'description'    => 'Detailed pedidos report',
            'category'       => 'List',
            'manager'        => 'OSB',
            'company'        => 'OSB',
        ];
    }

    // public function drawings()
    // {
    //     try {

    //         return null;
    //         return new Drawing();

    //         $drawing = new Drawing();
    //         $drawing->setName('ATRAVEL');
    //         $drawing->setDescription('ATRAVEL');
    //         $drawing->setPath(public_path('FrontEnd/images/logoatsfundo.png'));
    //         $drawing->setHeight(90);
    //         $drawing->setCoordinates('D1:I6');
    //         return $drawing;
    //     } catch (\Throwable $th) {
    //         dd($th);
    //     }
    // }

    public static function beforeExport(BeforeExport $event)
    {
        $event->writer->getProperties()->setCreator('OSB');
    }

    public function title(): string
    {
        return 'Export ' . Carbon::now()->format('Y-m-d H:i');
    }
}
