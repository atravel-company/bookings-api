<div class="modal modal-fixed fade modal-fixed" id="modalEnviaCamposApi" tabindex="1050" role="dialog" aria-labelledby="modalEnviaCamposApiLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        {{ csrf_field() }}
        <div class="modal-content">


            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                
                <h4 class="modal-title" id="modalEnviaCamposApiLabel">
                    <div class="w3-container">

                        <div class="w3-row-padding">
                            Choose which fields will be updated in the transfergest
                        </div>
                    </div>
                </h4>
            </div>

            <div class="modal-body" style="padding: 0px; margin-top: 9px;">

                <div class="w3-container">

                    <div class="w3-row-padding">
                        <div class="w3-col l6 m6">
                            <div class="form-group checkbox">
                                <label title="Att date and time in api transfergest">
                                    <input name="data_hora" type="checkbox" value="">
                                    <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                    Date and Time
                                </label>
                            </div>
                        </div>
                    </div>{{-- end w3-row-padding --}}

                    <div class="w3-row-padding">
                        <div class="w3-col l6 m6">
                            <div class="form-group checkbox">
                                <label title="Att  Number of Pax in api transfergest">
                                    <input name="lotacao" type="checkbox" value="">
                                    <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                    Number of Pax
                                </label>
                            </div>
                        </div>
                    </div>{{-- end w3-row-padding --}}

                    <div class="w3-row-padding">
                        <div class="w3-col l6 m6">
                            <div class="form-group checkbox">
                                <label title="Att address in api transfergest">
                                    <input name="moradas" type="checkbox" value="">
                                    <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                    Send address
                                </label>
                            </div>
                        </div>
                    </div>{{-- end w3-row-padding --}}

                    <div class="w3-row-padding">
                        <div class="w3-col l6 m6">
                            <div class="form-group checkbox">
                                <label title="Att address in api transfergest">
                                    <input name="valores" type="checkbox" value="">
                                    <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                    Send transfer values
                                </label>
                            </div>
                        </div>
                    </div>{{-- end w3-row-padding --}}


                    <div class="w3-row-padding">
                        <div class="w3-col l6 m6">
                            <div class="form-group checkbox">
                                <label title="Att address in api transfergest">
                                    <input name="sendemail" type="checkbox" checked >
                                    <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                    Notify by Email
                                </label>
                            </div>
                        </div>
                    </div>{{-- end w3-row-padding --}}


                    <div class="w3-row-padding">
                        <div class="col-xs-12" align="right">
                            <div class="form-group">

                                <span title="Send transfers" class="w3-button w3-green enviarDadosApi pull-right" style="font-size: 1em; margin-bottom: 10px; margin-top:10px">
                                    <i class="fa fa-arrow-right" style="margin-right: 2px; transform:  rotate(-45deg); font-size: 1.1em; "></i>
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
