@extends('Admin.layouts.app')

@section('content')
	<div class="container">

		<h1>New Category</h1>

		@if ($errors->any())
			<ul class="alert alert-warning">
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		@endif

		{!! Form::open(['url'=> route('categories.store'), 'files' => true]) !!}
		<!-- Nome Form Input -->
		<div class="form-group">
		{{Form::label('Imagem', 'image',['class' => 'control-label'])}}
		{{Form::file('path_image')}}
		</div>
		<div class="form-group">
			{!! Form::label('nome', 'Nome:') !!}
			{!! Form::text('name', null, ['class'=>'form-control']) !!}
		</div>

		<!-- Descricao Form Input -->
		<div class="form-group">
			{!! Form::label('descricao', 'Descrição:') !!}
			{!! Form::textarea('description', null, ['class'=>'form-control']) !!}
		</div>

		<div class="form-group">
			{!! Form::submit('Create Category', ['class'=>'btn btn-primary']) !!}
		</div>

		{!! Form::close() !!}
@endsection
