<?php

namespace App\Exports;

use Carbon\Carbon;
use App\PedidoGeral;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use \Maatwebsite\Excel\Sheet;


Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

class RoomsListExport implements FromView, WithProperties, WithDrawings, WithTitle, ShouldAutoSize, WithEvents {
    protected $id;

    function __construct( $id ) {
        $this->id = $id;
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function( AfterSheet $event ) {
                $event->sheet->styleCells(
                    'A1:B6',
                    [
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                         'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'color' => ['rgb' => "E8E9E8"]
                        ]
                    ]
                );
                $event->sheet->styleCells(
                    'A1:B1',
                    [
                       'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                    ]
                );
                 $event->sheet->styleCells(
                    'A2:A7',
                    [
                        'font' => [
                            'color' => ['rgb' => '008FFF'],
                        ],
                    ]
                );
                 $event->sheet->styleCells(
                    'A8:B8',
                    [
                        'font' => [
                            'color' => ['rgb' => '000'],
                            'bold' => true,
                        ],
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                    ]
                );
                  $event->sheet->styleCells(
                    'A10:C11',
                    [
                        'font' => [
                            'color' => ['rgb' => '000'],
                            'bold' => true,
                        ],
                    ]
                );
                   $event->sheet->styleCells(
                    'A12:C11',
                    [
                        'font' => [
                            'color' => ['rgb' => '000'],
                            'bold' => true,
                        ],
                    ]
                );
            }
        ];
    }

    public function view(): View {
        return view( 'Admin.profile.Excel.roomlist', [
            'pedido' => PedidoGeral::find( $this->id )
        ] );
    }

    public function properties(): array {
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

    public function drawings() {
        $drawing = new Drawing();
        $drawing->setName( 'ATRAVEL' );
        $drawing->setDescription( 'ATRAVEL' );
        $drawing->setPath( public_path( 'FrontEnd/images/logoatsfundo.png' ) );
        $drawing->setHeight( 90 );
        $drawing->setCoordinates( 'D1:I6' );
        return $drawing;
    }

    public static function beforeExport( BeforeExport $event ) {
        $event->writer->getProperties()->setCreator( 'OSB' );
    }

    public function title(): string {
        return 'Export ' . Carbon::now()->format( 'Y-m-d H:i' );
    }

}

