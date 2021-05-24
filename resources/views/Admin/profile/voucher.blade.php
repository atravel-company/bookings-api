@extends('Admin.layouts.app')

@section('content')
<style type="text/css">

    @media print
    {
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
    #app-navbar-collapse {
        display: none !important;
    }

    body {
        color: #000000 !important;
        font-family: arial !important;
        background-color: #ffffff !important;
    }
</style>
<script type="text/javascript" src="{{ URL::asset('Admin/js/node_modules/printthis/printThis.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('Admin/js/voucher.js') }}"></script>


<script type="text/javascript">
    load ="{{ asset('Admin/css/w3.css') }}";
</script>

<div class="container" id="cabeca" style="margin-top: -50px;">
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
                    <img class="w3-margin-bottom" id="editSupplier_img" width="65%" style="width:65%;"
                        src="<?php echo asset("storage/$usuario->path_image")?>">
                </td>
            </tr>
        </table>
<br>
        <div class="w3-col l12" style="margin-top:10px; margin-bottom:20px;">
            <div class="form-group">


                <table width="100%" class="w3-centered">
                    <tr>
                        <th style="text-align: left; width:75%;">TOPICOS E DESCOBERTAS LDA</th>
                        <td align="right" style="text-align: left; width:25%; font-size: small;">Telef.(+351) 917 250
                            405</td>
                    </tr>
                    <tr>
                        <td style="text-align: left; color:#24AEC9;">Av. da Liberdade,</td>
                        <td style="text-align: left; font-size: small;">Mobile.(+351) 912 032 695</td>
                    </tr>
                    <tr>
                        <td style="text-align: left; color:#24AEC9;">245, 4ºA </td>
                        <td style="text-align: left; font-size: small;">reservations: incoming@atravel.pt</td>
                    </tr>
                    <tr>
                        <td style="text-align: left; color:#24AEC9;">1250-143 Lisboa</td>
                        <td style="text-align: left; font-size: small;">www.atstravel.pt</td>
                    </tr>
                    <tr>
                        <td style="text-align: left; font-size: small; color:#24AEC9;">Licence RNVAT 8019</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="color:#24AEC9;text-align: left; font-size: small;">VAT 514 974 567</td>
                        <td></td>
                    </tr>
                </table>

            </div>
        </div>

    </div>
</div>


@foreach($pedidoQuartos as $key => $pedidoQuarto)
<div class="container" id="hotel{{$pedidoQuarto->id}}">

    <table width="100%">
        <tr>
            <td width="49%"><b>Hotel Voucher</b>&nbsp;
                <a title="" onclick="PrintQuarto({{$pedidoQuarto->id}})" class="btn fa fa-print no-print"
                style="color:teal" aria-hidden="false"></a>
            </td>
            <td width="49%"><span class="w3-right"><b>Voucher Nº:&nbsp;</b>{{$pedidoGeral->referencia}}</span></td>
        </tr>
    </table>

    <div class="w3-row-padding w3-padding-32" style="border-top: 2px solid #00bad1;">
        <table width="100%">
            <tr>
                <td width="49%"><b>Hotel:</b> {{$produto->nome}}</td>
            </tr>
            <tr>
                <td width="49%" align="right"><b>Customer:</b> {{$pedidoGeral->lead_name}}</td>
            </tr>
        </table>
        <table width="100%" style="margin-top:25px">
            <tr>
                <td width="33%"><b>C. in:</b> {{$pedidoQuarto->checkin}}</td>
                <td width="33%"><b>C. out:</b> {{$pedidoQuarto->checkout}}</td>
                <td width="33%"><b>Number of rooms:</b> {{$pedidoQuarto->rooms}}</td>
            </tr>

            <tr>
                <td width="33%"><b>Room Type:</b> {{$pedidoQuarto->type}}</td>
                <td width="33%"><b>Board:</b> {{$pedidoQuarto->plan}}</td>
                <td width="33%"><b>Nº of Pax:</b> {{$pedidoQuarto->people}}</td>
            </tr>

            @if($pedidoQuarto->remark)
            <tr>
                <td><br><b>Remarks: </b><br>
                    @foreach($pedidoQuartos as $remark)
                    @if($remark->remark && $remark->remark != "")
                    {!! html_entity_decode($remark->remark) !!}
                    @endif
                    @endforeach
                </td>
            </tr>
            @endif
        </table>

        {{-- <table width="100%" style="margin-top:25px">
            @foreach($pedidoQuarto->pedidoquartoroom as $i =>  $room)
                <tr {{  ($i > 0 ? 'style=margin-top:15px; ' : '') }}>
        <td>
            <b>Quarto {{ $i + 1 }}</b> <i>( {{ $room->pedidoquartoroomname->count() }} pax )</i>
        </td>
        @foreach ($room->pedidoquartoroomname as $peopleRoom)
        <td>
            <tr>
                <td>
                    <b>Room name: </b> {{ $peopleRoom->name }}
                </td>
            </tr>
        </td>
        @endforeach
        </tr>
        @endforeach
        </table> --}}

    </div>
    <br>
    <br>
