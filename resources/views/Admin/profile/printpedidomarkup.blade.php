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
            }

        </style>
        @push('javascript')
            <script type="text/javascript" src="{{ URL::asset('Admin/js/node_modules/printthis/printThis.js') }}"></script>
            <script type="text/javascript" src="{{ URL::asset('Admin/js/voucher.js') }}"></script>
            <script type="text/javascript">
                load = "{{ asset('Admin/css/w3.css') }}";
            </script>
        @endpush



        <div class="container" id="cabeca">
            <div class="w3-row-padding">
                <table>
                    <td style="width: 50%;">
                        <img class="w3-margin-bottom" id="editSupplier_img" width="70%" style="width:70%; margin:0 auto;"
                            src="<?php echo asset('storage/LogotipoAtravelCor.png'); ?>">
                    </td>
                    <td align="right" style="width: 50%;">
                        <b>
                            <font size="2">In partnership with:</font>
                        </b>
                        @php
                            $imgPath = $usuario->path_image;
                            if (preg_match('/_user/', $usuario->path_image)) {
                                $imgPath = asset(str_replace('/storage/app/public', '/storage', $usuario->path_image));
                            } else {
                                $imgPath = asset('/storage/' . $imgPath);
                            }
                        @endphp


                        <img class="w3-margin-bottom" id="editSupplier_img" width="65%" style="width:65%;"
                            src="{{ $imgPath }}">
                    </td>
                </table>

                <div class="w3-col l12" style="margin-top:10px; margin-bottom:20px;">
                    <div class="form-group">
                        <table width="100%" class="w3-centered">
                            <tr>
                                <th style="text-align: left; width:30%;">TOPICOS E DESCOBERTAS LDA</th>
                                <td align="right" style="text-align: left; width:25%; font-size: small; width: 30%;">
                                    Telef.(+351) 282-457 306</td>
                                <td style="width: 40%;"></td>
                            </tr>
                            <tr>
                                <td class="footer" style="text-align: left; color:#24AEC9;">Av. da Liberdade,</td>
                                <td style="text-align: left; font-size: small;">Mobile.(+351) 912 032 695</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="footer" style="text-align: left; color:#24AEC9;">245, 4ºA </td>
                                <td style="text-align: left; font-size: small;">reservations: incoming@atravel.pt</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="footer" style="text-align: left; color:#24AEC9;">1250-143 , Lisboa</td>
                                <td style="text-align: left; font-size: small;">www.atstravel.pt</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="text-align: left; font-size: small; color:#24AEC9;">Licence RNVAT 3559</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="color:#24AEC9;text-align: left; font-size: small;">VAT 505 121 433</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="w3-row-padding w3-padding-32" style="border-top: 2px solid #00bad1;">
                <table width="100%" style="margin-bottom: 15px;">
                    <tr>
                        <td><b>Agency:</b> {{ $usuario->name }}</td>
                        <td><b>Lead Name:</b> {{ $pedido_geral->lead_name }} </td>
                        <td><b>Responsable:</b> {{ $pedido_geral->responsavel }} </td>
                        <td><b>Reference</b> {{ $pedido_geral->referencia }} </td>
                    </tr>
                </table>
            </div>
        </div>
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
                        @endphp

                        @foreach ($quartos->sortBy('checkin') as $quarto)
                            @php
                                $valor_quarto = App\ValorQuarto::where('pedido_produto_id', $quarto->pedido_produto_id)->first();
                                $kick = ($valor_quarto->kick * $valor_quarto->valor_quarto) / 100;
                                $markup = ($valor_quarto->markup * $valor_quarto->valor_quarto) / 100;
                                $valor_quarto_markup_kick = $valor_quarto->valor_quarto - $kick + $markup;

                                $valor_quarto_id = (int) $valor_quarto->id;
                                if ($id != $valor_quarto_id) {
                                    $kick_back += $kick;
                                    $id = $valor_quarto->id;

                                    if ($valor_quarto->kick != null || $valor_quarto->markup != null) {
                                        $subtotal += $valor_quarto_markup_kick;
                                    } else {
                                        $subtotal += $valor_quarto->valor_quarto;
                                    }
                                } elseif ($subtotal == 0) {
                                    if ($valor_quarto->kick != null || $valor_quarto->markup != null) {
                                        $subtotal = $valor_quarto_markup_kick;
                                    } else {
                                        $subtotal = $valor_quarto->valor_quarto;
                                    }
                                }
                            @endphp

                            <tr>
                                <td colspan="5"><b>Hotel:</b> {{ $quarto->nome }}</td>
                            </tr>
                            <tr>
                                <td width="20%"><b>C. in:</b>
                                    {{ Carbon\Carbon::parse($quarto->checkin)->format('d-m-y') }}
                                </td>
                                <td width="22%"><b>C. out:</b>
                                    {{ Carbon\Carbon::parse($quarto->checkout)->format('d-m-y') }}
                                </td>
                                <td width="13%"><b>Rooms:</b> {{ $quarto->rooms }}</td>
                                <td width="24%"><b>Room Type:</b> {{ $quarto->type }}</td>
                                <td width="13%"><b>Board:</b> {{ $quarto->plan }}</td>
                                <td width="8%"><b>Pax:</b> {{ $quarto->people }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                            </tr>
                            @if ($old_produto_id != $quarto->pedido_produto_id)
                                @foreach ($extras_quartos as $extra_quarto)
                                    @if ($extra_quarto->pedido_produto_id == $quarto->pedido_produto_id)
                                        <tr>
                                            <td colspan="2"><b>Extra name:</b> {{ $extra_quarto->name }}</td>
                                            <td><b>Qty:</b> {{ $extra_quarto->amount }}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        @php $subtotal += $extra_quarto->total; @endphp
                                    @endif
                                @endforeach
                            @endif
                            <tr>
                                <td colspan="3"><br>{!! html_entity_decode($quarto->remark) !!}</td>
                            </tr>
                            <tr>
                                <td colspan="5"><br></td>
                            </tr>
                            @php
                                $old_produto_id = $quarto->pedido_produto_id;
                            @endphp
                        @endforeach
                        @php $total = $total + $subtotal; @endphp
                        @if (isset($kick_back) && $kick_back != 0)
                            <tr>
                                <td colspan="3"></td>
                                <!--<td style="text-align: right;"><b>KICKBACK:</b></td>
                                        <td> {{ number_format($kick_back, 2, ',', '.') }}</td>-->
                            </tr>
                        @endif
                        <tr>
                            <td colspan="3"></td>
                        </tr>
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
                    @endphp
                    <table width="100%" style="margin-bottom: 15px;">
                        @foreach ($golfes->sortBy('data') as $golfe)
                            @php
                                $valor_golves = App\ValorGolf::where('pedido_produto_id', $golfe->pedido_produto_id)->first();
                                $kick = ($valor_golves->kick * $valor_golves->valor_golf) / 100;
                                $markup = ($valor_golves->markup * $valor_golves->valor_golf) / 100;
                                $valor_golves_markup_kick = $valor_golves->valor_golf - $kick + $markup;

                                $valor_id = (int) $valor_golves->id;
                                if ($id != $valor_id) {
                                    $kick_back += $kick;
                                    $id = $valor_golves->id;

                                    if ($valor_golves->kick != null || $valor_golves->markup != null) {
                                        $subtotal += $valor_golves_markup_kick;
                                    } else {
                                        $subtotal += $valor_golves->valor_golf;
                                    }
                                } elseif ($subtotal == 0) {
                                    if ($valor_golves->kick != null || $valor_golves->markup != null) {
                                        $subtotal = $valor_golves_markup_kick;
                                    } else {
                                        $subtotal = $valor_golves->valor_golf;
                                    }
                                }

                            @endphp
                            <tr>
                                <td colspan="5"><b>Golf Course:</b> {{ $golfe->nome }}</td>
                            </tr>
                            <tr>
                                <td width="40%"><b>Date:</b> {{ Carbon\Carbon::parse($golfe->data)->format('d-m-y') }}
                                </td>
                                <td width="40%"><b>Starting Time:</b>
                                    {{ Carbon\Carbon::parse($golfe->hora)->format('H:i') }}
                                </td>
                                <td width="20%"><b>Pax:</b> {{ $golfe->people }}</td>
                            </tr>
                            @if ($old_produto_id != $golfe->pedido_produto_id)
                                @foreach ($extras_golfes as $extra_golfe)
                                    @if ($extra_golfe->pedido_produto_id == $golfe->pedido_produto_id)
                                        <tr>
                                            <td colspan="2"><b>Extra name:</b> {{ $extra_golfe->name }}</td>
                                            <td><b>Qty:</b> {{ $extra_golfe->amount }}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        @php $subtotal = $subtotal + $extra_golfe->total; @endphp
                                    @endif
                                @endforeach
                            @endif
                            <tr>
                                <td colspan="3"><br>{!! html_entity_decode($golfe->remark) !!}</td>
                            </tr>
                            <tr>
                                <td colspan="5"><br></td>
                            </tr>
                            @php
                                $old_produto_id = $golfe->pedido_produto_id;
                            @endphp
                        @endforeach
                        @php $total = $total + $subtotal; @endphp
                        @if (isset($kick_back) && $kick_back != 0)
                            <tr>
                                <td colspan="3"></td>
                                <!--<td style="text-align: right;"><b>KICKBACK:</b></td>
                                        <td> {{ number_format($kick_back, 2, ',', '.') }}</td>-->
                            </tr>
                        @endif
                        <tr>
                            <td colspan="3"></td>
                        </tr>
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
                    @endphp
                    <table width="100%" style="margin-bottom: 15px;">
                        @foreach ($transfers->sortBy('data') as $transfer)
                            @php
                                $valor_transfer = App\ValorTransfer::where('pedido_produto_id', $transfer->pedido_produto_id)->first();
                                $kick = ($valor_transfer->kick * $valor_transfer->valor_transfer) / 100;
                                $markup = ($valor_transfer->markup * $valor_transfer->valor_transfer) / 100;
                                $valor_transfer_markup_kick = $valor_transfer->valor_transfer - $kick + $markup;

                                $valor_transfer_id = (int) $valor_transfer->id;
                                if ($id != $valor_transfer_id) {
                                    $kick_back += $kick;
                                    $id = $valor_transfer->id;

                                    if ($valor_transfer->kick != null || $valor_transfer->markup != null) {
                                        $subtotal += $valor_transfer_markup_kick;
                                    } else {
                                        $subtotal += $valor_transfer->valor_transfer;
                                    }
                                } elseif ($subtotal == 0) {
                                    if ($valor_transfer->kick != null || $valor_transfer->markup != null) {
                                        $subtotal = $valor_transfer_markup_kick;
                                    } else {
                                        $subtotal = $valor_transfer->valor_transfer;
                                    }
                                }
                            @endphp
                            <tr>
                                <td colspan="5"><b>Company:</b> {{ $transfer->nome }}</td>
                            </tr>
                            <tr width="100%" style="margin-bottom:20px">
                                <td colspan="5"> <b>Date:</b>
                                    {{ Carbon\Carbon::parse($transfer->data)->format('d-m-y') }}
                                    |
                                    <b>Time:</b> {{ Carbon\Carbon::parse($transfer->hora)->format('H:i') }}
                                </td>
                            </tr>
                            <tr>
                                <td width="25%"><b>From:</b> {{ $transfer->pickup }}</td>
                                <td width="20%"><b>To:</b> {{ $transfer->dropoff }}</td>
                                <td width="18%"><b>Flight Nº:</b> {{ $transfer->flight }}</td>
                                <td width="15%"><b>Adults:</b> {{ $transfer->adult }}</td>
                                <td width="12%"><b>Children:</b> {{ $transfer->children }}</td>
                                <td width="11%"><b>Babies:</b> {{ $transfer->babie }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @if ($old_produto_id != $transfer->pedido_produto_id)
                                @foreach ($extras_transfers as $extra_transfer)
                                    @if ($extra_transfer->pedido_produto_id == $transfer->pedido_produto_id)
                                        <tr>
                                            <td colspan="2"><b>Extra name:</b> {{ $extra_transfer->name }}</td>
                                            <td><b>Qty:</b> {{ $extra_transfer->amount }}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        @php $subtotal = $subtotal + $extra_transfer->total; @endphp
                                    @endif
                                @endforeach
                            @endif
                            <tr>
                                <td colspan="3"><br>{!! html_entity_decode($transfer->remark) !!}</td>
                            </tr>
                            <tr>
                                <td colspan="5"><br></td>
                            </tr>
                            @php
                                $old_produto_id = $transfer->pedido_produto_id;
                            @endphp
                        @endforeach
                        @php $total = $total + $subtotal; @endphp
                        @if (isset($kick_back) && $kick_back != 0)
                            <tr>
                                <td colspan="3"></td>
                                <!--<td style="text-align: right;"><b>KICKBACK:</b></td>
                                        <td> {{ number_format($kick_back, 2, ',', '.') }}</td>-->
                            </tr>
                        @endif
                        <tr>
                            <td colspan="3"></td>
                        </tr>
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
                    @endphp
                    <table width="100%" style="margin-bottom: 15px;">
                        @foreach ($carros->sortBy('pickup_data') as $carro)
                            @php
                                $valor_car = App\ValorCar::where('pedido_produto_id', $carro->pedido_produto_id)->first();
                                $kick = ($valor_car->kick * $valor_car->valor_car) / 100;
                                $markup = ($valor_car->markup * $valor_car->valor_car) / 100;
                                $valor_car_markup_kick = $valor_car->valor_car - $kick + $markup;

                                $valor_car_id = (int) $valor_car->id;
                                if ($id != $valor_car_id) {
                                    $kick_back += $kick;
                                    $id = $valor_car->id;

                                    if ($valor_car->kick != null || $valor_car->markup != null) {
                                        $subtotal += $valor_car_markup_kick;
                                    } else {
                                        $subtotal += $valor_car->valor_car;
                                    }
                                } elseif ($subtotal == 0) {
                                    if ($valor_car->kick != null || $valor_car->markup != null) {
                                        $subtotal = $valor_car_markup_kick;
                                    } else {
                                        $subtotal = $valor_car->valor_car;
                                    }
                                }
                            @endphp
                            <tr>
                                <td colspan="5"><b>Company:</b> {{ $carro->nome }}</td>
                            </tr>
                            <tr style="margin-top: 20px;">
                                <td colspan="5"></td>
                            </tr>
                            <tr>
                                <td width="35%"><b>Drop Off Location:</b> {{ $carro->dropoff }} </td>
                                <td width="25%"><b>Pick-up Date:</b>
                                    {{ Carbon\Carbon::parse($carro->pickup_data)->format('d-m-y') }}
                                </td>
                                <td width="20%"><b>Pick-up Hour:</b>
                                    {{ Carbon\Carbon::parse($carro->pickup_hora)->format('H:i') }}
                                </td>
                                <td width="20%"><b>TAX:</b> {{ number_format($carro->tax, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td><b>Pick-up Location:</b> {{ $carro->pickup }} </td>
                                <td><b>Drop Off Date:</b>
                                    {{ Carbon\Carbon::parse($carro->dropoff_data)->format('d-m-y') }}
                                </td>
                                <td><b>Drop Off Hour:</b> {{ Carbon\Carbon::parse($carro->dropoff_hora)->format('H:i') }}
                                </td>
                                <td width="20%"><b>Tax Type:</b>{{ $carro->tax_type }}</td>
                                <td colspan="2"></td>
                            </tr>
                            @if (($old_produto_id != 0 && $old_produto_id != $carro->pedido_produto_id) || $loop->last)
                                @foreach ($extras_carros as $extra_carro)
                                    @if ($extra_carro->pedido_produto_id == $carro->pedido_produto_id)
                                        <tr>
                                            <td colspan="2"><b>Extra name:</b> {{ $extra_carro->name }}</td>
                                            <td><b>Qty:</b> {{ $extra_carro->amount }}</td>
                                        </tr>
                                        @php $subtotal = $subtotal + $extra_carro->total; @endphp
                                    @endif
                                @endforeach
                            @endif
                            <tr>
                                <td colspan="3"><br>{!! html_entity_decode($carro->remark) !!}</td>
                            </tr>
                            <tr>
                                <td colspan="5"><br></td>
                            </tr>
                            @php
                                $old_produto_id = $carro->pedido_produto_id;
                            @endphp
                        @endforeach
                        @php $total = $total + $subtotal; @endphp
                        @if (isset($kick_back) && $kick_back != 0)
                            <tr>
                                <td colspan="3"></td>
                                <!--<td style="text-align: right;"><b>KICKBACK:</b></td>
                                            <td> {{ number_format($kick_back, 2, ',', '.') }}</td>-->
                            </tr>
                        @endif
                        <tr>
                            <td colspan="3"></td>
                        </tr>
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
                    @endphp
                    <table width="100%" style="margin-bottom: 15px;">
                        @foreach ($bilhetes->sortBy('data') as $bilhete)
                            @php
                                $valor_ticket = App\ValorTicket::where('pedido_produto_id', $bilhete->pedido_produto_id)->first();
                                $markup = ($valor_ticket->markup * $valor_ticket->valor_ticket) / 100;
                                $kick = ($valor_ticket->kick * $valor_ticket->valor_ticket) / 100;
                                $valor_ticket_markup_kick = $valor_ticket->valor_ticket - $kick + $markup;

                                $valor_ticket_id = (int) $valor_ticket->id;
                                if ($id != $valor_ticket_id) {
                                    $kick_back += $kick;
                                    $id = $valor_ticket->id;

                                    if ($valor_ticket->kick != null || $valor_ticket->markup != null) {
                                        $subtotal += $valor_ticket_markup_kick;
                                    } else {
                                        $subtotal += $valor_ticket->valor_ticket;
                                    }
                                } elseif ($subtotal == 0) {
                                    if ($valor_ticket->kick != null || $valor_ticket->markup != null) {
                                        $subtotal = $valor_ticket_markup_kick;
                                    } else {
                                        $subtotal = $valor_ticket->valor_ticket;
                                    }
                                }
                            @endphp
                            <tr>
                                <td colspan="5"><b>Company:</b> {{ $bilhete->nome }}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Date:</b> {{ Carbon\Carbon::parse($bilhete->data)->format('d-m-y') }}
                                </td>
                                <td width="25%"><b>Hour:</b> {{ Carbon\Carbon::parse($bilhete->hora)->format('H:i') }}
                                </td>
                                <td width="15%"><b>Adults:</b> {{ $bilhete->adult }}</td>
                                <td width="15%"><b>Children:</b> {{ $bilhete->children }}</td>
                                <td width="15%"><b>Babies:</b> {{ $bilhete->babie }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td colspan="2"></td>
                            </tr>
                            @if (($old_produto_id != 0 && $old_produto_id != $bilhete->pedido_produto_id) || $loop->last)
                                @foreach ($extras_bilhetes as $extra_bilhete)
                                    @if ($extra_bilhete->pedido_produto_id == $bilhete->pedido_produto_id)
                                        <tr>
                                            <td colspan="2"><b>Extra name:</b> {{ $extra_bilhete->name }}</td>
                                            <td><b>Qty:</b> {{ $extra_bilhete->amount }}</td>
                                            <td></td>
                                            <td></td>
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
                        @php $total = $total + $subtotal; @endphp

                        @if (isset($kick_back) && $kick_back != 0)
                            <tr>
                                <td colspan="3"></td>
                                <!--<td style="text-align: right;"><b>KICKBACK:</b></td>
                                            <td> {{ number_format($kick_back, 2, ',', '.') }}</td>-->
                            </tr>
                        @endif
                        <tr>
                            <td colspan="3"></td>
                        </tr>
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
                        <td width="25%" style="text-align: right;"><b>TOTAL:</b></td>
                        <td width="15%"> {{ number_format($total, 2, ',', '.') }}</td>
                    </tr>
                    @foreach ($payments as $payment)
                        <tr>
                            <td width="60%"></td>
                            <td width="25%" style="text-align: right;">
                                {{ Carbon\Carbon::parse($payment->date)->format('d/m/y') }} -
                                <b>PAGO:</b>
                            </td>
                            <td width="15%"> {{ number_format($payment->payment, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td width="60%"></td>
                        <td width="25%" style="border-top: 2px solid; text-align: right;"><b>TOTAL PAGO:</b></td>
                        <td width="15%" style="border-top: 2px solid"> {{ number_format($total_payments, 2, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td width="60%"></td>
                        <td width="25%" style="border-top: 2px solid; text-align: right;"><b>POR PAGAR:</b></td>
                        <td width="15%" style="border-top: 2px solid">
                            {{ number_format($total - $total_payments, 2, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    @endsection

@endif
