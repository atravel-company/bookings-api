@extends('Admin.layouts.app')


@push('javascript')

    <script type="text/javascript" src="{{ URL::asset('Admin/js/lightslider.js') }}" ></script>


    <script type="text/javascript" src="{{ URL::asset('Admin/js/principal.js') }}" ></script>
    <script>
        var assetBaseUrl = "{{ asset('').'admin/' }}";
    </script>

@endpush


@section('content')

@push('css')
    <link href="{{ asset('Admin/css/lightslider.css') }}" rel="stylesheet">

@endpush

<style type="text/css">
.laranja{
    background-color: #F5AA3B;
    color: white;
}

.transparente{
    background-color: #000000;
    opacity: .5;
    color: white;
}
.transparente.button:hover{
    background-color: red;
    opacity: .2;
    color: white;
}

</style>


<div class="w3-container">
    <div class="w3-row" >

        <div class="w3-col l6">

<script>
var sing;

var Singleton = (function () {
    var instance;

    function createInstance(x) {
        var object = new Object(x);
        return object;
    }

    return {
        getInstance: function (valor) {
            if (!instance) {
                instance = createInstance(valor);
            }
            return instance;
        }
    };
})();

function run(inst) {
    sing = Singleton.getInstance(inst)
}
</script>

<div class="w3-content">
        <div class="w3-row w3-padding" style="overflow: auto;">
                <div class="w3-col" style=" position: relative; width:600px; height:450px;">
                     @foreach($produto->imagem->sortByDesc('title') as $key=>$imagem)
                     <script>
                        $(document).ready(function() {
                            run({{$key}});
                            var slideIndex = sing;
                        showDivs(slideIndex);
                        });

                        </script>

                     <img class="w3-card-4" id="mySlides{{$key}}" src="<?php echo asset("storage/$imagem->path_image")?>" style="border: 2px solid; border-color: white; width:600px; height:450px; display:none;">

                     @endforeach

                     <!-- <button class="transparente" style=" border: 2px solid; position: absolute; top:50%; left:5px; border-radius: 50%;"><b><</b></button>
                     <button class="transparente" style="border: 2px solid;position: absolute; top:50%; right:5px; border-radius: 50%;"><b>></b></button> -->

                </div>

            </div>
  <div class="w3-row">




<div class="w3-col w3-padding">
            <ul id="content-slider" class="w3-margin-top" >
            @foreach($produto->imagem->sortByDesc('title') as $key=>$imagem)


                <li class="w3-padding " style="height: 70px;">

                        <img id="demo{{$key}}" class="lslider  w3-opacity w3-hover-opacity-off w3-border" src="<?php echo asset("storage/$imagem->path_image")?>" style="width:69px; cursor:pointer;" onclick="currentDiv({{$key}})">

                </li>
                @endforeach
            </ul>
        </div>

  </div>
</div>




<script>
$(document).ready(function() {
            $("#content-slider").lightSlider({
                loop:true,
                keyPress:true,
                item: 8,
                slideMargin:1,
                slideMove:2,
                easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
                responsive : [
            {
                breakpoint:800,
                settings: {
                    item:3,
                    slideMove:1,
                    slideMargin:6,
                  }
            },
            {
                breakpoint:480,
                settings: {
                    item:2,
                    slideMove:1
                  }
            }
        ]
            });
});

var guarda=document.getElementById("mySlides0");


var i=0;




function loadSlide(n){
    n++;
    showDivs(n);
}


function plusDivs(n) {
  showDivs(slideIndex += n);
}

function currentDiv(n) {
  showDivs(slideIndex = n);
}




function showDivs(n) {
  var x = document.getElementById("mySlides"+n);
  var dots = document.getElementById("demo"+n);
  console.log(x, dots, n);
  if(guarda!=null){
    guarda.style.display= 'none';
  }
  if(x!=null){
    x.style.display = 'block';
  }
  if(guarda!=null&&x!=null){
    guarda = x;
  }
  //setTimeout(function(){ loadSlide(n) }, 3000);
}
</script>



        </div>
        <div class="w3-col l6">
        <div class="w3-content">
  <div class="w3-row w3-padding" style="overflow-y: auto;">
    <div class="w3-col" style="height:450px;">
        @if($destino===0)
    <h1 class="produto_nome">{{$produto->nome}}</h1>


    {!!$produto->descricao!!}
    @elseif($categoria===0)
    <!-- {{$produto->nome}}<br>
    {{$destino->name ?? ''}}<br> -->
    <h1 class="">{{$produto->nome}}</h1>


    {!!$produto->descricao!!}

    @else
    <!-- {{$produto->nome}}<br>
    {{$categoria->name}}<br>
    {{$destino->name}}<br> -->
    <h1 class="produto_nome">{{ $produto->nome }}</h1>


    {!!$produto->descricao!!}

    @endif

    </div>
    </div>
    <div class="w3-row">




