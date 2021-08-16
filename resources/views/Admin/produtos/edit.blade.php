@extends('Admin.layouts.app')

@push('css')
    <link href="{{ asset('Admin/css/jquery-te-1.4.0.css') }}" rel="stylesheet">
@endpush

@push('javascript')
    <script type="text/javascript" src="{{ URL::asset('Admin/js/produtos.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('Admin/js/jquery-te-1.4.0.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.jqte-test').jqte({
                source: false
            });

            // settings of status
            var jqteStatus = true;
            $(".status").click(function() {
                jqteStatus = jqteStatus ? false : true;
                $('.jqte-test').jqte({
                    "status": jqteStatus
                })
            });
        });

        function submitForm() {
            $('form#productUpdate')[0].submit();
        }

        function yesnoCheck() {
            if (document.getElementById('yesCheck').checked) {
                document.getElementById('ifYes').style.display = 'block';
                document.getElementById('emailsup').style.display = 'none'
            } else {
                document.getElementById('ifYes').style.display = 'none';
                document.getElementById('emailsup').style.display = 'block'
            }
        }
    </script>
@endpush

@section('content')
    @php
    $names = [];
    @endphp

    @foreach ($produto->categorias as $prodCat)
        @php($names[] = $prodCat->name)
    @endforeach

    <div class="container bordered-container">
        <h1 class="w3-center">{{ $produto->nome }}</h1>
        @if (Auth::check())
            @if ($errors->any())
                <ul class="alert alert-warning">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            {!! Form::open(['id' => 'productUpdate', 'url' => route('produtos.update', $produto->id), 'method' => 'post', 'enctype'=>'multipart/form-data' ]) !!}
            <input type="hidden" name="_method" value="put">
            <div class="w3-row-padding w3-padding-32 w3-margin-top">
                <div class="w3-col l6">
                    <div class="form-group">
                        {!! Form::label('category', 'Category:') !!}
                        <br>
                        @foreach ($categorias as $key => $categoria)
                            @if ($key % 3 == 0)
                                <br>
                            @endif
                            @if (in_array($categoria->name, $names))
                                <div class="w3-col l4">
                                    {{ Form::checkbox('ch[]', $categoria->id, null, ['class' => 'w3-checkbox', 'checked']) }}
                                    {{ $categoria->name }}
                                </div>
                            @else
                                <div class="w3-col l4">
                                    {{ Form::checkbox('ch[]', $categoria->id, null, ['class' => 'w3-checkbox']) }}
                                    {{ $categoria->name }}
                                </div>
                            @endif
                        @endforeach
                        <br>
                        <br>
                    </div>

                    <div class="form-group">
                        {!! Form::label('location', 'Location:') !!}
                        <select name="destino_id" class="w3-select w3-border w3-margin-bottom" style="padding: 3px 5px;">
                            @foreach ($destinos as $destino)
                                @if (isset($produto->destinos->name) and $produto->destinos->name == $destino->name)
                                    <option selected="selected" value="{{ $destino->id }}">{{ $destino->name }}
                                    </option>
                                @else
                                    <option value="{{ $destino->id }}">{{ $destino->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        {!! Form::label('supplier', 'Supplier:') !!}
                        <select name="supplier_id" class="w3-select w3-border w3-margin-bottom" style="padding: 3px 5px;">
                            @if (!isset($produto->suppliers->name))
                                <option selected="selected">The supplier of this product has been deleted</option>
                            @endif
                            @foreach ($suppliers as $supplier)
                                @if (isset($produto->suppliers->name))
                                    @if ($produto->suppliers->name == $supplier->name)
                                        <option selected="selected" value="{{ $supplier->id }}">{{ $supplier->name }}
                                            /
                                            {{ $supplier->social_denomination }}</option>
                                    @else
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }} /
                                            {{ $supplier->social_denomination }}</option>
                                    @endif
                                @else
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }} /
                                        {{ $supplier->social_denomination }}</option>
                                @endif


                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        {!! Form::label('form', 'Forms:') !!}
                        <br><br>
                        @if ($produto->alojamento)
                            <div class="w3-col l4">

                                {{ Form::checkbox('alojamento', true, null, ['class' => 'w3-checkbox', 'checked', 'onchange' => 'UpdateProdutoFormCheckBox(this,"alojamento")']) }}

                                {{ Form::hidden('alojamento', '1') }}

                                Alojamento
                            </div>
                        @else
                            <div class="w3-col l4">
                                {{ Form::checkbox('alojamento', true, null, ['class' => 'w3-checkbox']) }}
                                Alojamento
                            </div>
                        @endif
                        @if ($produto->golf)
                            <div class="w3-col l4">
                                {{ Form::checkbox('golf', true, null, ['class' => 'w3-checkbox', 'checked', 'onchange' => 'UpdateProdutoFormCheckBox(this,"golf")']) }}
                                {{ Form::hidden('golf', '1') }}
                                Golf
                            </div>
                        @else
                            <div class="w3-col l4">{{ Form::checkbox('golf', true, null, ['class' => 'w3-checkbox']) }}
                                Golf
                            </div>
                        @endif
                        @if ($produto->transfer)
                            <div class="w3-col l4">
                                {{ Form::checkbox('transfer', true, null, ['class' => 'w3-checkbox', 'checked', 'onchange' => 'UpdateProdutoFormCheckBox(this,"transfer")']) }}
                                {{ Form::hidden('transfer', '1') }}
                                Transfer
                            </div>
                        @else
                            <div class="w3-col l4">
                                {{ Form::checkbox('transfer', true, null, ['class' => 'w3-checkbox']) }}
                                Transfer
                            </div>
                        @endif
                        @if ($produto->car)
                            <br><br>
                            <div class="w3-col l4">
                                {{ Form::checkbox('car', true, null, ['class' => 'w3-checkbox', 'checked', 'onchange' => 'UpdateProdutoFormCheckBox(this,"car")']) }}
                                {{ Form::hidden('car', '1') }}
                                Rent Car
                            </div>
                        @else
                            <br><br>
                            <div class="w3-col l4">{{ Form::checkbox('car', true, null, ['class' => 'w3-checkbox']) }}
                                Rent Car
                            </div>
                        @endif

                        @if ($produto->ticket)
                            <div class="w3-col l4">
                                {{ Form::checkbox('ticket', true, null, ['class' => 'w3-checkbox', 'checked', 'onchange' => 'UpdateProdutoFormCheckBox(this,"ticket")']) }}
                                {{ Form::hidden('ticket', '1') }}
                                Ticket
                            </div>
                        @else
                            <div class="w3-col l4">{{ Form::checkbox('ticket', true, null, ['class' => 'w3-checkbox']) }}
                                Ticket
                            </div>
                        @endif
                    </div>
                </div>
                <div class="w3-col l6">
                    <div class="form-group">
                        {!! Form::label('nome', 'Nome:') !!}
                        {!! Form::text('nome', $produto->nome, [
    'class' => 'w3-input w3-border',
    'style' => 'padding: 3px 5px;',
]) !!}
                    </div>
                    <!-- Descricao Form Input -->

                    <div class="form-group">
                        {!! Form::label('descricao', 'Descrição:') !!}
                        {!! Form::textarea('descricao', $produto->descricao, ['class' => 'w3-input w3-border jqte-test', 'style' => ' height: 130px; resize: none; overflow-y: auto;']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('email', 'Reserve Email Type:') !!}
                        <br><br>
                        @if ($produto->email_type == 'no')
                            <div class="w3-col l12">
                                <input checked="checked" type="radio" onclick="javascript:yesnoCheck();" name="yesno"
                                    id="noCheck" value="no">
                                Supplier Reserve Email
                            </div>
                            <div class="w3-col l12" id="emailsup">
                                {!! Form::label('email', 'Email:') !!}
                                <select name="emailsup" class="w3-select w3-border w3-margin-bottom"
                                    style="padding: 3px 5px;">
                                    @if ($contatos)
                                        @foreach ($contatos as $contato)
                                            @if ($contato->email == $produto->email)
                                                <option selected="selected" value="{{ $contato->email }}">
                                                    {{ $contato->name }} / {{ $contato->email }}
                                                </option>
                                            @else
                                                <option value="{{ $contato->email }}">{{ $contato->name }} /
                                                    {{ $contato->email }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="w3-col l12">
                                <input value="yes" type="radio" onclick="yesnoCheck();" name="yesno" id="yesCheck">
                                Product Reserve Email
                            </div>
                            <div class="w3-col l12" style="display:none" id="ifYes">
                                {!! Form::label('email', 'Email:') !!}
                                {!! Form::text('mail', null, ['class' => 'w3-input w3-border', 'style' => 'padding: 3px 5px;']) !!}
                            </div>
                        @elseif($produto->email_type=='yes')
                            <div class="w3-col l12">
                                <input type="radio" onclick="javascript:yesnoCheck();" name="yesno" id="noCheck" value="no">
                                Supplier Reserve Email
                            </div>
                            <div class="w3-col l12" id="emailsup" style="display:none">
                                {!! Form::label('email', 'Email:') !!}
                                <select name="emailsup" class="w3-select w3-border w3-margin-bottom"
                                    style="padding: 3px 5px;">
                                    @foreach ($contatos as $contato)
                                        <option value="{{ $contato->email }}">{{ $contato->name }} /
                                            {{ $contato->email }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w3-col l12">
                                <input checked="checked" value="yes" type="radio" onclick="yesnoCheck();" name="yesno"
                                    id="yesCheck">
                                Product Reserve Email
                            </div>
                            <div class="w3-col l12" id="ifYes">
                                {!! Form::label('email', 'Email:') !!}
                                {!! Form::text('mail', $produto->email, ['class' => 'w3-input w3-border', 'style' => 'padding: 3px 5px;']) !!}
                            </div>
                        @else
                            <div class="w3-col l12"><input type="radio" onclick="javascript:yesnoCheck();" name="yesno"
                                    id="noCheck" value="no">
                                Supplier Reserve Email
                            </div>
                            <div class="w3-col l12" id="emailsup" style="display:none">
                                {!! Form::label('email', 'Email:') !!}
                                <select name="emailsup" class="w3-select w3-border w3-margin-bottom"
                                    style="padding: 3px 5px;">
                                    @foreach ($contatos as $contato)
                                        <option value="{{ $contato->email }}">{{ $contato->name }} /
                                            {{ $contato->email }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w3-col l12">
                                <input value="yes" type="radio" onclick="yesnoCheck();" name="yesno" id="yesCheck">
                                Product Reserve Email
                            </div>
                            <div class="w3-col l12" style="display:none" id="ifYes">
                                {!! Form::label('email', 'Email:') !!}
                                {!! Form::text('mail', null, ['class' => 'w3-input w3-border', 'style' => 'padding: 3px 5px;']) !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group w3-center">
                <button form="productUpdate" value="submit" type="submit" class="w3-button w3-blue w3-section w3-padding"
                    onclick="submitForm()">
                    Salvar
                </button>
            </div>
            {!! Form::close() !!}
        @endif
    </div>
@endsection
