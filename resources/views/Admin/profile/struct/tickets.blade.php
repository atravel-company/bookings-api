@if(isset($tickets[$key][$key1]))
    <button class="accordion"><span><b>Tickets </b></span></button>
    <div class="panel" style="overflow-y: auto;">
        <!-- TICKETS - RESERVA -->
        <p>
            <div class="w3-row w3-padding" style="overflow-y: scroll;">
                {{ Form::open(array('id'=>'form_tickets_'.$key.'_'.$key1, 'name'=>'form_tickets_'.$key.'_'.$key1, 'method'=>'GET')) }} 
                    <input type="hidden" name="produto_id_{{$key1}}" value="{{$produto[$key][$key1]->id}}">
                    <input type="hidden" name="key_max_{{$key1}}" value="{{ count($tickets[$key][$key1]) }}">
                    <table class="w3-table w3-striped w3-centered">
                        <tr style="background-color: #24AEC9; color: white;">
                            <th class="th-date">data</th>
                            <th class="th-date">hora</th>
                            <th class="th-number">adults</th>
                            <th class="th-number">children</th>
                            <th class="th-number">babies</th>
                            <th class="th-number">Total</th>
                            {{-- <th class="th-string">Remark</th> --}}
                            
                            @if(in_array(Auth::user()->email, $users_array))
                                <th class="th-ats-rate">ATS Rate</th>
                                {{-- <th class="th-ats-rate"></th> --}}
                                <th class="th-ats-rate">Total Profit</th>
                            @endif
                        </tr>
                        @foreach($tickets[$key][$key1] as $key2=>$ticket)
                            <input type="hidden" name="ticket_id{{$key}}_{{$key1}}_{{$key2}}" value="{{$ticket['id']}}">
                            <input type="hidden" name="pedido_produto_id{{$key1}}" value="{{$ticket['pedido_produto_id']}}">
                            <tr>
                                {{-- <td>
                                    {{$ticket['data']}}
                                </td>
                                <td>
                                    {{$ticket['hora']}}
                                </td> --}}

                                <td> <!-- Pickup Data -->
                                    <div class="form-group" style="width: 140px;">
                                        <div class='input-group date datetimepicker' style="position: relative;">
                                            <div style="position: absolute;"></div>
                                            <input value="{{  Carbon\Carbon::parse( $ticket['data'])->format('d/m/Y') }}" type='text' readonly name="data{{$key}}_{{$key1}}_{{$key2}}" class="th-price form-control ats-border-color checkin_date_ticket" placeholder="Data">
                                            <span class="input-group-addon ats-border-color">
                                                <span class="w3-large ats-text-color fa fa-calendar"></span>
                                            </span>
                                        </div>
                                    </div>                          
                                </td>
                                <td> <!-- Pickup Hora -->
                                    <div class="form-group" style="width: 140px;">
                                        <div class="input-group date timepicker" style="position: relative;">
                                            <div style="position: absolute;"></div>
                                            <input value="{{$ticket['hora']}}" type="text" readonly class="th-price form-control ats-border-color" name="hora{{$key}}_{{$key1}}_{{$key2}}" placeholder="Hora">
                                            <span class="input-group-addon ats-border-color">
                                                <span class="w3-large ats-text-color fa fa-calendar">
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </td>


                                <td>
                                    <input value="{{$ticket['adult']}}" id="ticket_adult{{$key}}_{{$key1}}_{{$key2}}" class="form-control loaddd" type="number" name="ticket_adult{{$key}}_{{$key1}}_{{$key2}}">
                                </td>
                                <td>
                                    <input value="{{$ticket['children']}}" id="ticket_children{{$key}}_{{$key1}}_{{$key2}}" class="form-control loaddd" type="number" name="ticket_children{{$key}}_{{$key1}}_{{$key2}}">
                                </td>
                                <td>
                                    <input value="{{$ticket['babie']}}" id="ticket_babie{{$key}}_{{$key1}}_{{$key2}}" class="form-control loaddd" type="number" name="ticket_babie{{$key}}_{{$key1}}_{{$key2}}">
                                </td>

                                @if(in_array(Auth::user()->email, $users_array))
                                    <td>
                                        <input value="{{$ticket['total']}}" id="realTicket{{$key}}_{{$key1}}_{{$key2}}" class="form-control loaddd" onchange="somaTicket({{$key}},{{$key1}},{{$key2}})" type="number" name="realTicket{{$key}}_{{$key1}}_{{$key2}}">
                                    </td>
                                @else
                                    <td>{{$ticket['total']}}</td>
                                @endif
                                {{-- <td>{{$ticket['remark']}}</td> --}}

                                @if(in_array(Auth::user()->email, $users_array))
                                    <td>
                                        <input value="{{$ticket['ats_rate']}}" id="atsRateTicket{{$key}}_{{$key1}}_{{$key2}}" class="form-control loaddd" onchange="somaTicket({{$key}},{{$key1}},{{$key2}})" type="number" name="atsRateTicket{{$key}}_{{$key1}}_{{$key2}}">
                                    </td>
                                    {{-- <td></td> --}}
                                    <td>
                                        <span id="atsProfitTicket{{$key}}_{{$key1}}_{{$key2}}">0.00</span> 
                                        <span onclick="enviaTicketsEsp('{{$ticket['id']}}','{{$key}}','{{$key1}}','{{$key2}}')" data-changed="false" class="buttonn{{$key}}"></span>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                {{ Form::close() }}
            </div>
        </p>
            <!-- TICKETS - RESERVA -->

        <!-- TICKETS - CALCULOS -->
        <div class="w3-row w3-padding">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <section>
                    <label><label style="color:green;">New</label> Remark:</label>
                    <div class="input-group">
                      <input type="text" name="remark_geral" class="form-control w3-block remark_geral" placeholder="Insert remark...">
                      <input type="hidden" name="remark_geral_id" class="remark_geral_id" value="{{$ticket['id']}}">
                      <input type="hidden" name="type_remark" class="type_remark" value="ticket">
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
                        <button style="float: right; margin-top: 4px;" data-pedido_id='{{$ticket['id']}}' data-type="ticket" data-toggle="modal" data-target="#modal-edit-remark" aria-hidden="true" class="editremark_button btn btn-default" type="button">Edit Remark</button>
                    @endif
                    <div class="remark-box" id="remark-box{{$ticket['id']}}">
                        @php $remark = ""; @endphp
                        @foreach($tickets[$key][$key1] as $Pedido)
                            @if($Pedido->remark !== null)
                                @php $remark .= $Pedido->remark."<br>"; @endphp
                            @endif
                        @endforeach
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
                                    + <span id="totalTicket{{$key}}_{{$key1}}">0.00</span> €
                                @else
                                    + {{$valorTicket[$key][$key1]->valor_ticket}} €
                               @endif
                        </div>
                        <div class="col-xs-7">
                            <b>Total Ticket</b>
                        </div>
                        <div class="col-xs-5" style="text-align: right">
                            @if(in_array(Auth::user()->email, $users_array))
                                + <span id="totalTicketExtra{{$key}}_{{$key1}}">0.00</span> €
                            @else
                                + {{$valorTicket[$key][$key1]->valor_extra}} €
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
                                - <span id="kickbackTicket{{$key}}_{{$key1}}">0.00</span> €
                            @else
                                - {{$valorTicket[$key][$key1]->kick}} €R
                           @endif
                        </div>
                        <div class="col-xs-7" style="display:{{$hidden}};">
                            <b>Kick-back</b>
                            @if(in_array(Auth::user()->email, $users_array))
                                <input value="{{$valorTicket[$key][$key1]->kick}}" class="loaddd" id="kickbackTicketInput{{$key}}_{{$key1}}" onchange="kickbackTicket({{$key}},{{$key1}})" type="number" min="0" max="100" name="">
                                <b>%</b>
                            @endif
                        </div>
                        @if(in_array(Auth::user()->email, $users_array))
                            <div class="col-xs-5" style="text-align: right">
                                    + <span id="markupTicket{{$key}}_{{$key1}}">0.00</span> €
                            </div>
                            <div class="col-xs-7">
                            <b style="padding-right: 16px;">Markup</b>
                                <input value="markupTicket{{$key}}_{{$key1}}" class="loaddd" id="markupTicketInput{{$key}}_{{$key1}}" onchange="markupTicket({{$key}},{{$key1}})" type="number" min="0" max="100" name="">
                                <b>%</b>
                            </div>
                         @endif

                        <div class="col-xs-5" style="border-top: 1px solid black;text-align: right;"">
                            @if(in_array(Auth::user()->email, $users_array))
                                <span id="finalTicket{{$key}}_{{$key1}}">0.00</span> €
                            @else
                                {{$valorTicket[$key][$key1]->total}} €
                           @endif
                           <span onclick="enviaTickets('{{$produto[$key][$key1]->pivot->id}}','{{$key}}','{{$key1}}')" class="buttonn{{$key}}">
                           <span class="hidden" id="totalProfitTicket{{$key}}_{{$key1}}"></span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- TICKETS - CALCULOS -->
    </div>
@endif