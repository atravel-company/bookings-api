<div class="modal modal-fixed fade modal-fixed" id="modalValidarPagamentoBooking" tabindex="1050" role="dialog" aria-labelledby="fechar_servico_modal_editarLabel" aria-hidden="true" data-backdrop="true">
    <div class="modal-dialog modal-md" role="document">
        {{ csrf_field() }}
        <div class="modal-content">


            <div class="modal-header">
                <h4 class="modal-title" id="fechar_servico_modal_editarLabel">
                    <div class="w3-container">

                        <div class="w3-row-padding">
                            Gestão de depósitos
                        </div>
                    </div>
                </h4>
            </div>

            <div class="modal-body bodyValidarPagamentoBooking" style="padding: 0px; margin-top: 9px;">

                <div class="w3-container">

                    <div class="w3-row-padding">

                        <div class="w3-col l6 m6">
                            
                            <div class="form-group">
                                {{ Form::label('valor_reserva', 'Valor Reserva') }}
                                <div class="input-group">
                                    <span class="input-group-addon" title="Valor total da reserva"><i class="fa fa-euro"></i></span>
                                    {{ Form::text('valor_reserva', null, array('class' => 'form-control',  'readonly' =>  true )) }}
                                </div>
                            </div>
                        </div>
    
                        <div class="w3-col l6 m6">
                            <div class="form-group">
                                {{ Form::label('total_pago', 'Total Pago') }}
                                <div class="input-group">
                                    <span class="input-group-addon" title="Valor total pago"><i class="fa fa-euro"></i></span>
                                    {{ Form::text('total_pago', null, array('class' => 'form-control' , 'readonly' =>  true )  ) }}
                                </div>
                            </div>
                        </div>
    
                       
                    </div>{{-- end w3-row-padding --}}
    
                    <div class="w3-row-padding">
                        <div class="w3-col l6 m6">
                            <div class="form-group">
                                {{ Form::label('valor_falta', 'Valor em falta') }}
                                <div class="input-group">
                                    <span class="input-group-addon" title="Valor em falta"><i class="fa fa-euro"></i></span>
                                    {{ Form::text('valor_falta', null, array('class' => 'form-control' , 'readonly' =>  true )  ) }}
                                </div>
                            </div>
                        </div>
    
                        <div class="w3-col l6 m6">
                            <div class="form-group">
                                {{ Form::label('deposito', 'Novo Deposito') }}
                                <div class="input-group">
                                    <span class="input-group-addon" title="Valor a ser depositado"><i class="fa fa-euro"></i></span>
                                    {{ Form::text('deposito', null, array('class' => 'form-control' , 'value' => '0.00' )  ) }}
                                </div>
                            </div>
                        </div>
    
                    </div>
    
    
    
                    <div class="showPagamentos" style="display:none">
    
                        <div class="w3-row-padding">
                            <div class="col-md-12">
                                <div class="page-title">
                                    Pagamentos
                                </div>
                            </div>
                          </div>
          
                          <div class="w3-row-padding">
                              <div class="w3-col l12 m12">
          
                                <ul class="timeline" id="data-card-logs">
                                  
                                </ul>
          
                              </div>
                          </div>
    
                    </div>
    
                    
                    <div class="w3-row-padding">
                        <div class="col-xs-12" align="right">
                            <div class="form-group">
                                
                                <span title="Save a new deposit" class="w3-button w3-green guardarDeposito pull-right" style="font-size: 1em; margin-bottom: 10px;" >
                                    <i class="fa fa-euro" style="margin-right: 2px; font-size: 1.1em; "></i>
                                     Send
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

            </div><!-- MODAL BODY -->

        </div>

    </div>

</div>

