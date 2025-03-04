@extends('Admin.layouts.app')

@section('content')
	<div class="container">

		<h1>Category Edit: {{$categoria->name}}</h1>

		@if ($errors->any())
			<ul class="alert alert-warning">
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		@endif
		<div class="w3-container w3-cell">
		<img class="w3-card-4"  src="<?php echo asset("storage/$categoria->path_image")?>"  style="width:228px;height:171px;">
		</div>
		{!! Form::open(['url'=> route('categories.update',$categoria->id), 'files' => true, 'method'=>'put']) !!}
		<!-- Nome Form Input -->
		<div class="form-group">

		<br/>
		{{Form::file('path_image')}}
		</div>
		<div class="form-group">
			{!! Form::label('name', 'Name:') !!}
			{!! Form::text('name', $categoria->name, ['class'=>'form-control']) !!}
		</div>

		<!-- Descricao Form Input -->
		<div class="form-group">
			{!! Form::label('description', 'Description:') !!}
			{!! Form::textarea('description', $categoria->description, ['class'=>'form-control']) !!}
		</div>

		<div class="form-group">
			{!! Form::submit('Local Save', ['class'=>'btn btn-primary']) !!}
		</div>

		{!! Form::close() !!}
@endsection
