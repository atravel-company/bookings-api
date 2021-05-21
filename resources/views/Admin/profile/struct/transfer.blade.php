@if(isset($transfers[$key][$key1]))
    <button class="accordion"><span style="color: #24AEC9;"><b>Transfers </b></span></button>
    <div class="panel" style="overflow-y: auto;">
        <!-- TRANSFERS - RESERVA -->
        <p>
            <div class="w3-row w3-padding" style="overflow-y: scroll;">
                {{ Form::open(array('id'=>'form_transfers_'.$key.'_'.$key1, 'name'=>'form_transfers_'.$key.'_'.$key1, 'method'=>'GET')) }}
                    <div>
                        <i class="fa fa-arrow-circle-left arrow-left"></i>
                    </div>
                    <input type="hidden" class="produto_id" name="produto_id_{{$key1}}" value="{{$produto[$key][$key1]->id}}">
                    <input type="hidden" class="key_max" name="key_max_{{$key1}}" value="{{ count($transfers[$key][$key1]) }}">
                    <table class="w3-table w3-striped w3-centered transfer-table">
                    <!-- Lista de Transfers -->
                        <tr style="background-color: #24AEC9; color: white;">
                            @if(in_array(Auth::user()->email, $users_array))
                                <th class="th-number"><span class="add-transfer" data-key="{{$key}}" data-key1="{{$key1}}"><i class="fa fa-plus-circle fa-2x" aria-hidden="true"></i></span></th>
                            @endif
                            <th class="th-date">Data</th>
                            <th class="th-date">Hora</th>
                            <th class="th-number">Adults</th>
                            <th class="th-number">Children</th>
                            <th class="th-number">Babies</th>
                            <th style="min-width: 110px;">Flight Nº</th>
                            <th class="th-string">Pick-up</th>
                            <th class="th-string">Drop Off</th>
                            <th class="th-date">Company</th>
                            <th class="th-price">Total</th>
                            {{-- <th class="th-string">Remark</th> --}}
                            
                            @if(in_array(Auth::user()->email, $users_array))
                                <th class="th-ats-rate">ATS Rate</th>
                                <th class="th-ats-rate"></th>
                                <th class="th-ats-rate">Total Profit</th>
                            @endif
                        </tr>

                        @foreach($transfers[$key][$key1] as $key2=>$transfer)
                            <input type="hidden" name="transfer_id{{$key}}_{{$key1}}_{{$key2}}" value="{{$transfer['id']}}">
                            <input type="hidden" name="pedido_produto_id{{$key1}}" value="{{$transfer['pedido_produto_id']}}">
                            <tr>
                                @if(in_array(Auth::user()->email, $users_array))
                                    <td> 
                                        @if( Auth::user()->id == 2)
                                            @if(isset($transfers[$key]))
                                                <span title="Send transfers to transfergest" class="remove-transfer sendTransferTransfergest" data-pedidogeral-id="{{  $pedido->id }}" data-transfer-id="{{  $transfer['id'] }}" style="background-color: #fbb040; height: 32px;
                                                        width: 30px;
                                                        float: LEFT;border-radius: 100%">
                                                    <i class="fa fa-arrow-right" style="transform:  rotate(-45deg); color:black; font-size: 1.5em "></i>
                                                </span>
                                            @endif
                                        @endif

                                        <span style="float: right" class="remove-transfer" onclick="return confirm('Are you sure you want to delete?')?removeRow({{$transfer['id']}}, {{$key}}, $(this), 'transfer'):'';">
                                            <i class="fa fa-minus-circle fa-2x" aria-hidden="true"></i>
                                        </span>
                                    </td>
                                @endif
                                <td> <!-- Data -->
                                    <div class="form-group" style="width: 140px;">
                                        <div class='input-group date datetimepicker' style="position: relative;">
                                            <div style="position: absolute;"></div>
                                            <input value="{{  Carbon\Carbon::parse( $transfer['data'])->format('d/m/Y') }}" type='text' name="data{{$key}}_{{$key1}}_{{$key2}}" class="th-price form-control ats-border-color checkin_date_transfer" placeholder="Data">
                                            <span class="input-group-addon ats-border-color">
                                                <span class="w3-large ats-text-color fa fa-calendar"></span>
                                            </span>
                                        </div>
                                    </div>                          
                                </td>
                                <td> <!-- Hora -->
                                    <div class="form-group" style="width: 140px;">
                                        <div class="input-group date timepicker" style="position: relative;">
                                            <div style="position: absolute;"></div>
                                            <input value="{{$transfer['hora']}}" type="text" class="th-price form-control ats-border-color" name="hora{{$key}}_{{$key1}}_{{$key2}}" placeholder="Hora">
                                            <span class="input-group-addon ats-border-color">
                                                <span class="w3-large ats-text-color fa fa-calendar">
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <input value="{{$transfer['adult']}}" id="adult{{$key}}_{{$key1}}_{{$key2}}" class="form-control w3-block loaddd" type="number" name="adult{{$key}}_{{$key1}}_{{$key2}}">
                                </td>
                                <td>
                                    <input value="{{$transfer['children']}}" id="children{{$key}}_{{$key1}}_{{$key2}}" class=" form-control w3-block loaddd" type="number" name="children{{$key}}_{{$key1}}_{{$key2}}">
                                </td>
                                <td>
                                    <input value="{{$transfer['babie']}}" id="babie{{$key}}_{{$key1}}_{{$key2}}" class="form-control w3-block loaddd" type="number" name="babie{{$key}}_{{$key1}}_{{$key2}}">
                                </td>
                                <td>
                                    <input value="{{$transfer['flight']}}" id="flight{{$key}}_{{$key1}}_{{$key2}}" class="form-control w3-block loaddd" type="text" name="flight{{$key}}_{{$key1}}_{{$key2}}">
                                </td>
                                <td>
                                    <input value="{{$transfer['pickup']}}" id="pickup{{$key}}_{{$key1}}_{{$key2}}" class="form-control w3-block loaddd" type="text" name="pickup{{$key}}_{{$key1}}_{{$key2}}">
                                    
                                </td>
                                <td>
                                    <input value="{{$transfer['dropoff']}}" id="dropoff{{$key}}_{{$key1}}_{{$key2}}" class="form-control w3-block loaddd" type="text" name="dropoff{{$key}}_{{$key1}}_{{$key2}}">
                                </td>

                                @if(in_array(Auth::user()->email, $users_array))
                                    <td>
                                        <input value="{{$transfer['company']}}" id="company{{$key}}_{{$key1}}_{{$key2}}" onchange="" class="form-control w3-block loaddd" type="text" name="company{{$key}}_{{$key1}}_{{$key2}}">
                                    </td>
                                @else
                                    <td>{{$transfer['company']}}</td>
                                @endif

                                @if(in_array(Auth::user()->email, $users_array))
                                    <td>
                                        <input value="{{$transfer['total']}}" id="realTransfer{{$key}}_{{$key1}}_{{$key2}}" onchange="somaTransfer({{$key}},{{$key1}},{{$key2}})" style="width: 90%;" type="number" name="realTransfer{{$key}}_{{$key1}}_{{$key2}}" class="form-control loaddd">
                                    </td>
                                @else
                                    <td>{{$transfer['total']}}</td>
                                @endif
                                @if(in_array(Auth::user()->email, $users_array))
                                    <td>
                                        <input value="{{$transfer['ats_rate']}}" id="atsRateTransfer{{$key}}_{{$key1}}_{{$key2}}" onchange="somaTransfer({{$key}},{{$key1}},{{$key2}})" style="width: 90%;" type="number" name="atsRateTransfer{{$key}}_{{$key1}}_{{$key2}}" class="form-control loaddd">
                                    </td>
                                    <td></td>
                                    <td >
                                        <span id="atsProfitTransfer{{$key}}_{{$key1}}_{{$key2}}">0.00</span> 
                                        <span  onclick="enviaTransfersEsp('{{$transfer['id']}}','{{$key}}','{{$key1}}','{{$key2}}')" class="buttonn{{$key}}"></span>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                {{ Form::close() }}
            </p>
            <!-- TRANSFERS - RESERVA -->
            <!-- TRANSFERS - EXTRAS -->
            <p>
                {{ Form::open(array('id'=>'form_extras'.$key.'_'.$key1, 'name'=>'form_extras'.$key.'_'.$key1, 'method'=>'GET')) }}
                <input type="hidden" class="produto_id" name="produto_id_{{$key1}}" value="{{$produto[$key][$key1]->id}}">
                <table class="w3-table w3-striped w3-centered transfer-extras-table">
                    <tr style="background-color: #24AEC9; color: white;">
                        @if(in_array(Auth::user()->email, $users_array))
                            <th class="th-number"><span class="add-transfer-extras" data-key1="{{$key1}}" data-key="{{$key}}"><i class="fa fa-plus-circle fa-2x" aria-hidden="true"></i></span></th>
                        @endif
                        <th class="th-select">Extra</th>
                        <th class="th-number">Amount</th>
                        <th class="th-number">Rate</th>
                        <th style="min-width: 900px;"></th>
                        <th class="th-price">Total</th>
                        
                        @if(in_array(Auth::user()->email, $users_array))
                            <th class="th-ats-rate">ATS Rate</th>
                            <th class="th-ats-rate">ATS Total Rate</th>
                            <th class="th-ats-rate">Total Profit</th>
                        @endif
                    </tr>
                    @php $id_extra = 0; @endphp
                    <input type="hidden" class="pedido_produto_id" name="pedido_produto_id{{$key1}}" value="{{$transfer['pedido_produto_id']}}">
                    @foreach($extras as $key2=>$extra)
                        @if($produto[$key][$key1]->pivot->id==$extra->pedido_produto_id and $extra->tipo=='transfer')
                        <input type="hidden" name="tipo{{$key}}_{{$key1}}_{{$id_extra}}" value="transfer" class="form-control">
                            <tr class="extra-tr">
                            <input type="hidden" name="extra_id{{$key}}_{{$key1}}_{{$id_extra}}" value="{{$extra->id}}">
                                @if(in_array(Auth::user()->email, $users_array))
                                    <td>
                                        <span class="remove-extra" onclick="return confirm('Are you sure you want to delete?')?removeRow({{$extra->id}}, {{$key}}, $(this), 'extra'):'';"><i class="fa fa-minus-circle fa-2x" aria-hidden="true"></i></span>
                                    </td>
                                @endif
                                <td>
                                    <select id="extra_name{{$key}}_{{$key1}}_{{$id_extra}}" name="extra_name{{$key}}_{{$key1}}_{{$id_extra}}" class="form-control w3-block loaddd">
                                        @foreach($tipos_extras as $tipo_extra)
                                            @if( $produto[$key][$key1]->pivot->produto_id == $tipo_extra->produto_id)
                                                @if($tipo_extra->id == $extra->extra_id)
                                                    <option value="{{ $tipo_extra->extra_id }}" selected="">{{ $tipo_extra->name }}</option>
                                                @else
                                                    <option value="{{ $tipo_extra->extra_id }}">{{ $tipo_extra->name }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input value="{{$extra->amount}}" id="transfer_extra_amount{{$key}}_{{$key1}}_{{$id_extra}}" class="form-control w3-block loaddd" type="number" onchange="somaExtra('{{$extra->amount}}',{{$key}},{{$key1}},{{$id_extra}},'Transfer')" name="transfer_extra_amount{{$key}}_{{$key1}}_{{$id_extra}}">
                                </td>

                                @if(in_array(Auth::user()->email, $users_array))
                                    <td>
                                        <input value="{{$extra->rate}}" class="form-control loaddd" id="extraRate{{$key}}_{{$key1}}_{{$id_extra}}" onchange="somaExtra('{{$extra->amount}}',{{$key}},{{$key1}},{{$id_extra}},'Transfer')" type="number" name="extraRate{{$key}}_{{$key1}}_{{$id_extra}}">
                                    </td>
                                    <td></td>
                                    <td id="extraTotal{{$key}}_{{$key1}}_{{$id_extra}}">0.00</td>
                                @else
                                    <td>{{$extra->rate}}&nbsp;€</td>
                                    <td></td>
                                    <td>{{$extra->total}}&nbsp;€</td>
                                @endif

                                @if(in_array(Auth::user()->email, $users_array))
                                    <td>
                                        <input value="{{$extra->ats_rate}}" class="form-control loaddd" id="atsExtraRate{{$key}}_{{$key1}}_{{$id_extra}}" onchange="somaExtra('{{$extra->amount}}',{{$key}},{{$key1}},{{$id_extra}},'Transfer')" type="number" name="atsExtraRate{{$key}}_{{$key1}}_{{$id_extra}}">
                                    </td>
                                    <td id="atsTotalExtraRate{{$key}}_{{$key1}}_{{$id_extra}}">0.00</td>
                                    <td >
                                        <span id="atsExtraProfit{{$key}}_{{$key1}}_{{$id_extra}}">0.00</span>
                                        <span onclick="enviaExtra('{{$extra->id}}','{{$key}}','{{$key1}}','{{$id_extra}}', 3)" data-changed="false" class="buttonn{{$key}}"></span>
                                    </td>
                                @endif
                            </tr>
                            @php $id_extra++; @endphp
                        @endif
                    @endforeach
                    <input type="hidden" class="key_max" name="key_max_{{$key1}}" value="{{$id_extra}}">
                    <!-- Estático new -->
                    @if(in_array(Auth::user()->email, $users_array))
                        <select data-extraID="extra-select" class="form-control w3-block loaddd hidden">
                            <option value="">Select</option>
                            @foreach($tipos_extras as $tipo_extra)
                                @if( $produto[$key][$key1]->pivot->produto_id == $tipo_extra->produto_id)
                                    <option value="{{ $tipo_extra->extra_id }}">{{ $tipo_extra->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    @endif
                </table>
                {{ Form::close() }}
            </div>
        </p>
        <!-- TRANSFERS - EXTRAS -->
        <!-- TRANSFERS - CALCULOS -->
        <div class="w3-row w3-padding">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <section>
                    <label><label style="color:green;">New</label> Remark:</label>
                    <div class="input-group">
                      <input type="text" name="remark_geral" class="form-control w3-block remark_geral" placeholder="Insert remark...">
                      <input type="hidden" name="remark_geral_id" class="remark_geral_id" value="{{$transfer['id']}}">
                      <input type="hidden" name="type_remark" class="type_remark" value="transfer">
                      @if(in_array(Auth::user()->email, $users_array))
                            <input type="hidden" class="remark_operador" value="ats">
                        @else
                            <input type="hidden" class="remark_operador" value="agency">
                       @endif
                      <span class="input-group-btn">
                        <button class="sendremark_button btn btn-default" type="button">Send</button>
                      </span>
                    </div><!-- /input-group -->
                    <label style="margin-top:10px">Remarks:</label>
                    @if(in_array(Auth::user()->email, $users_array))
                        <button style="float: right; margin-top: 4px;" data-pedido_id='{{$transfer['id']}}' data-type="transfer" data-toggle="modal" data-target="#modal-edit-remark" aria-hidden="true" class="editremark_button btn btn-default" type="button">Edit Remark</button>
                    @endif
                    <div class="remark-box" id="remark-box{{$transfer['id']}}">

                        @php $remark = ""; @endphp
                        @foreach($transfers[$key][$key1] as $Pedido)
                            @if($Pedido->remark !== null)
                                @php $remark .= $Pedido->remark."<br>"; @endphp
                            @endif
                        @endforeach
                        {!! $remark !!}
                        {{-- {!! html_entity_decode($transfer['remark']) !!} --}} 
                        {{-- html_entity_decode(App\PedidoQuartoRoom::where('pedido_quarto_id', '=', $quarto['id'])->first()['remark']) --}}
                    </div>
                </section>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label>Calculation:</label>
                    <div style="border: 2px solid; height: 177px;">
                    <div class="col-md-1"><b>Total:</b></div>
                    <div class="col-md-11">
                        <div class="col-xs-5" style="text-align: right">
                            @if(in_array(Auth::user()->email, $users_array))
                                    + <span id="totalTransfer{{$key}}_{{$key1}}">0.00</span> €
                                @else
                                    + {{$valorTransfer[$key][$key1]->valor_transfer}} €
                               @endif
                        </div>
                        <div class="col-xs-7">
                            <b>Total Transfers</b>
                        </div>
                        <div class="col-xs-5" style="text-align: right">
                            @if(in_array(Auth::user()->email, $users_array))
                                + <span id="totalTransferExtra{{$key}}_{{$key1}}">0.00</span> €
                            @else
                                + {{$valorTransfer[$key][$key1]->valor_extra}} €
                            @endif
                        </div>
                        <div class="col-xs-7">
                            <b>Total Extras</b>
                        </div>

                        @php
                            if(isset($valorGolf[$key][$key1]->kick) || in_array(Auth::user()->email, $users_array)){
                                $hidden = "block";
                            } else{
                                $hidden = "none";
                            }

                        @endphp

                        <div class="col-xs-5" style="text-align: right; display:{{$hidden}};">
                            @if(in_array(Auth::user()->email, $users_array))
                                - <span id="kickbackTransfer{{$key}}_{{$key1}}">0.00</span> €
                            @else
                                - {{$valorTransfer[$key][$key1]->kick}} €
                           @endif
                        </div>
                        <div class="col-xs-7" style="display:{{$hidden}};">
                            <b>Kick-back</b>
                            @if(in_array(Auth::user()->email, $users_array))
                                <input value="{{$valorTransfer[$key][$key1]->kick}}" class="loaddd" id="kickbackTransferInput{{$key}}_{{$key1}}" onchange="kickbackTransfer({{$key}},{{$key1}})" type="number" min="0" max="100" name="">
                                <b>%</b>
                            @endif
                        </div>
                        @if(in_array(Auth::user()->email, $users_array))
                        <div class="col-xs-5" style="text-align: right">
                                + <span id="markupTransfer{{$key}}_{{$key1}}">0.00</span> €
                            {{-- </div>
                                @else
                                + {{$valorTransfer[$key][$key1]->markup}} €
                             --}}
                        </div>
                        @endif
                        @if(in_array(Auth::user()->email, $users_array))
                            <div class="col-xs-7">
                                <b style="padding-right: 16px;">Markup</b>
                                <input value="{{$valorTransfer[$key][$key1]->markup}}" class="loaddd" id="markupTransferInput{{$key}}_{{$key1}}" onchange="markupTransfer({{$key}},{{$key1}})" type="number" min="0" max="100" name="">
                                <b>%</b>
                            </div>
                        @endif
                            <div class="col-xs-5" style="border-top: 1px solid black;text-align: right;"">
                            @if(in_array(Auth::user()->email, $users_array))
                                <span id="finalTransfer{{$key}}_{{$key1}}">0.00</span> €
                            @else
                                {{$valorTransfer[$key][$key1]->total}} €
                           @endif
                           <span onclick="enviaTransfers('{{$produto[$key][$key1]->pivot->id}}','{{$key}}','{{$key1}}')" class="buttonn{{$key}}"></span>
                           <span class="hidden" id="totalProfitTransfer{{$key}}_{{$key1}}"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- TRANSFERS - CALCULOS -->



        @if( Auth::user()->id == 2)

            <div class="row" style="margin-bottom: 10px; margin-top: 10px">
                <div class="col-md-offset-1 col-md-11 ">

                    <span title="Clique to send transfer payments to Transfergest" class="w3-button validarTransfergest pull-right" data-pedidogeral-id="{{  $produto[$key][$key1]->pivot->id }}" style="font-size: 1em; background-color: #aaf1ad">
                        <i class="fa fa-euro" style="margin-right: 2px; font-size: 1.1em; "></i>
                        Pag.
                    </span>

                </div>
            </div>
            
        @endif


    </div>
@endif