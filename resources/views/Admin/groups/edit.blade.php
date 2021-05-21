@extends('Admin.layouts.app')

@section('content')
	<div class="container">

		<h1>Edit Group</h1>

		@if ($errors->any())
			<ul class="alert alert-warning">
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		@endif

		{!! Form::open(['url'=>route('groups.update',$role->id),'method'=>'put']) !!}
		<!-- Nome Form Input -->
		<div class="form-group">
			{!! Form::label('name', 'Name:') !!}
			{!! Form::text('name',$role->name, ['class'=>'form-control']) !!}
		</div>


		<div class="form-group">
			{!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
		</div>

		{!! Form::close() !!}
@endsection
