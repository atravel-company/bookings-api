@extends('Admin.layouts.app')

@section('content')
<script type="text/javascript" src="{{ URL::asset('Admin/js/roles.js') }}"></script>



<div class="w3-container">
    <h1>Groups</h1>
    @if (Auth::check())

    @foreach($roles as $role)
    <table class="table table-striped table-bordered table-hover w3-container">
        {{-- dd($role->users) --}}
        <thead>
            <tr>
                <th>Group</th>
                <th>Users</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td style="width: 489px !important;">{{ $role->name }}</td>
                <td>
                    <table>
                        @foreach($role->users()->orderBy("name")->get() as $user)
                        <tr>

                            <td style="width: 489px !important;">{{$user->name}}</td>
                            <td>&nbsp;&nbsp;<span onclick="delink({{$user->id}},'{{$role->id}}')" class="btn btn-xs  w3-red">Delete</span></td>



                        </tr>
                        @endforeach
                        <tr>
                            <td><select id="role{{$role->id}}" name="role{{$role->id}}">
                                    @foreach($users as $nome)
                                    <option value="{{$nome->id}}">{{$nome->name}}</option>
                                    @endforeach
                                </select></td>
                            <td>&nbsp;&nbsp;<span onclick="link({{$role->id}},'{{$role->name}}')" class="btn btn-xs  w3-blue ">Enviar</span></td>
                        </tr>
                    </table>
                </td>
                <td>
                    @hasrole('edita')
                    <a href="{{ route('groups.edit',['id'=>$role->id]) }}" class="btn btn-info btn-xs">Edit</a>&nbsp&nbsp&nbsp
                    @else
                    Not have permission!
                    @endhasallroles



                    @hasrole('apaga')
                    <a href="{{ route('groups.destroy',['id'=>$role->id]) }}" class="btn btn-danger btn-xs">Delete</a>
                    @else
                    Not have permission!
                    @endhasallroles
                </td>
            </tr>


        </tbody>

    </table>




    @endforeach
    @hasrole('cria')
    <a href="{{ route('groups.create') }}" class="btn btn-success  btn-xs">Create</a>
    @else
    Not have permission!
    @endhasallroles




    @else









    @endif





</div>

@endsection
