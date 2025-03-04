


@extends('Admin.layouts.app')

@section('content')
	<div class="container">

		<h1 class="w3-center">Novo Produto</h1>
@if (Auth::check())
		@if ($errors->any())
			<ul class="alert alert-warning">
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		@endif




		{!! Form::open(['url'=> route('produtos.store')]) !!}
		<!-- Nome Form Input -->


      <!--{{Form::label('Imagem', 'Image',['class' => 'control-label'])}}

    <div class="w3-cell-row w3-margin-bottom">
    <div class="w3-container w3-section w3-cell w3-margin-bottom">

    <img class="w3-margin-bottom" id="editSupplier_img" src="<?php echo asset("storage/padrao.png")?>">



    </div>
    <div class="w3-container w3-section w3-cell w3-margin-bottom" id="error" ></div>
    </div>-->


    <div class="w3-row-padding w3-padding-32 w3-margin-top">

    <div class="w3-col l6">


    <!-- Nome Form Input -->
    <!--<div class="form-group">

    {{Form::file('path_image',['id'=>'path_image','onchange'=>"readURL(this,'#editSupplier_img')"])}}
    </div>-->

    <div class="form-group">
       {!! Form::label('category', 'Category:') !!}
       <!-- <select name="categoria_id" class="w3-select w3-border w3-margin-bottom" style="padding: 3px 5px;"> --><br>
       		@foreach($categorias as $key => $categoria)
  			<!-- <option value="{{$categoria->id}}">{{$categoria->name}}</option> -->
        @if(($key%3)==0)
        <br>
        @endif
        <div class="w3-col l4">{{ Form::checkbox('ch[]',$categoria->id,null,['class'=>'w3-checkbox']) }}
        {{$categoria->name}}
        </div>

  			@endforeach
        <br><br>
		<!-- </select> -->
    </div>

    <div class="form-group">
       {!! Form::label('location', 'Location:') !!}
       <select name="destino_id" class="w3-select w3-border w3-margin-bottom" style="padding: 3px 5px;">
       <option value=""></option>
       		@foreach($destinos as $destino)
  			<option value="{{$destino->id}}">{{$destino->name}}</option>
  			@endforeach
		</select>
    </div>

    <div class="form-group">
       {!! Form::label('supplier', 'Supplier:') !!}
       <select name="supplier_id" class="w3-select w3-border w3-margin-bottom" style="padding: 3px 5px;">
       <option value=""></option>
       		@foreach($suppliers as $supplier)
  			<option value="{{$supplier->id}}">{{$supplier->name}} / {{$supplier->social_denomination}}</option>
  			@endforeach
		</select>
    </div>

	  <div class="form-group">
       {!! Form::label('form', 'Forms:') !!}
       <br><br>

       <div class="w3-col l4">{{ Form::checkbox('alojamento',true,null,['class'=>'w3-checkbox']) }}
        Alojamento
        </div>
        <div class="w3-col l4">{{ Form::checkbox('golf',true,null,['class'=>'w3-checkbox']) }}
        Golf
        </div>
       <div class="w3-col l4">{{ Form::checkbox('transfer',true,null,['class'=>'w3-checkbox']) }}
        Transfer
        </div>
        <br><br>
        <div class="w3-col l4">{{ Form::checkbox('car',true,null,['class'=>'w3-checkbox']) }}
        Rent Car
        </div>
        <div class="w3-col l4">{{ Form::checkbox('ticket',true,null,['class'=>'w3-checkbox']) }}
        Ticket
        </div>

    </div>




    </div>



    <div class="w3-col l6">
    <div class="form-group">
			{!! Form::label('nome', 'Nome:') !!}
			{!! Form::text('nome', null, ['class'=>'w3-input w3-border', 'style'=>'padding: 3px 5px;']) !!}
	</div>
    <!-- Descricao Form Input -->



<link href="{{ asset('css/jquery-te-1.4.0.css') }}" rel="stylesheet">
<script type="text/javascript" src="{{ URL::asset('js/jquery-te-1.4.0.min.js') }}"></script>

<script>
$(document).ready(function(){
  $('.jqte-test').jqte({source: false});

  // settings of status
  var jqteStatus = true;
  $(".status").click(function()
  {
    jqteStatus = jqteStatus ? false : true;
    $('.jqte-test').jqte({"status" : jqteStatus})
  });
});
</script>



	<div class="form-group">
		{!! Form::label('descricao', 'Descrição:') !!}
		{!! Form::textarea('descricao', null, ['class'=>'w3-input w3-border jqte-test', 'style'=>' height: 130px; resize: none;']) !!}
	</div>





    </div>





    </div>


<div class="form-group w3-center">
			{!! Form::submit('Criar Produto', ['class'=>'w3-button  w3-green w3-section w3-padding ']) !!}
		</div>







		{!! Form::close() !!}

@else






<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <h1 class="text-center login-title">Sign in to continue</h1>
            <div class="account-wall">
                <img class="profile-img center-block" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120"
                    alt="">
                    <br/>
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
</div>







@endif
	</div>


@endsection
