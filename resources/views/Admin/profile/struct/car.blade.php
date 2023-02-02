@if (isset($cars[$key][$key1]))
    <button class="accordion"><span><b>Cars</b></span></button>
    <div class="panel" style="overflow-y: auto;">
        <!-- RENT-A-CAR - RESERVAS -->
        <div class="w3-row w3-padding" style="overflow-x: scroll;">
            <p>
                {{ Form::open(['id' => 'form_cars_' . $key . '_' . $key1, 'name' => 'form_cars_' . $key . '_' . $key1, 'method' => 'GET']) }}
                <input type="hidden" name="produto_id_{{ $key1 }}" value="{{ $produto[$key][$key1]->id }}">
                <input type="hidden" name="key_max_{{ $key1 }}" value="{{ count($cars[$key][$key1]) }}">
            <table class="w3-table w3-striped w3-centered car-table">
                <tr style="background-color: #F5AA3B; color: white;">
                    <th colspan="7" class="th-info-lable">Pick-up</th>
                    <th colspan="4" class="th-info-lable">Drop Off</th>
                    <th colspan="6" class="th-info-lable">Car Info</th>
                    <th colspan="3" class="th-info-lable"></th>
                </tr>
                <tr style="background-color: #24AEC9; color: white;">
                    @if (in_array($authUser->email, $users_array))
                        <th class="th-number" style="text-align:center;">
                            <span class="add-car" data-key="{{ $key }}" data-key1="{{ $key1 }}">
                                <i class="fa fa-plus-circle fa-2x" aria-hidden="true"></i>
                            </span>
                        </th>
                    @endif
                    <th class="th-string">Pick-up Location</th>
                    <th class="th-date">Pick-up Date</th>
                    <th class="th-date">Pick-up Hour</th>
                    <th class="th-number">Flight Nº</th>
                    <th class="th-number">Country Origin</th>
                    <th class="th-string">Airport</th>
                    <th class="th-string" style="border-left: 1px solid white;">Drop Off Location</th>
                    <th class="th-date">Drop Off Date</th>
                    <th class="th-date">Drop Off Hour</th>
                    <th class="th-number">Flight Nº</th>
                    {{-- <th class="th-number">Country Origin</th> --}}
                    {{-- <th class="th-string">Airport</th> --}}
                    <th class="th-string" style="border-left: 1px solid white;">Group</ht>
                    <th class="th-string">Model</th>
                    <th class="th-price">Daily Rate</th>
                    <th class="th-number">Nr Days</th>
                    <th class="th-price">Tax</th>
                    {{-- <th class="th-string">Tax Type</th> --}}
                    <th class="th-price">Total</th>
                    {{-- <th class="th-string">Remark</th> --}}

                    @if (in_array($authUser->email, $users_array))
                        <th class="th-ats-rate">ATS Rate</th>
                        <th class="th-ats-rate">ATS Total Rate</th>
                        <th class="th-ats-rate">Total Profit</th>
                    @endif
                </tr>

                @foreach ($cars[$key][$key1] as $key2 => $car)
                    <input type="hidden" name="car_id{{ $key }}_{{ $key1 }}_{{ $key2 }}"
                        value="{{ $car['id'] }}">
                    <input type="hidden" name="pedido_produto_id{{ $key1 }}"
                        value="{{ $car['pedido_produto_id'] }}">
                    <tr>
                        @if (in_array($authUser->email, $users_array))
                            <td style="text-align:center;">
                                <!-- Transfer Remove -->
                                <span class="remove-car"
                                    onclick="return confirm('Are you sure you want to delete?')?removeRow({{ $car['id'] }}, {{ $key }}, $(this), 'car'):'';"><i
                                        class="fa fa-minus-circle fa-2x" aria-hidden="true"></i></span>
                            </td>
                        @endif
                        <td>
                            <!-- Pickup Local -->
                            <input type="text"
                                name="car_pickup_name_{{ $key }}_{{ $key1 }}_{{ $key2 }}"
                                value="{{ $car['pickup'] }}" class="form-control">
                        </td>
                        <td>
                            <!-- Pickup Data -->
                            <div class="form-group" style="width: 140px;">
                                <div class='input-group date datetimepicker{{ $key }}_{{ $key1 }}_{{ $key2 }}'
                                    id='datetimepicker14' style="position: relative;">
                                    <div style="position: absolute;"
                                        id="checkin_car{{ $key }}_{{ $key1 }}_{{ $key2 }}">
                                    </div>
                                    <input value="{{ Carbon\Carbon::parse($car['pickup_data'])->format('d/m/Y') }}"
                                        id="init_car{{ $key }}_{{ $key1 }}_{{ $key2 }}"
                                        type='text'
                                        name="car_pickup_date_{{ $key }}_{{ $key1 }}_{{ $key2 }}"
                                        class="th-price form-control ats-border-color checkin_date_car"
                                        placeholder="Data">
                                    <span class="input-group-addon ats-border-color">
                                        <span class="w3-large ats-text-color fa fa-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <!-- Pickup Hora -->
                            <div class="form-group" style="width: 140px;">
                                <div class="input-group date timepicker" style="position: relative;">
                                    <div style="position: absolute;"></div>
                                    <input value="{{ $car['pickup_hora'] }}" type="text"
                                        class="th-price form-control ats-border-color"
                                        name="car_pickup_hora_{{ $key }}_{{ $key1 }}_{{ $key2 }}"
                                        placeholder="Hora">
                                    <span class="input-group-addon ats-border-color">
                                        <span class="w3-large ats-text-color fa fa-calendar">
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <input type="text"
                                name="car_pickup_flight_{{ $key }}_{{ $key1 }}_{{ $key2 }}"
                                value="{{ $car['pickup_flight'] }}" class="form-control">
                        </td>
                        <td>
                            <input type="text"
                                name="car_pickup_country_{{ $key }}_{{ $key1 }}_{{ $key2 }}"
                                value="{{ $car['pickup_country'] }}" class="form-control">
                        </td>
                        <td>
                            <input type="text"
                                name="car_pickup_airport_{{ $key }}_{{ $key1 }}_{{ $key2 }}"
                                value="{{ $car['pickup_airport'] }}" class="form-control">
                        </td>
                        <td>
                            <input type="text"
                                name="car_dropoff_name_{{ $key }}_{{ $key1 }}_{{ $key2 }}"
                                value="{{ $car['dropoff'] }}" class="form-control">
                        </td>
                        {{-- <td>
                                    {{$car['dropoff_data']}}
                                </td>
                                <td>{{$car['dropoff_hora']}}</td> --}}

                        <td>
                            <!-- Dropoff Data -->
                            <div class="form-group" style="width: 140px;">
                                <div class='input-group date datetimepickers{{ $key }}_{{ $key1 }}_{{ $key2 }}'
                                    id='datetimepicker15' style="position: relative;">
                                    <div style="position: absolute;"
                                        id="checkout_car{{ $key }}_{{ $key1 }}_{{ $key2 }}">
                                    </div>
                                    <input value="{{ Carbon\Carbon::parse($car['dropoff_data'])->format('d/m/Y') }}"
                                        id="find_car{{ $key }}_{{ $key1 }}_{{ $key2 }}"
                                        type='text' readonly
                                        name="car_dropoff_date_{{ $key }}_{{ $key1 }}_{{ $key2 }}"
                                        class="th-price form-control ats-border-color" placeholder="Data">
                                    <span class="input-group-addon ats-border-color">
                                        <span class="w3-large ats-text-color fa fa-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <!-- Dropoff Hora -->
                            <div class="form-group" style="width: 140px;">
                                <div class="input-group date timepicker" style="position: relative;">
                                    <div style="position: absolute;"></div>
                                    <input value="{{ $car['dropoff_hora'] }}" type="text" readonly
                                        class="th-price form-control ats-border-color"
                                        name="car_dropoff_hora_{{ $key }}_{{ $key1 }}_{{ $key2 }}"
                                        placeholder="Hora">
                                    <span class="input-group-addon ats-border-color">
                                        <span class="w3-large ats-text-color fa fa-calendar">
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <input type="text"
                                name="car_dropoff_flight_{{ $key }}_{{ $key1 }}_{{ $key2 }}"
                                value="{{ $car['dropoff_flight'] }}" class="form-control">
                        </td>
                        {{-- <td>
                                    <input type="text" name="car_dropoff_country_{{$key}}_{{$key1}}_{{$key2}}" value="{{$car['dropoff_country']}}" class="form-control">
                                </td>
                                <td>
                                    <input type="text" name="car_dropoff_airport_{{$key}}_{{$key1}}_{{$key2}}" value="{{$car['dropoff_airport']}}" class="form-control">
                                </td> --}}
                        <td>
                            <input type="text"
                                name="car_group_{{ $key }}_{{ $key1 }}_{{ $key2 }}"
                                value="{{ $car['group'] }}" class="form-control">
                        </td>
                        <td>
                            <input type="text"
                                name="car_model_{{ $key }}_{{ $key1 }}_{{ $key2 }}"
                                value="{{ $car['model'] }}" class="form-control">
                        </td>

                        @if (in_array($authUser->email, $users_array))
                            <td>
                                <input value="{{ $car['rate'] }}"
                                    id="realCar{{ $key }}_{{ $key1 }}_{{ $key2 }}"
                                    onchange="somaCar({{ $key }},{{ $key1 }},{{ $key2 }})"
                                    type="number"
                                    name="realCar{{ $key }}_{{ $key1 }}_{{ $key2 }}"
                                    class="loaddd form-control">
                            </td>
                            <td id="dias{{ $key }}_{{ $key1 }}_{{ $key2 }}"></td>

                            <script type="text/javascript">
                                checkin = '#checkin_car{{ $key }}_{{ $key1 }}_{{ $key2 }}';
                                checkout = '#checkout_car{{ $key }}_{{ $key1 }}_{{ $key2 }}';
                                $('.datetimepicker{{ $key }}_{{ $key1 }}_{{ $key2 }}').datetimepicker({
                                    widgetParent: checkin,
                                    format: 'DD/MM/YYYY',
                                    ignoreReadonly: true,
                                    widgetPositioning: {
                                        vertical: 'bottom'
                                    },
                                }).on("dp.change", function(e) {
                                    $('.datetimepickers{{ $key }}_{{ $key1 }}_{{ $key2 }}').data(
                                        "DateTimePicker").minDate(e.date);

                                    mudadia($('#init_car{{ $key }}_{{ $key1 }}_{{ $key2 }}').val(), $(
                                            '#find_car{{ $key }}_{{ $key1 }}_{{ $key2 }}').val(),
                                        '{{ $key }}_{{ $key1 }}_{{ $key2 }}');

                                    somaCar({{ $key }}, {{ $key1 }}, {{ $key2 }});
                                });

                                $('.datetimepickers{{ $key }}_{{ $key1 }}_{{ $key2 }}').datetimepicker({
                                    widgetParent: checkout,
                                    format: 'DD/MM/YYYY',
                                    ignoreReadonly: true,
                                    widgetPositioning: {
                                        vertical: 'bottom'
                                    },
                                }).on("dp.change", function(e) {

                                    mudadia($('#init_car{{ $key }}_{{ $key1 }}_{{ $key2 }}').val(), $(
                                            '#find_car{{ $key }}_{{ $key1 }}_{{ $key2 }}').val(),
                                        '{{ $key }}_{{ $key1 }}_{{ $key2 }}');

                                    somaCar({{ $key }}, {{ $key1 }}, {{ $key2 }});
                                });

                                function parseDate(str, str2) {
                                    var date1 = new Date(str);
                                    var date2 = new Date(str2);
                                    var timeDiff = date2.getTime() - date1.getTime();
                                    var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                                    $("#dias{{ $key }}_{{ $key1 }}_{{ $key2 }}").html(diffDays);
                                }

                                function mudadia(str, str2, chave) {
                                    strn = str.split("/");
                                    nd = strn[2] + '-' + strn[1] + '-' + strn[0];
                                    str2n = str2.split("/");
                                    nd2 = str2n[2] + '-' + str2n[1] + '-' + str2n[0];

                                    var date1 = new Date(nd);
                                    var date2 = new Date(nd2);
                                    var timeDiff = date2.getTime() - date1.getTime();
                                    var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

                                    $("#dias" + chave).html(diffDays);
                                }

                                parseDate("{{ $car['pickup_data'] }}", "{{ $car['dropoff_data'] }}");
                            </script>
                        @else
                            <td>{{ $car['rate'] }}</td>
                            <td>{{ $car['days'] }}</td>
                        @endif

                        @if (in_array($authUser->email, $users_array))
                            <td>
                                <input value="{{ $car['tax'] }}"
                                    id="tax{{ $key }}_{{ $key1 }}_{{ $key2 }}"
                                    onchange="somaCar({{ $key }},{{ $key1 }},{{ $key2 }})"
                                    type="number"
                                    name="tax{{ $key }}_{{ $key1 }}_{{ $key2 }}"
                                    class="loaddd form-control">
                            </td>
                            {{-- <td>
                                        <input value="{{$car['tax_type']}}" id="taxType{{$key}}_{{$key1}}_{{$key2}}" type="text" class="loaddd form-control" name="taxType{{$key}}_{{$key1}}_{{$key2}}">
                                    </td> --}}
                            <td id="totalizaCar{{ $key }}_{{ $key1 }}_{{ $key2 }}">0.00
                            </td>
                        @else
                            <td>{{ $car['tax'] }}</td>
                            <td>{{ $car['tax_type'] }}</td>
                            <td>{{ $car['total'] }}</td>
                        @endif
                        {{-- <td>Car Remark</td> --}}

                        @if (in_array($authUser->email, $users_array))
                            <td>
                                <input value="{{ $car['ats_rate'] }}"
                                    id="atsRateCar{{ $key }}_{{ $key1 }}_{{ $key2 }}"
                                    onchange="somaCar({{ $key }},{{ $key1 }},{{ $key2 }})"
                                    type="number"
                                    name="atsRateCar{{ $key }}_{{ $key1 }}_{{ $key2 }}"
                                    class="loaddd form-control">
                            </td>
                            <td id="atsTotalRateCar{{ $key }}_{{ $key1 }}_{{ $key2 }}">
                                0.00</td>
                            <td>
                                <span
                                    id="atsProfitCar{{ $key }}_{{ $key1 }}_{{ $key2 }}">0.00</span>
                                <span
                                    onclick="enviaCarsEsp('{{ $car['id'] }}','{{ $key }}','{{ $key1 }}','{{ $key2 }}')"
                                    class="buttonn{{ $key }}"></span>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </table>
            {{ Form::close() }}
            </p>
            <!-- RENT-A-CAR - RESERVAS -->
            <!-- RENT-A-CAR - EXTRAS -->
            <p>
                {{ Form::open(['id' => 'form_extras' . $key . '_' . $key1, 'name' => 'form_extras' . $key . '_' . $key1, 'method' => 'GET']) }}
                <input type="hidden" class="produto_id" name="produto_id_{{ $key1 }}"
                    value="{{ $produto[$key][$key1]->id }}">
            <table class="w3-table w3-striped w3-centered car-extras-table">
                <tr style="background-color: #24AEC9; color: white;">
                    @if (in_array($authUser->email, $users_array))
                        <th class="th-number"><span class="add-car-extras" data-key1="{{ $key1 }}"
                                data-key="{{ $key }}"><i class="fa fa-plus-circle fa-2x"
                                    aria-hidden="true"></i></span></th>
                    @endif
                    <th class="th-select">Extra</th>
                    <th class="th-number">Amount</th>
                    <th class="th-number">Rate</th>
                    <th style="min-width: 1887px;"></th>
                    <th class="th-price">Total</th>

                    @if (in_array($authUser->email, $users_array))
                        <th class="th-ats-rate">ATS Rate</th>
                        <th class="th-ats-rate">ATS Total Rate</th>
                        <th class="th-ats-rate">Total Profit</th>
                    @endif
                </tr>
                @php $id_extra = 0; @endphp
                <input type="hidden" class="pedido_produto_id" name="pedido_produto_id{{ $key1 }}"
                    value="{{ $car['pedido_produto_id'] }}">
                @foreach ($extras as $key2 => $extra)
                    @if ($produto[$key][$key1]->pivot->id == $extra->pedido_produto_id and $extra->tipo == 'cars')
                        <input type="hidden"
                            name="tipo{{ $key }}_{{ $key1 }}_{{ $id_extra }}"
                            value="car" class="form-control">
                        <tr class="extra-tr">
                            <input type="hidden"
                                name="extra_id{{ $key }}_{{ $key1 }}_{{ $id_extra }}"
                                value="{{ $extra->id }}">
                            @if (in_array($authUser->email, $users_array))
                                <td>
                                    <span class="remove-extra"
                                        onclick="return confirm('Are you sure you want to delete?')?removeRow({{ $extra->id }}, {{ $key }}, $(this), 'extra'):'';"><i
                                            class="fa fa-minus-circle fa-2x" aria-hidden="true"></i></span>
                                </td>
                            @endif
                            @if (in_array($authUser->email, $users_array))
                                <td>
                                    <select
                                        id="extra_name{{ $key }}_{{ $key1 }}_{{ $id_extra }}"
                                        name="extra_name{{ $key }}_{{ $key1 }}_{{ $id_extra }}"
                                        class="form-control w3-block loaddd">
                                        <option value="">Select</option>
                                        @foreach ($tipos_extras as $tipo_extra)
                                            @if ($produto[$key][$key1]->pivot->produto_id == $tipo_extra->produto_id)
                                                @if ($tipo_extra->extra_id == $extra->extra_id)
                                                    <option value="{{ $tipo_extra->extra_id }}" selected="selected">
                                                        {{ $tipo_extra->name }}</option>
                                                @else
                                                    <option value="{{ $tipo_extra->extra_id }}">
                                                        {{ $tipo_extra->name }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                            @else
                                <td>{{ $extra->name }}</td>
                            @endif
                            <td>
                                <input value="{{ $extra->amount }}"
                                    id="car_extra_amount{{ $key }}_{{ $key1 }}_{{ $id_extra }}"
                                    onchange="somaExtra('',{{ $key }},{{ $key1 }},{{ $id_extra }},'Car')"
                                    class="form-control w3-block loaddd" type="number"
                                    name="car_extra_amount{{ $key }}_{{ $key1 }}_{{ $id_extra }}">
                            </td>

                            @if (in_array($authUser->email, $users_array))
                                <td>
                                    <input value="{{ $extra->rate }}"
                                        id="extraRate{{ $key }}_{{ $key1 }}_{{ $id_extra }}"
                                        onchange="somaExtra('',{{ $key }},{{ $key1 }},{{ $id_extra }},'Car')"
                                        type="number"
                                        name="extraRate{{ $key }}_{{ $key1 }}_{{ $id_extra }}"
                                        class="form-control loaddd">
                                </td>
                                <td></td>
                                <td id="extraTotal{{ $key }}_{{ $key1 }}_{{ $id_extra }}">
                                    0.00</td>
                            @else
                                <td>{{ $extra->rate }} €</td>
                                <td></td>
                                <td>{{ $extra->total }} €</td>
                            @endif

                            @if (in_array($authUser->email, $users_array))
                                <td>
                                    <input value="{{ $extra->ats_rate }}"
                                        id="atsExtraRate{{ $key }}_{{ $key1 }}_{{ $id_extra }}"
                                        onchange="somaExtra('',{{ $key }},{{ $key1 }},{{ $id_extra }},'Car')"
                                        type="number" class="loaddd form-control"
                                        name="atsExtraRate{{ $key }}_{{ $key1 }}_{{ $key2 }}">
                                </td>
                                <td
                                    id="atsTotalExtraRate{{ $key }}_{{ $key1 }}_{{ $id_extra }}">
                                    0.00</td>
                                <td>
                                    <span
                                        id="atsExtraProfit{{ $key }}_{{ $key1 }}_{{ $id_extra }}">0.00</span>
                                    <span
                                        onclick="enviaExtra('{{ $extra->id }}','{{ $key }}','{{ $key1 }}','{{ $id_extra }}', 4)"
                                        data-changed="false"
                                        class="buttonn{{ $key }} hidden form-control"></span>
                                </td>
                            @endif
                        </tr>
                        @php $id_extra++; @endphp
                    @endif
                @endforeach
                <input type="hidden" class="key_max" name="key_max_{{ $key1 }}"
                    value="{{ $id_extra }}">
                <!-- Estático new -->
                @if (in_array($authUser->email, $users_array))
                    <select data-extraID="extra-select" class="form-control w3-block loaddd hidden">
                        <option value="">Select</option>
                        @foreach ($tipos_extras as $tipo_extra)
                            @if ($produto[$key][$key1]->pivot->produto_id == $tipo_extra->produto_id)
                                <option value="{{ $tipo_extra->extra_id }}">{{ $tipo_extra->name }}</option>
                            @endif
                        @endforeach
                    </select>
                @endif
            </table>
            {{ Form::close() }}
            </p>
        </div>
        <!-- RENT-A-CAR - EXTRAS -->
        <!-- RENT-A-CAR - CALCULOS -->
        <div class="w3-row w3-padding">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <section>
                    <label><label style="color:green;">New</label> Remark:</label>
                    <div class="input-group">
                        <input type="text" name="remark_geral" class="form-control w3-block remark_geral"
                            placeholder="Insert remark...">
                        <input type="hidden" name="remark_geral_id" class="remark_geral_id"
                            value="{{ $car['id'] }}">
                        <input type="hidden" name="type_remark" class="type_remark" value="car">
                        @if (in_array($authUser->email, $users_array))
                            <input type="hidden" class="remark_operador" value="ats">
                        @else
                            <input type="hidden" class="remark_operador" value="agency">
                        @endif
                        <span class="input-group-btn">
                            <button class="sendremark_button btn btn-default" type="button">Send</button>
                        </span>
                    </div><!-- /input-group -->
                    <label style="margin-top:10px">Remarks:</label>
                    @if (in_array($authUser->email, $users_array))
                        <button style="float: right; margin-top: 4px;" data-pedido_id='{{ $car['id'] }}'
                            data-type="car" data-toggle="modal" data-target="#modal-edit-remark" aria-hidden="true"
                            class="editremark_button btn btn-default" type="button">Edit Remark</button>
                    @endif
                    <div class="remark-box" id="remark-box{{ $car['id'] }}">
                        @php $remark = ""; @endphp
                        @foreach ($cars[$key][$key1] as $Pedido)
                            @if ($Pedido->remark !== null)
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
                            @if (in_array($authUser->email, $users_array))
                                + <span id="totalCar{{ $key }}_{{ $key1 }}">0.00</span> €
                            @else
                                + {{ $valorCar[$key][$key1]->valor_car }} €
                            @endif
                        </div>
                        <div class="col-xs-7">
                            <b>Total Cars</b>
                        </div>
                        <div class="col-xs-5" style="text-align: right">
                            @if (in_array($authUser->email, $users_array))
                                + <span id="totalCarExtra{{ $key }}_{{ $key1 }}">0.00</span> €
                            @else
                                + {{ $valorCar[$key][$key1]->valor_extra }} €
                            @endif
                        </div>
                        <div class="col-xs-7">
                            <b>Total Extras</b>
                        </div>

                        @php
                            if (isset($valorGolf[$key][$key1]->kick) || in_array($authUser->email, $users_array)) {
                                $hidden = 'block';
                            } else {
                                $hidden = 'none';
                            }
                        @endphp

                        <div class="col-xs-5" style="text-align: right; display:{{ $hidden }};">
                            @if (in_array($authUser->email, $users_array))
                                - <span id="kickbackCar{{ $key }}_{{ $key1 }}">0.00</span> €
                            @else
                                - {{ $valorCar[$key][$key1]->kick }} €
                            @endif
                        </div>
                        <div class="col-xs-7" style="display:{{ $hidden }};">
                            <b>Kick-back</b>
                            @if (in_array($authUser->email, $users_array))
                                <input value="{{ $valorCar[$key][$key1]->kick }}" class="loaddd"
                                    id="kickbackCarInput{{ $key }}_{{ $key1 }}"
                                    onchange="kickbackCar({{ $key }},{{ $key1 }})" type="number"
                                    min="0" max="100" name="">
                                <b>%</b>
                            @endif
                        </div>
                        @if (in_array($authUser->email, $users_array))
                            <div class="col-xs-5" style="text-align: right">
                                + <span id="markupCar{{ $key }}_{{ $key1 }}">0.00</span> €
                                {{-- @else
                                    + {{$valorCar[$key][$key1]->markup}} €
                            @endif --}}
                            </div>
                            <div class="col-xs-7">
                                <b style="padding-right: 16px;">Markup</b>
                                <input value="{{ $valorCar[$key][$key1]->markup }}" class="loaddd"
                                    id="markupCarInput{{ $key }}_{{ $key1 }}"
                                    onchange="markupCar({{ $key }},{{ $key1 }})" type="number"
                                    min="0" max="100" name="">
                                <b>%</b>
                            </div>
                        @endif

                        <div class="col-xs-5" style="border-top: 1px solid black;text-align: right;"">
                            @if (in_array($authUser->email, $users_array))
                                <span id="finalCar{{ $key }}_{{ $key1 }}">0.00</span> €
                            @else
                                {{ $valorCar[$key][$key1]->total }} €
                            @endif
                            <span
                                onclick="enviaCars('{{ $produto[$key][$key1]->pivot->id }}','{{ $key }}','{{ $key1 }}')"
                                class="buttonn{{ $key }}"></span>
                            <span class="hidden"
                                id="totalProfitCar{{ $key }}_{{ $key1 }}"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- RENT-A-CAR - CALCULOS -->

        @if ($authUser->id == 2)
            <div class="w3-row w3-padding">
                @include('Admin.profile.struct.remark_internal', [
                    'users_array' => $users_array,
                    'user' => $authUser,
                    'model' => $car,
                    'modelAll' => $cars,
                    'modelType' => 'car',
                ])
            </div>
        @endif
    </div>
@endif
