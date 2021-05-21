@extends('Admin.layouts.app')


    <script type="text/javascript" src="{{ URL::asset('Admin/js/relatorio.js') }}" ></script>



@section('content')


<div class="w3-container">

<script>
    var assetBaseUrl = "{{ asset('') }}";
</script>

<span class="w3-center"><h1>Bookings Reports</h1></span>
<div class="row" style="padding:30px 0;">
    <div class="col-lg-3 col-sm-6 col-md-3 col-md-offset-3 col-lg-offset-3">
        <label class="ats-label">Start</label>
        <div class="form-group">
            <div class="input-group" style="position: relative;">
                <input type="text" class="form-control ats-border-color datepicker" id="checkin" name="checkin" placeholder="Check-In">
                <span class="input-group-addon ats-border-color">
                    <span class="w3-large ats-text-color fa fa-calendar"></span>
                </span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-md-3">
        <label class="ats-label">End</label>
        <div class="form-group">
            <div class="input-group" style="position: relative;">
                <input type="text" class="form-control ats-border-color datepicker2" id="checkout" name="checkout" placeholder="Check-Out">
                <span class="input-group-addon ats-border-color">
                    <span class="w3-large ats-text-color fa fa-calendar"></span>
                </span>
            </div>
        </div>
    </div>
</div>
<div class="w3-row-padding">
    <div class="container-fluid">
        <table class="table cell-border compact stripe" id="datatable-reports">
            <thead>
                <tr>
                    <th>Arr</th>
                    <th>Client</th>
                    <th>RNTS</th>
                    <th>Hotel</th>
                    <th>T.OPER</th>
                    <th style="background-color:#0078ff;">ROOM</th>
                    <th style="background-color:#1dcb4a;">GOLF</th>
                    <th style="background-color:yellow;">TRANSF</th>
                    <th style="background-color:#ffdd97;">CAR</th>
                    <th style="background-color:#2ea7ff;">EXTRAS</th>
                    <th style="background-color:#1259b4;">K.BACK</th>
                    <th style="background-color:#1259b4;">PAID</th>
                    <th style="background-color:#1259b4;">UNPAID</th>
                    <th style="background-color:yellow;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                {{-- {{dd($pedidos_reports)}} --}}
                @foreach($pedidos_reports as $pedido_report)
                @php
                    $total_kickback = (($pedido_report->quarto_kick / 100) * $pedido_report->valor_quarto) + (($pedido_report->golf_kick / 100) * $pedido_report->valor_golf) + (($pedido_report->transfer_kick / 100) * $pedido_report->valor_transfer) + (($pedido_report->car_kick / 100) * $pedido_report->valor_car) + (($pedido_report->ticket_kick / 100) * $pedido_report->valor_ticket);

                    $quarto_total = $pedido_report->valor_quarto - (($pedido_report->quarto_kick / 100) * $pedido_report->valor_quarto) + (($pedido_report->quarto_markup / 100) * $pedido_report->valor_quarto);
                    $golf_total = $pedido_report->valor_golf - (($pedido_report->golf_kick / 100) * $pedido_report->valor_golf) + (($pedido_report->golf_markup / 100) * $pedido_report->valor_golf);
                    $transfer_total = $pedido_report->valor_transfer - (($pedido_report->transfer_kick / 100) * $pedido_report->valor_transfer) + (($pedido_report->transfer_markup / 100) * $pedido_report->valor_transfer);
                    $car_total = $pedido_report->valor_car - (($pedido_report->car_kick / 100) * $pedido_report->valor_car) + (($pedido_report->car_markup / 100) * $pedido_report->valor_car);
                    $ticket_total = $pedido_report->valor_ticket - (($pedido_report->ticket_kick / 100) * $pedido_report->valor_ticket) + (($pedido_report->ticket_markup / 100) * $pedido_report->valor_ticket);

                    $total_extras = $pedido_report->quarto_extra + $pedido_report->golf_extra + $pedido_report->transfer_extra;
                @endphp
                <tr>
                    <td>
                        @if($pedido_report->data_quartos)
                            {{ Carbon\Carbon::parse( $pedido_report->data_quartos )->format('d/m/y') }}
                        @elseif($pedido_report->data_golf)
                            {{Carbon\Carbon::parse( $pedido_report->data_golf )->format('d/m/y') }}
                        @elseif($pedido_report->data_transfer)
                            {{ Carbon\Carbon::parse( $pedido_report->data_transfer )->format('d/m/y')}}
                        @elseif($pedido_report->data_car)
                            {{ Carbon\Carbon::parse( $pedido_report->data_car )->format('d/m/y')}}
                        @endif
                    </td>
                    <td>{{$pedido_report->client}}</td>
                    <td>
                        @if($pedido_report->room_nights)
                            {{$pedido_report->room_nights}}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{$pedido_report->produto}}</td>
                    <td>{{$pedido_report->operador}}</td>
                    <td>{{$pedido_report->valor_quarto + (($pedido_report->quarto_markup / 100) * $pedido_report->valor_quarto)}}</td>
                    <td>{{$pedido_report->valor_golf + (($pedido_report->golf_markup / 100) * $pedido_report->valor_golf)}}</td>
                    <td>{{$pedido_report->valor_transfer + (($pedido_report->transfer_markup / 100) * $pedido_report->valor_transfer)}}</td>
                    <td>{{$pedido_report->valor_car + (($pedido_report->car_markup / 100) * $pedido_report->valor_car)}}</td>
                    <td>{{ $total_extras }}</td>
                    <td>{{number_format( $total_kickback, 2, ',', ' ')}}</td>
                    <td style="background-color:yellow;">{{number_format( $quarto_total + $golf_total + $transfer_total + $car_total + $ticket_total + $total_extras, 2, ',', ' ')}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


@endsection

