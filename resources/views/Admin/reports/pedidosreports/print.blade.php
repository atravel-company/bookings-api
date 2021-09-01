<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ATS Portugal</title>

    <!--load everything font-awesome-->
    <style type="text/css">
        #app-navbar-collapse {
            display: none !important;
        }

        @media print {
            tr.head-tr {
                background-color: #000 !important;
                color: #fff;
                -webkit-print-color-adjust: exact;
            }

            tr.body-tr td {
                border-bottom: 2pt solid black;
                -webkit-print-color-adjust: exact;
            }
        }

        tr.body-tr td {
            border-bottom: 2pt solid black;
            -webkit-print-color-adjust: exact;
        }
    </style>
    <link href="https://www.w3schools.com/w3css/4/w3.css" rel="stylesheet">


    <script type="text/javascript" src="{{ URL::asset('Admin/js/node_modules/printthis/printThis.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('Admin/js/voucher.js') }}"></script>



</head>

<body style="background: white; color: black">

    <div class="container" id="cabeca">
        <div class="w3-row-padding">
            <table style="margin-top:30px; padding-bottom:85px; width: 49.5%;">
                <tr>
                    <td style=" text-align:left;">
                        <img class="w3-margin-bottom" id="editSupplier_img" style="width:70%; margin:0 auto;"
                            src="<?php echo asset("storage/LogotipoAtravelCor.png")?>">
                    </td>
                </tr>
            </table>
            <table style="width: 25%;position: absolute;right: 0px;top: 10px;" class="w3-centered">
                <tr>
                    <th style="text-align: left; width:30%;">TOPICOS E DESCOBERTAS LDA</th>
                </tr>
                <tr>
                    <td style="text-align: left; color:#24AEC9; font-size: 11.5px">Av. da Liberdade</td>
                </tr>
                <tr>
                    <td style="text-align: left; color:#24AEC9; font-size: 11.5px">243, 4ºA </td>
                </tr>
                <tr>
                    <td style="text-align: left; color:#24AEC9; font-size: 11.5px">1250-143 Lisboa</td>
                <tr>
                    <td style="text-align: left; font-size: 10px; color:#24AEC9;">Licence RNVAT 8019</td>
                </tr>
                <tr>
                    <td style="color:#24AEC9;text-align: left; font-size: 10px;">VAT 514 974 567</td>
                </tr>
                <tr>
                    <td align="right" style="text-align: left; width:25%; font-size: 10px; width: 30%;">Telef.(+351)
                        282-457 306</td>
                </tr>
                <tr>
                    <td style="text-align: left; font-size: 10px;">Mobile.(+351) 912 032 695</td>
                </tr>
                <tr>
                    <td style="text-align: left; font-size: 10px;">reservations: incoming@atravel.pt</td>
                </tr>
                <tr>
                    <td style="text-align: left; font-size: 10px;">www.atstravel.pt</td>
                </tr>
            </table>

            <div style="margin-top: -14px;left: 1%;position: absolute;font-size: 14px;">
                <b>INVOICE FROM {{request('start')}} TO {{request('end')}}</b>
                <b>Total invoices - {{ $pedidos->count() }}</b>
            </div>

        </div>

        <hr style="border:solid 1.5px #24AEC9">
        <div class="container w3-row-padding">



            {{-- {{dd($pedidos_reports)}} --}}
            <table border='1'
                class="table table-striped display compact table-bordered table-hover table-responsive nowrap"
                style="width:100%; border-collapse: collapse!important;">


                <thead>
                    <tr>
                        <th orderable=false searchable=false rowspan="2" text-align="center">#</th>
                        <th rowspan="2">Arr</th>
                        <th rowspan="2">Client</th>
                        <th rowspan="2">RNTS</th>
                        <th rowspan="2">Bed N.</th>
                        <th rowspan="2">ADR</th>
                        <th rowspan="2">Supplier</th>
                        <th rowspan="2">T.OPER</th>
                        <th style="background-color:yellow; text-align:center; border-left:1px solid #000;"
                            colspan="10">
                            Money Received
                        </th>
                        @if(Route::current()->getName() == "pedidos.reports.index.ats" or Route::current()->getName() ==
                        "pedidos.reports.buscar.ats")
                        <th style="background-color:yellow; text-align:center; color:red;" colspan="7">Money Paid</th>
                        @endif
                    </tr>
                    <tr>
                        <th style="background-color:#0078ff;">ROOM</th>
                        <th style="background-color:#1dcb4a;">GOLF</th>
                        <th style="background-color:yellow;">TRA</th>
                        <th style="background-color:#ffdd97;">CAR</th>
                        <th style="background-color:#2ea7ff;">EXTRAS</th>
                        <th style="background-color:#1259b4;">K.BACK</th>
                        <th style="background-color:yellow;">TOTAL</th>
                        <th style="background-color:yellow;">V.PAID</th>
                        <th style="background-color:yellow;">UNPAID</th>
                        @if(Route::current()->getName() == "pedidos.reports.index.ats" or Route::current()->getName() ==
                        "pedidos.reports.buscar.ats")
                        <th class="ats_hidden" style="background-color:#0078ff;">ROOM</th>
                        <th class="ats_hidden" style="background-color:#1dcb4a;">GOLF</th>
                        <th class="ats_hidden" style="background-color:yellow;">TRA</th>
                        <th class="ats_hidden" style="background-color:#ffdd97;">CAR</th>
                        <th class="ats_hidden" style="background-color:#2ea7ff;">EXTRAS</th>
                        <th class="ats_hidden" style="background-color:yellow;">TOTAL</th>
                        <th class="ats_hidden" style="background-color:#1dcb4a;">PROFIT</th>
                        @endif
                    </tr>
                </thead>
                <tbody>

                    @php
                    $TotalValorNaoPago = 0;
                    $TotalTotal = 0;
                    $totalPago = 0;
                    @endphp

                    @forelse($pedidos->sortBy(function($p){
                        return \Carbon\Carbon::parse($p->DataFirstServico)->format('d/m/Y');
                    }) as $pedidogeral)

                    @php
                    $TotalTotal += floatval(preg_replace('/[^\d.]/', '', ($pedidogeral->valor)));
                    $totalPago += floatval(preg_replace('/[^\d.]/', '', ($pedidogeral->TotalPagamento)));
                    $ValorTotalNaoPago = $totalPago - $TotalTotal;

                    @endphp

                    <tr>
                        <td class="hidden" data-status="{{$pedidogeral->status}}">{{ $pedidogeral->id }}</td>
                        <td> {{ \Carbon\Carbon::parse($pedidogeral->DataFirstServico)->format('d/m/Y') }} </td>
                        <td style="text-align:left;"> {{ $pedidogeral->lead_name }} </td>
                        <td>
                            @php $rnts = 0; @endphp
                            @foreach($pedidogeral->pedidoprodutos as $pedidoprodutos)
                            @forelse($pedidoprodutos->pedidoquarto as $quarto)
                            @php $rnts += $quarto->rnts; @endphp
                            @empty
                            @php $rnts += 0; @endphp
                            @endforelse
                            @endforeach
                            {{ $rnts }}
                        </td>
                        <td>
                            @php $bednight = 0; @endphp
                            @foreach($pedidogeral->pedidoprodutos as $pedidoprodutos)
                            @forelse($pedidoprodutos->pedidoquarto as $quarto)
                            @php $bednight += $quarto->bednight; @endphp
                            @empty
                            @php $bednight += 0; @endphp
                            @endforelse
                            @endforeach
                            {{ $bednight }}
                        </td>
                        <td>
                            @php $adr = 0; @endphp
                            @php $rnts = ($rnts == 0 ? 1 : $rnts); @endphp
                            @foreach($pedidogeral->pedidoprodutos as $pedidoprodutos)
                            @if($pedidoprodutos->valorquarto)
                            @php $adr = $pedidoprodutos->valorquarto->valor_quarto / $rnts; @endphp
                            @endif
                            @endforeach
                            {{ floor($adr*100)/100 }}
                        </td>
                        <td style="text-align:left;">

                            @if(request('hotel') !== '0' and request('hotel') !== null )

                            {{ $pedidogeral->pedidoprodutos()->where('produto_id', request('hotel')  )->first()->produto->nome }}
                            @else
                            {{ $pedidogeral->pedidoprodutos()->first()->produto->nome }}
                            @endif

                        </td>
                        <td style="text-align:left;"> {{ $pedidogeral->user->name }} </td>
                        <td style="text-align:right;"> {{ $pedidogeral->valortotalquarto }} </td>
                        <td style="text-align:right;"> {{ $pedidogeral->valortotalgolf }} </td>
                        <td style="text-align:right;"> {{ $pedidogeral->valortotaltransfer }}</td>
                        <td style="text-align:right;"> {{ $pedidogeral->valortotalcar }} </td>
                        <td style="text-align:right;"> {{ $pedidogeral->valortotalextras }} </td>
                        <td style="text-align:right;"> {{ $pedidogeral->ValorTotalKickBack }} </td>
                        <td style="background-color:yellow;text-align:right;" data-comentario="coluna TOTAL">
                            {{ number_format(floor($pedidogeral->valor * 100)/100, 2, ",", ".") }}
                        </td>
                        <td style="background-color:yellow;text-align:right;" data-comentario="coluna vPaid">
                            {{ $pedidogeral->TotalPagamento }}
                        </td>
                        <td style="background-color:yellow;text-align:right;" data-comentario="coluna unpaid">
                            @if($pedidogeral->ValorNaoPago > 0)
                            <span style='color:green !important; font-weight: 600 '>
                                {{$pedidogeral->ValorNaoPago }}
                            </span>
                            @elseif($pedidogeral->ValorNaoPago < 0) <span
                                style='color:red !important; font-weight: 600 '>
                                {{$pedidogeral->ValorNaoPago }}
                                </span>
                                @else
                                {{ $pedidogeral->ValorNaoPago }}
                                @endif
                        </td>

                    </tr>
                    @empty

                    @endforelse
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="3" style="text-align:right">Total:</th>
                        <th style="text-align:center;"></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style="text-align:right;"></th>
                        <th style="text-align:right;"></th>
                        <th style="text-align:right;"></th>
                        <th style="text-align:right;"></th>
                        <th style="text-align:right;"></th>
                        <th style="text-align:right;"></th>
                        <th style="text-align:right;">
                            {{ number_format( floor($TotalTotal * 100)/100 , 2 , ",", ".") }}
                        </th>
                        <th style="text-align:right;">

                            {{ number_format( $totalPago , 2 , ",", ".") }}
                        </th>
                        <th style="text-align:right;">
                            @if($ValorTotalNaoPago > 0)
                            <span style='color:green !important; font-weight: 600 '>
                                {{ number_format( $ValorTotalNaoPago , 2 , ",", ".") }}
                            </span>
                            @elseif($ValorTotalNaoPago < 0) <span style='color:red !important; font-weight: 600 '>
                                {{ number_format( $ValorTotalNaoPago , 2 , ",", ".") }}
                                </span>
                                @else
                                {{ number_format( $ValorTotalNaoPago , 2 , ",", ".") }}
                                @endif

                        </th>

                    </tr>
                </tfoot>

            </table>


        </div>
    </div>
</body>

</html>
