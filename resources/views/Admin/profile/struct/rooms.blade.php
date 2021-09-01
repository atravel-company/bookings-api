@if(isset($quartos[$key][$key1]))

    <button class="accordion"><span><b>Rooms </b></span></button>
    <div class="panel" style="overflow-y: auto;">
        <!-- ROOMS - RESERVA -->
        <p>
            <div class="w3-row w3-padding" style="overflow-x: scroll;">
                {{ Form::open(array('id'=>'form_rooms_'.$key.'_'.$key1, 'name'=>'form_rooms_'.$key.'_'.$key1, 'method'=>'GET')) }}
                    <input type="hidden" class="produto_id" name="produto_id_{{$key1}}" value="{{$produto[$key][$key1]->id}}">
                    <input type="hidden" class="key_max" name="key_max_{{$key1}}" value="{{ count($quartos[$key][$key1]) }}">
                    <table class="w3-table w3-striped w3-centered rooms-table">
                        <!-- Lista de Quartos -->
                        <tr style="background-color: #24AEC9; color: white;">
                            @if(in_array(Auth::user()->email, $users_array))
                                <th class="th-number"><span class="add-quarto" data-key="{{$key}}" data-key1="{{$key1}}"><i class="fa fa-plus-circle fa-2x" aria-hidden="true"></i></span></th>
                            @endif
                            <th class="th-string">Room Type</th>
                            <th class="th-number">R. List</th>
                            <th class="th-number">Room</th>
                            <th class="th-date">Checkin</th>
                            <th class="th-date">Checkout</th>
                            <th class="th-number">Nights</th>
                            <th class="th-number">People Nº</th>
                            <th class="th-number">Board</th>

                            @if(in_array(Auth::user()->email, $users_array))
                                <th style="min-width: 120px;">R. Rate/night</th>
                            @endif

                            <th class="th-price">Special Offer</th>
                            <th class="th-price">Special Value</th>
                            <th class="th-price">Daily Rate</th>
                            <th class="th-price">Total</th>
                            <!-- <th class="th-string">Remark</th> -->

                            @if(in_array(Auth::user()->email, $users_array))
                                <th class="th-ats-rate">ATS Rate</th>
                                <th class="th-ats-rate">ATS Total Rate</th>
                                <th class="th-ats-rate">Total Profit</th>
                            @endif
                        </tr>
                        @foreach($quartos[$key][$key1] as $key2=>$quarto)
                            {{-- <input type="hidden" name="quarto_id{{$key}}_{{$key1}}_{{$key2}}" value="{{$quarto['id']}}">
                            <input type="hidden" name="pedido_produto_id{{$key1}}" value="{{$quarto['pedido_produto_id']}}"> --}}
                            <tr id="room-original">
                                    <input type="hidden" name="quarto_id{{$key}}_{{$key1}}_{{$key2}}" value="{{$quarto['id']}}">
                                    <input type="hidden" name="pedido_produto_id{{$key1}}" value="{{$quarto['pedido_produto_id']}}">

                                @if(in_array(Auth::user()->email, $users_array))
                                    <td> <!-- Room Remove -->
                                        <span class="remove-quarto" onclick="return confirm('Are you sure you want to delete?')?removeRow({{$quarto['id']}}, {{$key}}, $(this), 'alojamento'):'';"><i class="fa fa-minus-circle fa-2x" aria-hidden="true"></i></span>
                                    </td>
                                    <td> <!-- Room Type -->
                                        <input type="text" value="{{$quarto['type']}}" id="tipologia{{$key}}_{{$key1}}_{{$key2}}"  name="tipologia{{$key}}_{{$key1}}_{{$key2}}" class="form-control w3-block">
                                    </td>
                                    <td> <!-- Room pax icon modal -->
                                        <i class="fa fa-users room-pax-icon" data-toggle="modal" data-target="#room-pax-names" data-quarto-id="{{$quarto['id']}}" aria-hidden="true"></i>
                                        <input class="agency-name" type="hidden" value="{{$geral[$key]['nome']}}">
                                        <input class="agency-lead-name" value="{{$pedido->lead_name}}" type="hidden">
                                        <input class="pedido_geral_id" value="{{$pedido->id}}" type="hidden">
                                        <input class="produto_room_id" value="{{$produto[$key][$key1]->id}}" type="hidden">
                                        <input class="quarto_id" value="{{$quarto['id']}}" type="hidden">
                                    </td>
                                    <td> <!-- Amount -->
                                        <input type="number" value="{{ $quarto['rooms'] }}" style="text-align: center" id="quantidade{{$key}}_{{$key1}}_{{$key2}}" onchange="soma({{$key}},{{$key1}},{{$key2}})" name="quantidade{{$key}}_{{$key1}}_{{$key2}}" class="form-control w3-block" required="required" min="{{ ($quarto['rooms'] > 0 ? $quarto['rooms'] : 0) }}"  data-quarto-id="{{$quarto['id']}}" >
                                    </td>
                                    <td> <!-- Checkin -->
                                        <div class="form-group" style="width: 140px;">
                                            <div class='input-group date datetimepicker{{$key}}_{{$key1}}_{{$key2}}' id='datetimepicker12' style="position: relative;">
                                                <div style="position: absolute;" id="checkin{{$key}}_{{$key1}}_{{$key2}}"></div>
                                                <input value="{{$quarto['ini']}}" type='text' name="init{{$key}}_{{$key1}}_{{$key2}}" id="init{{$key}}_{{$key1}}_{{$key2}}" class="checkin_date th-price form-control ats-border-color roomCheckin" placeholder="Check-In">
                                                <span class="input-group-addon ats-border-color">
                                                    <span class="w3-large ats-text-color fa fa-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td> <!-- Checkout -->
                                        <div class="form-group" style="width: 140px;">
                                            <div class="input-group date datetimepickers{{$key}}_{{$key1}}_{{$key2}}" id='datetimepicker13' style="position: relative;">
                                                <div style="position: absolute;" id="checkout{{$key}}_{{$key1}}_{{$key2}}">
                                                </div>
                                                <input value="{{$quarto['out']}}" type="text" class="th-price form-control ats-border-color roomCheckout" id="find{{$key}}_{{$key1}}_{{$key2}}" name="checkout{{$key}}_{{$key1}}_{{$key2}}" placeholder="Check-Out">
                                                <span class="input-group-addon ats-border-color">
                                                    <span class="w3-large ats-text-color fa fa-calendar">
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <!-- Agência -->
                                    <td>{{$quarto['type']}}</td>
                                    <td> <!-- Room pax icon modal -->
                                        <i class="fa fa-users room-pax-icon" data-toggle="modal" data-target="#room-pax-names" data-quarto-id="{{$quarto['id']}}"  aria-hidden="true"></i>
                                        <input class="agency-name" type="hidden" value="{{$geral[$key]['nome']}}">
                                        <input class="agency-lead-name" value="{{$pedido->lead_name}}" type="hidden">
                                        <input class="pedido_geral_id" value="{{$pedido->id}}" type="hidden">
                                        <input class="produto_room_id" value="{{$produto[$key][$key1]->id}}" type="hidden">
                                        <input class="quarto_id" value="{{$quarto['id']}}" type="hidden">
                                    </td>
                                    <td>{{$quarto['rooms']}}</td>
                                    <td>{{$quarto['checkin']}}</td>
                                    <td>{{$quarto['checkout']}}</td>
                                @endif

                                <td id="dias{{$key}}_{{$key1}}_{{$key2}}"  ></td>

                                <script type="text/javascript">
                                    checkin='#checkin{{$key}}_{{$key1}}_{{$key2}}';
                                    checkout='#checkout{{$key}}_{{$key1}}_{{$key2}}';
                                    $('.datetimepicker{{$key}}_{{$key1}}_{{$key2}}').datetimepicker({widgetParent: checkin, format: 'DD/MM/YYYY', ignoreReadonly: true, widgetPositioning: { vertical: 'bottom'},}).on("dp.change", function (e) {
                                        $('.datetimepickers{{$key}}_{{$key1}}_{{$key2}}').data("DateTimePicker").minDate(e.date);

                                        mudadia(document.getElementById('init{{$key}}_{{$key1}}_{{$key2}}').value,document.getElementById('find{{$key}}_{{$key1}}_{{$key2}}').value,'{{$key}}_{{$key1}}_{{$key2}}');

                                        soma({{$key}},{{$key1}},{{$key2}});
                                    });

                                    $('.datetimepickers{{$key}}_{{$key1}}_{{$key2}}').datetimepicker({
                                       widgetParent: checkout,
                                       format: 'DD/MM/YYYY',
                                       ignoreReadonly: true,
                                       widgetPositioning: {
                                        vertical: 'bottom'
                                        },
                                    }).on("dp.change", function (e) {

                                        mudadia(document.getElementById('init{{$key}}_{{$key1}}_{{$key2}}').value,document.getElementById('find{{$key}}_{{$key1}}_{{$key2}}').value,'{{$key}}_{{$key1}}_{{$key2}}');

                                        soma({{$key}},{{$key1}},{{$key2}})
                                    });

                                    function parseDate(str,str2) {
                                        var date1 = new Date(str);
                                        var date2 = new Date(str2);
                                        var timeDiff = date2.getTime() - date1.getTime();
                                        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                                        $("#dias{{$key}}_{{$key1}}_{{$key2}}").html(diffDays)
                                    }

                                    function mudadia(str,str2,chave) {
                                        strn = str.split("/");
                                        nd=strn[2]+'-'+strn[1]+'-'+strn[0];
                                        str2n = str2.split("/");
                                        nd2=str2n[2]+'-'+str2n[1]+'-'+str2n[0];

                                        var date1 = new Date(nd);
                                        var date2 = new Date(nd2);
                                        var timeDiff = date2.getTime() - date1.getTime();
                                        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

                                        $("#dias"+chave).html(diffDays)
                                    }

                                    parseDate("{{$quarto['checkin']}}","{{$quarto['checkout']}}")

                                </script>

                                @if(in_array(Auth::user()->email, $users_array))

                                    <td>
                                        <input id="pessoas{{$key}}_{{$key1}}_{{$key2}}" style="text-align: center" onchange="soma({{$key}},{{$key1}},{{$key2}})" type="number" value="{{$quarto['people']}}" name="pessoas{{$key}}_{{$key1}}_{{$key2}}" class="form-control w3-block people_number" data-quarto-id="{{$quarto['id']}}" required="required">
                                    </td>
                                    <td>
                                        <select data-boardID="people-number-select" onchange="soma({{$key}},{{$key1}},{{$key2}})" id="board{{$key}}_{{$key1}}_{{$key2}}" style="width: 100px;" class="form-control ats-border-color" name="board{{$key}}_{{$key1}}_{{$key2}}" placeholder="" required="required">


                                            @if($quarto['plan']=='BB')
                                                <option selected="selected" value="BB">BB</option>
                                            @else
                                                <option value="BB">BB</option>
                                            @endif

                                            @if($quarto['plan']=='RO')
                                                <option selected="selected" value="RO">RO</option>
                                            @else
                                                <option value="RO">RO</option>
                                            @endif
                                            @if($quarto['plan']=='SC')
                                                <option selected="selected" value="SC">SC</option>
                                            @else
                                                <option value="SC">SC</option>
                                            @endif

                                            @if($quarto['plan']=='HB')
                                                <option selected="selected" value="HB">HB</option>
                                            @else
                                                <option value="HB">HB</option>
                                            @endif
                                            @if($quarto['plan']=='FB')
                                                <option selected="selected" value="FB">FB</option>
                                            @else
                                                <option value="FB">FB</option>
                                            @endif
                                            @if($quarto['plan']=='SEMI AI')
                                                <option selected="selected" value="SEMI AI">SEMI AI</option>
                                            @else
                                                <option value="SEMI AI">SEMI AI</option>
                                            @endif
                                            @if($quarto['plan']=='AI')
                                                <option selected="selected" value="AI">AI</option>
                                            @else
                                                <option value="AI">AI</option>
                                            @endif
                                        </select>
                                    </td>
                                @else
                                    <td  id="pessoas{{$key}}_{{$key1}}_{{$key2}}">
                                        <input style="text-align:center;" disabled="true" type="text" value="{{$quarto['people']}}" class="people_number form-control w3-block">
                                    </td>
                                    <td id="board{{$key}}_{{$key1}}_{{$key2}}">
                                        {{$quarto['plan']}}
                                    </td>
                                @endif

                                @if(in_array(Auth::user()->email, $users_array))
                                    <td>
                                        <input value="{{$quarto['night']}}" id="real{{$key}}_{{$key1}}_{{$key2}}" onchange="soma({{$key}},{{$key1}},{{$key2}})" class="form-control w3-block loaddd" type="number" name="real{{$key}}_{{$key1}}_{{$key2}}" required="required">
                                    </td>
                                    <td>
                                        <input value="{{$quarto['offer_name']}}" id="oferta{{$key}}_{{$key1}}_{{$key2}}" onchange="special_offer({{$key}}_{{$key1}}_{{$key2}})" class="form-control w3-block" type="text" name="oferta{{$key}}_{{$key1}}_{{$key2}}">
                                    </td>
                                @else
                                    <td>{{$quarto['offer_name']}}</td>
                                @endif

                                @if(in_array(Auth::user()->email, $users_array))
                                    <td>
                                        <input value="{{$quarto['offer']}}" id="desconto{{$key}}_{{$key1}}_{{$key2}}" onchange="soma({{$key}},{{$key1}},{{$key2}})" class="form-control w3-block loaddd" type="number" name="desconto{{$key}}_{{$key1}}_{{$key2}}">
                                    </td>
                                @else
                                    <td>{{$quarto['offer']}} €</td>
                                @endif

                                @if(in_array(Auth::user()->email, $users_array))
                                    <td id="preco{{$key}}_{{$key1}}_{{$key2}}">0.00</td>
                                @else
                                    <td>{{$quarto['price']}} €</td>
                                @endif

                                @if(in_array(Auth::user()->email, $users_array))
                                    <td id="totaliza{{$key}}_{{$key1}}_{{$key2}}">0.00</td>
                                @else
                                    <td>{{$quarto['total']}} €</td>
                                @endif
                                <!-- <td>{{App\PedidoQuartoRoom::where('pedido_quarto_id', '=', $quarto['id'])->first()['remark']}}</td> -->

                                @if(in_array(Auth::user()->email, $users_array))
                                    <td>
                                        <input value="{{$quarto['ats_rate']}}" id="atsRate{{$key}}_{{$key1}}_{{$key2}}" onchange="soma({{$key}},{{$key1}},{{$key2}})" class="form-control w3-block loaddd" type="number" name="atsRate{{$key}}_{{$key1}}_{{$key2}}" required="required">
                                    </td>
                                    <td id="atsTotalRate{{$key}}_{{$key1}}_{{$key2}}">0.00</td>
                                    <td >
                                        <span id="atsProfit{{$key}}_{{$key1}}_{{$key2}}">0.00</span>
                                        <span  onclick="enviaQuartosEsp('{{$quarto['id']}}','{{$key}}','{{$key1}}','{{$key2}}')" class="buttonn{{$key}}"></span>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                {{ Form::close() }}
            </p>
            <!-- ROOMS - RESERVA -->
            <!-- ROOMS - EXTRAS -->
            <p>
                {{ Form::open(array('id'=>'form_extras'.$key.'_'.$key1, 'name'=>'form_extras'.$key.'_'.$key1, 'method'=>'GET')) }}
                <input type="hidden" class="produto_id" name="produto_id_{{$key1}}" value="{{$produto[$key][$key1]->id}}">
                    <table class="w3-table w3-striped w3-centered rooms-extras-table">
                        <tr style="background-color: #24AEC9; color: white;">
                            @if(in_array(Auth::user()->email, $users_array))
                                <th class="th-number"><span class="add-quarto-extras" data-key1="{{$key1}}" data-key="{{$key}}"><i class="fa fa-plus-circle fa-2x" aria-hidden="true"></i></span></th>
                            @endif
                            <th class="th-select">Extra</th>
                            <th class="th-number">Amount</th>
                            <th class="th-number">Rate</th>
                            @if(in_array(Auth::user()->email, $users_array))
                                <th style="min-width: 1054px;"></th>
                            @else
                                <th style="min-width: 919px;"></th>
                            @endif
                            <th class="th-price">Total</th>
                            @if(in_array(Auth::user()->email, $users_array))
                                <th class="th-ats-rate">ATS Rate</th>
                                <th class="th-ats-rate">ATS Total Rate</th>
                                <th class="th-ats-rate">Total Profit</th>
                            @endif
                        </tr>
                        @php $id_extra = 0; @endphp
                        <input type="hidden" class="pedido_produto_id" name="pedido_produto_id{{$key1}}" value="{{$quarto['pedido_produto_id']}}">
                        @foreach($extras as $key2=>$extra)
                            @if(($produto[$key][$key1]->pivot->id==$extra->pedido_produto_id) and ($extra->tipo=='alojamento'))
                            <input type="hidden" name="tipo{{$key}}_{{$key1}}_{{$id_extra}}" value="alojamento" class="form-control">
                                <tr class="extra-tr">
                                <input type="hidden" name="extra_id{{$key}}_{{$key1}}_{{$id_extra}}" value="{{$extra->id}}">
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
                                        <input value="{{$extra->amount}}" id="room_extra_amount{{$key}}_{{$key1}}_{{$id_extra}}" onchange="somaExtra('',{{$key}},{{$key1}},{{$id_extra}},'Acc')" class="form-control w3-block loaddd" type="number" name="room_extra_amount{{$key}}_{{$key1}}_{{$id_extra}}">
                                    </td>

                                    @if(in_array(Auth::user()->email, $users_array))
                                        <td>
                                            <input value="{{$extra->rate}}" id="extraRate{{$key}}_{{$key1}}_{{$id_extra}}" onchange="somaExtra('',{{$key}},{{$key1}},{{$id_extra}},'Acc')" type="number" name="extraRate{{$key}}_{{$key1}}_{{$id_extra}}" class="form-control loaddd">
                                        </td>
                                        <td></td>
                                        <td id="extraTotal{{$key}}_{{$key1}}_{{$id_extra}}">0.00</td>
                                    @else
                                        <td>{{$extra->rate}} €</td>
                                        <td></td>
                                        <td>{{$extra->total}} €</td>
                                    @endif


                                    @if(in_array(Auth::user()->email, $users_array))
                                        <td>
                                            <input value="{{$extra->ats_rate}}" id="atsExtraRate{{$key}}_{{$key1}}_{{$id_extra}}" onchange="somaExtra('',{{$key}},{{$key1}},{{$id_extra}},'Acc')" type="number" class="loaddd form-control" name="atsExtraRate{{$key}}_{{$key1}}_{{$key2}}">
                                        </td>
                                        <td id="atsTotalExtraRate{{$key}}_{{$key1}}_{{$id_extra}}">0.00</td>
                                        <td >
                                            <span id="atsExtraProfit{{$key}}_{{$key1}}_{{$id_extra}}">0.00</span>
                                            <span  onclick="enviaExtra('{{$extra->id}}','{{$key}}','{{$key1}}','{{$id_extra}}', 1)" data-changed="false" class="buttonn{{$key}} hidden form-control"></span>
                                        </td>
                                    @endif
                                </tr>
                                @php $id_extra++; @endphp
                            @endif
                        @endforeach
                        <input type="hidden" class="key_max" name="key_max_{{$key1}}" value="{{$id_extra}}">
                        <!-- Estático new -->
                        @if(in_array(Auth::user()->email, $users_array))
                            <select data-extraID="extra-select" class="w3-block loaddd form-control hidden">
                                <option value="">Select</option>
                                @foreach($tipos_extras as $tipo_extra)
                                    @if( $produto[$key][$i]->pivot->produto_id == $tipo_extra->produto_id)
                                        <option value="{{ $tipo_extra->extra_id }}">{{ $tipo_extra->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        @endif
                    </table>
                {{ Form::close() }}
            </div>
        </p>
        <!-- ROOMS - RESERVA -->

        <!-- ROOMS - CALCULOS -->
        <div class="w3-row w3-padding">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <section>
                    <label><label style="color:green;">New</label> Remark:</label>
                    <div class="input-group">
                      <input type="text" name="remark_geral" class="form-control w3-block remark_geral" placeholder="Insert remark...">
                      <input type="hidden" name="remark_geral_id" class="remark_geral_id" value="{{$quarto['id']}}">
                      <input type="hidden" name="type_remark" class="type_remark" value="room">
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
                        <button style="float: right; margin-top: 4px;" data-pedido_id='{{$quarto['id']}}' data-type="room" data-toggle="modal" data-target="#modal-edit-remark" aria-hidden="true" class="editremark_button btn btn-default" type="button">Edit Remark</button>
                    @endif
                    <div class="remark-box" id="remark-box{{$quarto['id']}}">
                        @php $remark = ""; @endphp
                        @foreach($quartos[$key][$key1] as $Pedido)
                            @if($Pedido->remark !== null)
                                @php $remark .= $Pedido->remark."<br>"; @endphp
                            @endif
                        @endforeach
                        {!! $remark !!}
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
                                    + <span id="totalAcc{{$key}}_{{$key1}}">0.00</span> €
                                @else
                                    + {{$valor[$key][$key1]->valor_quarto}} €
                               @endif
                        </div>
                        <div class="col-xs-7">
                            <b>Total Accommodation</b>
                        </div>
                        <div class="col-xs-5" style="text-align: right">
                            @if(in_array(Auth::user()->email, $users_array))
                                + <span id="totalAccExtra{{$key}}_{{$key1}}">0.00</span> €
                            @else
                                + {{$valor[$key][$key1]->valor_extra}} €
                            @endif
                        </div>
                        <div class="col-xs-7">
                            <b>Total Extras</b>
                        </div>
                        @php
                            if(isset($valor[$key][$key1]->kick) || in_array(Auth::user()->email, $users_array)){
                                $hidden = "block";
                            } else{
                                $hidden = "none";
                            }

                        @endphp
                        <div class="col-xs-5" style="text-align: right; display: {{$hidden}}">
                            @if(in_array(Auth::user()->email, $users_array))
                                - <span id="kickbackAcc{{$key}}_{{$key1}}">0.00</span> €
                            @else
                                {{-- @if($valor[$key][$key1]->kick) --}}
                                    - {{$valor[$key][$key1]->kick}} €
                                {{-- @endif --}}
                           @endif
                        </div>

                        <div class="col-xs-7" style="display: {{$hidden}}">
                            {{-- @if($valor[$key][$key1]->kick) --}}
                            <b>Kick-back</b>
                                <input value="{{$valor[$key][$key1]->kick}}" id="kickbackAccInput{{$key}}_{{$key1}}" onchange="kickbackAcc({{$key}},{{$key1}})" type="number" min="0" max="100" name="" class="loaddd">
                                <b>%</b>
                            {{-- @endif --}}
                        </div>
                        @if(in_array(Auth::user()->email, $users_array))
                        <div class="col-xs-5" style="text-align: right">
                           {{--  @if(in_array(Auth::user()->email, $users_array)) --}}
                                + <span id="markupAcc{{$key}}_{{$key1}}">0.00</span> €
                           {{--  @else
                                + {{$valor[$key][$key1]->markup}} €
                           @endif --}}
                        </div>
                        <div class="col-xs-7">
                            <b style="padding-right: 16px;">Markup</b>
                                <input value="{{$valor[$key][$key1]->markup}}" id="markupAccInput{{$key}}_{{$key1}}" onchange="markupAcc({{$key}},{{$key1}})" type="number" min="0" max="100" name="" class="loaddd">
                                <b>%</b>
                        </div>
                        @endif
                        <div class="col-xs-5" style="border-top: 1px solid black;text-align: right;"">
                            @if(in_array(Auth::user()->email, $users_array))
                                <span id="finalAcc{{$key}}_{{$key1}}">0.00</span> €
                            @else
                                {{$valor[$key][$key1]->total}} €
                           @endif
                           <span onclick="enviaQuartos('{{$produto[$key][$key1]->pivot->id}}','{{$key}}','{{$key1}}')" class="buttonn{{$key}}"></span>
                           <span class="hidden" id="totalProfitAcc{{$key}}_{{$key1}}"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ROOMS - CALCULOS -->
    </div>
@endif
