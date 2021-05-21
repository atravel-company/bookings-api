@extends('Admin.layouts.app')

@section('content')
<script type="text/javascript" src="{{ URL::asset('Admin/js/produtos.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('Admin/js/dist/cropper.js') }}"></script>
<link href="{{ asset('css/dist/cropper.css') }}" rel="stylesheet">
<style type="text/css">
  .fileUpload {
    position: relative;
    overflow: hidden;
    margin: 10px;
  }

  .fileUpload input.upload {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    padding: 0;
    font-size: 50px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
  }

  .w3-col .l2 .w3-border {
    width: auto !important;
  }

  @media (min-width: 300px) {
    .w3-col .l2 .w3-border {
      width: auto !important;
    }

  }
</style>
<div class="w3-container">
  <div class="row">
    <div class="col-lg-6">
      <h1 style="margin-top:0px!important;">Products</h1>
    </div>
    <div class="col-lg-6">
      @hasrole('cria')
      <a href="{{ route('produtos.create') }}" class="w3-button  w3-green w3-right">Create</a>
      @else
      Not have permission!
      @endhasallroles
    </div>
  </div>
  {!! Form::open(['url'=>route('produtos')]) !!}
  <div class="w3-center">
    {!! Form::label('search', 'Search:') !!}
    {!! Form::text('nome', null,['id'=>'searchname']) !!}
    <!-- <select name="pagina"  id="pagina">
    <option value="1" selected="selected">1</option>
    <option value="2" selected="selected">2</option>
    </select> -->
    {!! Form::submit('Search', ['class'=>'w3-button w3-blue']) !!}
  </div>
  <div class="w3-center">{{ $produtos->appends(Request::only('nome'))->links() }}</div>
  {!! Form::close() !!}

  @if (Auth::check())

  @foreach($produtos as $produto)
  <table class="table table-striped table-bordered table-hover w3-container">

    <thead>
      <tr>
        <th>Nome</th>
        <th>Descrição</th>
        <th rowspan=1 style="width: 153px;">
          @if($produto->estado == 1)
          <i onclick="changeState({{$produto->id}}, 0)"
            style="position: absolute; margin-top: -2px; cursor:pointer; color:#4CAF50;"
            class="fa fa-2x fa-check-circle" aria-hidden="true"></i>
          @else
          <i onclick="changeState({{$produto->id}}, 1)"
            style="position: absolute; margin-top: -2px; cursor:pointer; color:#bf5329;"
            class="fa fa-2x fa-check-circle" aria-hidden="true"></i>
          @endif
          <span><label style="width:30px;"></label></span>
          @hasrole('edita')
          <a href="{{ route('produtos.edit',['id'=>$produto->id]) }}"
            class="btn btn-info btn-xs">Edit</a>&nbsp&nbsp&nbsp
          @else
          Not have permission!
          @endhasallroles

          @hasrole('apaga')
          <a href="{{ route('produtos.destroy',['id'=>$produto->id]) }}" class="btn btn-danger btn-xs">Delete</a>
          @else
          Not have permission!
          @endhasallroles
        </th>
      </tr>
    </thead>

    <tbody>
      <tr>
        <td style="width: 380px">{{ $produto->nome }}</td>
        <td colspan=2>
          <div style="overflow-x: scroll; width:auto !important;">{!! $produto->descricao !!}</div>
        </td>
      </tr>

      <tr>
        <td colspan="5" class="w3-accordion-content prod-det-cont w3-card-2  w3-border-indigo w3-container">

          <div
            class="w3-border-top w3-border-right w3-border-left w3-accordion-content prod-det-cont  w3-container w3-padding-16 w3-light-grey w3-border-black">
            <b>Photos & fact sheets</b>
            <i onclick="myFunction({{ $produto->id }},'pictures')" class="fa fa-plus w3-right w3-button"
              style="font-size:18px"></i>
            <div id="pictures{{ $produto->id }}" class="w3-margin-top" style="display: none;">

              <div class="">
                <!-- {!! Form::open(['files' => true, 'class'=>'w3-container', 'id'=>'upload_form']) !!} -->
                <div class="w3-row">
                  <div class="w3-col l1">
                    <div class="fileUpload btn">
                      <span onclick="fotomodal({{$produto->id}})" style="border-style: dashed; font-size: 33px">+
                      </span>
                    </div>
                  </div>
                  <div id="reload{{ $produto->id }}">
                    <div class="reload{{ $produto->id }}">
                      @foreach($produto->imagem as $imagem)


                      <div class="w3-col l2">
                        <div class="w3-cell-row w3-margin-bottom w3-center">
                          <div class="w3-container w3-section w3-cell w3-margin-bottom">
                            <div class="w3-col l12">
                              <div class="w3-col l4"><i onclick="fotomodaledit({{ $produto->id }},{{$imagem->id}})"
                                  style="cursor:pointer" class="fa fa-pencil-square-o" aria-hidden="true"></i></div>
                              <div class="w3-col l4"><i onclick="DeleteFoto({{$imagem->id}},{{ $produto->id }})"
                                  style="cursor:pointer" class="fa fa-trash" aria-hidden="true"></i></div>
                              @if($imagem->title=='principal')
                              <div class="w3-col l4"><i onclick="fotoprincipal({{$imagem->id}},{{ $produto->id }})"
                                  class="fa fa-star"
                                  style="-webkit-text-fill-color: yellow;-webkit-text-stroke-width: 1px;-webkit-text-stroke-color: black; cursor:pointer;"
                                  aria-hidden="true"></i></div>
                              @else
                              <div class="w3-col l4"><i onclick="fotoprincipal({{$imagem->id}},{{ $produto->id }})"
                                  style="cursor:pointer" class="fa fa-star-o" aria-hidden="true"></i></div>
                              @endif
                            </div>
                            <div class="w3-col l12">
                              <img style="width:136px;height:76px;" class="w3-card-4"
                                src="<?php echo asset("storage/$imagem->path_image")?>">
                            </div>

                          </div>
                        </div>
                      </div>
                      <!---MODAL EDIT-->


                      <div id="fotomodaledit{{$produto->id}}_{{$imagem->id}}" class="w3-modal w3-animate-opacity">
                        <div class="w3-modal-content w3-card-4 w3-animate-opacity" style="width:995px">

                          <div class="w3-center"><br>
                            <span onclick="closefotomodaledit({{ $produto->id }},{{$imagem->id}})"
                              class="w3-button w3-xlarge w3-hover-red w3-display-topright"
                              title="Close Modal">&times;</span>
                          </div>


                          <div class="w3-section">
                            <div class="w3-container">
                              {!! Form::open(['id'=>'upload_form'.$produto->id.'_'.$imagem->id]) !!}
                              <div class="w3-row" style="">
                                <div style="display: flex;">
                                  <div class="w3-col l4 w3-center w3-border  w3-padding w3-row-padding-32"
                                    style="overflow: auto; padding-top: 47.5px !important;">
                                    <div class="img-container">
                                      <div class="w3-col l12">&nbsp;</div>
                                      <div class="w3-col l12">
                                        <img class="w3-padding w3-margin-top w3-margin-bottom"
                                          id="image{{$produto->id}}_{{$imagem->id}}"
                                          src="<?php echo asset("storage/$imagem->path_image")?>" alt="Picture"></div>
                                    </div>
                                  </div>
                                  <div class="w3-col l8 w3-center w3-border  w3-padding w3-row-padding">


                                    <div id="result{{$produto->id}}_{{$imagem->id}}"
                                      class="w3-margin-top w3-margin-bottom" style="overflow: auto;">
                                      <div class="w3-col l12">
                                        <h3>Preview</h3>
                                      </div>
                                      <div class="alert{{$produto->id}}_{{$imagem->id}} w3-center w3-col l12"
                                        style="width: 300px; height: 225px; overflow: hidden; margin-left: 25%;"></div>
                                    </div>

                                  </div>
                                </div>
                                <div class="w3-col l12">
                                  <!-- <input type="file" name="path_image" id="path_image{{ $produto->id }}" class="upload" onchange="changeImage(this,{{ $produto->id }})" /> -->
                                  <input type="file" name="path_image" id="path_image{{ $produto->id }}_{{$imagem->id}}"
                                    onchange="readURLedit(this,'#image{{$produto->id}}_{{$imagem->id}}',{{$produto->id}},{{$imagem->id}})" />
                                </div>
                                <!---->
                                <div class=" w3-col l12 w3-border">
                                  <span style="display: block;" onclick="redimedit({{$produto->id}},{{$imagem->id}})"
                                    id="redimbtn{{$produto->id}}_{{$imagem->id}}"
                                    class="w3-button w3-orange w3-block">Redim</span>
                                </div>
                                <div class="w3-col l12 w3-border">
                                  <span style="display: none;" id="button{{$produto->id}}_{{$imagem->id}}"
                                    class="w3-button w3-blue w3-block">Save</span>
                                </div>

                              </div>
                              {!! Form::close() !!}
                            </div>
                            <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">

                            </div>

                          </div>
                        </div>
                      </div>

                      <!---MODAL EDIT-->

                      @endforeach
                    </div>
                  </div>
                  <div id="imagens{{ $produto->id }}">

                  </div>
                </div>
                <!-- {!! Form::close() !!} -->
              </div>

              <!--<div class="w3-animate-opacity w3-margin-top" id="com{{ $produto->id }}"></div>-->

              <!--@hasrole('comenta')
                <form  data-field-id="{{ $produto->id }}" data-variable="{{Auth::id()}}"  action="#">
                        {{ csrf_field() }}
                        <label for="comment"></label>
                        <input type="text" id="co{{ $produto->id }}" name="com" class="form-control">
                        <input type="hidden" value="{{ $produto->id }}" id="id{{ $produto->id }}" name="id">
                        <input type="submit" value="Send" id="bt" class="btn btn-default" >
                        </form>
                        @else
                    Not have permission!
                  @endhasallroles-->
            </div>
          </div>
          <div
            class="w3-border-top w3-border-right w3-border-left w3-accordion-content prod-det-cont  w3-container w3-padding-16 w3-light-grey w3-border-black">
            <b>Contracts</b>
            <i id="" onclick="myFunction({{ $produto->id }},'brochures')" class="fa fa-plus w3-right w3-button"
              style="font-size:18px"></i>

            <!--div aqui-->
            <div id="brochures{{ $produto->id }}" class="w3-margin-top" style="display: none;">
              <div class="">
                {!! Form::open(['files' => true, 'class'=>'w3-container', 'id'=>'upload_form']) !!}
                <div class="w3-row">
                  <div class="w3-col l1">

                    <div class="btn">
                      <span name="" id="" onclick="createpdf({{ $produto->id }},'Brochures')" type="file"
                        style="border-style: dashed; font-size: 33px">+</span>

                    </div>


                  </div>
                  <div id="reloadpdf{{ $produto->id }}">
                    <div class="reloadpdf{{ $produto->id }}">


                      @foreach($produto->pdf as $pdf)

                      @if($pdf->type=='Brochures')
                      <div class="w3-col l2 w3-border" style="width: auto !important; margin-top: 2%">
                        <div class="w3-col l12">
                          <div class="w3-col l4">&nbsp;</div>
                          <div class="w3-col l2 w3-center"><i
                              onclick="PdfModalEdit({{$produto->id}},{{$pdf->id}},'Brochures')" style="cursor:pointer"
                              class="fa fa-pencil-square-o" aria-hidden="true"></i></div>
                          <div class="w3-col l2 w3-center"><i
                              onclick="DeletePdf({{ $produto->id }},{{$pdf->id}},'Brochures')" style="cursor:pointer"
                              class="fa fa-trash" aria-hidden="true"></i></div>
                          <div class="w3-col l4">&nbsp;</div>
                        </div>
                        <div class="w3-cell-row  w3-center">
                          <div class="w3-container w3-section w3-cell w3-margin-bottom">
                            <a href="#"
                              onClick="window.open('{{ asset('storage/pdfs/Brochures/'.$pdf->path_image)}}','pagename','resizable,height=260,width=370'); return false;"
                              style="color: red;" class="fa-3x fa fa-file-pdf-o" aria-hidden="true"></a>

                          </div>
                        </div>
                        <div class="w3-cell-row  w3-center">
                          <a href="#"
                            onClick="window.open('{{ asset('storage/pdfs/Brochures/'.$pdf->path_image)}}','pagename','resizable,height=260,width=370'); return false;"
                            class="btn">{{$pdf->title}}</a>
                        </div>

                      </div>


                      <!--................................................MODAL.................................................-->


                      <div id="pdfeditmodalBrochures{{$produto->id}}_{{$pdf->id}}" class="w3-modal w3-animate-opacity">
                        <div class="w3-modal-content w3-card-4 w3-animate-opacity" style="max-width:600px">

                          <div class="w3-center"><br>
                            <span
                              onclick="document.getElementById('pdfeditmodalBrochures{{$produto->id}}_{{$pdf->id}}').style.display='none'; modal=0;"
                              class="w3-button w3-xlarge w3-hover-red w3-display-topright"
                              title="Close Modal">&times;</span>

                          </div>

                          <h5 class="w3-center"><b>Edit Brochure: {{$pdf->title}}</b></h5>

                          <div class="w3-container w3-section w3-cell w3-margin-bottom w3-center" id="bankcreateerror">
                          </div>

                          {!! Form::open([ 'class'=>'w3-container']) !!}
                          <div class="w3-section w3-padding"> {!! Form::label('title', 'Title:') !!}
                            {!! Form::text('Titlte', $pdf->title, ['id'=>'titleBrochures'.$produto->id.'_'.$pdf->id,
                            'class'=>'w3-input w3-border w3-margin-bottom']) !!}


                            <!-- <select name="categoria_id" class="w3-select w3-border w3-margin-bottom" style="padding: 3px 5px;"> --><br>
                            <div class="form-group">
                              {!! Form::label('groups', 'View by groups:') !!}
                              @php ($names = [])
                              @foreach($pdf->regras as $key=> $pdfRegra)
                              @php ($names[]=$pdfRegra->name)

                              @endforeach


                              @foreach($roles as $key => $role)

                              <!-- <option value="{{$role->id}}">{{$role->name}}</option> -->
                              @if(($key%3)==0)
                              <br><br>
                              @endif

                              @if(in_array($role->name, $names))
                              <div class="w3-col l4">
                                {{ Form::checkbox('ch'.$produto->id.'_'.$pdf->id.'[]',$role->id,null,['class'=>'w3-checkbox', 'checked']) }}
                                {{$role->name}}
                              </div>
                              @else
                              <div class="w3-col l4">
                                {{ Form::checkbox('ch'.$produto->id.'_'.$pdf->id.'[]',$role->id,null,['class'=>'w3-checkbox']) }}
                                {{$role->name}}
                              </div>
                              @endif
                              @endforeach
                              <br><br>
                              <!-- </select> -->
                            </div>


                            <span id="button{{$produto->id}}_{{$pdf->id}}"
                              class="w3-button w3-block w3-blue w3-section w3-padding">Save</span>
                          </div>
                          {!! Form::close() !!}

                          <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">

                          </div>

                        </div>
                      </div>


                      <!--................................................MODAL.................................................-->

                      @endif
                      @endforeach


                    </div>
                  </div>


                  <div id="pdfsBrochures{{ $produto->id }}">

                  </div>
                </div>
                {!! Form::close() !!}
              </div>
            </div>


          </div>
          <div
            class="w3-border-top w3-border-right w3-border-left w3-accordion-content prod-det-cont  w3-container w3-padding-16 w3-light-grey w3-border-black">
            <b>Special Offers</b>
            <i id="" onclick="myFunction({{ $produto->id }},'campaigns')" class="fa fa-plus w3-right w3-button"
              style="font-size:18px"></i>

            <!--div aqui-->
            <div id="campaigns{{ $produto->id }}" class="w3-margin-top" style="display: none;">
              <div class="">
                {!! Form::open(['files' => true, 'class'=>'w3-container', 'id'=>'upload_form']) !!}
                <div class="w3-row">
                  <div class="w3-col l1">

                    <div class="btn">
                      <span name="" id="" onclick="createpdf({{ $produto->id }},'Campaigns')" type="file"
                        style="border-style: dashed; font-size: 33px">+</span>

                    </div>

                  </div>


                  <div id="reloadpdfcamp{{ $produto->id }}">
                    <div class="reloadpdfcamp{{ $produto->id }}">

                      @foreach($produto->pdf as $pdf)

                      @if($pdf->type=='Campaigns')
                      <div class="w3-col l2 w3-border" style="width: auto !important; margin-top: 2%">
                        <div class="w3-col l12">
                          <div class="w3-col l4">&nbsp;</div>
                          <div class="w3-col l2 w3-center"><i
                              onclick="PdfModalEdit({{$produto->id}},{{$pdf->id}},'Campaigns')" style="cursor:pointer"
                              class="fa fa-pencil-square-o" aria-hidden="true"></i></div>
                          <div class="w3-col l2 w3-center"><i
                              onclick="DeletePdf({{ $produto->id }},{{$pdf->id}},'Campaigns')" style="cursor:pointer"
                              class="fa fa-trash" aria-hidden="true"></i></div>
                          <div class="w3-col l4">&nbsp;</div>
                        </div>
                        <div class="w3-cell-row  w3-center">
                          <div class="w3-container w3-section w3-cell w3-margin-bottom">
                            <a href="#"
                              onClick="window.open('{{ asset('storage/pdfs/Campaigns/'.$pdf->path_image)}}','pagename','resizable,height=260,width=370'); return false;"
                              style="color: red;" class="fa-3x fa fa-file-pdf-o" aria-hidden="true"></a>

                          </div>
                        </div>
                        <div class="w3-cell-row  w3-center">
                          <a href="#"
                            onClick="window.open('{{ asset('storage/pdfs/Campaigns/'.$pdf->path_image)}}','pagename','resizable,height=260,width=370'); return false;"
                            class="btn">{{$pdf->title}}</a>
                        </div>
                      </div>
                      <!--................................................MODAL.................................................-->


                      <div id="pdfeditmodalCampaigns{{$produto->id}}_{{$pdf->id}}" class="w3-modal w3-animate-opacity">
                        <div class="w3-modal-content w3-card-4 w3-animate-opacity" style="max-width:600px">

                          <div class="w3-center"><br>
                            <span
                              onclick="document.getElementById('pdfeditmodalCampaigns{{$produto->id}}_{{$pdf->id}}').style.display='none'; modal=0;"
                              class="w3-button w3-xlarge w3-hover-red w3-display-topright"
                              title="Close Modal">&times;</span>

                          </div>

                          <h5 class="w3-center"><b>Edit Campaigns: {{$pdf->title}}</b></h5>

                          <div class="w3-container w3-section w3-cell w3-margin-bottom w3-center" id="bankcreateerror">
                          </div>

                          {!! Form::open([ 'class'=>'w3-container']) !!}
                          <div class="w3-section w3-padding"> {!! Form::label('title', 'Title:') !!}
                            {!! Form::text('Titlte', $pdf->title, ['id'=>'titleCampaigns'.$produto->id.'_'.$pdf->id,
                            'class'=>'w3-input w3-border w3-margin-bottom']) !!}


                            <!-- <select name="categoria_id" class="w3-select w3-border w3-margin-bottom" style="padding: 3px 5px;"> --><br>
                            <div class="form-group">
                              {!! Form::label('groups', 'View by groups:') !!}
                              @php ($names = [])
                              @foreach($pdf->regras as $key=> $pdfRegra)
                              @php ($names[]=$pdfRegra->name)

                              @endforeach


                              @foreach($roles as $key => $role)

                              <!-- <option value="{{$role->id}}">{{$role->name}}</option> -->
                              @if(($key%3)==0)
                              <br><br>
                              @endif

                              @if(in_array($role->name, $names))
                              <div class="w3-col l4">
                                {{ Form::checkbox('ch'.$produto->id.'_'.$pdf->id.'[]',$role->id,null,['class'=>'w3-checkbox', 'checked']) }}
                                {{$role->name}}
                              </div>
                              @else
                              <div class="w3-col l4">
                                {{ Form::checkbox('ch'.$produto->id.'_'.$pdf->id.'[]',$role->id,null,['class'=>'w3-checkbox']) }}
                                {{$role->name}}
                              </div>
                              @endif
                              @endforeach
                              <br><br>
                              <!-- </select> -->
                            </div>


                            <span id="button{{$produto->id}}_{{$pdf->id}}"
                              class="w3-button w3-block w3-blue w3-section w3-padding">Save</span>
                          </div>
                          {!! Form::close() !!}

                          <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">

                          </div>

                        </div>
                      </div>


                      <!--................................................MODAL.................................................-->

                      @endif
                      @endforeach


                    </div>
                  </div>


                  <div id="pdfsCampaigns{{ $produto->id }}">

                  </div>
                </div>
                {!! Form::close() !!}
              </div>
            </div>


          </div>
          <div
            class="w3-border w3-accordion-content prod-det-cont  w3-container w3-padding-16 w3-light-grey w3-border-black">
            <b>Extras</b>
            <i id="" onclick="myFunction({{ $produto->id }},'extras')" class="fa fa-plus w3-right w3-button"
              style="font-size:18px"></i>

            <!--div aqui-->

            <div id="extras{{ $produto->id }}" class="w3-margin-top" style="display: none;">
              <div class="">

                <div class="w3-row">
                  <div class="w3-col l1">

                    <div class="btn">
                      <span name="" id="" onclick="createextra({{ $produto->id }})" type="file"
                        style="border-style: dashed; font-size: 33px">+</span>

                    </div>

                  </div>
                  <div id="reloadExtra{{$produto->id}}">
                    @foreach($produto->extras as $extraProd)
                    <div class="w3-col l2 w3-border" style="width: auto !important; margin-top: 2%">
                      <div class="w3-row">
                        <div class="w3-col l12">
                          <div class="w3-col l4">&nbsp;</div>
                          <div class="w3-col l2 w3-center"><i
                              onclick="ExtraModalEdit({{ $produto->id }},{{$extraProd->id}})" style="cursor:pointer"
                              class="fa fa-pencil-square-o" aria-hidden="true"></i></div>
                          <div class="w3-col l2 w3-center"><i
                              onclick="DeleteExtra({{$produto->id}},{{ $extraProd->id }})" style="cursor:pointer"
                              class="fa fa-trash" aria-hidden="true"></i></div>
                          <div class="w3-col l4">&nbsp;</div>
                        </div>
                        <div class="w3-cell-row  w3-center">
                          {{$extraProd->name}}
                        </div>
                      </div>
                    </div>

                    <!--edit extras-->

                    <div id="extraseditmodal{{$produto->id}}_{{$extraProd->id}}" class="w3-modal w3-animate-opacity">
                      <div class="w3-modal-content w3-card-4 w3-animate-opacity" style="max-width:600px">

                        <div class="w3-center"><br>
                          <span
                            onclick="document.getElementById('extraseditmodal{{$produto->id}}_{{$extraProd->id}}').style.display='none'; modal=0;"
                            class="w3-button w3-xlarge w3-hover-red w3-display-topright"
                            title="Close Modal">&times;</span>

                        </div>

                        <h5 class="w3-center"><b>Edit Extra</b></h5>

                        <div class="w3-container w3-section w3-cell w3-margin-bottom w3-center"
                          id="extracreateerror{{$produto->id}}_{{$extraProd->id}}"></div>


                        {!! Form::open([ 'class'=>'w3-container']) !!}
                        <div class="w3-section">
                          <div class="form-group">
                            {!! Form::label('extra', 'Extra:') !!}
                            <div class="w3-container w3-section w3-cell w3-right w3-btn w3-ripple w3-blue"
                              onclick="create({{$produto->id}},{{$extraProd->id}});">Create New Extra</div>
                            <div id="selec{{$produto->id}}_{{$extraProd->id}}">
                              <select name="extra" class="w3-select w3-border w3-margin-bottom"
                                id="extrainput{{$produto->id}}_{{$extraProd->id}}" style="padding: 3px 5px;">
                                @foreach($extras as $extra)
                                @if($extraProd->name==$extra->name)

                                <option value="{{$extra->id}}" selected="selected">{{$extra->name}}</option>
                                @else
                                <option value="{{$extra->id}}">{{$extra->name}}</option>
                                @endif
                                @endforeach
                              </select>
                            </div>
                          </div>


                          <div class="form-group">
                            {!! Form::label('type', 'Type:') !!}
                            <select name="typeextra" id="typeextra{{$produto->id}}_{{$extraProd->id}}"
                              class="w3-select w3-border w3-margin-bottom" style="padding: 3px 5px;">

                              @if($produto->alojamento==1)
                              @if($extraProd->pivot->formulario=='alojamento')
                              <option value="alojamento" selected="selected">Alojamento</option>
                              @else
                              <option value="alojamento">Alojamento</option>
                              @endif
                              @endif
                              @if($produto->golf==1)
                              @if($extraProd->pivot->formulario=='golf')
                              <option value="golf" selected="selected">Golf</option>
                              @else
                              <option value="golf">Golf</option>
                              @endif
                              @endif
                              @if($produto->transfer==1)
                              @if($extraProd->pivot->formulario=='transfer')
                              <option value="transfer" selected="selected">Transfer</option>
                              @else
                              <option value="transfer">Transfer</option>
                              @endif
                              @endif
                              @if($produto->car==1)
                              @if($extraProd->pivot->formulario=="car")
                              <option value="car" selected="selected">Ren Car</option>
                              @else
                              <option value="car">Ren Car</option>
                              @endif
                              @endif
                              @if($produto->ticket==1)
                              @if($extraProd->pivot->formulario=='ticket')
                              <option value="ticket" selected="selected">Ticket</option>
                              @else
                              <option value="ticket">Ticket</option>
                              @endif
                              @endif
                            </select>
                          </div>

                          <span onclick="SendExtraModalEdit({{ $produto->id }},{{$extraProd->id}})"
                            class="w3-button w3-block w3-blue w3-section w3-padding">Save</span>

                        </div>
                        {!! Form::close() !!}

                        <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">

                        </div>

                      </div>
                    </div>

                    <!--edit extras-->

                    @endforeach
                  </div>

                  <div id="extrasDiv{{ $produto->id }}">

                  </div>
                </div>

              </div>
            </div>


          </div>
        </td>

      </tr>
    </tbody>

  </table>


  <!--................................................MODAL.................................................-->


  <div id="pdfcreatemodalBrochures{{$produto->id}}" class="w3-modal w3-animate-opacity">
    <div class="w3-modal-content w3-card-4 w3-animate-opacity" style="max-width:600px">

      <div class="w3-center"><br>
        <span
          onclick="document.getElementById('pdfcreatemodalBrochures{{ $produto->id }}').style.display='none'; modal=0;"
          class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">&times;</span>

      </div>

      <h5 class="w3-center"><b>Create Brochures PDFs</b></h5>

      <div class="w3-container w3-section w3-cell w3-margin-bottom w3-center" id="createbrochureerror"></div>

      {!! Form::open([ 'class'=>'w3-container']) !!}
      <div class="w3-section">
        {!! Form::label('upload', 'Upload:') !!}
        {{Form::file('path_pdfBrochures',['id'=>'path_pdfBrochures'.$produto->id,'onchange'=>"sendpdf($produto->id ,'Brochures')", 'enctype' => 'multipart/form-data'])}}


        {!! Form::label('title', 'Title:') !!}
        {!! Form::text('Titlte', null, ['id'=>'titleBrochures'.$produto->id, 'class'=>'w3-input w3-border
        w3-margin-bottom']) !!}


        <!-- <select name="categoria_id" class="w3-select w3-border w3-margin-bottom" style="padding: 3px 5px;"> --><br>

        {!! Form::label('groups', 'View by groups:') !!}
        <div class="form-group">

          @foreach($roles as $key => $role)

          <!-- <option value="{{$role->id}}">{{$role->name}}</option> -->
          @if(($key%3)==0)
          <br><br>
          @endif

          <div class="w3-col l4">{{ Form::checkbox('ch'.$produto->id.'[]',$role->id,null,['class'=>'w3-checkbox']) }}
            {{$role->name}}
          </div> @endforeach
          <br><br>
          <!-- </select> -->
        </div>


        <span id="buttonBrochures{{$produto->id}}"
          class="w3-button w3-block w3-green w3-section w3-padding">Create</span>
      </div>
      {!! Form::close() !!}

      <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">

      </div>

    </div>
  </div>


  <!--................................................MODAL.................................................-->

  <!--................................................MODAL.................................................-->


  <div id="pdfcreatemodalCampaigns{{$produto->id}}" class="w3-modal w3-animate-opacity">
    <div class="w3-modal-content w3-card-4 w3-animate-opacity" style="max-width:600px">

      <div class="w3-center"><br>
        <span
          onclick="document.getElementById('pdfcreatemodalCampaigns{{ $produto->id }}').style.display='none'; modal=0;"
          class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">&times;</span>

      </div>

      <h5 class="w3-center"><b>Create Campaigns PDFs</b></h5>

      <div class="w3-container w3-section w3-cell w3-margin-bottom w3-center" id="createcampaignerror"></div>

      {!! Form::open([ 'class'=>'w3-container']) !!}
      <div class="w3-section">
        {!! Form::label('upload', 'Upload:') !!}
        {{Form::file('path_pdfCampaigns',['id'=>'path_pdfCampaigns'.$produto->id,'onchange'=>"sendpdf($produto->id ,'Campaigns')"])}}


        {!! Form::label('title', 'Title:') !!}
        {!! Form::text('Titlte', null, ['id'=>'titleCampaigns'.$produto->id, 'class'=>'w3-input w3-border
        w3-margin-bottom']) !!}
        <br>
        {!! Form::label('groups', 'View by groups:') !!}
        <div class="form-group">

          @foreach($roles as $key => $role)

          <!-- <option value="{{$role->id}}">{{$role->name}}</option> -->
          @if(($key%3)==0)
          <br><br>
          @endif

          <div class="w3-col l4">{{ Form::checkbox('ch'.$produto->id.'[]',$role->id,null,['class'=>'w3-checkbox']) }}
            {{$role->name}}
          </div> @endforeach
          <br><br>
          <!-- </select> -->
        </div>


        <span id="buttonCampaigns{{$produto->id}}"
          class="w3-button w3-block w3-green w3-section w3-padding">Create</span>
      </div>
      {!! Form::close() !!}

      <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">

      </div>

    </div>
  </div>

  <!--................................................MODAL.................................................-->

  <style>
    .img-container img {
      max-width: 100%;
    }
  </style>
  <!--................................................MODAL.................................................-->
  <script>
    var assetBaseUrl = "{{ asset('') }}";
  </script>

  <div id="fotomodal{{$produto->id}}" class="w3-modal w3-animate-opacity">
    <div class="w3-modal-content w3-card-4 w3-animate-opacity" style="width:995px">

      <div class="w3-center"><br>
        <span onclick="closefotomodal({{$produto->id}})" class="w3-button w3-xlarge w3-hover-red w3-display-topright"
          title="Close Modal">&times;</span>
      </div>
      <div class="w3-section">
        <div class="w3-container">
          {!! Form::open(['id'=>'upload_form'.$produto->id]) !!}
          <div class="w3-row" style="">
            <div style="display: flex;">
              <div class="w3-col l4 w3-center w3-border  w3-padding w3-row-padding-32"
                style="overflow: auto; padding-top: 47.5px !important; ">
                <div class="img-container">
                  <div class="w3-col l12">&nbsp;</div>
                  <div class="w3-col l12"><img class="w3-padding w3-margin-top w3-margin-bottom"
                      id="image{{$produto->id}}" src="<?php echo asset("storage/padrao.png")?>" alt="Picture"></div>
                </div>
              </div>
              <div class="w3-col l8 w3-center w3-border  w3-padding w3-row-padding">


                <div id="result{{$produto->id}}" class="w3-margin-top w3-margin-bottom" style="overflow: auto;">
                  <div class="w3-col l12">
                    <h3>Preview</h3>
                  </div>
                  <div class="alert{{$produto->id}} w3-center w3-col l12"
                    style="width: 300px; height: 225px; overflow: hidden; margin-left: 25%;"></div>
                </div>

              </div>
            </div>
            <div class="w3-col l12">
              <!-- <input type="file" name="path_image" id="path_image{{ $produto->id }}" class="upload" onchange="changeImage(this,{{ $produto->id }})" /> -->
              <input type="file" name="path_image" id="path_image{{ $produto->id }}"
                onchange="readURL(this,'#image{{$produto->id}}',{{$produto->id}})" />
            </div>

            <div class=" w3-col l12 w3-border">
              <span style="display: block;" onclick="redim({{$produto->id}})" id="redimbtn{{$produto->id}}"
                class="w3-button w3-orange w3-block">Redim</span>
            </div>
            <div class="w3-col l12 w3-border">
              <span style="display: none;" id="button{{$produto->id}}" class="w3-button w3-blue w3-block">Save</span>
            </div>

          </div>
          {!! Form::close() !!}
        </div>
        <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
        </div>
      </div>
    </div>
  </div>

  <!--................................................MODAL.................................................-->

  <!--................................................MODAL.................................................-->


  <div id="extrascreatemodal{{$produto->id}}" class="w3-modal w3-animate-opacity">
    <div class="w3-modal-content w3-card-4 w3-animate-opacity" style="max-width:600px">

      <div class="w3-center"><br>
        <span onclick="document.getElementById('extrascreatemodal{{$produto->id}}').style.display='none'; modal=0;"
          class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">&times;</span>

      </div>

      <h5 class="w3-center"><b>Add Extra</b></h5>

      <div class="w3-container w3-section w3-cell w3-margin-bottom w3-center" id="extracreateerror{{$produto->id}}">
      </div>


      {!! Form::open([ 'class'=>'w3-container']) !!}
      <div class="w3-section">
        <div class="form-group">
          {!! Form::label('extra', 'Extra:') !!}
          <div class="w3-container w3-section w3-cell w3-right w3-btn w3-ripple w3-blue"
            onclick="create({{$produto->id}},0);">Create New Extra</div>
          <div id="selec{{$produto->id}}_0">
            <select name="extra" class="w3-select w3-border w3-margin-bottom" id="extrainput{{$produto->id}}"
              style="padding: 3px 5px;">
              @foreach($extras as $extra)
              <option value="{{$extra->id}}">{{$extra->name}}</option>
              @endforeach
            </select>
          </div>
        </div>


        <div class="form-group">
          {!! Form::label('type', 'Type:') !!}
          <select name="typeextra" id="typeextra{{$produto->id}}" class="w3-select w3-border w3-margin-bottom"
            style="padding: 3px 5px;">
            @if($produto->alojamento==1)
            <option value="alojamento">Alojamento</option>
            @endif
            @if($produto->golf==1)
            <option value="golf">Golf</option>
            @endif
            @if($produto->transfer==1)
            <option value="transfer">Transfer</option>
            @endif
            @if($produto->car==1)
            <option value="car">Ren Car</option>
            @endif
            @if($produto->ticket==1)
            <option value="ticket">Ticket</option>
            @endif
          </select>
        </div>


        {!! Form::submit('Add', ['class'=>'w3-button w3-block w3-green w3-section w3-padding']) !!}
      </div>
      {!! Form::close() !!}

      <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">

      </div>

    </div>
  </div>

  <!--................................................MODAL................................................. -->

  <br /><br />
  @endforeach

  <div class="w3-center">{{ $produtos->appends(Request::only('nome'))->links() }}</div>
  <div id="createmodal" class="w3-modal w3-animate-opacity">
    <div class="w3-modal-content w3-card-4 w3-animate-opacity" style="max-width:600px">

      <div class="w3-center"><br>
        <span onclick="document.getElementById('createmodal').style.display='none'; "
          class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">&times;</span>

      </div>

      <h5 class="w3-center"><b>Add Extra</b></h5>

      <div class="w3-container w3-section w3-cell w3-margin-bottom w3-center" id="extracreateerror"></div>

      {!! Form::open(['id'=>'extracreateform', 'class'=>'w3-container']) !!}
      <!-- Nome Form Input -->


      <!--{{Form::label('Imagem', 'Image',['class' => 'control-label'])}}

    <div class="w3-cell-row w3-margin-bottom">
    <div class="w3-container w3-section w3-cell w3-margin-bottom">

    <img class="w3-margin-bottom" id="editSupplier_img" src="<?php echo asset("storage/padrao.png")?>">



    </div>
    <div class="w3-container w3-section w3-cell w3-margin-bottom" id="error" ></div>
    </div>-->


      <div class="w3-row-padding w3-padding-32 w3-margin-top">
        <div class="w3-col l12">
          <div class="form-group">
            {!! Form::label('name', 'Name:') !!}
            {!! Form::text('name', null, ['id'=>'nameextra','class'=>'w3-input w3-border', 'style'=>'padding: 3px
            5px;']) !!}
          </div>
          <!-- Descricao Form Input -->
          <div class="form-group">
            {!! Form::label('description', 'Description:') !!}
            {!! Form::textarea('description', null, ['id'=>'textextra' ,'class'=>'w3-input w3-border', 'style'=>'
            height: 130px; resize: none;']) !!}
          </div>

        </div>

      </div>


      <div class="form-group w3-center">
        {!! Form::submit('Create Extra', ['class'=>'w3-button w3-green w3-section w3-padding ']) !!}
      </div> {!! Form::close() !!}

      <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">

      </div>

    </div>
  </div>


  <!--................................................MODAL.................................................-->
  @else


  <div class="container">
    <div class="row">
      <div class="col-sm-6 col-md-4 col-md-offset-4">
        <h1 class="text-center login-title">Sign in to continue</h1>
        <div class="account-wall">
          <img class="profile-img center-block"
            src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120"
            alt="">
          <br />
          {!! Form::open(['url'=>'enter', 'class'=>'form-signin']) !!}
          <div class="form-group">

            {!! Form::text('login', null, ['class'=>'form-control', 'placeholder'=>'Email']) !!}

          </div>
          <div class="form-group">
            {!! Form::password('Password', ['class'=>'form-control', 'placeholder'=>'Password']) !!}
          </div>
          <div class="form-group">
            {!! Form::submit('Sign in', ['class'=>'btn btn-lg btn-primary btn-block']) !!}
          </div>

          <div class="form-group">
            {!! Form::checkbox('remember', 'remember-me', ['class'=>'checkbox pull-left']); !!}
            {!! Form::label('remember', 'Remember me') !!}
          </div>

          {!! Form::close() !!}


        </div>

      </div>
    </div>
  </div> @endif

</div>

@endsection
