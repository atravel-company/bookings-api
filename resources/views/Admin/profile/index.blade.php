@extends('Admin.layouts.app')


@section('content')

@php

$users_array = [ 'sales@atravel.pt', 'incoming@atravel.pt', 'transfers@atravel.pt',
'bookings@atravel.pt','accounts@atravel.pt', 'bookings2@atravel.pt'];

$users_api_transfergest = [
'sales@atravel.pt',
'incoming@atravel.pt',
'bookings@atravel.pt',
];

$authUser = Auth::user();

@endphp

<div class="w3-container">

    <script>
        var assetBaseUrl = "{{ asset('').'admin/' }}";
        function formatMonetario(nr) {
            var negativo = false;
            if(Math.sign(parseFloat(nr)) == '-1' || Math.sign(parseFloat(nr)) == '-0'){
                negativo = true;
                nr = nr * -1;
            }
            numeroFormatado = nr.toString();
            if (numeroFormatado.indexOf(',') != -1){ numeroFormatado = numeroFormatado.replace(',', '.'); }
            numeroFormatado = parseFloat(numeroFormatado) * 100;
            numeroFormatado =  Math.floor(numeroFormatado) / 100;
            if(negativo ==  true){
                numeroFormatado = numeroFormatado * -(1);
            }
            return numeroFormatado;
        }
    </script>

    <div class="w3-row-padding" style="font-family: arial!important;">
        @php
            $data_inicial = \Carbon\Carbon::now()->format("d/m/Y");
            $data_final = \Carbon\Carbon::now()->add(1, 'year')->format("d/m/Y");
            if(\Request::route()->getName() == "profile.search"){
                $data_inicial = $in;
                $data_final = $out;
            }
        @endphp

        <!-- FORNMULÁRIO DE PESQUISA -->
        {!! Form::open(['url'=> route('profile.search'), 'method'=> 'get', 'id' => "profileFormSearch"]) !!}

            <div class="w3-col l2 m2 s5" style="padding-right: 10px;">
                <label class="ats-label">Start</label>
                <div class="form-group">
                    <div class='input-group date datetimepicker1' id='datetimepicker12' style="position: relative;">
                        <div class="find-data" id="checkin1"></div>
                        <input value="{{$data_inicial}}" type='text' readonly name="in" id="in" class="form-control ats-border-color"
                            placeholder="Check-In" />
                        <span class="input-group-addon ats-border-color">
                            <span class="w3-large ats-text-color fa fa-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="w3-col l2 m2 s5" style="padding-right: 10px;">
                <label class="ats-label">End</label>
                <div class="form-group">
                    <div class="input-group date datetimepickers1" id='datetimepicker13' style="position: relative;">
                        <div class="find-data" id="checkout1"></div>
                        <input value="{{$data_final}}" type="text" readonly class="form-control ats-border-color" id="out"
                            name="out" placeholder="Check-Out">
                        <span class="input-group-addon ats-border-color">
                            <span class="w3-large ats-text-color fa fa-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="w3-col l2 m2 s12" style="padding-right: 10px;">
                <label class="ats-label">Request type</label>
                <div class="form-group">
                    <div class="input-group w3-block">
                        @php
                            $usuariosType = [
                                "-1",
                                "0",
                                "In Progress",
                                "Waiting Confirmation",
                                "Edited",
                                "Confirmed",
                                "Cancelled"
                            ];
                        @endphp
                        <select id="usuarios" class="form-control ats-border-color" name="tipo" placeholder="">
                            @foreach ($usuariosType as  $type)
                                <option {{ $tipo == $type ? "selected='selected'": null }} value="{{$type}}">
                                    @if($type == "-1")
                                        Select
                                    @elseif($type == '0')
                                        All
                                    @else
                                        {{ $type}}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="w3-col l2 m2 s12" style="padding-right: 10px;">
                <label class="ats-label">Tour Operator</label>
                <div class="form-group">
                    <div class="input-group w3-block">
                        <select width="100%" class="form-control ats-border-color select-simple" name="operator_id">
                            <option value="0">Select</option>
                            @foreach($utilizadores->sortBy("name")->values()->all() as $operadores)
                            @if(in_array($authUser->email, $users_array))
                            @if($operador_id == $operadores->id)
                            <option selected value="{{$operadores->id}}">{{$operadores->name}}</option>
                            @else
                            <option value="{{$operadores->id}}">{{$operadores->name}}</option>
                            @endif
                            @else
                            @if($operador_id == $operadores->id)
                            <option selected value="{{$operadores->id}}">{{$operadores->name}}</option>
                            @endif
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="w3-col l2 m3 s12" style="padding-right: 10px;">
                <label class="ats-label">Lead Name</label>
                <div class="form-group">
                    <div class="input-group w3-block">
                        <input value="{{$lead}}" type='text' name="lead" id="lead" class="form-control ats-border-color"
                            placeholder="Lead" />
                    </div>
                </div>
            </div>

            <div class="w3-col l2 m2 s12">
                <div class="form-group" style="padding-top: 10px;">
                    {!! Form::submit('Search', ['class'=>'w3-button w3-blue w3-section w3-padding ']) !!}
                    <button class='w3-button w3-orange w3-section w3-padding' onClick='clearFormInput()' type="button">
                        Clear
                    </button>
                </div>
            </div>
        {!! Form::close() !!}
        <!-- FORNMULÁRIO DE PESQUISA -->

        {{-- @endif --}}
        @if(!empty($pedidos) and $pedidos->total() > 0 )
            <div class="w3-center pedidos-links">
                {{ $pedidos->links() }}
            </div>
            @foreach($pedidos as $key => $pedido)
                @php $pedido = (object) $pedido; @endphp
                <div class="w3-row">
                    @php $fontButtonSize = "15px"; @endphp
                    @if($pedido->status=='In Progress')
                        @php $button_color = "#2196f3"; @endphp
                    @elseif($pedido->status=='Waiting Client Confirmation')
                        @php $button_color = "#f39305"; @endphp
                    @elseif($pedido->status=='Waiting Confirmation')
                        @php $button_color = "#f39305"; @endphp
                        @php $fontButtonSize = "12px"; @endphp
                    @elseif($pedido->status=='Confirmed')
                        @php $button_color = "green"; @endphp
                    @elseif($pedido->status=='Edited')
                        @php $button_color = "#F5AA3B"; @endphp
                    @elseif($pedido->status=='Cancelled')
                        @php $button_color = "red"; @endphp
                    @else
                        @php $button_color = "red"; @endphp
                    @endif

                    @if(in_array($authUser->email, $users_array))
                        @if($pedido->status != 'Cancelled')
                            <div class="add-product">
                                <i data-agency="{{$geral[$key]['nome']}}" data-id="{{$pedido->id}}"
                                    data-referency="{{$pedido->referencia}}" data-leadname="{{$pedido->lead_name}}" data-toggle="modal"
                                    data-target="#modal-add-product" aria-hidden="true" class="fa fa-plus-circle fa-2x"
                                    aria-hidden="true">
                                    </i>
                            </div>
                            <div class="edit-pedidogeral">
                                <i data-agency="{{$geral[$key]['nome']}}" data-id="{{$pedido->id}}"
                                    data-referency="{{$pedido->referencia}}" data-leadname="{{$pedido->lead_name}}" data-toggle="modal"
                                    data-target="#modal-edit-pedidogeral" aria-hidden="true" class="fa fa-pencil-square fa-2x"
                                    aria-hidden="true">
                                    </i>
                            </div>
                        @endif
                    @endif
                    <!-- MENU DE INFORMAÇÃO -->
                    <button class="accordion accordion-agency {{$pedido->id}}" style="background-color: #24AEC9; color: white; font-size:15px; font-weight: bold">
                        <div class="w3-col l3 m6 s12">
                            <div class="info-agency">
                                <b style="color: #333; font-size:15px; font-weight: bold"> Agency: </b>
                                <label>{{$geral[$key]['nome']}}</label>
                            </div>
                            @if($pedido->type == "New Booking")
                            <div class="info-agency">
                                <b style="color: #333; font-size:15px; font-weight: bold"> Type: </b>
                                {{$pedido->type}}
                            </div>
                            @else
                            <div class="info-agency" style="background-color:red;">
                                <b style="color: #fff; font-size:15px; font-weight: bold"> Type: </b>
                                {{$pedido->type}}
                            </div>
                            @endif
                        </div>
                        <div class="w3-col l3 m6 s12">
                            <div class="info-agency">
                                <b style="color: #333; font-size:15px; font-weight: bold">Lead Name: </b>
                                <label>{{$pedido->lead_name}}</label>
                            </div>
                            <div class="info-agency">
                                <b style="color: #333; font-size:15px; font-weight: bold "> Responsible: </b>
                                {{$pedido->responsavel}}
                            </div>
                        </div>
                        <div class="w3-col l3 m6 s12">
                            <div class="info-agency">
                                <b style="color: #333; font-size:15px; font-weight: bold ">Agency Reference: </b>
                                {{$pedido->referencia}}
                            </div>
                            <div class="info-agency">
                                <b style="color: #333; font-size:15px; font-weight: bold"> Created: </b>
                                <span style="font-size:15px; font-weight: bold">{{ Carbon\Carbon::parse($pedido->created_at)->format('d/m/y') }}</span>
                                <b style="color: #333; font-size:15px; font-weight: bold "> / Check-In: </b>
                                <span style="font-size:15px; font-weight: bold">
                                    {{ Carbon\Carbon::parse($pedido->DataFirstServico)->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="w3-col l3 m6 s12">
                            <div class="info-agency">
                                @if(in_array($authUser->email, $users_array))
                                <b style="color:#333; font-size:15px; font-weight: bold">Grand Profit:</b>
                                <label id="grandProfit{{$key}}"> 0.00 </label>
                                @endif
                            </div>
                            <div class="info-agency">
                                <b style="color:#333; font-size:15px; font-weight: bold">Grand Total:</b>
                                @if(in_array($authUser->email, $users_array))
                                    <label class="grandTotal{{$key}}">0.00</label>
                                @else
                                    <label>{{$pedido->valor != null ? $pedido->valor : "0.00"}}</label>
                                @endif
                            </div>
                        </div>
                        <div class="status-bar"
                            style="color:#fff; background-color:{{ $button_color }}; font-size: {{ $fontButtonSize }} ; min-height: 30px;">
                            {{$pedido->status}}
                        </div>
                    </button>

                    <!-- MENU DE INFORMAÇÃO -->

                    <div id="panel{{$key}}" class="panel" style="overflow-y: auto; background-color:#333; font-size:15px; font-weight: bold">
                        <div class="w3-row w3-padding unclosed">
                            <div class="w3-col l12">
                                @php $i = 0; @endphp

                                @foreach($pedido->produtoss as $key1 => $produtoss)

                                @php
                                    $tempProdutoKey = collect( $produto[$key] )->map(function ($q) {return (object)$q;});
                                    $produto[$key][$key1] = $tempProdutoKey[$key1];
                                    $produto[$key][$key1]->pivot = (object)$produto[$key][$key1]->pivot;
                                @endphp

                                <!-- MENU DE INFORMAÇÃO GERAL DE PRODUTO -->
                                @if(in_array($authUser->email, $users_array))

                                <div style="background-color: {{ $button_color }};height: 30px;padding: 5px;"
                                    id="product{{$pedido->id}}{{$produto[$key][$key1]->pivot->id}}"
                                    data-pedido-geral-id="{{$pedido->id}}" data-key="{{$key}}"
                                    data-product-id="{{$produto[$key][$key1]->pivot->id}}" class="accordion accordion-product">
                                    <span style=" color: white;">
                                        <div class="w3-col l3"><b>
                                        <i style="color:#333;" class="fa fa-university fa-1x"
                                                    aria-hidden="true" title="Product">
                                                    </i>
                                            </b>{{$produto[$key][$key1]->nome}}
                                        </div>

                                        @php
                                        $suppliers = App\SupplierContact::where('supplier_id', '=',
                                        $produto[$key][$key1]->supplier_id)->where('type', '=', 'Reservations')->get();
                                        @endphp

                                        <div class="w3-col l4 m8">
                                            <b>
                                                <i style="color:#333; font-weight: bold; font-size: 18px"
                                                class="fa fa-envelope fa-1x" aria-hidden="true"></i>
                                            </b>
                                            <select id="email_{{$produto[$key][$key1]->id}}_{{$pedido->id}}"
                                                style="width: 362px; color:#000!important;">
                                                <option value="{{$produto[$key][$key1]->email ? $produto[$key][$key1]->email : "
                                                    - "}}">
                                                    {{$produto[$key][$key1]->email ?
                                                    $produto[$key][$key1]->email."(".$produto[$key][$key1]->nome.")" : " - "}}
                                                </option>
                                                @foreach($suppliers as $supplier)
                                                <option value="{{$supplier->email}}" style="color:#000!important;">
                                                    {{$supplier->email}} ({{$supplier->name}})
                                                </option>
                                                @endforeach
                                                <option value="sales@atravel.pt">sales@atravel.pt</option>
                                            </select>
                                        </div>

                                        <div class="w3-col l1 m4">
                                            <b>
                                            <i style="color:#333;"
                                                    class="fa fa-exclamation-triangle fa-1x" aria-hidden="true">
                                                </i>
                                            </b>
                                            {{$produto[$key][$key1]->pivot->email_check ? $produto[$key][$key1]->pivot->email_check : " - "}}
                                        </div>
                                        <div class="w3-col l2 m4">
                                            <b style="color:#333; font-weight: bold; font-size: 18px ">Total
                                                Product:&nbsp;&nbsp;</b>
                                            @if(in_array($authUser->email, $users_array))

                                            <div style="display:inline-flex; font-weight: bold; font-size: 18px"
                                                id="totalProduct{{$key}}_{{$key1}}">
                                                0.00
                                            </div>
                                            <div>
                                                <span
                                                    onclick="enviaProduct('{{$produto[$key][$key1]->pivot->id}}','{{$key}}','{{$key1}}')"
                                                    class="buttonn{{$key}}"></span>
                                            </div>
                                            @else

                                            <div>{{$produto[$key][$key1]->pivot->valor}}&nbsp;€&nbsp;</div>
                                            @endif
                                        </div>
                                        <div class="w3-col l2 m4">
                                            <b style="color:#333; font-weight: bold; font-size: 18px ">Product
                                                Profit:&nbsp;&nbsp;</b>
                                            <span style="display:inline-flex; font-weight: bold; font-size: 18px"
                                                id="profitProduct{{$key}}_{{$key1}}">
                                                0.00
                                            </span>
                                            <span style="display:none; font-weight: bold; font-size: 18px "
                                                id="profitProduct_hidden{{$key}}_{{$key1}}">
                                                0.00
                                            </span>

                                            <span class="mail{{$key}}"
                                                onclick="mail('{{collect($produto[$key][$key1])}}', {{$produto[$key][$key1]->id}}, {{$pedido->id}})"></span>
                                        </div>
                                    </span>
                                </div>

                                @else
                                <button class="accordion accordion-product">
                                    <span>
                                        <b>Product: </b>{{$produto[$key][$key1]->nome}}
                                    </span>
                                </button>
                                @endif
                                <!-- MENU DE INFORMAÇÃO GERAL DE PRODUTO -->

                                <div class="panel" style="overflow-y: auto;">
                                    <div class="w3-row w3-padding">
                                        <div class="w3-col l12">
                                            @include('Admin.profile.struct.rooms', ['authUser' => $authUser])
                                            <!-- ROOMS -->
                                            @include('Admin.profile.struct.golfs', ['authUser' => $authUser])
                                            <!-- GOLF -->
                                            @include('Admin.profile.struct.transfer', ['authUser' => $authUser])
                                            <!-- TRANSFERS -->
                                            @include('Admin.profile.struct.car', ['authUser' => $authUser])
                                            <!-- RENT A CAR -->
                                            @include('Admin.profile.struct.tickets', ['authUser' => $authUser])
                                            <!-- TICKETS -->
                                        </div>
                                        <div class="w3-row w3-padding">
                                            <div class="w3-col l2">&nbsp;</div>

                                            <div class="w3-col l2">
                                                <span class="w3-right">
                                                    <table frame="box">
                                                        <tr id="tr_total_{{$key}}_{{$key1}}">
                                                        </tr>
                                                    </table>
                                                </span>
                                            </div>

                                            <div class="w3-col l2">
                                                @if(in_array($authUser->email, $users_array))
                                                <span class="w3-right">
                                                    <table frame="box">
                                                        <tr id="tr_profit_{{$key}}_{{$key1}}">
                                                            <script type="text/javascript">
                                                                var valorKickbackAcc;
                                                                var valorKickbackGolf;
                                                                var valorKickbackTransfer;
                                                                var valorKickbackCar;
                                                                var valorKickbackTicket;
                                                                var valorMarkupAcc;
                                                                var valorMarkupGolf;
                                                                var valorMarkupTransfer;
                                                                var valorMarkupCar;
                                                                var valorMarkupTicket;
                                                            </script>
                                                        </tr>
                                                    </table>
                                                </span>
                                                @endif
                                            </div>


                                            <div class="w3-col l12">
                                                @if($pedido->status=='Edited' || $pedido->status=='Waiting Confirmation')
                                                @if(in_array($authUser->email, $users_array))
                                                <span class="w3-right">
                                                    <span class="w3-button w3-gray"
                                                        onclick="mail('{{ collect($produto[$key][$key1]) }}', {{$produto[$key][$key1]->id}}, {{$pedido->id}})">
                                                        Send email
                                                    </span>
                                                </span>
                                                @endif
                                                @endif
                                            </div>

                                            <div class="w3-col l12">
                                                @if($pedido->status=='Edited' || $pedido->status=='Waiting Confirmation')
                                                @if(in_array($authUser->email, $users_array))
                                                @if($produto[$key][$key1]->pivot->email_check=='wait')
                                                <span class="w3-right">
                                                    <table frame="box">
                                                        <tr>
                                                            <td>
                                                                <span class="w3-button w3-green"
                                                                    onclick="mailConf({{ collect($produto[$key][$key1]) }})">Confirmed
                                                                    email
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </span>
                                                @endif
                                                @endif
                                                @endif
                                            </div>

                                            <div class="w3-col l12">
                                                @if($pedido->status=='Confirmed')
                                                @if(in_array($authUser->email, $users_array))
                                                <span class="w3-right" style="margin-left: 1%">
                                                    <a data-url="{{ route('profile.download.excel.roomlist', $pedido->id) }}"
                                                        href="{{ route('profile.download.excel.roomlist', $pedido->id) }}"
                                                        data-pedido="{{$pedido->id}}" data-button="downloadExcelRoomList"
                                                        class="btn btn-danger btn-xs" target="_blank">
                                                        Excel Print RoomList
                                                    </a>
                                                </span>
                                                @endif

                                                <span class="w3-right">
                                                    <a href="{{ route('profile.voucher',['pedido_produto_id'=>$produto[$key][$key1]->pivot->id,'pedido_id'=>$pedido->id]) }}"
                                                        class="btn btn-info btn-xs">
                                                        Voucher
                                                    </a>
                                                </span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                @php $i++; @endphp
                                @endforeach
                            </div>

                            @php $total_payments = 0; @endphp

                            @if(isset($pedido->getTotalPayment))

                            @if( isset($pedido->getTotalPayment) == false )
                            @php $total_payments = 0; @endphp
                            @else
                            @foreach($pedido->getTotalPayment as $payments)
                            @php $total_payments += (float) $payments['payment']; @endphp
                            @endforeach
                            @endif
                            @else

                            @if(!isset($pedido->get_total_payment))
                            @php $total_payments = 0; @endphp
                            @else
                            @foreach($pedido->get_total_payment as $payments)
                            @php $total_payments += (float) $payments['payment']; @endphp
                            @endforeach
                            @endif
                            @endif

                            @php

                            $total_pedido = 0;
                            foreach($pedido->produtos as $produto_valor){
                            $total_pedido += (float) $produto_valor['valor'];
                            }


                            $total_payments = (float) $total_payments;
                            $total_pedido = (float) $total_pedido;

                            if($total_pedido < $total_payments){
                                $background_button="#green" ; $color_button="#333" ;
                                }elseif($total_pedido==$total_payments){
                                    $background_button="#4CAF50" ; $color_button="#333" ;
                                }else{
                                    $background_button="#eee" ; $color_button="#333" ; }
                            @endphp
                            <div class="w3-row w3-padding">
                                <div class="w3-col l12 pull-right">
                                    @if(in_array($authUser->email, $users_array))
                                    <div class="w3-left col-lg-3">
                                        <label style="color:#fff; font-size:20px; font-weight: bold "><b>Total Paid:
                                            </b>
                                        </label>
                                        <div class="input-group">
                                            <input
                                                style="background-color:{{$background_button}};color:{{$color_button}}; font-size:20px; font-weight: bold"
                                                type="text" value="{{$total_payments}}" id="total-paid{{$key}}"
                                                class="form-control w3-block" name="total-paid" disabled>
                                            <button type="hidden" id="total_pedido" class="grandTotal{{$key}} hidden"></button>
                                            <span class="input-group-btn">
                                                <button data-agency="{{$geral[$key]['nome']}}" data-id="{{$pedido->id}}"
                                                    data-total="{{$pedido->valor}}" data-leadname="{{$pedido->lead_name}}"
                                                    class="payments-modal-btn btn btn-default" data-toggle="modal"
                                                    data-target="#payments-modal" aria-hidden="true" type="button">
                                                    See all
                                                </button>
                                            </span>
                                        </div><!-- /input-group -->
                                    </div>
                                    @endif
                                    <span class="w3-right">
                                        <table frame="box">
                                            <tr>

                                                @if(in_array($authUser->email, $users_api_transfergest))
                                                    @if(isset($transfers[$key]))
                                                    <td>
                                                        <span title="Send All transfers to transfergest"
                                                            class="w3-button w3-block sendTransfergest"
                                                            data-pedidogeral-id="{{  $pedido->id }}"
                                                            style="font-size: 1em; background-color: #fbb040">
                                                            <i class="fa fa-arrow-right"
                                                                style="margin-right: 2px; transform:  rotate(-45deg); font-size: 1.1em; "></i>
                                                            TG
                                                        </span>
                                                    </td>
                                                    @endif
                                                @endif

                                                @if($pedido->status=='Confirmed')
                                                <td>
                                                    <span style="background-color:#008000!important; color:#fff!important;"
                                                        class="w3-button w3-yellow w3-block" id="imprimir-pedido"
                                                        data-id="{{ $pedido->id }}" data-type="noats">Print</span>
                                                </td>

                                                @if(in_array($authUser->email, $users_array ))
                                                <td style="padding-right: 30px;">
                                                    <span style="background-color:#fb5a00 !important ; color:#fff!important;"
                                                        class="w3-button w3-yellow w3-block" id="imprimir-pedido"
                                                        data-id="{{ $pedido->id }}" data-type="ats">Print ATs</span>
                                                </td>
                                                @foreach($pedido->pedidoprodutos as $p)

                                                @if($p['tipoproduto'] == 'quarto')
                                                <td>
                                                    <span class="w3-button w3-yellow w3-block" id="imprimir-pedido-markup"
                                                        data-id="{{ $pedido->id }}">Print w/markup</span>
                                                </td>
                                                @break
                                                @endif
                                                @endforeach
                                                @endif

                                                @endif
                                                @if($pedido->status=='Cancelled')
                                                @if(in_array($authUser->email, $users_array))
                                                <td>
                                                    <span onclick="edita('{{$pedido->id}}','{{$key}}', $(this))"
                                                        class="w3-button w3-blue w3-block">
                                                        Edit
                                                    </span>
                                                </td>
                                                @endif
                                                @else

                                                @if(in_array($authUser->email, $users_array))
                                                    @if($pedido->status=='In Progress')
                                                    <td>
                                                        <span onclick="envia('{{$pedido->id}}','{{$key}}')"
                                                            class="w3-button w3-blue w3-block">
                                                            Send
                                                        </span>
                                                    </td>
                                                    @endif

                                                    @if($pedido->status=='Waiting Client Confirmation')
                                                    <td>
                                                        <span onclick="edita('{{$pedido->id}}','{{$key}}', $(this))"
                                                            class="w3-button w3-blue w3-block">
                                                            Edit
                                                        </span>
                                                    </td>
                                                    @endif

                                                    @if($pedido->status=='Edited')
                                                    <td>
                                                        <span onclick="edita('{{$pedido->id}}','{{$key}}', $(this))"
                                                            class="w3-button w3-blue w3-block">
                                                            Edit
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span onclick="confirmaAts('{{$pedido->id}}','{{$key}}')"
                                                            class="w3-button w3-green w3-block">
                                                            Confirm
                                                        </span>
                                                    </td>
                                                    @endif

                                                    @if($pedido->status=='Confirmed')
                                                    <td>
                                                        <span onclick="edita('{{$pedido->id}}','{{$key}}'), $(this)"
                                                            class="w3-button w3-blue w3-block">
                                                            Edit
                                                        </span>
                                                    </td>
                                                    @endif

                                                    @if($pedido->status=='In Progress')
                                                    <td>
                                                        <span onclick="recalcular('{{$pedido->id}}','{{$key}}'), $(this)"
                                                            class="w3-button w3-green w3-block">
                                                            Recalcular
                                                        </span>
                                                    </td>
                                                    @endif

                                                    @if($pedido->status=='Waiting Confirmation')
                                                    <td>
                                                        <span onclick="edita('{{$pedido->id}}','{{$key}}'), $(this)"
                                                            class="w3-button w3-blue w3-block">
                                                            Edit
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span onclick="confirmaAts('{{$pedido->id}}','{{$key}}')"
                                                            class="w3-button w3-green w3-block">
                                                            Confirm
                                                        </span>
                                                    </td>
                                                    @endif
                                                @else
                                                    @if($pedido->status=='Waiting Client Confirmation')
                                                    <td>
                                                        <span onclick="confirma('{{$pedido->id}}','{{$key}}')"
                                                            class="w3-button w3-green w3-block">
                                                            Confirm
                                                        </span>
                                                    </td>
                                                    @endif
                                                @endif
                                                <td>
                                                    @if(in_array($authUser->email, $users_array))
                                                        <span onclick="cancel('{{$pedido->id}}','{{$key}}')"
                                                            class="w3-button w3-red w3-block">
                                                            Cancel
                                                        </span>
                                                    @endif
                                                </td>
                                                @endif
                                            </tr>
                                        </table>
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- MENU DE INFORMAÇÃO DE PRODUTO -->
                </div>
            @endforeach
        @else
            @php
            $key = 0;
            @endphp
            <div class="w3-row">
                <span style="padding-top:20%; text-align:center">
                    <h2>Não existem pedidos com estas descrições.</h1>
                </span>
            </div>
        @endif
    </div>

</div>

<!-- Modal Pax Number Room -->
<div class="modal fade" id="room-pax-names" tabindex="-1" role="dialog" aria-labelledby="room-pax-namesLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="room-pax-namesLabel">Agency: <label
                        id="room-pax-agency-names-modal"></label> | Lead Name: <label
                        id="room-pax-lead-names-modal"></label> | <b>Change Pax names</b></h5>
                <label>Alert: Saving will overwrite the number of people.</label>
            </div>
            <div class="modal-body">
                <div id="room_pax_names_div">
                </div>
            </div>
            <div class="modal-footer">
                <span>
                    <button type="button" class="btn btn-secondary room_pax_close" data-dismiss="modal">Close</button>
                    @if(in_array($authUser->email, $users_array))
                    <button type="button" class="btn btn-primary room_pax_save">Save</button>
                    <button type="button" class="btn btn-danger room_pax_clear_rooms"
                        title="Delete all rooms with no people">Delete empty rooms</button>
                    @endif
                    <input type="hidden" id="people_number_modal">
                    <input type="hidden" id="quarto_id">
                </span>
            </div>
        </div>
    </div>
</div>

@if(in_array($authUser->email, $users_array))
    @php
    $key = 0;
    @endphp
    <!-- Menu para apagar o produto -->
    <div id="contextMenu" class="dropdown clearfix">
        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu"
            style="display:block;position:static;margin-bottom:5px;">
            <li>
                <input type="hidden" id="remove_product_id">
                <input type="hidden" id="remove_pedido_geral_id">
                <input type="hidden" id="remove_key">
                <a tabindex="-1" href="#" class="remove_product_btn"
                    onclick="edita($('#remove_pedido_geral_id').val(),'{{$key}}', $(this))">Apagar</a>
            </li>
            {{-- <li class="divider"></li> --}}
            {{-- <li><a tabindex="-1" href="#">Final Action</a> --}}
            </li>
        </ul>
    </div>

    <!-- Modal Add Product -->
    <div class="modal fade" id="modal-add-product" role="dialog" aria-labelledby="modal-add-productLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h5 class="modal-title" id="modal-add-productLabel"><b>Add Product - <label
                                id="add_product_referency"></label></b></h5>
                    <hr>
                    <label>Agency: <label id="add_product_agency"></label> | Lead Name: <label
                            id="add_product_lead_name"></label></label>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-3">
                            {{-- <div class="input-group"> --}}
                                <label>Type:</label>
                                <select id="add_product_type" width="100%" name="product_type" class="select-simple">
                                    <option value=""> Selecione</option>
                                    <option value="alojamento">Accommodation</option>
                                    <option value="golf">Golf</option>
                                    <option value="transfer">Transfer</option>
                                    <option value="car">Rent-a-Car</option>
                                    <option value="ticket">Ticket</option>
                                </select>
                                {{--
                            </div> --}}
                        </div>
                        <div class="col-lg-7">
                            <div class="input-group" style="min-width:100%">
                                <label>Product:</label>
                                <select id="add_product_product_id" width="100%" name="products" class="select-simple">
                                    <option>Select a product ...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="input-group">
                                <label>N. of Rows:</label>
                                <input id="add_product_qtd" type="text" class="form-control ats-border-color">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span>
                        <input type="hidden" id="add_product_pedido_geral_id">
                        <button type="button" class="btn btn-secondary close_add_products"
                            data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary create_add_products_btn">Create</button>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Remarks -->
    @include("Admin.profile.modals.editarRemark")
    <!-- Modal Edit Remarks -->

     <!-- Modal Edit Remarks Internos -->
    @include("Admin.profile.modals.editarRemarkInterno")
    <!-- Modal Edit Remarks Internos -->


    <!-- Modal Edit Pedido -->
    <div class="modal fade" id="modal-edit-pedidogeral" role="dialog" aria-labelledby="modal-edit-pedidogeralLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h5 class="modal-title" id="modal-edit-pedidogeralLabel"><b>Edit - <label id="edit_agency"></label></b>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="input-group" style="min-width:100%">
                                <label>Lead Name:</label>
                                <input id="edit_lead_name" type="text" class="form-control ats-border-color">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="input-group" style="min-width:100%">
                                <label>Reference</label>
                                <input id="edit_referency" type="text" class="form-control ats-border-color">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span>
                        <input type="hidden" id="edit_pedido_geral_id">
                        <button type="button" class="btn btn-secondary close_add_products"
                            data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary edit_products_btn">Edit</button>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Payment -->
    <div class="modal fade" id="payments-modal" tabindex="-1" role="dialog" aria-labelledby="payments-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h5 class="modal-title" id="payments-modalLabel"><b>Payments</b> | Agency: <label
                            id="payments-agency-modal"></label> | Lead Name: <label id="payments-leadname-modal"></label>
                    </h5>
                </div>
                <div class="modal-body">
                    <section>
                        <label><label style="color:green;">New</label> Payment:</label>
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <div class='input-group date datetimepickerFormat' style="position: relative;">
                                        <div style="position: absolute;"></div>
                                        <input type='text' readonly name="payment_date" id="payment_date"
                                            class="th-price form-control ats-border-color" placeholder="Insert date...">
                                        <span class="input-group-addon ats-border-color">
                                            <span class="w3-large ats-text-color fa fa-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="input-group">
                                    <input type="text" name="payment" id="payment_value"
                                        class="form-control w3-block payment" placeholder="Insert value...">
                                    <input type="hidden" name="payment_pedido_geral_id" id="payment_pedido_geral_id">
                                    <span class="input-group-btn">
                                        <button class="sendpayment_button btn btn-default" type="button">Send</button>
                                    </span>
                                </div><!-- /input-group -->
                            </div>
                        </div>
                        <label style="margin-top:10px">Payments:</label>
                        <div class="payments-box">
                            <table id="payments-table"></table>
                        </div>
                    </section>
                    <br>

                    <div>
                        <input type="hidden" name="total-paid-modal" id="total-paid-modal" class="total-paid-modal">
                        <input type="hidden" name="total-pedido-modal" id="total-pedido-modal" class="total-pedido-modal">
                        <label><b>Total: </b></label> <label class="payments_total_modal"> 0</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <span>
                        <button type="button" class="btn btn-secondary room_pax_close" data-dismiss="modal">Close</button>
                        <input type="hidden" id="people_number_modal">
                    </span>
                </div>
            </div>
        </div>
    </div>
@endif

@push('javascript')
    <script type="text/javascript" src="{{ URL::asset('Admin/js/profile.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endpush

@include("Admin.profile.modalValidarPagamentoTransfergest")
@include("Admin.profile.modalValidarCamposEnviarApi")
@include("Admin.profile.modalValidarCamposEnviarTransferUniApi")

@endsection

@push("css")
@routes

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<style>

    [id^="new_room"] input[type='number'] {
        text-align: center;
    }

    .checkbox {
        font-size: 1.3em;
    }

    .checkbox label:after {
        content: '';
        display: table;
        clear: both;
    }

    .checkbox .cr {
        position: relative;
        display: inline-block;
        border: 1px solid #a9a9a9;
        border-radius: .25em;
        width: 1.7em !important;
        height: 1.7em !important;
        float: left;
        margin-right: .5em;
    }

    .checkbox .cr .cr-icon {
        position: absolute;
        font-size: 1.2em !important;
        line-height: 0;
        top: 50%;
        left: 15%;
    }

    .checkbox label input[type="checkbox"] {
        display: none;
    }

    .checkbox label input[type="checkbox"]+.cr>.cr-icon {
        opacity: 0;
    }

    .checkbox label input[type="checkbox"]:checked+.cr>.cr-icon {
        opacity: 1;
    }

    .checkbox label input[type="checkbox"]:disabled+.cr {
        opacity: .5;
    }
</style>
@endpush

@push("javascript")

<script>
$(document).ready(function(){

    window.clearFormInput = () => {
        $(':input','#profileFormSearch')
            .not(':button, :submit, :reset, :hidden, select')
            .val('')
            .prop('checked', false)
            .prop('selected', false);
    }

    $(".sendTransferTransfergest").on("click", function(e){

        e.preventDefault();
        e.stopImmediatePropagation();
        e.stopPropagation();

        $("#modalEnviaCamposApitransferunico").modal("show");

        $(".EnviaDadosTransfer").attr("data-transfer-id", $(this).attr("data-transfer-id"));
        $(".EnviaDadosTransfer").attr("data-pedidogeral-id", $(this).attr("data-pedidogeral-id"));
    });

    $(".EnviaDadosTransfer").on("click", function(){
        $.ajax({
            url: "{{ route('sendtransfer.transfergest') }}",
            method: 'post',
            data: {
                pedido_transfer_id : $(this).attr("data-transfer-id"),
                pedido_geral_id : $(this).attr("data-pedidogeral-id"),
                send_data_hora : $("#modalEnviaCamposApitransferunico").find("input[name='data_hora']").is(":checked"),
                send_pax : $("#modalEnviaCamposApitransferunico").find("input[name='lotacao']").is(":checked"),
                send_address : $("#modalEnviaCamposApitransferunico").find("input[name='moradas']").is(":checked"),
                send_profit : $("#modalEnviaCamposApitransferunico").find("input[name='valores']").is(":checked"),
                send_email : $("#modalEnviaCamposApi").find("input[name='sendemail']").is(":checked"),
            },
            beforeSend: function(){
                $(".loader").show();
            },
            success: function(json){
                $.confirm({
                        content: json.msg,
                        theme: 'dark',
                        buttons: {
                            confirm: {
                                btnClass: 'btn-warning',
                                action: function() {
                                    $("#modalEnviaCamposApitransferunico").modal("hide");
                                }
                            },
                        }
                    });
            },
            error: function(error){
                console.log(error);
            },complete:function(){
                $(".EnviaDadosTransfer").removeAttr("data-transfer-id");
                $(".loader").hide();
            }
        });
    });

    $(".sendTransfergest").on("click", function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        e.stopPropagation();
        $("#modalEnviaCamposApi").modal("show");
        $(".enviarDadosApi").attr("data-pedidogeral-id", $(this).attr("data-pedidogeral-id"));
    });

    $(".enviarDadosApi").on("click", function(){

        $.ajax({

            url: "{{ route('enviarservico.transfergest') }}",
            method: 'post',
            data: {
                pedido_geral_id : $(this).attr("data-pedidogeral-id"),
                send_data_hora : $("#modalEnviaCamposApi").find("input[name='data_hora']").is(":checked"),
                send_pax : $("#modalEnviaCamposApi").find("input[name='lotacao']").is(":checked"),
                send_address : $("#modalEnviaCamposApi").find("input[name='moradas']").is(":checked"),
                send_profit : $("#modalEnviaCamposApi").find("input[name='valores']").is(":checked"),
                send_email : $("#modalEnviaCamposApi").find("input[name='sendemail']").is(":checked"),
            },
            beforeSend: function(){
                    $(".loader").show();
            },
            success: function(json){
                $.confirm({
                        content: json.msg,
                        theme: 'dark',
                        buttons: {
                            confirm: {
                                btnClass: 'btn-warning',
                                action: function() {
                                    $("#modalEnviaCamposApi").modal("hide");
                                }
                            },
                        }
                    });
            },
            error: function(error){
                console.log(error);
            },complete:function(){
                $(".enviarDadosApi").removeAttr("data-pedidogeral-id");
                $(".loader").hide();
            }
        });

    });

    $(".validarTransfergest").on("click", function(e){

        e.preventDefault();
        e.stopImmediatePropagation();
        e.stopPropagation();

        let id = $(this).attr("data-id");

            $.ajax({
                url: "{{ route('buscarpagamentos.transfergest') }}",
                method: 'post',
                data: {
                    id : $(this).attr("data-pedidogeral-id"),
                },beforeSend:function(){

                    $(".loader").show();
                    $("#modalValidarPagamentoBooking").modal("show");
                    let main = $(".bodyValidarPagamentoBooking").find(".timeline");
                    main.empty();

                    $(".bodyValidarPagamentoBooking").find("input[name='cliente_booking_id']").remove();
                    $(".bodyValidarPagamentoBooking").find("input[name='deposito']").val( 0 );
                },
                success: function(json){

                    $(".bodyValidarPagamentoBooking").find("input[name='valor_reserva']").val( json.valor_reserva );
                    $(".bodyValidarPagamentoBooking").find("input[name='total_pago']").val( json.total_pago );
                    $(".bodyValidarPagamentoBooking").find("input[name='valor_falta']").val( json.valor_falta );

                    $(".bodyValidarPagamentoBooking").append(`<input type="hidden" value="${json.id}" name="cliente_booking_id">`);


                    console.log(json);

                    if( json.pagamentos.length >= 1){

                        $(".bodyValidarPagamentoBooking").find(".showPagamentos").fadeIn();

                        let main = $(".bodyValidarPagamentoBooking").find(".timeline");

                        $.each(json.pagamentos,  function(index, p){

                            main.append(`
                                <li class="card-link">
                                    <span>Depósito de: </span>
                                    <span style="color:#21c0e8">${parseFloat(p.valor_pago).toFixed(2)}€</span>
                                    <span style="margin-right:5px;">, at ${moment(p.created_at).format("D/MM/Y")}</span>
                                    <span style="margin-right:5px;">| pgt. by: <span style="font-weight: 700">${p.user != null ? p.user.name : null}</span></span>
                                </li>
                            `);
                        });

                    }else{

                        $(".bodyValidarPagamentoBooking").find(".showPagamentos").fadeOut();

                    }

                },
                complete: function(){
                    $(".loader").hide();
                }
            });

    });

    $(".guardarDeposito").on("click", function(e){

        e.preventDefault();
        e.stopImmediatePropagation();
        e.stopPropagation();

        $.ajax({
            url: "{{ route('adddeposito.transfergest') }}",
            method: 'post',
            data: {
                id: $(".bodyValidarPagamentoBooking").find("input[name='cliente_booking_id']").val(),
                valor_reserva: $(".bodyValidarPagamentoBooking").find("input[name='valor_reserva']").val(),
                valor_deposito: $(".bodyValidarPagamentoBooking").find("input[name='deposito']").val(),
            },
            beforeSend: function(){

                $(".loader").show();

                if( $(".bodyValidarPagamentoBooking").find("input[name='deposito']").val() <= 0){
                    $.confirm({
                        title: 'Ops!',
                        content: 'Value 0 for deposit is not permited',
                        theme: 'dark',
                        buttons: {

                            confirm: {
                                btnClass: 'btn-warning',
                                action: function() {
                                return true;
                                }
                            },
                        }
                    });

                    return false;
                }
            },
            success: function(json){

                $.confirm({
                    title: 'All Done!',
                    content: 'New deposit add successfull',
                    animation: 'zoom',
                    closeAnimation: 'scale',
                    theme: 'dark',
                    buttons: {

                        confirm: {
                            btnClass: 'btn-warning',
                            action: function() {
                                $("#modalValidarPagamentoBooking").modal("hide");
                            }
                        },
                    }
                });

            },
            complete: function(){
                $(".loader").hide();
            }
        });
    });

    var tr_total = $('[id^="tr_total_"]');
    for (var i = 0; i < tr_total.length; i++) {
        var id = tr_total[i].id;
        var idParts = id.split('_');
        var key = idParts[2];
        var key1 = idParts[3];

        observeElement('finalAcc' + key + '_' + key1, key, key1);
        observeElement('finalGolf' + key + '_' + key1, key, key1);
        observeElement('finalTransfer' + key + '_' + key1, key, key1);
        observeElement('finalCar' + key + '_' + key1, key, key1);
        observeElement('finalTicket' + key + '_' + key1, key, key1);
    }

    var tr_profit = $('[id^="tr_profit_"]');
    for (var i = 0; i < tr_profit.length; i++) {
        var id = tr_profit[i].id;
        var idParts = id.split('_');
        var key = idParts[2];
        var key1 = idParts[3];

        observeProfitElements(key, key1);
    }
});

function calculateValues(key, key1) {
    var finalAcc = parseFloat($('#finalAcc' + key + '_' + key1).html()) || 0;
    var finalGolf = parseFloat($('#finalGolf' + key + '_' + key1).html()) || 0;
    var finalTransfer = parseFloat($('#finalTransfer' + key + '_' + key1).html()) || 0;
    var finalCar = parseFloat($('#finalCar' + key + '_' + key1).html()) || 0;
    var finalTicket = parseFloat($('#finalTicket' + key + '_' + key1).html()) || 0;

    var indiv = parseFloat($('#totalProduct' + key + '_' + key1).html()) || 0;

    var totalProduct = finalAcc + finalGolf + finalTransfer + finalCar + finalTicket;
    $('#totalProduct' + key + '_' + key1).html(totalProduct.toFixed(2));

    var grand = parseFloat($('.grandTotal' + key).html()) || 0;
    grand = grand - indiv + totalProduct;

    $('.grandTotal' + key).html(grand.toFixed(2));
}

function observeElement(id, key, key1) {
    var targetNode = document.getElementById(id);
    if (targetNode) {
        var config = { childList: true, subtree: true };

        var callback = function(mutationsList, observer) {
            calculateValues(key, key1);
        };

        var observer = new MutationObserver(callback);
        observer.observe(targetNode, config);
    }
}

function calculateProfit(key, key1) {
    var totalProfitAcc = parseFloat($('#totalProfitAcc' + key + '_' + key1).html()) || 0;
    var totalProfitGolf = parseFloat($('#totalProfitGolf' + key + '_' + key1).html()) || 0;
    var totalProfitTransfer = parseFloat($('#totalProfitTransfer' + key + '_' + key1).html()) || 0;
    var totalProfitCar = parseFloat($('#totalProfitCar' + key + '_' + key1).html()) || 0;
    var totalProfitTicket = parseFloat($('#totalProfitTicket' + key + '_' + key1).html()) || 0;

    totalProfitAcc -= $('#kickbackAcc' + key + '_' + key1).html() || valorKickbackAcc;
    totalProfitGolf -= $('#kickbackGolf' + key + '_' + key1).html() || valorKickbackGolf;
    totalProfitTransfer -= $('#kickbackTransfer' + key + '_' + key1).html() || valorKickbackTransfer;
    totalProfitCar -= $('#kickbackCar' + key + '_' + key1).html() || valorKickbackCar;
    totalProfitTicket -= $('#kickbackTicket' + key + '_' + key1).html() || valorKickbackTicket;

    totalProfitAcc = isNaN(totalProfitAcc) ? 0 : totalProfitAcc + valorMarkupAcc;
    totalProfitGolf = isNaN(totalProfitGolf) ? 0 : totalProfitGolf + valorMarkupGolf;
    totalProfitTransfer = isNaN(totalProfitTransfer) ? 0 : totalProfitTransfer + valorMarkupTransfer;
    totalProfitCar = isNaN(totalProfitCar) ? 0 : totalProfitCar + valorMarkupCar;
    totalProfitTicket = isNaN(totalProfitTicket) ? 0 : totalProfitTicket + valorMarkupTicket;

    var indiv2 = parseFloat($('#profitProduct' + key + '_' + key1).html()) || 0;

    var profitProduct = totalProfitAcc + totalProfitGolf + totalProfitTransfer + totalProfitCar + totalProfitTicket;
    $('#profitProduct' + key + '_' + key1).html(profitProduct.toFixed(2));

    var grandProfit = parseFloat($('#grandProfit' + key).html()) || 0;

    grandProfit = grandProfit - indiv2 + profitProduct;
    $('#grandProfit' + key).html(grandProfit.toFixed(2));
}

function observeProfitElements(key, key1) {
    var elementsToObserve = [
        '#totalProfitAcc' + key + '_' + key1,
        '#kickbackAcc' + key + '_' + key1,
        '#markupAcc' + key + '_' + key1,
        '#totalProfitGolf' + key + '_' + key1,
        '#kickbackGolf' + key + '_' + key1,
        '#markupGolf' + key + '_' + key1,
        '#totalProfitTransfer' + key + '_' + key1,
        '#kickbackTransfer' + key + '_' + key1,
        '#markupTransfer' + key + '_' + key1,
        '#totalProfitCar' + key + '_' + key1,
        '#kickbackCar' + key + '_' + key1,
        '#markupCar' + key + '_' + key1,
        '#totalProfitTicket' + key + '_' + key1,
        '#kickbackTicket' + key + '_' + key1,
        '#markupTicket' + key + '_' + key1
    ];

    elementsToObserve.forEach(function (selector) {
        var targetNode = document.querySelector(selector);
        if (targetNode) {
            var observer = new MutationObserver(function () {
                calculateProfit(key, key1);
            });
            observer.observe(targetNode, { childList: true, subtree: true });
        }
    });
}
</script>
@endpush
