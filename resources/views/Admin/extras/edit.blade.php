@extends('Admin.layouts.app')

@section('content')
	<div class="container">

		<h1>Extra Edit: {{$extra->name}}</h1>

		@if ($errors->any())
			<ul class="alert alert-warning">
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		@endif

		{!! Form::open(['url'=>route('extras.update',$extra->id), 'method'=>'put']) !!}
		<!-- Nome Form Input -->

		<div class="form-group">
			{!! Form::label('name', 'Name:') !!}
			{!! Form::text('name', $extra->name, ['class'=>'form-control']) !!}
		</div>

		<!-- Descricao Form Input -->
		<div class="form-group">
			{!! Form::label('description', 'Description:') !!}
			{!! Form::textarea('description', $extra->description, ['class'=>'form-control']) !!}
		</div>

		<div class="form-group">
			{!! Form::submit('Extra Save', ['class'=>'btn btn-primary']) !!}
		</div>

		{!! Form::close() !!}
@endsection
