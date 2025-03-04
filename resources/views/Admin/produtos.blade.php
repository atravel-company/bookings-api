@extends('home')
@section('content')
	<div class="container">
		<h1>Produtos</h1>
		@if (Auth::check())
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nome</th>
						<th>Descrição</th>
						<th>Ação</th>
					</tr>
				</thead>
				<tbody>
					@foreach($produtos as $produto)
						<tr>
							<td>{{ $produto->id }}</td>
							<td>{{ $produto->nome }}</td>
							<td>{{ $produto->descricao }}</td>
							<td><a href="{{ route('produtos.edit',['id'=>$produto->id]) }}" class="btn btn-info btn-xs">Edit</a>&nbsp&nbsp&nbsp<a href="{{ route('produtos.destroy',['id'=>$produto->id]) }}" class="btn btn-danger btn-xs">Delete</a></td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<a href="{{ route('produtos.create') }}" class="btn btn-success  btn-xs">Create</a>
		@else
			<div class="container">
			    <div class="row">
			        <div class="col-sm-6 col-md-4 col-md-offset-4">
			            <h1 class="text-center login-title">Sign in to continue</h1>
			            <div class="account-wall">
			                <img class="profile-img center-block" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120"
			                    alt="">
			                <form class="form-signin">
				                <input type="text" class="form-control" placeholder="Email" required autofocus>
				                <input type="password" class="form-control" placeholder="Password" required>
				                <button class="btn btn-lg btn-primary btn-block" type="submit">
				                    Sign in</button>
				                <label class="checkbox pull-left">
				                    <input type="checkbox" value="remember-me">
				                    Remember me
				                </label>
				                <a href="#" class="pull-right need-help">Need help? </a><span class="clearfix"></span>
			                </form>
			            </div>
			            <a href="#" class="text-center new-account">Create an account </a>
			        </div>
			    </div>
			</div>
		@endif	
	</div>
@endsection