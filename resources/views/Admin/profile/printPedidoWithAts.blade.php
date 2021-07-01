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
        /* Chrome sets own margins, we change these printer settings */
        margin: 27mm 16mm 27mm 16mm;
    }

    .container {
        width: 1160px;
        padding: inherit;
    }

    .w3-row-padding {
        padding: inherit;
    }

    @media print
    {
        @page {
            size: A4; /* DIN A4 standard, Europe */
            margin:0;
        }
        html, body {
            width: 130mm;
            height: 282mm;
            overflow:visible;
        }
        body {
            padding-top:15mm;
        }
    }

</style>
<script type="text/javascript" src="{{ URL::asset('Admin/js/node_modules/printthis/printThis.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('Admin/js/voucher.js') }}"></script>
<script type="text/javascript">
    load = "{{ asset('Admin/css/w3.css') }}";

</script>




<div class="container" id="cabeca">
    <div class="w3-row-padding">
        <table>
            <tr>
                <td style="width: 50%;">
                    <img class="w3-margin-bottom" id="editSupplier_img" width="70%" style="width:70%; margin:0 auto;"
                        src="<?php echo asset("storage/LogotipoAtravelCor.png")?>">
                </td>
                <td align="right" style="width: 50%;">
                    <b>
                        <font size="2">In partnership with:</font>
                    </b>

                    {{-- @php $imgPath = $usuario->path_image; @endphp

                    @if(preg_match('/_user/', $imgPath) )
                    @php
                    $imgPath = asset("storage/".str_replace("public/storage/", '', $imgPath ));
                    $imgPath = str_replace("public/storage//", '', $usuario->path_image);
                    @endphp
                    @else
                    @php
                    $imgPath = asset("storage/".$imgPath );
                    @endphp
                    @endif --}}
                    @php
                        $imgPath = str_replace("/storage/app/public","/storage",$usuario->path_image)
                    @endphp


                    <img class="w3-margin-bottom" id="editSupplier_img" width="65%" style="width:65%;"
                        src="{{asset( $imgPath )}}">
                </td>
            </tr>
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
                        <td style="text-align: left; font-size: small; color:#24AEC9;">Licence RNVAT 8019</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="color:#24AEC9;text-align: left; font-size: small;">VAT 514 974 567</td>
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