</div>
@endforeach

<!--golf-->

@foreach($PedidoGames as $key=>$PedidoGame)
<div class="container" id="game{{$PedidoGame->id}}">

    <table width="100%">
        <tr>
            <td width="49%"><b>Golf Services Voucher</b><a title="" onclick="PrintGame({{$PedidoGame->id}})"
                    class="btn fa fa-print no-print" style="color:teal" aria-hidden="true"></a></td>
            <td width="49%"><span class="w3-right"><b>Voucher Nº:&nbsp;</b>{{$pedidoGeral->referencia}}</span></td>
        </tr>
    </table>

    <div class="w3-row-padding w3-padding-32" style="border-top: 2px solid #00bad1;">
        <table width="100%">
            <tr>
                <td width="100%"><b>Golf Course:</b> {{$produto->nome}}</td>
            </tr>
            <tr>
                <td width="100%" align="right"><b>Client Name:</b> {{$pedidoGeral->lead_name}}</td>
            </tr>
        </table>
        <table width="100%" style="margin-top:25px">
            <tr>
                <td width="33%"><b>Nº of Pax:</b> {{$PedidoGame->people}}</td>
                <td width="33%"><b>Date:</b> {{$PedidoGame->data}}</td>
                <td width="33%"><b>Starting Time:</b> {{ Carbon\Carbon::parse( $PedidoGame->hora)->format('H:i') }}</td>
            </tr>
            @if($PedidoGame->remark)
            <tr>
                <td><br><b>Remarks: </b><br>
                    @foreach($PedidoGames as $remark)
                    @if($remark->remark && $remark->remark != "")
                    {!! html_entity_decode($remark->remark) !!}
                    @endif
                    @endforeach
                </td>
            </tr>
            @endif
        </table>
    </div>
    <br>
    <br>
</div>
@endforeach

<!--transfer-->

@foreach($PedidoTransfers as $key=>$PedidoTransfer)
<div class="container" id="transfer{{$PedidoTransfer->id}}">
    <table width="100%">
        <tr>
            <td width="49%"><b>Transfers Voucher</b><a title="" onclick="PrintTransfer({{$PedidoTransfer->id}})"
                    class="btn fa fa-print no-print" style="color:teal" aria-hidden="true"></a></td>
            <td width="49%"><span class="w3-right"><b>Voucher Nº:&nbsp;</b>{{$pedidoGeral->referencia}}</span></td>
        </tr>
    </table>
    <div class="w3-row-padding w3-padding-32" style="border-top: 2px solid #00bad1;">
        <table width="100%">
            <tr width="100%" style="margin-bottom:40px">
                <td><b>Company:</b> ATS Travel {{-- $produto->nome --}}</td>
            </tr>
            <tr width="100%" style="margin-bottom:40px">
                <td style="text-align:right;" width="100%"><b>Client Name:</b> {{$pedidoGeral->lead_name}}</td>
            </tr>
            <tr width="100%" style="margin-bottom:20px">
                <td width="33%"> <b>Date:</b> {{ Carbon\Carbon::parse( $PedidoTransfer->data )->format('d/m/y') }} |
                    <b>Time:</b> {{ Carbon\Carbon::parse( $PedidoTransfer->hora)->format('H:i') }}</td>
            </tr>
        </table>
        <br>
        <table width="100%">
            <tr>
                <td width="33%">
                    <b>From:</b> {{$PedidoTransfer->pickup}}
                </td>
                <td width="33%">
                    <b>To:</b> {{$PedidoTransfer->dropoff}}
                </td>
                <td>
                    <b>Nº Voo:</b> {{$PedidoTransfer->flight}}
                </td>
            </tr>
            <tr>
                <td width="33%">
                    <b>Nº Adults:</b> {{$PedidoTransfer->adult}}
                </td>
                <td width="33%">
                    <b>Nº Children:</b> {{$PedidoTransfer->children}}
                </td>
                <td width="33%">
                    <b>Nº babies:</b> {{$PedidoTransfer->babie}}
                </td>
            </tr>
            @if($PedidoTransfer->remark)
            <tr>
                <td><br><b>Remarks: </b><br>
                    @foreach($PedidoTransfers as $remark)
                    @if($remark->remark && $remark->remark != "")
                    {!! html_entity_decode($remark->remark) !!}
                    @endif
                    @endforeach
                </td>
            </tr>
            @endif
        </table>

    </div>
    <br>
</div>
@endforeach

<!--car-->

