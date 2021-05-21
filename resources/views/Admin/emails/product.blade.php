<html>
<meta name="viewport" content="width=device-width, initial-scale=1" charset="utf-8">
<style>
    @media print {
        tr.head{
            background-color: #24AEC9!important;
            color: white!important;
            font-size:14px!important;
            -webkit-print-color-adjust: exact; 
        }
        .align-center{
            text-align:center!important;
        }
        .table-body{
            border-bottom:1px #4e4e4e solid;
            font-size:10px!important;
        }
        tr.head th{
            padding:3px!important;
        }
        .footer{
            font-size:10px!important;
            line-height: 1;
        }
    }

    tr.head{
        background-color: #24AEC9;
        color: white;
        font-size:14px!important;
    }
    tr.head th{
        padding:5px!important;
    }
    .align-center{
        text-align:center!important;
    }
    .table-body td{
        border-bottom:1px #4e4e4e solid;
        font-size:10px!important;
    }

    .footer{
        font-size:10px!important;
        line-height: 1;
    }
</style>

@php
    $path = asset('storage/LogotipoAtravelCor.png');
    if(!file_exists($path)){
        $path = "https://atsportugal.com/public/storage/LogotipoAtravelCor.png";
    }
@endphp

<link href="./css/w3.css" rel="stylesheet">
    <head></head>

    <body>
        <div class="w3-row w3-padding">
            <table class="w3-table w3-striped w3-centered" {{-- style="max-width: 100%" --}}>
                <tr>
                    <th>&nbsp;</th>
                    <th style="float:right; clear:right; text-align: right; margin-bottom: 20px">
                        <img width=190 src="{{ $message->embed($path) }}">
                    </th>
                </tr>       
            </table>
        </div>
        <div class="w3-row w3-padding">
            Caros colegas,<br>
            Em representação do operador:
            <b>{{$usuario->name}}</b>
            Solicitamos que seja efectuada a seguinte reserva:
        </div>
        <br>
								
        <div class="w3-row w3-padding">
            <span><b>Lead Name: </b> {{$pedido->lead_name}}</span>	
        </div>

        <br>
    

        @if($quartos->first())

            <div class="w3-row w3-padding">
                <span><b>Hotel {{ $quartos->first()->produto->produto->nome }}</b></span>
            </div>
            <div class="w3-row w3-padding">
                <table class="w3-table w3-striped w3-centered">
                    <tr class="head" style="background-color: #24AEC9; color: white;">
                        <th>Room Type</th>
                        <th>Rooms</th>
                        <th>Nights</th>
                        <th>Pax</th>
                        <th>Board</th>
                        <th>Checkin</th>
                        <th>Checkout</th>
                        <th>Special Offer</th>
                        <th>Daily Rate</th>
                    </tr>    
                    @foreach($quartos->sortByDesc("checkin") as $key2=>$quarto) 
                        @if($quarto['type'] and $quarto['rooms'] and $quarto['plan'] and $quarto['checkin'] and $quarto['checkout'])        
                            <tr class="table-body">
                                <td>{{$quarto['type']}}</td>
                                <td align="center" class="align-center">{{$quarto['rooms']}}</td>
                                <td align="center" class="align-center">{{$quarto['days']}}</td>
                                <td align="center" class="align-center">{{$quarto['people']}}</td>
                                <td align="center" class="align-center">{{$quarto['plan']}}</td>
                                <td>{{ Carbon\Carbon::parse($quarto['checkin'])->format('d/m/Y') }}</td>
                                <td>{{ Carbon\Carbon::parse($quarto['checkout'])->format('d/m/Y')}}</td>
                                <td>{{$quarto['offer_name']}}</td>
                                <td>{{$quarto['price']}}</td>           
                            </tr>
                        @endif
                    @endforeach     
                </table>
            </div>
            
            <div class="w3-row w3-padding">
                <table class="w3-table w3-striped w3-centered">
                    <tr class="head" style="background-color: #24AEC9; color: white;">
                        <th>Extra</th>
                        <th>Amount</th>  
                    </tr>
                    @foreach($extras as $key2=>$extra)
                        @if($produto['pivot']['id']==$extra->pedido_produto_id and $extra->tipo=='alojamento')
                            <tr class="table-body">
                                <td>{{$extra->name}}</td>
                                <td align="center" class="align-center">{{$extra->amount}}</td>   
                            </tr>
                        @endif
                    @endforeach
                </table>
            </div>
            <br>
        @endif
       
        @if($golfs->first())
            <div class="w3-row w3-padding">
                <table class="w3-table w3-striped w3-centered">
                    <tr>
                        <th style="text-align: left;">Golf
                        </th>
                    </tr>
                </table>
            </div>
            <div class="w3-row w3-padding">
                <table class="w3-table w3-striped w3-centered">
                    <tr class="head" style="background-color: #24AEC9; color: white;">
                        <th>Date</th>
                        <th>Golf Course</th>
                        <th>Tee Time</th>
                        <th>Players</th>
                        <th>Players Free</th>   
                    </tr>
                    @foreach($golfs->sortByDesc("data") as $key2=>$golf)
                        @if($golf['data'] and $golf['hora'] and $golf['people'])
                            <tr class="table-body">
                                <td>{{ Carbon\Carbon::parse($golf['data'])->format('d/m/Y') }}</td>
                                <td>{{$produto['nome']}}</td>
                                <td align="center" class="align-center">{{ Carbon\Carbon::parse($golf['hora'])->format('H:i') }}</td>
                                <td align="center" class="align-center">{{$golf['people']}}</td>
                                <td align="center" class="align-center">{{$golf['free']}}</td>  
                            </tr>
                        @endif
                    @endforeach
                </table>
            </div>
            
            <div class="w3-row w3-padding">
                <table class="w3-table w3-striped w3-centered">
                    <tr class="head" style="background-color: #24AEC9; color: white;">
                        <th>Extra</th>
                        <th>Amount</th>
                    </tr>
                    @foreach($extras as $key2=>$extra)
                        @if($produto['pivot']['id']==$extra->pedido_produto_id and $extra->tipo=='golf')
                            <tr class="table-body">
                                <td>{{$extra->name}}</td>
                                <td align="center" class="align-center">{{$extra->amount}}</td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            </div>
            <br><br>
        @endif

        @if($transfers->first())

            <div class="w3-row w3-padding">
                <table class="w3-table w3-striped w3-centered">
                    <tr>
                        <th style="text-align: left;">
                            Transfer
                        </th>
                    </tr>
                </table>
            </div>
            <div class="w3-row w3-padding">

                <table class="w3-table w3-striped w3-centered">
                    <tr class="head" style="background-color: #24AEC9; color: white;">
                        <th>Date</th>
                        <th>Adults</th>
                        <th>Children</th>
                        <th>Babies</th>
                        <th>Pick-up</th>
                        <th>Flight Nº</th>
                        <th>Pick-up Time</th>
                        <th>Drop Off</th>
                        <th>ATS RATE</th>
                    </tr>
                    @foreach($transfers->sortByDesc("data") as $key2=>$transfer)
                        @if($transfer['data'] and $transfer['pickup'] and $transfer['hora'] and $transfer['dropoff'])
                            <tr class="table-body">
                                <td>{{ Carbon\Carbon::parse($transfer['data'])->format('d/m/Y') }}</td>
                                <td align="center" class="align-center">{{$transfer['adult']}}</td>
                                <td align="center" class="align-center">{{$transfer['children']}}</td>
                                <td align="center" class="align-center">{{$transfer['babie']}}</td>
                                <td>{{$transfer['pickup']}}</td>
                                <td>{{$transfer['flight']}}</td>
                                <td align="center" class="align-center">{{ Carbon\Carbon::parse($transfer['hora'])->format('H:i') }}</td>
                                <td>{{$transfer['dropoff']}}</td>
                                <td>{{$transfer['ats_rate']}}</td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            </div>

            <div class="w3-row w3-padding">
                <table class="w3-table w3-striped w3-centered">
                    <tr class="head" style="background-color: #24AEC9; color: white;">
                        <th>Extra</th>
                        <th>Amount</th>
                    </tr>
                    @foreach($extras as $key2=>$extra)
                        @if($produto['pivot']['id']==$extra->pedido_produto_id and $extra->tipo=='transfer')
                            <tr class="table-body">
                                <td>{{$extra->name}}</td>
                                <td align="center" class="align-center">{{$extra->amount}}</td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            </div>
            <br><br>
        @endif
     
        @if($cars->first())
            <div class="w3-row w3-padding">
                <table class="w3-table w3-striped w3-centered">
                    <tr>
                        <th style="text-align: left;">Rent a Car
                        </th>
                    </tr>
                </table>
            </div>
            <div class="w3-row w3-padding" style="overflow-x: auto;">
                <table class="w3-table w3-striped">
                    <tr class="head" style="background-color: #F5AA3B; color: white;">
                        <th colspan="6" style="border-bottom: 1px solid white; border-left: 1px solid white;">Pick-up</th>
                        <th colspan="6" style="border-bottom: 1px solid white; border-left: 1px solid white;">Drop Off</th>
                        <th colspan="7" style="border-bottom: 1px solid white; border-left: 1px solid white;">Car Info</th>
                    </tr>
                    <tr class="head" style="background-color: #24AEC9; color: white;">
                        <th>Pick-up Location</th>
                        <th>Pick-up Date</th>
                        <th>Pick-up Hour</th>
                        <th>Flight Nº</th>
                        <th>Country Origin</th>
                        <th>Airport</th>
                        <th style="border-left: 1px solid white;">Drop Off Location</th>
                        <th>Drop Off Date</th>
                        <th>Drop Off Hour</th>
                        <th>Flight Nº</th>
                        <th>Country Origin</th>
                        <th style="border-left: 1px solid white;">Airport</th>
                        <th>Group</th>
                        <th>Model</th>
                        <th>Daily Rate</th>
                        <th>Nr Days</th>
                        <th>Tax</th>
                        <th>Tax Type</th>
                        <th>Total</th>
                    </tr>

                    @foreach($cars->sortByDesc("pickup_data") as $key2=>$car)
                        @if($car['pickup'] and $car['pickup_data'] and $car['pickup_hora'] and $car['dropoff'] and $car['dropoff_data'] and $car['dropoff_hora'] and $car['group'] and $car['model'])
                            <tr class="table-body">
                                <td>{{$car['pickup']}}</td>
                                <td>{{ Carbon\Carbon::parse($car['pickup_data'])->format('d/m/Y') }}</td>
                                <td>{{ Carbon\Carbon::parse($car['pickup_hora'])->format('H:i') }}</td>
                                <td>{{$car['pickup_flight']}}</td>
                                <td>{{$car['pickup_country']}}</td>
                                <td>{{$car['pickup_airport']}}</td>
                                <td>{{$car['dropoff']}}</td>
                                <td>{{ Carbon\Carbon::parse($car['dropoff_data'])->format('d/m/Y') }}</td>
                                <td>{{ Carbon\Carbon::parse($car['dropoff_hora'])->format('H:i') }}</td>
                                <td>{{$car['dropoff_flight']}}</td>
                                <td>{{$car['dropoff_country']}}</td>
                                <td>{{$car['dropoff_airport']}}</td>
                                <td>{{$car['group']}}</td>
                                <td>{{$car['model']}}</td>
                                
                                <td>{{$car['rate']}}</td>
                                <td>{{$car['days']}}</td>
                                
                                <td>{{$car['tax']}}</td>
                                <td>{{$car['tax_type']}}</td>
                                <td>{{$car['total']}}</td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            </div>

            <div class="w3-row w3-padding">
                <table class="w3-table w3-striped w3-centered">
                    <tr class="head" style="background-color: #24AEC9; color: white;">
                        <th>Extra</th>
                        <th>Amount</th>
                    </tr>
                    @foreach($extras as $key2=>$extra)
                        @if($produto['pivot']['id']==$extra->pedido_produto_id and $extra->tipo=='car')
                            <tr class="table-body">
                                <td>{{$extra->name}}</td>
                                <td align="center" class="align-center">{{$extra->amount}}</td>   
                            </tr>
                        @endif
                    @endforeach
                </table>
            </div>
            <br><br>
        @endif
      
        @if($tickets->first())
            <div class="w3-row w3-padding">
                <table class="w3-table w3-striped w3-centered">
                    <tr>
                        <th style="text-align: left;">Tours
                        </th>
                    </tr>
                </table>
            </div>
            <div class="w3-row w3-padding">
                <table class="w3-table w3-striped w3-centered">
                    <tr class="head" style="background-color: #24AEC9; color: white;">
                        <th>data</th>
                        <th>hora</th>
                        <th>adults</th>
                        <th>childrens</th>
                        <th>babies</th>
                        <th>Total</th>
                    @foreach($tickets->sortByDesc("data") as $key2=>$ticket)
                        @if($ticket['data'] and $ticket['hora'])
                            <tr class="table-body">
                                <td>{{$ticket['data']}}</td>
                                <td>{{$ticket['hora']}}</td>
                                <td align="center" class="align-center">{{$ticket['adult']}}</td>
                                <td align="center" class="align-center">{{$ticket['children']}}</td>
                                <td align="center" class="align-center">{{$ticket['babie']}}</td>
                                <td>{{$ticket['total']}}</td>
                            </tr>
                        @endif      
                    @endforeach
                </table>
            </div>

            <div class="w3-row w3-padding">
                <table class="w3-table w3-striped w3-centered">
                    <tr class="head" style="background-color: #24AEC9; color: white;">
                        <th>Extra</th>
                        <th>Amount</th>
                    </tr>
                    @foreach($extras as $key2=>$extra)
                        @if($produto['pivot']['id']==$extra->pedido_produto_id and $extra->tipo=='ticket')
                            <tr class="table-body">
                                <td>{{$extra->name}}</td>
                                <td align="center" class="align-center">{{$extra->amount}}</td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            </div>
            <br><br><br>
        @endif
                                        
        <div class="w3-row w3-padding">
            <table class="w3-table w3-striped w3-centered">
                <tr>
                    <td style="text-align: left;">Aguardamos Confirmação e pro forma.
                    </td>
                </tr>    
                <tr>
                    <td style="text-align: left;">Sem outro assunto de momento, subscrevemo-nos com elevada consideração.</td>
                </tr> 
            </table>
        </div>

        <br><br>
        <hr width="549px">

        <div class="w3-row w3-padding">
            <table class="w3-table w3-striped w3-centered" width="549px">
            <tr>
                    <td class="footer" style="text-align: left;">ATS Travel</td>
                    <td class="footer" style="text-align: right; color:#24AEC9;">Av. da Liberdade,</td>
                </tr>    
                <tr>
                    <td class="footer" style="text-align: left; font-size: small;">TOPICOS E DESCOBERTAS - licence RNVAT 8019</td>
                    <td class="footer" style="text-align: right; color:#24AEC9;">245, 4ºA </td>
                </tr> 
                <tr>
                    <td class="footer" style="text-align: left; font-size: small;">VAT 514 974 567</td>
                    <td class="footer" style="text-align: right; color:#24AEC9;">1250-143 Lisboa</td>
                </tr> 
                <tr>
                    <td class="footer" style="text-align: left; font-size: small;">Reg. Na C.R.C. de Portimão sob o nº 3628/000822</td>
                    <td class="footer" style="text-align: right; color:#24AEC9;">sales@atravel.pt</td>
                </tr>
                <tr>
                    <td class="footer" style="text-align: left; font-size: small;">Sociedade por Quotas</td>
                    <td class="footer" style="text-align: right; color:#24AEC9;">www.atsportugal.com</td>
                </tr>
            </table>
        </div>

        <br>
    </body>
</html>