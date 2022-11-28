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

        <link rel="stylesheet" href="{{asset('Admin/css/w3.css')}}">


        {{ renderCabecalhoVouchers($pedido_geral, $usuario) }}

        @php $total = 0; @endphp

        <!--Quartos-->
        @if ($quartos->isEmpty() != true)
            <div class="container">
                <div class="w3-row-padding w3-padding-32" style="border-top: 2px solid #00bad1;">
                    <table width="1160px" style="margin-bottom: 15px;">
                        @php
                            $subtotal = 0;
                            $old_produto_id = 0;
                            $id = 0;
                        @endphp

                        @foreach ($quartos->sortBy('checkin') as $quarto)
                            <tr>
                                <td colspan="5"><b>Hotel:</b> {{ $quarto->nome }}</td>
                            </tr>
                            <tr>
                                <td width="350px"><b>C. in:</b>
                                    {{ Carbon\Carbon::parse($quarto->checkin)->format('d-m-Y') }}
                                </td>
                                <td width="200px"><b>C. out:</b>
                                    {{ Carbon\Carbon::parse($quarto->checkout)->format('d-m-Y') }} </td>
                                <td width="200px"><b>Rooms:</b> {{ $quarto->rooms }}</td>
                                <td width="300px"><b>Unit €:</b> {{ number_format($quarto->price, 2, ',', '.') }}</td>
                                <td width="200px"><b>Total €:</b> {{ number_format($quarto->total, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td><b>Room Type:</b> {{ $quarto->type }}</td>
                                <td><b>Board:</b> {{ $quarto->plan }}</td>
                                <td><b>Pax:</b> {{ $quarto->people }}</td>
                                <td colspan="2"></td>
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
                            @endphp
                        @endforeach

                        @php
                            $PedidosIds = $quartos->unique('pedido_produto_id')->pluck('pedido_produto_id');
                            $ValoresPedidosProduto = App\ValorQuarto::whereIn('pedido_produto_id', $PedidosIds->toArray())->get();

                            $dados = renderPrintSubTotalSection($ValoresPedidosProduto, 'valor_quarto', $total, $subtotal, $m = false, $k = true, $t = false);
                            $total += $dados->total;
                        @endphp
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
                        $old_produto_id = [];
                        $id = 0;
                    @endphp

                    <table width="1160px" style="margin-bottom: 15px; border">
                        @foreach ($golfes->sortBy('data')->values()->all()
        as $key => $golfe)
                            <tr>
                                <td style="border-top:1px solid black" colspan="5"><b>Golf Course:</b> {{ $golfe->nome }}
                                </td>
                            </tr>
                            <tr>
                                <td width="350px"><b>Date:</b> {{ Carbon\Carbon::parse($golfe->data)->format('d-m-Y') }}
                                </td>
                                <td width="200px"><b>Starting Time:</b>
                                    {{ Carbon\Carbon::parse($golfe->hora)->format('H:i') }} </td>
                                <td width="200px"><b>Pax:</b> {{ $golfe->people }}</td>
                                <td width="300px"><b>Unit €:</b> {{ number_format($golfe->rate, 2, ',', '.') }}</td>
                                <td width="200px"><b>Total €:</b> {{ number_format($golfe->total, 2, ',', '.') }}</td>
                            </tr>


                            @if (!in_array($golfe->pedido_produto_id, $old_produto_id))
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
                                $old_produto_id[] = $golfe->pedido_produto_id;
                            @endphp
                        @endforeach

                        @php
                            $PedidosIds = $golfes->unique('pedido_produto_id')->pluck('pedido_produto_id');
                            $ValoresPedidosProduto = App\ValorGolf::whereIn('pedido_produto_id', $PedidosIds->toArray())->get();
                            $dados = renderPrintSubTotalSection($ValoresPedidosProduto, 'valor_golf', $total, $subtotal, $m = false, $k = true, $t = true);

                            $total += $dados->total;

                        @endphp
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
                        $id = 0;
                    @endphp
                    <table width="1160px" style="margin-bottom: 15px;">
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
                                <td width="350px"><b>From:</b> {{ $uniqueTransfer->pickup }}</td>
                                <td width="200px"><b>To:</b> {{ $uniqueTransfer->dropoff }}</td>
                                <td width="200px"><b>Flight Nº:</b> {{ $uniqueTransfer->flight }}</td>
                                <td width="300px"><b></td>
                                <td width="200px"><b>Total €:</b>
                                    {{ number_format($uniqueTransfer->total, 2, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td><b>Adults:</b> {{ $uniqueTransfer->adult }}</td>
                                <td><b>Children:</b> {{ $uniqueTransfer->children }}</td>
                                <td><b>Babies:</b> {{ $uniqueTransfer->babie }}</td>
                                <td colspan="2"></td>
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
                            @endphp
                        @endforeach

                        @php
                            $PedidosIds = $transfers->unique('pedido_produto_id')->pluck('pedido_produto_id');
                            $ValoresPedidosProduto = App\ValorTransfer::whereIn('pedido_produto_id', $PedidosIds->toArray())->get();
                            $dados = renderPrintSubTotalSection($ValoresPedidosProduto, 'valor_transfer', $total, $subtotal , $m = false, $k = true, $t = true);

                            $total += $dados->total;

                        @endphp
                    </table>
                </div>
            </div>
        @endif
        <!-- END TRANSFERS -->


        <!--car-->
        @if ($carros->isEmpty() != true)
            <div class="container">
                <div class="w3-row-padding w3-padding-32" style="border-top: 2px solid #00bad1;">
                    @php
                        $subtotal = 0;
                        $old_produto_id = 0;
                        $id = 0;
                    @endphp
                    <table width="1160px" style="margin-bottom: 15px;">
                        @foreach ($carros->sortBy('pickup_data') as $carro)
                            <tr>
                                <td colspan="5"><b>Company:</b> {{ $carro->nome }}</td>
                            </tr>
                            <tr style="margin-top: 20px;">
                                <td colspan="5"><b>Drop Off Location:</b> {{ $carro->dropoff }}</td>
                            </tr>
                            <tr>
                                <td width="350px"><b>Pick-up Date:</b>
                                    {{ Carbon\Carbon::parse($carro->pickup_data)->format('d-m-y') }}
                                </td>
                                <td width="200px"><b>Pick-up Hour:</b>
                                    {{ Carbon\Carbon::parse($carro->pickup_hora)->format('H:i') }}
                                </td>
                                <td width="200px"><b>TAX:</b> {{ number_format($carro->tax, 2, ',', '.') }}</td>
                                <td width="300px"><b>Unit €:</b> {{ number_format($carro->rate, 2, ',', '.') }}</td>
                                <td width="200px"><b>Total €:</b> {{ number_format($carro->total, 2, ',', '.') }}</td>
                            </tr>

                            <tr style="margin-top: 20px;">
                                <td colspan="5"><b>Pick-up Location:</b> {{ $carro->pickup }}</td>
                            </tr>

                            <tr>
                                <td><b>Drop Off Date:</b>
                                    {{ Carbon\Carbon::parse($carro->dropoff_data)->format('d-m-y') }}
                                </td>
                                <td><b>Drop Off Hour:</b> {{ Carbon\Carbon::parse($carro->dropoff_hora)->format('H:i') }}
                                </td>
                                <td><b>Tax Type:</b>{{ $carro->tax_type }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><b>Group:</b> {{ $carro->group }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>


                            @if (($old_produto_id != 0 && $old_produto_id != $carro->pedido_produto_id) || $loop->last)
                                @foreach ($extras_carros as $extra_carro)
                                
                                    @if ($extra_carro->pedido_produto_id == $carro->pedido_produto_id and $extra_carro->amount != null )
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
                            @endphp
                        @endforeach

                        @php
                            $PedidosIds = $carros->unique('pedido_produto_id')->pluck('pedido_produto_id');
                            $ValoresPedidosProduto = App\ValorCar::whereIn('pedido_produto_id', $PedidosIds->toArray())->get();
                            $dados = renderPrintSubTotalSection($ValoresPedidosProduto, 'valor_car', $total, $subtotal , $m = false, $k = true, $t = true);
                            $total += $dados->total;

                        @endphp
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
                        $id = 0;
                    @endphp
                    <table width="1160px" style="margin-bottom: 15px;">
                        @foreach ($bilhetes->sortBy('data') as $bilhete)
                            <tr>
                                <td colspan="5"><b>Company:</b> {{ $bilhete->nome }}</td>
                            </tr>
                            <tr>
                                <td width="350px"><b>Date:</b>
                                    {{ Carbon\Carbon::parse($bilhete->data)->format('d-m-y') }}
                                </td>
                                <td width="200px"><b>Hour:</b> {{ Carbon\Carbon::parse($bilhete->hora)->format('H:i') }}
                                </td>
                                <td width="200px"><b></td>
                                <td width="300px"><b></td>
                                <td width="200px"><b>Total €:</b> {{ number_format($bilhete->total, 2, ',', '.') }}</td>
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

                            @php $old_produto_id = $bilhete->pedido_produto_id; @endphp
                        @endforeach

                        @php
                            $PedidosIds = $bilhetes->unique('pedido_produto_id')->pluck('pedido_produto_id');
                            $ValoresPedidosProduto = App\ValorTicket::whereIn('pedido_produto_id', $PedidosIds->toArray())->get();
                            $dados = renderPrintSubTotalSection($ValoresPedidosProduto, 'valor_ticket', $total, $subtotal , $m = false, $k = true, $t = true);
                            $total += $dados->total;
                        @endphp
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


        <div class="container">
            <div class="w3-row-padding w3-padding-28" style="border-top: 2px solid #00bad1;">
                <table width="100%" style="margin-bottom: 15px;">
                    <tr>
                        <td width="60%"></td>
                        <td width="25%" style="text-align: right;"><b>TOTAL BOOKING € :</b></td>
                        <td width="15%"> {{ formatarDecimal($total) }}</td>
                    </tr>
                    @foreach ($payments as $payment)
                        <tr>
                            <td width="60%"></td>
                            <td width="25%" style="text-align: right;">
                                {{ Carbon\Carbon::parse($payment->date)->format('d/m/y') }} -
                                <b>PAID:</b>
                            </td>
                            <td width="15%"> {{ formatarDecimal($payment->payment) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td width="60%"></td>
                        <td width="25%" style="border-top: 2px solid; text-align: right;"><b>TOTAL PAID € :</b></td>
                        <td width="15%" style="border-top: 2px solid"> {{ formatarDecimal($total_payments) }}
                        </td>
                    </tr>
                    <tr>
                        <td width="60%"></td>
                        <td width="25%" style="border-top: 2px solid; text-align: right;"><b>DUE €:</b></td>
                        <td width="15%" style="border-top: 2px solid">
                            {{ formatarDecimal($total - $total_payments) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

    @endsection

@endif