@foreach($PedidoCars as $key=>$PedidoCar)
<div class="container" id="car{{$PedidoCar->id}}">

    <table width="100%">
        <tr>
            <td width="49%"><b>Rent a Car Voucher</b><a title="" onclick="PrintCar({{$PedidoCar->id}})"
                    class="btn fa fa-print no-print" style="color:teal" aria-hidden="true"></a></td>
            <td width="49%"><span class="w3-right"><b>Voucher Nº:&nbsp;</b>{{$pedidoGeral->referencia}}</span></td>
        </tr>
    </table>
    <div class="w3-row-padding w3-padding-32" style="border-top: 2px solid #00bad1;">
        <table width="100%">
            <tr>
                <td width="100%"><b>Company:</b> {{$produto->nome}}</td>
            </tr>
            @if($PedidoCar->group != null)
            <tr>
                <td><b>Group: </b> {{ $PedidoCar->group }}</td>
            </tr>
            @endif

            @if($PedidoCar->model != null)
            <tr>
                <td><b>Model: </b> {{ $PedidoCar->model }} </td>
            </tr>
            @endif
            <tr>
                <td width="100%" align="right"><b>Client Name:</b> {{$pedidoGeral->lead_name}}</td>
            </tr>
        </table>
        <table width="100%" style="margin-top:25px;">
            <tr width="100%">
                <td><b>Pick-up Information: </b></td>
            </tr>
            <tr>
                <td width="33%"><b>Pick-up Location:</b> {{$PedidoCar->pickup}}</td>
                <td width="33%"><b>Pick-up Date:</b> {{$PedidoCar->pickup_data}}</td>
                <td width="33%"><b>Pick-up Hour:</b> {{ Carbon\Carbon::parse( $PedidoCar->pickup_hora)->format('H:i') }}
                </td>
            </tr>
        </table>

        <table width="100%" style="margin-top:15px;">
            <tr width="100%">
                <td><b>Drop Off Information: </b></td>
            </tr>
            <tr>
                <td width="33%"><b>Drop Off Location:</b> {{$PedidoCar->dropoff}}</td>
                <td width="33%"><b>Drop Off Date:</b> {{$PedidoCar->dropoff_data}}</td>
                <td width="33%"><b>Drop Off Hour:</b>
                    {{ Carbon\Carbon::parse( $PedidoCar->dropoff_hora)->format('H:i') }}</td>
            </tr>

            @if($PedidoCar->remark)
            <tr>
                <td><br><b>Remarks: </b><br>
                    @foreach($PedidoCars as $remark)
                    @if($remark->remark && $remark->remark != "")
                    {!! html_entity_decode($remark->remark) !!}
                    @endif
                    @endforeach
                </td>
            </tr>
            @endif
        </table>

    </div>
    <br>
    <br>
</div>
@endforeach



<!--ticket-->

@foreach($PedidoTickets as $key=>$PedidoTicket)
<div class="container" id="ticket{{$PedidoTicket->id}}">

    <table width="100%">
        <tr>
            <td width="49%"><b>Tours Voucher</b><a title="" onclick="PrintTicket({{$PedidoTicket->id}})"
                    class="btn fa fa-print no-print" style="color:teal" aria-hidden="true"></a></td>
            <td width="49%"><span class="w3-right"><b>Voucher Nº:&nbsp;</b>{{$pedidoGeral->referencia}}</span></td>
        </tr>
    </table>

    <div class="w3-row-padding w3-padding-32 w3-margin-top" style="border-top: 2px solid #00bad1;">
        <table width="100%">
            <tr>
                <td width="100%"><b>Company:</b> {{$produto->nome}}</td>
            </tr>
            <tr>
                <td width="100%"><b>Client Name:</b> {{$pedidoGeral->lead_name}}</td>
            </tr>
        </table>

        <table width="100%" style="margin-top:25px;">
            <tr>
                <td width="33%"><b>Date:</b> {{$PedidoTicket->data}}</td>
                <td width="33%"><b>Hour:</b> {{ Carbon\Carbon::parse( $PedidoTicket->hora)->format('H:i') }}</td>
                <td width="33%"><b>Nº Adults:</b> {{$PedidoTicket->adult}}</td>
            </tr>

            <tr>
                <td width="33%"><b>Nº Children:</b> {{$PedidoTicket->children}}</td>
                <td width="33%"><b>Nº babies:</b> {{$PedidoTicket->babie}}</td>
                <td width="33%"></td>
            </tr>
            @if($PedidoTicket->remark)
            <tr>
                <td><br><b>Remarks: </b><br>
                    @foreach($PedidoTickets as $remark)
                    @if($remark->remark && $remark->remark != "")
                    {!! html_entity_decode($remark->remark) !!}
                    @endif
                    @endforeach
                </td>
            </tr>
            @endif
        </table>

    </div>
    <br>
    <br>
</div>
@endforeach

@endsection
