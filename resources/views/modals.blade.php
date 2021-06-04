<style>

.modal-content {
    background: #333;
    color: #000;
    margin-top: 100px;
}

.modal-body {
    background: #fff;
}

</style>

<input type="hidden" id="mensagem_ok" class="btn btn-info btn-lg" data-toggle="modal" data-target="#Modalok">
<div id="Modalok" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="color:#5cb85c;"> <span class='glyphicon glyphicon-ok'></span> Success</h4>
      </div>
      <div class="modal-body" style="padding-left: 20px; padding-right: 20px;">
        <p class="debug-url"></p>
      </div>
      <div class="modal-footer">

      <p style='text-align:center; margin:0;'><img class="img-logo" src="{{ asset('FrontEnd/images/logoats.png') }}" ></p>
      </div>
    </div>
  </div>
</div>

<input type="hidden" id="mensagem_ko" class="btn btn-info btn-lg" data-toggle="modal" data-target="#Modalko">
<div id="Modalko" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="color:#d9534f;"><span class='glyphicon glyphicon-warning-sign'></span> Aviso</h4>
      </div>
      <div class="modal-body" style="padding-left: 20px; padding-right: 20px;">
        <p class="debug-url"></p>
      </div>
      <div class="modal-footer">

       <p style='text-align:center; margin:0;'><img class="img-logo" src="{{ asset('FrontEnd/images/logoats.png') }}" ></p>

      </div>
    </div>
  </div>
</div>
