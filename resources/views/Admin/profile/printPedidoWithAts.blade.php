@if ($_SERVER['REMOTE_ADDR'] != '81.193.176.84' and config('app.server_status') == 'down')
    @push('css')
        <style>
            .code {
                border-right: 2px solid;
                font-size: 35px;
                font-family: 'Roboto', sans-serif;
                padding: 0 15px 0 15px;
                text-align: center;
            }

        </style>
    @endpush
    @section('content')
        <div class="w3-container">
            <div class="flex-center position-ref full-height">
                <div class="code">
                    402
                </div>
                <div class="message" style="padding: 10px;">
                    <img src="{{ asset('Admin/img/manutention.jpg') }}" alt="" style="width: 80%">
                </div>
            </div>
        </div>
    @endsection
@else

    @extends('Admin.layouts.app')

    @section('content')

        <style type="text/css">
            #app-navbar-collapse {
                display: none !important;
            }

            body {
                color: #000000 !important;
                font-family: arial !important;
                background-color: #ffffff !important;
                margin: 0px;
            }

            @page {
                size: 210mm 297mm;
                margin: 27mm 16mm 27mm 16mm;
            }

            .container {
                width: 1160px;
                padding: inherit;
            }

            .w3-row-padding {
                padding: inherit;
            }

            @media print {
                @page {
                    size: A4;
                    /* DIN A4 standard, Europe */
                    margin: 0;
                }

                html,
                body {
                    width: 130mm;
                    height: 282mm;
                    overflow: visible;
                }

                body {
                    padding-top: 15mm;
                }
            }

        </style>

        <link rel="stylesheet" href="{{ asset('Admin/css/w3.css') }}">

        {{ renderCabecalhoVouchers($pedido_geral, $usuario) }}


        @php $total = 0; @endphp

        <!--Quartos-->
        @if ($quartos->isEmpty() != true)
            <div class="container">
                <div class="w3-row-padding w3-padding-32" style="border-top: 2px solid #00bad1;">
                    <table width="100%" style="margin-bottom: 15px;">
                        @php
                            $subtotal = 0;
                            $old_produto_id = 0;
                            $kick_back = 0;
                            $id = 0;
                            $totalAtsPedido = 0;
                        @endphp

                        @foreach ($quartos->sortBy('checkin') as $quarto)
                            <tr>
                                <td colspan="5"><b>Hotel:</b> {{ $quarto->nome }}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>C. in:</b>
                                    {{ Carbon\Carbon::parse($quarto->checkin)->format('d-m-Y') }}</td>
                                <td width="20%"><b>C. out:</b>
                                    {{ Carbon\Carbon::parse($quarto->checkout)->format('d-m-Y') }} </td>
                                <td width="20%"><b>Rooms:</b> {{ $quarto->rooms }}</td>
                                <td width="15%"><b>Unit €:</b> {{ number_format($quarto->price, 2, ',', '.') }}</td>
                                <td width="15%"><b>Total €:</b> {{ number_format($quarto->total, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td><b>Room Type:</b> {{ $quarto->type }}</td>
                                <td><b>Board:</b> {{ $quarto->plan }}</td>
                                <td><b>Pax:</b> {{ $quarto->people }}</td>
                                <td width="15%"><b style="color:#FF5722">ATS RATE €:</b>
                                    {{ number_format($quarto->ats_rate, 2, ',', '.') }}</td>
                                <td width="15%"><b style="color:#FF5722">ATS TOTAL €:</b>
                                    {{ number_format($quarto->ats_total_rate, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                                <td width="15%"><b style="color:green">Profit €:</b>
                                    {{ number_format(\App\PedidoQuarto::find($quarto->PedidoItemId)->profit, 2, ',', '.') }}
                                </td>
                            </tr>
                            @if ($old_produto_id != $quarto->pedido_produto_id)
                                @foreach ($extras_quartos as $extra_quarto)
                                    @if ($extra_quarto->pedido_produto_id == $quarto->pedido_produto_id)
                                        <tr>
                                            <td colspan="2"><b>Extra name:</b> {{ $extra_quarto->name }}</td>
                                            <td><b>Qty:</b> {{ $extra_quarto->amount }}</td>
                                            <td><b>Unit €:</b> {{ number_format($extra_quarto->rate, 2, ',', '.') }}</td>
                                            <td><b>Total €:</b> {{ number_format($extra_quarto->total, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                        @php $subtotal += $extra_quarto->total; @endphp
                                    @endif
                                @endforeach
                            @endif
                            @if ($quarto->remark)
                                <tr>
                                    <td colspan="3"><br>Remarks:<br>{!! html_entity_decode($quarto->remark) !!}</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="5"><br></td>
                            </tr>
                            @php
                                $old_produto_id = $quarto->pedido_produto_id;
                                $totalAtsPedido += $quarto->ats_total_rate;
                            @endphp
                        @endforeach
                        {{-- end foreach quartos --}}

                        @php
                            $PedidosIds = $quartos->unique('pedido_produto_id')->pluck('pedido_produto_id');
                            $ValoresPedidosProduto = App\ValorQuarto::whereIn('pedido_produto_id', $PedidosIds->toArray())->get();
                            $dados = renderPrintSubTotalSection($ValoresPedidosProduto, 'valor_quarto', $total, $subtotal, $m = true, $k = true, $t = true, $withAts = true);

                            $total += $dados->total;
                            $profit = $dados->profit;
                        @endphp
                        {{ renderSingleDecimalTableRow('ATS RATE', '#FF5722', floor($totalAtsPedido * 100) / 100) }}
                        {{ renderSingleDecimalTableRow('PROFIT', '#07a307', floor($profit * 100) / 100) }}
                    </table>
                </div>
            </div>
        @endif

        <!--golf-->
        @if ($golfes->isEmpty() != true)
            <div class="container">
                <div class="w3-row-padding w3-padding-32" style="border-top: 2px solid #00bad1;">
                    @php
                        $subtotal = 0;
                        $old_produto_id = 0;
                        $kick_back = 0;
                        $id = 0;
                        $totalAtsPedido = 0;
                    @endphp
                    <table width="100%" style="margin-bottom: 15px; border">
                        @foreach ($golfes->sortBy('data') as $golfe)
                            <tr>
                                <td colspan="5"><b>Golf Course:</b> {{ $golfe->nome }}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Date:</b> {{ Carbon\Carbon::parse($golfe->data)->format('d-m-Y') }}
                                </td>
                                <td width="20%"><b>Starting Time:</b>
                                    {{ Carbon\Carbon::parse($golfe->hora)->format('H:i') }} </td>
                                <td width="20%"><b>Pax:</b> {{ $golfe->people }}</td>
                                <td width="15%"><b>Unit €:</b> {{ number_format($golfe->rate, 2, ',', '.') }}</td>
                                <td width="15%"><b>Total €:</b> {{ number_format($golfe->total, 2, ',', '.') }}</td>
                            </tr>

                            <tr>
                                <td colspan="3"></td>
                                <td width="15%"><b style="color:#FF5722">ATS RATE €:</b>
                                    {{ number_format($golfe->ats_rate, 2, ',', '.') }}</td>
                                <td width="15%"><b style="color:#FF5722">ATS TOTAL €:</b>
                                    {{ number_format($golfe->ats_total_rate, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                                <td width="15%"><b style="color:green">Profit €:</b>
                                    {{ number_format(\App\PedidoGame::find($golfe->PedidoItemId)->profit, 2, ',', '.') }}
                                </td>
                            </tr>

                            @if ($old_produto_id != $golfe->pedido_produto_id)
                                @foreach ($extras_golfes as $extra_golfe)
                                    @if ($extra_golfe->pedido_produto_id == $golfe->pedido_produto_id)
                                        <tr>
                                            <td><b>Extra name:</b> {{ $extra_golfe->name }}</td>
                                            <td><b>Qty:</b> {{ $extra_golfe->amount }}</td>
                                            <td></td>
                                            <td><b>Unit €:</b> {{ number_format($extra_golfe->rate, 2, ',', '.') }}</td>
                                            <td><b>Total €:</b> {{ number_format($extra_golfe->total, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                        @php $subtotal = $subtotal + $extra_golfe->total; @endphp
                                    @endif
                                @endforeach
                            @endif
                            @if ($golfe->remark)
                                <tr>
                                    <td colspan="3"><br>Remarks:<br>{!! html_entity_decode($golfe->remark) !!}</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="5"><br></td>
                            </tr>
                            @php
                                $old_produto_id = $golfe->pedido_produto_id;
                                $totalAtsPedido += $golfe->ats_total_rate;
                            @endphp
                        @endforeach

                        @php
                            $PedidosIds = $golfes->unique('pedido_produto_id')->pluck('pedido_produto_id');
                            $ValoresPedidosProduto = App\ValorGolf::whereIn('pedido_produto_id', $PedidosIds->toArray())->get();
                            $dados = renderPrintSubTotalSection($ValoresPedidosProduto, 'valor_golf', $total, $subtotal, $m = true, $k = true, $t = true, $withAts = true);
                            $total += $dados->total;
                            $profit = $dados->profit;
                        @endphp
                        {{ renderSingleDecimalTableRow('ATS RATE', '#FF5722', floor($totalAtsPedido * 100) / 100) }}
                        {{ renderSingleDecimalTableRow('PROFIT', '#07a307', floor($profit * 100) / 100) }}
                    </table>
                </div>
            </div>
        @endif

        <!--transfer-->
        @if ($transfers->isEmpty() != true)
            <div class="container">
                <div class="w3-row-padding w3-padding-32" style="border-top: 2px solid #00bad1;">
                    @php
                        $subtotal = 0;
                        $old_produto_id = 0;
                        $kick_back = 0;
                        $id = 0;
                        $totalAtsPedido = 0;
                    @endphp
                    <table width="100%" style="margin-bottom: 15px;">
                        @foreach ($transfers->sortBy('data') as $uniqueTransfer)

                            <tr>
                                <td colspan="5"><b>Company:</b> {{ $uniqueTransfer->nome }}</td>
                            </tr>
                            <tr width="100%" style="margin-bottom:20px">
                                <td colspan="5"> <b>Date:</b>
                                    {{ Carbon\Carbon::parse($uniqueTransfer->data)->format('d-m-y') }} |
                                    <b>Time:</b> {{ Carbon\Carbon::parse($uniqueTransfer->hora)->format('H:i') }}
                                </td>
                            </tr>
                            <tr>
                                <td width="30%"><b>From:</b> {{ $uniqueTransfer->pickup }}</td>
                                <td width="20%"><b>To:</b> {{ $uniqueTransfer->dropoff }}</td>
                                <td width="20%"><b>Flight Nº:</b> {{ $uniqueTransfer->flight }}</td>
                                <td width="15%"><b></td>
                                <td width="15%"><b>Total €:</b> {{ number_format($uniqueTransfer->total, 2, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td><b>Adults:</b> {{ $uniqueTransfer->adult }}</td>
                                <td><b>Children:</b> {{ $uniqueTransfer->children }}</td>
                                <td><b>Babies:</b> {{ $uniqueTransfer->babie }}</td>
                                <td width="15%"><b style="color:#FF5722">ATS RATE €:</b>
                                    {{ number_format($uniqueTransfer->ats_rate, 2, ',', '.') }}</td>
                                <td width="15%"><b style="color:green">Profit €:</b>
                                    {{ number_format(\App\PedidoTransfer::find($uniqueTransfer->PedidoItemId)->profit, 2, ',', '.') }}
                                </td>
                            </tr>



                            @if ($old_produto_id != $uniqueTransfer->pedido_produto_id)
                                @foreach ($extras_transfers as $extra_transfer)
                                    @if ($extra_transfer->pedido_produto_id == $uniqueTransfer->pedido_produto_id)
                                        <tr>
                                            <td colspan="2"><b>Extra name:</b> {{ $extra_transfer->name }}</td>
                                            <td><b>Qty:</b> {{ $extra_transfer->amount }}</td>
                                            <td><b>Unit €:</b> {{ number_format($extra_transfer->rate, 2, ',', '.') }}
                                            </td>
                                            <td><b>Total €:</b> {{ number_format($extra_transfer->total, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                        @php $subtotal = $subtotal + $extra_transfer->total; @endphp
                                    @endif
                                @endforeach
                            @endif
                            @if ($uniqueTransfer->remark)
                                <tr>
                                    <td colspan="3"><br>Remarks:<br>{!! html_entity_decode($uniqueTransfer->remark) !!}</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="5"><br></td>
                            </tr>

                            @php
                                $old_produto_id = $uniqueTransfer->pedido_produto_id;
                                $totalAtsPedido = \App\PedidoTransfer::find($uniqueTransfer->PedidoItemId)->atsTotalRate;
                            @endphp
                        @endforeach

                        @php
                            $PedidosIds = $transfers->unique('pedido_produto_id')->pluck('pedido_produto_id');
                            $ValoresPedidosProduto = App\ValorTransfer::whereIn('pedido_produto_id', $PedidosIds->toArray())->get();
                            $dados = renderPrintSubTotalSection($ValoresPedidosProduto, 'valor_transfer', $total, $subtotal, $m = true, $k = true, $t = true, $withAts = true);

                            $total += $dados->total;
                            $profit = $dados->profit;
                        @endphp
                        {{ renderSingleDecimalTableRow('ATS RATE', '#FF5722', floor($totalAtsPedido * 100) / 100) }}
                        {{ renderSingleDecimalTableRow('PROFIT', '#07a307', floor($profit * 100) / 100) }}
                    </table>
                </div>
            </div>
        @endif

        <!--car-->
        @if ($carros->isEmpty() != true)
            <div class="container">
                <div class="w3-row-padding w3-padding-32" style="border-top: 2px solid #00bad1;">
                    @php
                        $subtotal = 0;
                        $old_produto_id = 0;
                        $kick_back = 0;
                        $id = 0;
                        $totalAtsPedido = 0;
                    @endphp
                    <table width="100%" style="margin-bottom: 15px;">
                        @foreach ($carros->sortBy('pickup_data') as $carro)
                            <tr>
                                <td colspan="5"><b>Company:</b> {{ $carro->nome }}</td>
                            </tr>
                            <tr style="margin-top: 20px;">
                                <td colspan="5"><b>Drop Off Location:</b> {{ $carro->dropoff }}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Pick-up Date:</b>
                                    {{ Carbon\Carbon::parse($carro->pickup_data)->format('d-m-y') }}
                                </td>
                                <td width="20%"><b>Pick-up Hour:</b>
                                    {{ Carbon\Carbon::parse($carro->pickup_hora)->format('H:i') }}
                                </td>
                                <td width="20%"><b>TAX:</b> {{ number_format($carro->tax, 2, ',', '.') }}</td>
                                <td width="15%"><b>Unit €:</b> {{ number_format($carro->rate, 2, ',', '.') }}</td>
                                <td width="15%"><b>Total €:</b> {{ number_format($carro->total, 2, ',', '.') }}</td>
                            </tr>
                            <tr style="margin-top: 20px;">
                                <td colspan="5"><b>Pick-up Location:</b> {{ $carro->pickup }}</td>
                            </tr>
                            <tr>
                                <td><b>Drop Off Date:</b>
                                    {{ Carbon\Carbon::parse($carro->dropoff_data)->format('d-m-y') }} </td>
                                <td><b>Drop Off Hour:</b>
                                    {{ Carbon\Carbon::parse($carro->dropoff_hora)->format('H:i') }} </td>
                                <td width="20%"><b>Tax Type:</b>{{ $carro->tax_type }}</td>

                                <td width="15%"><b style="color:#FF5722">ATS RATE €:</b>
                                    {{ number_format($golfe->ats_rate, 2, ',', '.') }}</td>
                                <td width="15%"><b style="color:#FF5722">ATS TOTAL €:</b>
                                    {{ number_format($golfe->ats_total_rate, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                                <td width="15%"><b style="color:green">Profit €:</b>
                                    {{ number_format(\App\PedidoCar::find($carro->PedidoItemId)->profit, 2, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td><b>Group:</b> {{ $carro->group }}</td>
                                <td></td>
                                <td width="20%"></td>
                                <td colspan="2"></td>
                            </tr>
                            @if (($old_produto_id != 0 && $old_produto_id != $carro->pedido_produto_id) || $loop->last)
                                @foreach ($extras_carros as $extra_carro)
                                    @if ($extra_carro->pedido_produto_id == $carro->pedido_produto_id)
                                        <tr>
                                            <td colspan="2"><b>Extra name:</b> {{ $extra_carro->name }}</td>
                                            <td><b>Qty:</b> {{ $extra_carro->amount }}</td>
                                            <td><b>Unit €:</b> {{ number_format($extra_carro->rate, 2, ',', '.') }}</td>
                                            <td><b>Total €:</b> {{ number_format($extra_carro->total, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                        @php $subtotal = $subtotal + $extra_carro->total; @endphp
                                    @endif
                                @endforeach
                            @endif
                            @if ($carro->remark)
                                <tr>
                                    <td colspan="3"><br>Remarks:<br>{!! html_entity_decode($carro->remark) !!}</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="5"><br></td>
                            </tr>
                            @php
                                $old_produto_id = $carro->pedido_produto_id;
                                $totalAtsPedido += $carro->ats_total_rate;
                            @endphp
                        @endforeach

                        @php
                            $PedidosIds = $carros->unique('pedido_produto_id')->pluck('pedido_produto_id');
                            $ValoresPedidosProduto = App\ValorCar::whereIn('pedido_produto_id', $PedidosIds->toArray())->get();
                            $dados = renderPrintSubTotalSection($ValoresPedidosProduto, 'valor_car', $total, $subtotal, $m = true, $k = true, $t = true, $withAts = true);

                            $total += $dados->total;
                            $profit = $dados->profit;
                        @endphp
                        {{ renderSingleDecimalTableRow('ATS RATE', '#FF5722', floor($totalAtsPedido * 100) / 100) }}
                        {{ renderSingleDecimalTableRow('PROFIT', '#07a307', floor($profit * 100) / 100) }}
                    </table>
                </div>
            </div>
        @endif

        <!--ticket-->
        @if ($bilhetes->isEmpty() != true)
            <div class="container">
                <div class="w3-row-padding w3-padding-32" style="border-top: 2px solid #00bad1;">
                    @php
                        $subtotal = 0;
                        $old_produto_id = 0;
                        $kick_back = 0;
                        $id = 0;
                        $totalAtsPedido = 0;
                    @endphp
                    <table width="100%" style="margin-bottom: 15px;">
                        @foreach ($bilhetes->sortBy('data') as $bilhete)
                            <tr>
                                <td colspan="5"><b>Company:</b> {{ $bilhete->nome }}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Date:</b>
                                    {{ Carbon\Carbon::parse($bilhete->data)->format('d-m-y') }} </td>
                                <td width="20%"><b>Hour:</b> {{ Carbon\Carbon::parse($bilhete->hora)->format('H:i') }}
                                </td>
                                <td width="20%"><b></td>
                                <td width="15%"><b></td>
                                <td width="15%"><b>Total €:</b> {{ number_format($bilhete->total, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td><b>Adults:</b> {{ $bilhete->adult }}</td>
                                <td><b>Children:</b> {{ $bilhete->children }}</td>
                                <td><b>Babies:</b> {{ $bilhete->babie }}</td>
                                <td colspan="2"></td>
                            </tr>
                            @if (($old_produto_id != 0 && $old_produto_id != $bilhete->pedido_produto_id) || $loop->last)
                                @foreach ($extras_bilhetes as $extra_bilhete)
                                    @if ($extra_bilhete->pedido_produto_id == $bilhete->pedido_produto_id)
                                        <tr>
                                            <td colspan="2"><b>Extra name:</b> {{ $extra_bilhete->name }}</td>
                                            <td><b>Qty:</b> {{ $extra_bilhete->amount }}</td>
                                            <td><b>Unit €:</b> {{ number_format($extra_bilhete->rate, 2, ',', '.') }}
                                            </td>
                                            <td><b>Total €:</b> {{ number_format($extra_bilhete->total, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                        @php $subtotal = $subtotal + $extra_bilhete->total; @endphp
                                    @endif
                                @endforeach
                            @endif
                            <tr>
                                <td colspan="3"><br>{!! html_entity_decode($bilhete->remark) !!}</td>
                            </tr>
                            <tr>
                                <td colspan="5"><br></td>
                            </tr>

                            @php
                                $old_produto_id = $bilhete->pedido_produto_id;
                                $totalAtsPedido += \App\PedidoTicket::find($bilhete->PedidoItemId)->atsTotalRate;
                            @endphp
                        @endforeach

                        @php
                            $PedidosIds = $bilhetes->unique('pedido_produto_id')->pluck('pedido_produto_id');
                            $ValoresPedidosProduto = App\ValorTicket::whereIn('pedido_produto_id', $PedidosIds->toArray())->get();
                            $dados = renderPrintSubTotalSection($ValoresPedidosProduto, 'valor_ticket', $total, $subtotal, $m = true, $k = true, $t = true, $withAts = true);

                            $total += $dados->total;
                            $profit = $dados->profit;
                        @endphp
                        {{ renderSingleDecimalTableRow('ATS RATE', '#FF5722', floor($totalAtsPedido * 100) / 100) }}
                        {{ renderSingleDecimalTableRow('PROFIT', '#07a307', floor($profit * 100) / 100) }}
                    </table>
                </div>
            </div>
        @endif

        @php
            $total_payments = 0;
            foreach ($payments as $payment) {
                $total_payments += (float) $payment->payment;
            }
        @endphp

        <div class="container" style="margin-top:50px">
            <div class="w3-row-padding w3-padding-32" style="border-top: 2px solid #0513ffe3; border-top-style: dashed">
                <table width="100%">
                    <tr>
                        <td width="30%"> &nbsp;</td>
                        <td width="20%"> &nbsp;</td>
                        <td width="20%"> &nbsp;</td>
                        <td width="15%"> &nbsp;</td>
                        <td width="15%"> &nbsp;</td>
                    </tr>
                    {{ renderSingleDecimalTableRow('TOTAL PRODUCT', '#00', $total) }}
                    {{ renderSingleDecimalTableRow('Payments', '#FF5752', $total_payments) }}
                    {{ renderSingleDecimalTableRow('TOTAL ATS RATE', '#Fe1111', floor(($pedido_geral->valor - $pedido_geral->profit) * 100) / 100) }}
                    {{ renderSingleDecimalTableRow('PROFIT', '#07a307', $pedido_geral->profit - ($extra_quarto->amount ?? 0)) }}
                </table>
            </div>
        </div>

    @endsection
@endif
