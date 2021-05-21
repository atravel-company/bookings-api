@push('css')
  <style> 
   .golf-extras-table .th-price {
       min-width: 132px;
   }
   </style>
@endpush

@if(isset($golfs[$key][$key1]))
    <button class="accordion"><span><b>Golfs </b></span></button>
    <div class="panel" style="overflow-y: auto;">
        <!-- GOLF - RESERVA -->
        <p>
            <div class="w3-row w3-padding" style="overflow-y: scroll;">
                {{ Form::open(array('id'=>'form_golf_'.$key.'_'.$key1, 'name'=>'form_golf_'.$key.'_'.$key1, 'method'=>'GET')) }}
                    <input type="hidden" class="produto_id" name="produto_id_{{$key1}}" value="{{$produto[$key][$key1]->id}}">
                    <input type="hidden" class="key_max" name="key_max_{{$key1}}" value="{{ count($golfs[$key][$key1]) }}">
                    <table class="w3-table w3-striped w3-centered golf-table">

                        <tr style="background-color: #24AEC9; color: white;">
                            @if(in_array(Auth::user()->email, $users_array))
                                <th class="th-number"><span class="add-golf" data-key="{{$key}}" data-key1="{{$key1}}"><i class="fa fa-plus-circle fa-2x" aria-hidden="true"></i></span></th>
                            @endif
                            <th class="th-date">data</th>
                            <th class="th-date">hora</th>
                            <th class="th-string">course</th>
                            <th class="th-number">people</th>
                            <th class="th-number">Players Free</th>
                            <th class="th-number">Rate</th>
                            <th class="th-number">Total</th>
                            {{-- <th class="th-string">Remark</th> --}}
                            
                            @if(in_array(Auth::user()->email, $users_array))
                                <th class="th-ats-rate">ATS Rate</th>
                                <th class="th-ats-rate">ATS Total Rate</th>
                                <th class="th-ats-rate">Total Profit</th>
                            @endif
                        </tr>

                        @foreach($golfs[$key][$key1] as $key2=> $golf)


                            <input type="hidden" name="golfe_id{{$key}}_{{$key1}}_{{$key2}}" value="{{$golf['id']}}">
                            <input type="hidden" name="pedido_produto_id{{$key1}}" value="{{$golf['pedido_produto_id']}}">
                            <tr id="golf-original">
                                @if(in_array(Auth::user()->email, $users_array)) 
                                <td> <!-- Golf Remove -->
                                    <span class="remove-golf" onclick="return confirm('Are you sure you want to delete?')?removeRow({{$golf['id']}}, {{$key}}, $(this), 'golf'):'';"><i class="fa fa-minus-circle fa-2x" aria-hidden="true"></i></span>
                                </td>
                                @endif
                                <td>
                                    <div class="form-group">
                                        <div class="input-group datetimepicker" style="position: relative;">
                                            <input value="{{  Carbon\Carbon::parse( $golf['data'])->format('d/m/Y') }}" id="golfe_data{{$key}}_{{$key1}}_{{$key2}}" name="golfe_data{{$key}}_{{$key1}}_{{$key2}}" type="text" class="form-control ats-border-color checkin_date_golf">
                                            <span class="input-group-addon ats-border-color">
                                                <span class="w3-large ats-text-color fa fa-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td> <!-- Golf Hora -->
                                    <div class="form-group" style="width: 140px;">
                                        <div class='input-group timepicker'>
                                            <input value="{{$golf['hora']}}" type='text' name="golfe_hora{{$key}}_{{$key1}}_{{$key2}}" id="golfe_hora{{$key}}_{{$key1}}_{{$key2}}" class="th-price form-control ats-border-color" placeholder="Golf Hora">
                                            <span class="input-group-addon ats-border-color">
                                                <span class="w3-large ats-text-color fa fa-calendar"></span>
                                            </span>
                                        </div>
                                    </div>                          
                                </td>
                                <td>
                                    <input value="{{$golf['course']}}" id="golfe_course{{$key}}_{{$key1}}_{{$key2}}" name="golfe_course{{$key}}_{{$key1}}_{{$key2}}" type="text" class="form-control ats-border-color">
                                </td>
                                <td>
                                    <input value="{{$golf['people']}}" id="golfe_people{{$key}}_{{$key1}}_{{$key2}}" name="golfe_people{{$key}}_{{$key1}}_{{$key2}}" onchange="somaGolf({{$key}},{{$key1}},{{$key2}})" type="number" style="text-align: center" class="form-control ats-border-color">
                                </td>

                                @if(in_array(Auth::user()->email, $users_array))
                                    <td>
                                        <input value="{{$golf['free']}}" id="playersFree{{$key}}_{{$key1}}_{{$key2}}" name="playersFree{{$key}}_{{$key1}}_{{$key2}}" onchange="somaGolf({{$key}},{{$key1}},{{$key2}})" type="number" name="" class="form-control loaddd">
                                    </td>
                                @else
                                    <td>{{$golf['free']}}</td>
                                @endif

                                @if(in_array(Auth::user()->email, $users_array))
                                    <td>
                                        <input value="{{$golf['rate']}}" id="realGolf{{$key}}_{{$key1}}_{{$key2}}" onchange="somaGolf({{$key}},{{$key1}},{{$key2}})" class="form-control w3-block loaddd" type="number" name="realGolf{{$key}}_{{$key1}}_{{$key2}}">
                                    </td>
                                @else
                                    <td>{{$golf['rate']}}</td>
                                @endif

                                @if(in_array(Auth::user()->email, $users_array))
                                    <td id="totalizaGolf{{$key}}_{{$key1}}_{{$key2}}">0.00</td>
                                @else
                                    <td>{{$golf['total']}}</td>
                                @endif
                                {{-- <td><input type="text" class='form-control w3-block' name="golf_remark{{$key}}_{{$key1}}_{{$key2}}" value=""></td> --}}

                                @if(in_array(Auth::user()->email, $users_array))
                                    <td>
                                        <input value="{{$golf['ats_rate']}}" id="atsRateGolf{{$key}}_{{$key1}}_{{$key2}}" name="atsRateGolf{{$key}}_{{$key1}}_{{$key2}}" onchange="somaGolf({{$key}},{{$key1}},{{$key2}})" class="form-control w3-block loaddd" type="number" name="">
                                    </td>
                                    <td id="atsTotalRateGolf{{$key}}_{{$key1}}_{{$key2}}">0.00</td>
                                    <td >
                                        <span id="atsProfitGolf{{$key}}_{{$key1}}_{{$key2}}">0.00</span>
                                        <span  onclick="enviaGolfsEsp('{{$golf['id']}}','{{$key}}','{{$key1}}','{{$key2}}')" class="buttonn{{$key}}"></span>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                {{ Form::close() }}
            </p>
            <!-- GOLF - RESERVA -->
            <!-- GOLF - EXTRAS -->
            <p>
                {{ Form::open(array('id'=>'form_extras'.$key.'_'.$key1, 'name'=>'form_extras'.$key.'_'.$key1, 'method'=>'GET')) }}
                <input type="hidden" name="produto_id_{{$key1}}" value="{{$produto[$key][$key1]->id}}">
                    <table class="w3-table w3-striped w3-centered golf-extras-table">
                        <tr style="background-color: #24AEC9; color: white;">
                            @if(in_array(Auth::user()->email, $users_array))
                                <th class="th-number"><span class="add-golf-extras" data-key1="{{$key1}}" data-key="{{$key}}"><i class="fa fa-plus-circle fa-2x" aria-hidden="true"></i></span></th>
                            @endif
                            <th class="th-select">Extra</th>
                            <th class="th-number" style="min-width:140px">Amount</th>
                            <th class="th-price">Rate</th>
                            <th style="min-width: 322px;"></th>
                            
                            <th class="th-price">Total</th>
                            @if(in_array(Auth::user()->email, $users_array))
                                <th class="th-ats-rate">ATS Rate</th>
                                <th class="th-ats-rate">ATS Total Rate</th>
                                <th class="th-ats-rate">Total Profit</th>
                            @endif
                        </tr>
                        @php $id_extra = 0; @endphp
                        <input type="hidden" class="pedido_produto_id" name="pedido_produto_id{{$key1}}" value="{{$golf['pedido_produto_id']}}">
                        @foreach($extras as $key2=>$extra)
                            @if($produto[$key][$key1]->pivot->id==$extra->pedido_produto_id and $extra->tipo=='golf')
                            <input type="hidden" name="tipo{{$key}}_{{$key1}}_{{$id_extra}}" value="golf" class="form-control">
                                <tr class="extra-tr">
                                    <input type="hidden" name="extra_id{{$key}}_{{$key1}}_{{$id_extra}}" value="{{$extra->id}}" class="form-control">
                                    @if(in_array(Auth::user()->email, $users_array))
                                        <td>
                                            <span class="remove-extra" onclick="return confirm('Are you sure you want to delete?')?removeRow({{$extra->id}}, {{$key}}, $(this), 'extra'):'';"><i class="fa fa-minus-circle fa-2x" aria-hidden="true"></i></span>
                                        </td>
                                    @endif
                                    @if(in_array(Auth::user()->email, $users_array))
                                    <td>
                                        <select id="extra_name{{$key}}_{{$key1}}_{{$id_extra}}" name="extra_name{{$key}}_{{$key1}}_{{$id_extra}}" class="form-control w3-block loaddd">
                                        <option value="">Select</option>
                                        @foreach($tipos_extras as $tipo_extra)
                                            @if( $produto[$key][$key1]->pivot->produto_id == $tipo_extra->produto_id)
                                                @if($tipo_extra->extra_id == $extra->extra_id)
                                                    <option value="{{ $tipo_extra->extra_id }}" selected="selected">{{ $tipo_extra->name }}</option>
                                                @else
                                                    <option value="{{ $tipo_extra->extra_id }}">{{ $tipo_extra->name }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </select>
                                    </td>
                                    @else
                                        <td>{{$extra->name}}</td>
                                    @endif
                                    <td>
                                        <input value="{{$extra->amount}}" id="golf_extra_amount{{$key}}_{{$key1}}_{{$id_extra}}" onchange="somaExtra('',{{$key}},{{$key1}},{{$id_extra}},'Golf')" class="form-control w3-block loaddd" type="number" name="golf_extra_amount{{$key}}_{{$key1}}_{{$id_extra}}">
                                    </td>

                                    @if(in_array(Auth::user()->email, $users_array))
                                        <td>
                                            <input value="{{$extra->rate}}" class="form-control loaddd" id="extraRate{{$key}}_{{$key1}}_{{$id_extra}}" onchange="somaExtra('',{{$key}},{{$key1}},{{$id_extra}},'Golf')" type="number" name="extraRate{{$key}}_{{$key1}}_{{$id_extra}}">
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
                                            <input value="{{$extra->ats_rate}}" class="form-control loaddd" id="atsExtraRate{{$key}}_{{$key1}}_{{$id_extra}}" onchange="somaExtra('',{{$key}},{{$key1}},{{$id_extra}},'Golf')" type="number" name="extraRate{{$key}}_{{$key1}}_{{$id_extra}}">
                                        </td>
                                        <td id="atsTotalExtraRate{{$key}}_{{$key1}}_{{$id_extra}}">0.00</td>
                                        <td >
                                            <span id="atsExtraProfit{{$key}}_{{$key1}}_{{$id_extra}}">0.00</span> 
                                            <span onclick="enviaExtra('{{$extra->id}}','{{$key}}','{{$key1}}','{{$id_extra}}', 2)" data-changed="false" class="buttonn{{$key}}"></span>
                                        </td>
                                    @endif
                                </tr>
                                @php $id_extra++; @endphp
                            @endif
                        @endforeach
                        <input type="hidden" class="key_max" name="key_max_{{$key1}}" value="{{$id_extra}}">
                        <!-- Estático new -->
                        @if(in_array(Auth::user()->email, $users_array))
                            <select data-extraID='extra-select' class="form-control w3-block loaddd hidden">
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
        <!-- GOLF - EXTRAS -->
        <!-- GOLF - CALCULOS -->
        <div class="w3-row w3-padding">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <section>
                    <label><label style="color:green;">New</label> Remark:</label>
                    <div class="input-group">
                      <input type="text" name="remark_geral" class="form-control w3-block remark_geral" placeholder="Insert remark...">
                      <input type="hidden" name="remark_geral_id" class="remark_geral_id" value="{{$golf['id']}}">
                      <input type="hidden" name="type_remark" class="type_remark" value="golf">
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
                        <button style="float: right; margin-top: 4px;" data-pedido_id='{{$golf['id']}}' data-type="golf" data-toggle="modal" data-target="#modal-edit-remark" aria-hidden="true" class="editremark_button btn btn-default" type="button">Edit Remark</button>
                    @endif
                    <div class="remark-box" id="remark-box{{$golf['id']}}">
                        @php $remark = ""; @endphp
                        @foreach($golfs[$key][$key1] as $Pedido)
                            @if($Pedido->remark !== null)
                                @php $remark .= $Pedido->remark."<br>"; @endphp
                            @endif
                        @endforeach
                        {!! $remark !!}
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
                                    + <span id="totalGolf{{$key}}_{{$key1}}">0.00</span> €
                                @else
                                    + {{$valorGolf[$key][$key1]->valor_extra}} €
                               @endif
                        </div>
                        <div class="col-xs-7">
                            <b>Total Golf</b>
                        </div>
                        <div class="col-xs-5" style="text-align: right">
                            @if(in_array(Auth::user()->email, $users_array))
                                + <span id="totalGolfExtra{{$key}}_{{$key1}}">0.00</span> €
                            @else
                                + {{$valorGolf[$key][$key1]->valor_extra}} €
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

                        <div class="col-xs-5" style="text-align: right; display: {{$hidden}}">
                            @if(in_array(Auth::user()->email, $users_array))
                                - <span id="kickbackGolf{{$key}}_{{$key1}}">0.00</span> €
                            @else
                                - {{$valorGolf[$key][$key1]->kick}} €
                           @endif
                        </div>
                        <div class="col-xs-7" style="display: {{$hidden}}">
                            <b>Kick-back</b>
                            @if(in_array(Auth::user()->email, $users_array))
                                <input value="{{$valorGolf[$key][$key1]->kick}}" id="kickbackGolfInput{{$key}}_{{$key1}}" onchange="kickbackGolf({{$key}},{{$key1}})" type="number" min="0" max="100" name="" class="loaddd">
                                <b>%</b>
                            @endif
                        </div>
                        @if(in_array(Auth::user()->email, $users_array))
                            <div class="col-xs-5" style="text-align: right">
                                    + <span id="markupGolf{{$key}}_{{$key1}}">0.00</span> €
                                {{-- @else
                                    + {{$valorGolf[$key][$key1]->markup}} € --}}
                            </div>

                            <div class="col-xs-7">
                                <b style="padding-right: 16px;">Markup</b>
                                    <input value="{{$valorGolf[$key][$key1]->markup}}" class="loaddd" id="markupGolfInput{{$key}}_{{$key1}}" onchange="markupGolf({{$key}},{{$key1}})" type="number" min="0" max="100" name="">
                                    <b>%</b>
                            </div>
                        @endif
                        <div class="col-xs-5" style="border-top: 1px solid black;text-align: right;"">
                            @if(in_array(Auth::user()->email, $users_array))
                                <span id="finalGolf{{$key}}_{{$key1}}">0.00</span> €
                            @else
                                {{$valorGolf[$key][$key1]->total}} €
                           @endif
                           <span onclick="enviaGolfs('{{$produto[$key][$key1]->pivot->id}}','{{$key}}','{{$key1}}')" class="buttonn{{$key}}"></span>
                           <span class="hidden" id="totalProfitGolf{{$key}}_{{$key1}}">0.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- GOLF - CALCULOS -->
    </div>
@endif