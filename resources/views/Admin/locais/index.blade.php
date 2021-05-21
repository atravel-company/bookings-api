@extends('Admin.layouts.app')

@section('content')

<table class="table table-striped table-bordered table-hover w3-container">

    <thead>
        <tr>
            <th>
                <div class="w3-container w3-cell w3-left">Destiny</div>
            </th>
            <th><a href="{{ route('locais.create') }}" class="btn btn-success  btn-xs">Create</a></th>
        </tr>
    </thead>

    <tbody>

        @foreach($destinos as $destino)
        <div id="{{ $destino->id }}" class="w3-modal ">

            <div class="w3-modal-content w3-animate-zoom" style="max-width:50%">
                <span onclick="document.getElementById('{{ $destino->id }}').style.display='none'"
                    class="w3-button w3-red w3-display-topright">&times;</span>
                <img src="<?php echo asset("storage/$destino->path_image")?>" style="width:100%">
            </div>
        </div>
        <tr>
            <!--<td>{{ $destino->id }}</td>-->

            <td>
            {{ $destino->path_image }}
                <div class="w3-container w3-cell">
                    <img class="w3-card-4" onclick="document.getElementById('{{ $destino->id }}').style.display='block'"
                        src="<?php echo asset("storage/$destino->path_image")?>" style="width:152px;height:114px;">
                </div>
                <div class="w3-container w3-cell">
                    <b>{{ $destino->name }}</b>
                    <br />
                    {{ $destino->description }}
                </div>
            </td>
            <!--<td>{{ $destino->name }}</td>
					<td>{{ $destino->description }}</td>-->
            <td>
                @hasrole('edita')

                <a href="{{ route('locais.edit',['id'=>$destino->id]) }}"
                    class="btn btn-info btn-xs">Edit&nbsp;&nbsp;&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;
                @else
                Not have permission!
                @endhasrole

                <br /><br />
                @hasrole('apaga')
                <a href="{{ route('locais.destroy',['id'=>$destino->id]) }}" class="btn btn-danger btn-xs">Delete</a>
                @else
                Not have permission!
                @endhasrole
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