@if($quartos->isEmpty() != true)
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

            @foreach($quartos->sortBy("nome")->sortBy("checkin") as $quarto)


            <tr>
                <td colspan="5"><b>Hotel:</b> {{$quarto->nome}}</td>
            </tr>
            <tr>
                <td width="30%"><b>C. in:</b> {{ Carbon\Carbon::parse($quarto->checkin)->format('d-m-Y') }}</td>
                <td width="20%"><b>C. out:</b> {{ Carbon\Carbon::parse($quarto->checkout)->format('d-m-Y') }} </td>
                <td width="20%"><b>Rooms:</b> {{$quarto->rooms}}</td>
                <td width="15%"><b>Unit €:</b> {{ number_format($quarto->price, 2, ',', '.') }}</td>
                <td width="15%"><b>Total €:</b> {{ number_format($quarto->total, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td><b>Room Type:</b> {{$quarto->type}}</td>
                <td><b>Board:</b> {{$quarto->plan}}</td>
                <td><b>Pax:</b> {{$quarto->people}}</td>
                <td width="15%"><b style="color:#FF5722">ATS RATE €:</b>
                    {{ number_format($quarto->ats_rate, 2, ',', '.') }}</td>
                <td width="15%"><b style="color:#FF5722">ATS TOTAL €:</b>
                    {{ number_format($quarto->ats_total_rate, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td width="15%"><b style="color:green">Profit €:</b>
                    {{ number_format(\App\PedidoQuarto::find($quarto->PedidoItemId)->profit, 2, ',', '.') }}</td>
            </tr>
            @if($old_produto_id != $quarto->pedido_produto_id)
            @foreach($extras_quartos as $extra_quarto)
            @if($extra_quarto->pedido_produto_id == $quarto->pedido_produto_id)
            <tr>
                <td colspan="2"><b>Extra name:</b> {{ $extra_quarto->name }}</td>
                <td><b>Qty:</b> {{ $extra_quarto->amount }}</td>
                <td><b>Unit €:</b> {{ number_format($extra_quarto->rate, 2, ',', '.') }}</td>
                <td><b>Total €:</b> {{ number_format($extra_quarto->total, 2, ',', '.') }}</td>
            </tr>
            @php $subtotal += $extra_quarto->total; @endphp
            @endif
            @endforeach
            @endif
            @if($quarto->remark)
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


            @endforeach {{-- end foreach quartos --}}

            @php

            $PedidosIds = $quartos->unique('pedido_produto_id')->pluck('pedido_produto_id');
            $ValoresPedidosProduto = App\ValorQuarto::whereIn('pedido_produto_id', $PedidosIds->toArray() )->get();
            $profit = 0;
            foreach($ValoresPedidosProduto as $valor){
            $kick = ($valor->kick * $valor->valor_quarto)/100;
            $valor_markup_kick = $valor->valor_quarto - $kick;

            if($valor->kick != null || $valor->markup != null){
            $subtotal += $valor_markup_kick;
            }else{
            $subtotal += $valor->valor_quarto;
            }
            $profit += $valor->profit;
            }
            $total = $total + $subtotal;
            @endphp
            @if(isset($kick_back) && $kick_back != 0)
            <tr>
                <td colspan="3"></td>
                <td style="border-top: 2px solid; text-align: right;"><b>KICKBACK:</b></td>
                <td style="border-top: 2px solid"> {{ number_format($kick_back, 2, ',', '.') }}</td>
            </tr>
            @endif
            <tr>
                <td colspan="3"></td>
                <td style="border-top: 2px solid; text-align: right;"><b>SUBTOTAL:</b></td>
                <td style="border-top: 2px solid"> {{ number_format(floor($subtotal*100)/100 ,2, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td style="border-top: 2px solid; text-align: right;"><b style="color:#FF5722">ATS RATE:</b></td>
                <td style="border-top: 2px solid"> {{ number_format(floor($totalAtsPedido*100)/100 , 2, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td style="border-top: 2px solid; text-align: right;"><b
                        style="color:green; font-weight:bold">PROFIT:</b></td>
                <td style="border-top: 2px solid"> {{ number_format(floor($profit*100)/100 , 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>
</div>
@endif

<!--golf-->



@if($golfes->isEmpty() != true)
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
            @foreach($golfes->sortBy("nome")->sortBy("data") as $golfe)
            <tr>
                <td colspan="5"><b>Golf Course:</b> {{$golfe->nome}}</td>
            </tr>
            <tr>
                <td width="30%"><b>Date:</b> {{ Carbon\Carbon::parse($golfe->data)->format('d-m-Y') }} </td>
                <td width="20%"><b>Starting Time:</b> {{ Carbon\Carbon::parse($golfe->hora)->format('H:i') }} </td>
                <td width="20%"><b>Pax:</b> {{$golfe->people}}</td>
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
                    {{ number_format(\App\PedidoGame::find($golfe->PedidoItemId)->profit, 2, ',', '.') }}</td>
            </tr>

            @if($old_produto_id != $golfe->pedido_produto_id)
            @foreach($extras_golfes as $extra_golfe)
            @if($extra_golfe->pedido_produto_id == $golfe->pedido_produto_id)
            <tr>
                <td><b>Extra name:</b> {{ $extra_golfe->name }}</td>
                <td><b>Qty:</b> {{ $extra_golfe->amount }}</td>
                <td></td>
                <td><b>Unit €:</b> {{ number_format($extra_golfe->rate, 2, ',', '.') }}</td>
                <td><b>Total €:</b> {{ number_format($extra_golfe->total, 2, ',', '.') }}</td>
            </tr>
            @php $subtotal = $subtotal + $extra_golfe->total; @endphp
            @endif
            @endforeach
            @endif
            @if($golfe->remark)
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
            $ValoresPedidosProduto = App\ValorGolf::whereIn('pedido_produto_id', $PedidosIds->toArray() )->get();
            $profit = 0;
            foreach($ValoresPedidosProduto as $valor){
            $kick = ($valor->kick * $valor->valor_golf)/100;
            $valor_markup_kick = $valor->valor_golf - $kick;
            if($valor->kick != null || $valor->markup != null){
            $subtotal += $valor_markup_kick;
            }else{
            $subtotal += $valor->valor_golf;
            }
            $profit += $valor->profit;
            }
            $total = $total + $subtotal;
            @endphp
            @if(isset($kick_back) && $kick_back != 0)
            <tr>
                <td colspan="3"></td>
                <td style="border-top: 2px solid; text-align: right;"><b>KICKBACK:</b></td>
                <td style="border-top: 2px solid"> {{ number_format($kick_back, 2, ',', '.') }}</td>
            </tr>
            @endif
            <tr>
                <td colspan="3"></td>
                <td style="border-top: 2px solid; text-align: right;"><b>SUBTOTAL:</b></td>
                <td style="border-top: 2px solid"> {{ number_format(floor($subtotal*100)/100 , 2) }}</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td style="border-top: 2px solid; text-align: right;"><b style="color:#FF5722">ATS RATE:</b></td>
                <td style="border-top: 2px solid"> {{ number_format(floor($totalAtsPedido*100)/100 , 2, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td style="border-top: 2px solid; text-align: right;"><b
                        style="color:green; font-weight:bold">PROFIT:</b></td>
                <td style="border-top: 2px solid"> {{ number_format(floor($profit*100)/100 , 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>
</div>
@endif

<!--transfer-->
@if($transfers->isEmpty() != true)
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
            @foreach($transfers->sortBy("nome")->sortBy("data") as $uniqueTransfer)

            <tr>
                <td colspan="5"><b>Company:</b> {{$uniqueTransfer->nome}}</td>
            </tr>
            <tr width="100%" style="margin-bottom:20px">
                <td colspan="5"> <b>Date:</b> {{ Carbon\Carbon::parse( $uniqueTransfer->data )->format('d-m-y') }} |
                    <b>Time:</b> {{ Carbon\Carbon::parse( $uniqueTransfer->hora)->format('H:i') }}</td>
            </tr>
            <tr>
                <td width="30%"><b>From:</b> {{$uniqueTransfer->pickup}}</td>
                <td width="20%"><b>To:</b> {{$uniqueTransfer->dropoff}}</td>
                <td width="20%"><b>Flight Nº:</b> {{$uniqueTransfer->flight}}</td>
                <td width="15%"><b></td>
                <td width="15%"><b>Total €:</b> {{ number_format($uniqueTransfer->total, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td><b>Adults:</b> {{$uniqueTransfer->adult}}</td>
                <td><b>Children:</b> {{$uniqueTransfer->children}}</td>
                <td><b>Babies:</b> {{$uniqueTransfer->babie}}</td>
                <td width="15%"><b style="color:#FF5722">ATS RATE €:</b>
                    {{ number_format($uniqueTransfer->ats_rate, 2, ',', '.') }}</td>
                <td width="15%"><b style="color:green">Profit €:</b>
                    {{ number_format(\App\PedidoTransfer::find($uniqueTransfer->PedidoItemId)->profit, 2, ',', '.') }}
                </td>
            </tr>



            @if($old_produto_id != $uniqueTransfer->pedido_produto_id)
            @foreach($extras_transfers as $extra_transfer)
            @if($extra_transfer->pedido_produto_id == $uniqueTransfer->pedido_produto_id)
            <tr>
                <td colspan="2"><b>Extra name:</b> {{ $extra_transfer->name }}</td>
                <td><b>Qty:</b> {{ $extra_transfer->amount }}</td>
                <td><b>Unit €:</b> {{ number_format($extra_transfer->rate, 2, ',', '.') }}</td>
                <td><b>Total €:</b> {{ number_format($extra_transfer->total, 2, ',', '.') }}</td>
            </tr>
            @php $subtotal = $subtotal + $extra_transfer->total; @endphp
            @endif
            @endforeach
            @endif
            @if($uniqueTransfer->remark)
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
            $ValoresPedidosProduto = App\ValorTransfer::whereIn('pedido_produto_id', $PedidosIds->toArray() )->get();
            $profit = 0;
            foreach($ValoresPedidosProduto as $valor){
            $kick = ($valor->kick * $valor->valor_transfer)/100;
            $valor_markup_kick = $valor->valor_transfer - $kick;
            if($valor->kick != null || $valor->markup != null){
            $subtotal += $valor_markup_kick;
            }else{
            $subtotal += $valor->valor_transfer;
            }
            $profit += $valor->profit;
            }
            $total = $total + $subtotal;
            @endphp
            @if(isset($kick_back) && $kick_back != 0)
            <tr>
                <td colspan="3"></td>
                <td style="border-top: 2px solid; text-align: right;"><b>KICKBACK:</b></td>
                <td style="border-top: 2px solid"> {{ number_format($kick_back, 2, ',', '.') }}</td>
            </tr>
            @endif
            <tr>
                <td colspan="3"></td>
                <td style="border-top: 2px solid; text-align: right;"><b>SUBTOTAL:</b></td>
                <td style="border-top: 2px solid"> {{ number_format(floor($subtotal*100)/100 , 2) }}</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td style="border-top: 2px solid; text-align: right;"><b style="color:#FF5722">ATS RATE:</b></td>
                <td style="border-top: 2px solid"> {{ number_format(floor($totalAtsPedido*100)/100 , 2, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td style="border-top: 2px solid; text-align: right;"><b
                        style="color:green; font-weight:bold">PROFIT:</b></td>
                <td style="border-top: 2px solid"> {{ number_format( floor($profit*100)/100 , 2, ',', '.') }}</td>
            </tr>
        </table>


    </div>
</div>
@endif


<!--car-->
@if($carros->isEmpty() != true)
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
            @foreach($carros->sortBy("nome")->sortBy("pickup_data") as $carro)
            <tr>
                <td colspan="5"><b>Company:</b> {{$carro->nome}}</td>
            </tr>
            <tr style="margin-top: 20px;">
                <td colspan="5"><b>Drop Off Location:</b> {{$carro->dropoff}}</td>
            </tr>
            <tr>
                <td width="30%"><b>Pick-up Date:</b> {{ Carbon\Carbon::parse( $carro->pickup_data )->format('d-m-y') }}
                </td>
                <td width="20%"><b>Pick-up Hour:</b> {{ Carbon\Carbon::parse( $carro->pickup_hora )->format('H:i') }}
                </td>
                <td width="20%"><b>TAX:</b> {{ number_format($carro->tax, 2, ',', '.') }}</td>
                <td width="15%"><b>Unit €:</b> {{ number_format($carro->rate, 2, ',', '.') }}</td>
                <td width="15%"><b>Total €:</b> {{ number_format($carro->total, 2, ',', '.') }}</td>
            </tr>
            <tr style="margin-top: 20px;">
                <td colspan="5"><b>Pick-up Location:</b> {{$carro->pickup}}</td>
            </tr>
            <tr>
                <td><b>Drop Off Date:</b> {{ Carbon\Carbon::parse( $carro->dropoff_data )->format('d-m-y') }} </td>
                <td><b>Drop Off Hour:</b> {{ Carbon\Carbon::parse( $carro->dropoff_hora )->format('H:i') }} </td>
                <td width="20%"><b>Tax Type:</b>{{$carro->tax_type}}</td>

                <td width="15%"><b style="color:#FF5722">ATS RATE €:</b>
                    {{ number_format($golfe->ats_rate, 2, ',', '.') }}</td>
                <td width="15%"><b style="color:#FF5722">ATS TOTAL €:</b>
                    {{ number_format($golfe->ats_total_rate, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td width="15%"><b style="color:green">Profit €:</b>
                    {{ number_format(\App\PedidoCar::find($carro->PedidoItemId)->profit, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td><b>Group:</b> {{ $carro->group }}</td>
                <td></td>
                <td width="20%"></td>
                <td colspan="2"></td>
            </tr>
            @if(($old_produto_id != 0 && $old_produto_id != $carro->pedido_produto_id) || $loop->last)
            @foreach($extras_carros as $extra_carro)
            @if($extra_carro->pedido_produto_id == $carro->pedido_produto_id)
            <tr>
                <td colspan="2"><b>Extra name:</b> {{ $extra_carro->name }}</td>
                <td><b>Qty:</b> {{ $extra_carro->amount }}</td>
                <td><b>Unit €:</b> {{ number_format($extra_carro->rate, 2, ',', '.') }}</td>
                <td><b>Total €:</b> {{ number_format($extra_carro->total, 2, ',', '.') }}</td>
            </tr>
            @php $subtotal = $subtotal + $extra_carro->total; @endphp
            @endif
            @endforeach
            @endif
            @if($carro->remark)
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
            $ValoresPedidosProduto = App\ValorCar::whereIn('pedido_produto_id', $PedidosIds->toArray() )->get();
            $profit = 0;
            foreach($ValoresPedidosProduto as $valor){
            $kick = ($valor->kick * $valor->valor_car)/100;
            $valor_markup_kick = $valor->valor_car - $kick;
            if($valor->kick != null || $valor->markup != null){
            $subtotal += $valor_markup_kick;
            }else{
            $subtotal += $valor->valor_car;
            }
            $profit += $valor->profit;
            }
            $total = $total + $subtotal;
            @endphp
            @if(isset($kick_back) && $kick_back != 0)
            <tr>
                <td colspan="3"></td>
                <td style="border-top: 2px solid; text-align: right;"><b>KICKBACK:</b></td>
                <td style="border-top: 2px solid"> {{ number_format($kick_back, 2, ',', '.') }}</td>
            </tr>
            @endif
            <tr>
                <td colspan="3"></td>
                <td style="border-top: 2px solid; text-align: right;"><b>SUBTOTAL:</b></td>
                <td style="border-top: 2px solid"> {{ number_format(floor($subtotal*100)/100 , 2) }}</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td style="border-top: 2px solid; text-align: right;"><b style="color:#FF5722">ATS TOTAL:</b></td>
                <td style="border-top: 2px solid"> {{ number_format(floor($totalAtsPedido*100)/100 , 2, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td style="border-top: 2px solid; text-align: right;"><b
                        style="color:green; font-weight:bold">PROFIT:</b></td>
                <td style="border-top: 2px solid"> {{ number_format(floor($profit*100)/100 , 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>
</div>
@endif

<!--ticket-->
@if($bilhetes->isEmpty() != true)
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
            @foreach($bilhetes->sortBy("nome")->sortBy("data") as $bilhete)
            <tr>
                <td colspan="5"><b>Company:</b> {{$bilhete->nome}}</td>
            </tr>
            <tr>
                <td width="30%"><b>Date:</b> {{ Carbon\Carbon::parse( $bilhete->data )->format('d-m-y') }} </td>
                <td width="20%"><b>Hour:</b> {{ Carbon\Carbon::parse( $bilhete->hora )->format('H:i') }} </td>
                <td width="20%"><b></td>
                <td width="15%"><b></td>
                <td width="15%"><b>Total €:</b> {{ number_format($bilhete->total, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td><b>Adults:</b> {{$bilhete->adult}}</td>
                <td><b>Children:</b> {{$bilhete->children}}</td>
                <td><b>Babies:</b> {{$bilhete->babie}}</td>
                <td colspan="2"></td>
            </tr>
            @if(($old_produto_id != 0 && $old_produto_id != $bilhete->pedido_produto_id) || $loop->last)
            @foreach($extras_bilhetes as $extra_bilhete)
            @if($extra_bilhete->pedido_produto_id == $bilhete->pedido_produto_id)
            <tr>
                <td colspan="2"><b>Extra name:</b> {{ $extra_bilhete->name }}</td>
                <td><b>Qty:</b> {{ $extra_bilhete->amount }}</td>
                <td><b>Unit €:</b> {{ number_format($extra_bilhete->rate, 2, ',', '.') }}</td>
                <td><b>Total €:</b> {{ number_format($extra_bilhete->total, 2, ',', '.') }}</td>
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
            $totalAtsPedido += $bilhete->ats_total_rate;
            @endphp
            @endforeach

            @php

            $PedidosIds = $bilhetes->unique('pedido_produto_id')->pluck('pedido_produto_id');
            $ValoresPedidosProduto = App\ValorTicket::whereIn('pedido_produto_id', $PedidosIds->toArray() )->get();
            $profit = 0;
            foreach($ValoresPedidosProduto as $valor){
            $kick = ($valor->kick * $valor->valor_ticket)/100;
            $valor_markup_kick = $valor->valor_ticket - $kick;

            if($valor->kick != null || $valor->markup != null){
            $subtotal += $valor_markup_kick;
            }else{
            $subtotal += $valor->valor_ticket;
            }
            $profit += $valor->profit;
            }
            $total = $total + $subtotal;
            @endphp
            @if(isset($kick_back) && $kick_back != 0)
            <tr>
                <td colspan="3"></td>
                <td style="border-top: 2px solid; text-align: right;"><b>KICKBACK:</b></td>
                <td style="border-top: 2px solid"> {{ number_format($kick_back, 2, ',', '.') }}</td>
            </tr>
            @endif
            <tr>
                <td colspan="3"></td>
                <td style="border-top: 2px solid; text-align: right;"><b>SUBTOTAL:</b></td>
                <td style="border-top: 2px solid"> {{ number_format(floor($subtotal*100)/100 , 2) }}</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td style="border-top: 2px solid; text-align: right;"><b style="color:#FF5722">ATS RATE:</b></td>
                <td style="border-top: 2px solid"> {{ number_format(floor($totalAtsPedido*100)/100 , 2, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td style="border-top: 2px solid; text-align: right;"><b
                        style="color:green; font-weight:bold">PROFIT:</b></td>
                <td style="border-top: 2px solid"> {{ number_format(floor($profit*100)/100 , 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>
</div>
@endif

@php
$total_payments = 0;
foreach($payments as $payment){
$total_payments += (float) $payment->payment;
}
@endphp

<div class="container" style="margin-top:50px">
    <div class="w3-row-padding w3-padding-28" style="border-top: 2px solid #0513ffe3;">
        <table width="100%" style="margin-bottom: 15px;">
            <tr>
                <td width="60%"></td>
                <td width="25%" style="text-align: right; "><b style="color:#FF5722; font-weight:bold">TOTAL ATS
                        RATE:</b></td>
                <td width="15%">
                    @php
                    $total = $pedido_geral->valor - $pedido_geral->profit;
                    @endphp
                    {{ number_format(floor($total*100)/100 , 2, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td width="60%"></td>
                <td width="25%" style="border-top: 2px solid; text-align: right; "><b
                        style="color:green; font-weight:bold">TOTAL PROFIT:</b></td>
                <td width="15%" style="border-top: 2px solid"> {{ number_format($pedido_geral->profit, 2, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td width="60%"></td>
                <td width="25%" style="border-top: 2px solid; text-align: right; "><b>TOTAL PEDIDO:</b></td>
                <td width="15%" style="border-top: 2px solid; text-align: left; ">
                    {{ number_format(floor($pedido_geral->valor*100)/100 , 2, ',', '.') }}</td>
            </tr>

            @foreach($payments as $payment)
            <tr>
                <td width="60%"></td>
                <td width="25%" style="border-top: 2px solid;text-align: right;">
                    {{Carbon\Carbon::parse( $payment->date )->format('d/m/y')}} - <b>PAID:</b></td>
                <td width="15%" style="border-top: 2px solid;"> {{ number_format($payment->payment, 2, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td width="60%"></td>
                <td width="25%" style="border-top: 2px solid; text-align: right;"><b>TOTAL PAID:</b></td>
                <td width="15%" style="border-top: 2px solid"> {{ number_format($total_payments, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td width="60%"></td>
                <td width="25%" style="border-top: 2px solid; text-align: right;"><b>DUE:</b></td>
                <td width="15%" style="border-top: 2px solid">
                    {{ number_format($total - $total_payments, 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>
</div>

@endsection
