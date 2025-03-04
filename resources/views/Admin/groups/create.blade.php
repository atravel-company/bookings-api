@extends('Admin.layouts.app')

@section('content')
	<div class="container">

		<h1>New Group</h1>

		@if ($errors->any())
			<ul class="alert alert-warning">
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		@endif

		{!! Form::open(['url'=>route('groups.store') ]) !!}
		<!-- Nome Form Input -->
		<div class="form-group">
			{!! Form::label('name', 'Name:') !!}
			{!! Form::text('name', null, ['class'=>'form-control']) !!}
		</div>


		<div class="form-group">
			{!! Form::submit('Create Group', ['class'=>'btn btn-primary']) !!}
		</div>

		{!! Form::close() !!}
@endsection
