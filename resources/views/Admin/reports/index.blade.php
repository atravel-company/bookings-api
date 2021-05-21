@extends('Admin.layouts.app')

<script type="text/javascript" src="{{ URL::asset('Admin/js/relatorio.js') }}"></script>


@section('content')


<div class="w3-container">

<script>
    var assetBaseUrl = "{{ asset('') }}";
</script>
    <span class="w3-center"><h1>Profile Statistics</h1></span>

<div class="w3-row-padding">


<div class="w3-col l2 m4 s12" >
        <label class="ats-label">Start</label>
            <div class="form-group">
                <!-- <div class="input-group">
                    <input type="text" readonly class="form-control ats-border-color" name="" placeholder="Check-In">
                    <span class="input-group-addon ats-border-color">
                        <span class="w3-large ats-text-color fa fa-calendar"></span>
                    </span>
                </div> -->

                <div class='input-group date datetimepicker1' id='datetimepicker12' style="position: relative;">
                 <div style="width: 330px; position: absolute;" id="checkin1"></div>
                    <input type='text' readonly name="in" id="in" class="form-control ats-border-color" placeholder="Check-In" />
                    <span class="input-group-addon ats-border-color">
                        <span class="w3-large ats-text-color fa fa-calendar"></span>
                    </span>
                </div>
            </div>
        </div>


        <div class="w3-col l2 m4 s12">
        <label class="ats-label">End</label>
            <div class="form-group">
                <div class="input-group date datetimepickers1" id='datetimepicker13' style="position: relative;">
                <div style="width: 330px; position: absolute;" id="checkout1"></div>
                    <input type="text" readonly class="form-control ats-border-color" id="out" name="out" placeholder="Check-Out">
                    <span class="input-group-addon ats-border-color">
                        <span class="w3-large ats-text-color fa fa-calendar"></span>
                    </span>
                </div>
            </div>
        </div>



        <div class="w3-col l1 m4 s12">
        <label class="ats-label">Operator</label>

                <div class="input-group w3-block">
                    <select id="usuarios"  class="form-control ats-border-color" name="" placeholder="">
                    <option value="-1">Select</option>
                    <option value="0">All</option>
                    @foreach($usuarios->sortBy('nome') as $key1=>$usuario)
                      <option value="{{$usuario->id}}">
                        {{$usuario->name}}
                      </option>
                      @endforeach
                    </select>

                </div>

        </div>


        <div class="w3-col l1 m4 s12">
        <label class="ats-label">Accommodation</label>

                <div class="input-group w3-block">
                    <select id="produtosAlojamentos"  class="form-control ats-border-color" name="" placeholder="">
                    <option value="-1">Select</option>
                    <option value="0">All</option>
                    @foreach($produtosAlojamentos->sortBy('nome') as $key1=>$produtosAlojamento)
                      <option value="{{$produtosAlojamento->id}}">
                        {{$produtosAlojamento->nome}}
                      </option>
                    @endforeach
                    </select>

                </div>

        </div>

        <div class="w3-col l1 m4 s12">
        <label class="ats-label">Golf</label>

                <div class="input-group w3-block">
                    <select id="produtosGolfs"  class="form-control ats-border-color" name="" placeholder="">
                    <option value="-1">Select</option>
                    <option value="0">All</option>
                    @foreach($produtosGolfs->sortBy('nome') as $key1=>$produtosGolf)
                      <option value="{{$produtosGolf->id}}">
                        {{$produtosGolf->nome}}
                      </option>
                    @endforeach
                    </select>

                </div>

        </div>

        <div class="w3-col l1">&nbsp;</div>
        <div class="w3-col l4">
            <label class="ats-label">&nbsp;</label>
            <div class="input-group">
                <span class="form-control  w3-button w3-gray" onclick="analise()">Analyse</span>
            </div>
        </div>

        <div id="loading"></div>
</div>




@endsection

