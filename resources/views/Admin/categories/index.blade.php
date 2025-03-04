@extends('Admin.layouts.app')

@section('content')







<table class="table table-striped table-bordered table-hover w3-container">

		<thead>
			<tr>
				<th><div class="w3-container w3-cell w3-left">Category</div></th>
				<th><a href="{{ route('categories.create') }}" class="btn btn-success  btn-xs">Create</a></th>
				<!--<th>Nome</th>
				<th>Descrição</th>-->


			</tr>
		</thead>

		<tbody>

			@foreach($categorias as $categoria)
		<div id="{{ $categoria->id }}" class="w3-modal " >

    							<div class="w3-modal-content w3-animate-zoom" style="max-width:50%">
    							<span onclick="document.getElementById('{{ $categoria->id }}').style.display='none'" class="w3-button w3-red w3-display-topright">&times;</span>
    							<img src="<?php echo asset("storage/$categoria->path_image")?>" style="width:100%">
    							</div>
  							</div>
				<tr>
					<!--<td>{{ $categoria->id }}</td>-->
					<td>
						<div class="w3-container w3-cell">
							<img class="w3-card-4" onclick="document.getElementById('{{ $categoria->id }}').style.display='block'" src="<?php echo asset("storage/$categoria->path_image")?>"  style="width:152px;height:114px;">
							</img>



						</div>
						<div class="w3-container w3-cell">
							<b>{{ $categoria->name }}</b>
							<br/>
							{{ $categoria->description }}
							</div>
							</td>
					<!--<td>{{ $categoria->name }}</td>
					<td>{{ $categoria->description }}</td>-->
					<td>
					@hasrole('edita')

					<a href="{{ route('categories.edit',['id'=>$categoria->id]) }}" class="btn btn-info btn-xs">Edit&nbsp;&nbsp;&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;
					@else
						Not have permission!
					@endhasallroles


    		<br/><br/>
			@hasrole('apaga')

					<a href="{{ route('categories.destroy',['id'=>$categoria->id]) }}" class="btn btn-danger btn-xs">Delete</a>
					@else
						Not have permission!
					@endhasallroles
					<!--comenta
					&nbsp&nbsp&nbsp<a onclick="myFunction({{ $categoria->id }})" class="btn btn-default btn-xs">Comentario</a></td>-->
					</td>


				</tr>

			@endforeach


		</tbody>

	</table>









@endsection
