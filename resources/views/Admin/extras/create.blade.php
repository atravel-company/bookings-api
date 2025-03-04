@extends('Admin.layouts.app')

@section('content')
	<div class="container">

		<h1 class="w3-center">Novo Extra</h1>
@if (Auth::check())
		@if ($errors->any())
			<ul class="alert alert-warning">
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		@endif




		{!! Form::open(['url'=> route('extras.store')]) !!}
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
			{!! Form::text('name', null, ['class'=>'w3-input w3-border', 'style'=>'padding: 3px 5px;']) !!}
	</div>
    <!-- Descricao Form Input -->
	<div class="form-group">
		{!! Form::label('description', 'Description:') !!}
		{!! Form::textarea('description', null, ['class'=>'w3-input w3-border', 'style'=>' height: 130px; resize: none;']) !!}
	</div>





    </div>





    </div>


<div class="form-group w3-center">
			{!! Form::submit('Create Extra', ['class'=>'w3-button  w3-green w3-section w3-padding ']) !!}
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
