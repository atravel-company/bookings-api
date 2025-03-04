@extends('Admin.layouts.app')


@push('javascript')
<script type="text/javascript" src="{{ URL::asset('Admin/js/principal.js') }}"></script>

@endpush


@section('content')





<style type="text/css">
    .select2-selection__rendered {
        text-align: left;
    }

</style>


<div class="w3-container">
    <div class="w3-row">
        {!! Form::open(['url'=>route('main.search'), 'id'=>'main']) !!}
        <div class="w3-col l10">

            <div class="w3-col l4 w3-padding w3-center">
                <span class="w3-col l12 w3-padding" style="background-color: #24AEC9; color: white; "><b>Category</b></span>
                <select name="categoria_id" id="categoria_id" class="w3-block w3-col l12 w3-select w3-border">

                    <option value=0> Select... </option>
                    @foreach($categorias as $categoria)
                        @if(session('categoria_id') != $categoria->id)
                            <option value="{{$categoria->id}}">{{$categoria->name}}</option>
                        @else
                             <option selected value="{{$categoria->id}}">{{$categoria->name}}</option>
                        @endif

                    @endforeach
                </select>

            </div>



            <div class="w3-col l4 w3-padding w3-center">
                <span class="w3-col 12 w3-padding" style="background-color: #24AEC9; color: white; "><b>Location</b></span>
                <select style="text-align: left;" name="destino_id" id="destino_id" class="w3-block w3-col l12 w3-select w3-border selectpicker" data-live-search="true">
                    <option value=0> Select... </option>
                    @foreach($destinos as $destino)
                        @if(session('location') != $destino->id)
                            <option value="{{$destino->id}}"> {{$destino->name}} </option>
                        @else
                            <option selected value="{{$destino->id}}"> {{$destino->name}} </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div id="produtos" class="w3-rest w3-padding w3-center">

            </div>
        </div>

        <div class="w3-col l2" style="padding-right: 16px; padding-left: 17px;">
            <div class="w3-col l12">
                &nbsp;
            </div>
            <div class="w3-col l5">
                &nbsp;
            </div>
            <div class="w3-col l7">
                {!! Form::submit('Search', ['class'=>'w3-button w3-block w3-center w3-padding ', 'style'=>'background-color: #24AEC9; color:white;']) !!}
            </div>
        </div>

        <div class="w3-col l12">
            &nbsp;
        </div>
        {!! Form::close() !!}

        <div class="w3-col l10">
        </div>


    </div>

    <script>
        var assetBaseUrl = "{{ asset('').'admin/' }}";

    </script>

    <div id="resultado" class="w3-row w3-padding">

    </div>

</div>

@endsection

@push("javascript")

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    <script>
        $(document).ready(function() {

            $(".selectpicker").select2();

            if( $("select[name=destino_id]").val() != null &&  $("select[name=destino_id]").val() != null){

                $("form#main").submit();
            }

        });

    </script>
@endpush