<div class="w3-col l12  w3-padding ">

        <div class="w3-col l5 w3-padding ">
            <a href="javascript:history.back()" class="w3-button w3-margin-top w3-block" style="background-color:#24AEC9; color: white;">« PREV</a>

        </div>
        @if (Auth::check())
        <div class="w3-col l5 w3-padding ">
            <button id="booknowBtn" onclick='formulario("{{ $produto->nome }}",{{$produto->id}},{{$produto->alojamento ? 1 : 0}},{{$produto->golf ? 1 : 0}},{{$produto->transfer ? 1 : 0}},{{$produto->car ? 1 : 0}},{{$produto->ticket ? 1 : 0}},{{ Auth::user()->id }}, "{{--$imagem_logo--}}");' class="w3-button laranja w3-margin-top w3-block">BOOK NOW</button>
        </div>
        @endif
        </div>

  </div>
    </div>
    </div>

        <div class="w3-col l12">

        <div class="w3-col l6 w3-padding"><h3>Extras</h3>
        <div class="" style="border: 4px solid; border-color: white; overflow-y: auto; height: 90px;">
        <u>
        @foreach($produto->extras as $key=>$extra)
            @if(($key%3)==0 and $key!=0)

                <div class="w3-col l3 ">&nbsp;</div>
            @endif
                <li class="w3-col l3">{{$extra->name}}</li>
        @endforeach

        </u>
        </div>
        </div>
        </div>
        <div class="w3-col l6 w3-padding">


                    <div class="w3-col l12">
                    <h3>Rates</h3>
                    </div>
                 <div class="w3-col l12 w3-light-gray" style="border: 4px solid; border-color: #f5f8fa; overflow-y: auto; height: 90px;">

                        @foreach($produtoPdf as $key=>$pdf)
                            @php
                                $regras_id = [];
                                foreach($pdf->regras as $key2 => $regras){
                                    $regras_id[$key2] = $regras->id;
                                }

                                $role_users = DB::table('model_has_roles')->whereIn('role_id', $regras_id)->where('model_id', $user_id)->select('model_id')->distinct()->first();
                                $pdf_roles = DB::table('pdf_role')->where('produto_pdf_id', $pdf->id)->whereIn('role_id', $regras_id)->first();
                                /* echo '<pre>';
                                print_r($pdf_roles);
                                print_r($role_users);
                                print_r( $regras_id);
                                echo '</pre>'; */
                                /* print_r($regras_id); */
                            @endphp
                            @if($role_users && $pdf_roles)
                                @if($pdf->type=='Brochures')
                                <div style="float:left;">
                                <div class="w3-cell-row  w3-center">

                                        <a href="{{ asset('storage/pdfs/Brochures/'.$pdf->path_image)}}" style="color: red;" class="fa-3x fa fa-file-pdf-o" aria-hidden="true"></a>

                                </div>
                                <div class="w3-cell-row">
                                    <a href="{{ asset('storage/pdfs/Brochures/'.$pdf->path_image)}}" class="btn">{{$pdf->title}}</a>
                                </div>
                                </div>
                                @endif
                            @endif
                        @endforeach

                    </div>

                </div>

                <div class="w3-col l6 w3-padding">
                    <div class="w3-col l12">
                            <h3>Special Offers</h3>
                    </div>
                    <div class="w3-col l12  w3-light-gray" style="border: 4px solid; border-color: #f5f8fa; overflow-x: auto; height: 90px;">

                        @foreach($produto->pdf->sortBy('title') as $key=>$pdf)

                            @php
                                    $regras_id = [];
                                    foreach($pdf->regras as $key2 => $regras){
                                        $regras_id[$key2] = $regras->id;
                                    }

                                    $role_users = DB::table('model_has_roles')->whereIn('role_id', $regras_id)->where('model_id', $user_id)->select('model_id')->distinct()->first();
                                    $pdf_roles = DB::table('pdf_role')->where('produto_pdf_id', $pdf->id)->whereIn('role_id', $regras_id)->first();
                            @endphp

                        @if($role_users && $pdf_roles)
                            @if($pdf->type=='Campaigns')
                            <div style="float:left;">
                            <div class="w3-cell-row w3-center">

                                    <a href="{{ asset('storage/pdfs/Campaigns/'.$pdf->path_image)}}" style="color: red;" class="fa-3x fa fa-file-pdf-o" aria-hidden="true"></a>

                            </div>
                            <div class="w3-cell-row">
                                <a href="{{ asset('storage/pdfs/Campaigns/'.$pdf->path_image)}}" class="btn">{{$pdf->title}}</a>
                            </div>
                            </div>
                            @endif
                        @endif

                    @endforeach


                    </div>

</div>





    </div>


<!--................................................MODAL.................................................-->




  <div id="formModal"  class="w3-modal w3-animate-opacity">
    <div class="w3-modal-content w3-card-4 w3-animate-opacity w3-display-topmiddle" style="width:100%; height:100%;">

        <div id="loading">

        </div>
      </div>

    </div>
  </div>


  <!--................................................MODAL.................................................-->

</div>
@endsection
