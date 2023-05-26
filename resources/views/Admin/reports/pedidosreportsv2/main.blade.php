@extends('Admin.layouts.app')

@push('javascript')
    <script type="text/javascript" src="{{ URL::asset('Admin/js/reports/relatorio.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
    <script src="//cdn.datatables.net/plug-ins/1.10.20/sorting/datetime-moment.js" defer></script>
@endpush

@push('css')
    @routes
    <style>
        .dataTables_filter input {
            font-weight: bold;
        }

        div.dataTables_filter input {
            margin-left: 0 !important;

        }

        #reports-table th {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            border-right: 1px solid #000;
        }

        #reports-table th:first-child {
            border-left: 1px solid #000;
        }

        #reports-table td:last-child {
            border-left: 1px solid #000;
            border-top: none;
            border-bottom: 1px solid #000;
            border-right: 1px solid #000;
        }

        #reports-table {
            color: #000;
        }


        #reports-table .row-details {
            cursor: pointer;
        }

        #reports-table .row-details span {
            cursor: pointer;
        }

        #reports-table tbody tr.details td.details {
            /* padding: inherit; */
            padding-top: 20px;
            padding-right: 10px;
            padding-left: 10px;
        }

        .hiddenTable,
        .hiddenTable tr,
        .hiddenTable td,
        .hiddenTable th {
            /* border: inherit; */
        }

        table.table.hiddenTable thead {
            font-size: 10px;
        }

        table.table.hiddenTable tbody {
            font-size: 11.5px;
        }

        table.hiddenTable thead .sorting,
        table.hiddenTable thead .sorting_asc,
        table.hiddenTable thead .sorting_desc {
            background: none;
        }

        #reports-table {
            font-family: "Arial";
        }
    </style>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush

@section('content')

    @php
        $users_array = ['sales@atravel.pt', 'incoming@atravel.pt', 'transfers@atravel.pt', 'bookings@atravel.pt', 'accounts@atravel.pt', 'bookings2@atravel.pt'];
    @endphp

    <div class="w3-container">
        <span class="w3-center">
            <h1>Payments Control
                @if (in_array(Auth::user()->email, $users_array))
                    @if (Route::current()->getName() != 'pedidos.v2.reports.index.ats' and
                            Route::current()->getName() != 'pedidos.v2.reports.buscar.ats')
                        <a style="font-size:16px;" href="{{ route('pedidos.v2.reports.index.ats') }}">without ATS</a>
                    @else
                        <a style="font-size:16px;" href="{{ route('pedidos.v2.reports.index') }}">with ATS</a>
                    @endif
                @endif
            </h1>
        </span>
        <div class="container-fluid">
            @if (Route::current()->getName() != 'pedidos.v2.reports.index.ats' and
                    Route::current()->getName() != 'pedidos.v2.reports.buscar.ats')
                {{ Form::open(['route' => 'pedidos.v2.reports.buscar', 'method' => 'post', 'id' => 'search']) }}
            @else
                {{ Form::open(['route' => 'pedidos.v2.reports.buscar.ats', 'method' => 'post', 'id' => 'search']) }}
            @endif

            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    <div class="row" style="padding:30px 0;">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <label class="ats-label">Operator</label>
                            <select width="100%" id="operator" name="operator"
                                class="form-control ats-border-color select-simple">
                                <option value="0">Select</option>
                                @foreach ($utilizadores as $operadores)
                                    @if (request('operator') == $operadores->id)
                                        <option selected value="{{ $operadores->id }}">{{ $operadores->name }}</option>
                                    @else
                                        <option value="{{ $operadores->id }}">{{ $operadores->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <label class="ats-label">Supplier</label>
                            <select width="100%" class="form-control ats-border-color select-simple" id="hotel"
                                name="hotel">
                                <option value="0">Select</option>
                                @foreach ($produtos as $produto)
                                    @if (request('hotel') == $produto->id)
                                        <option selected value="{{ $produto->id }}">{{ $produto->nome }}</option>
                                    @else
                                        <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <label class="ats-label">Client</label>
                            <div class="form-group">
                                <div class="input-group" style="position: relative;width: 100%">
                                    <input value="{{ request('client') }}" type="text"
                                        class="form-control ats-border-color" id="client" name="client" width="100%">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <label class="ats-label">Start</label>
                            <div class="form-group">
                                <div class="input-group" style="position: relative;" id="datepicker">
                                    @if (request('start') != null and request('start') != '' and request('start') != 'null')
                                        @php
                                            $data = request('start');
                                            if (preg_match('/-/i', $data)) {
                                                $data = \Carbon\Carbon::parse($data)->format('d/m/Y');
                                            } else {
                                                $data = \Carbon\Carbon::createFromFormat('d/m/Y', $data)->format('d/m/Y');
                                            }
                                        @endphp
                                        <input autocomplete="off" value="{{ $data }}" type="text"
                                            class="form-control ats-border-color" id="start" name="start"
                                            placeholder="Check-in">
                                    @else
                                        <input autocomplete="off" value="{{ Carbon\Carbon::now()->format('d/m/Y') }}"
                                            type="text" class="form-control ats-border-color" id="start"
                                            name="start" placeholder="Check-In">
                                    @endif
                                    <span class="input-group-addon ats-border-color" style="cursor: pointer !important;">
                                        <span class="w3-large ats-text-color fa fa-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <label class="ats-label">End</label>
                            <div class="form-group">
                                <div class="input-group" style="position: relative;" id="datepicker2">
                                    @if (request('end') !== null and request('end') !== '')
                                        @php
                                            $data = request('end');
                                            if (preg_match('/-/i', $data)) {
                                                $data = \Carbon\Carbon::parse($data)->format('d/m/Y');
                                            } else {
                                                $data = \Carbon\Carbon::createFromFormat('d/m/Y', $data)->format('d/m/Y');
                                            }
                                        @endphp
                                        <input autocomplete="off" value="{{ $data }}" type="text"
                                            class="form-control ats-border-color" id="end" name="end"
                                            placeholder="Check-Out">
                                    @else
                                        <input autocomplete="off" value="{{ Carbon\Carbon::now()->format('d/m/Y') }}"
                                            type="text" class="form-control ats-border-color" id="end"
                                            name="end" placeholder="Check-Out">
                                    @endif
                                    <span class="input-group-addon ats-border-color" style="cursor: pointer !important;">
                                        <span class="w3-large ats-text-color fa fa-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4"
                            style="display: flex; flex-wrap: nowrap; align-content: center; justify-content: space-evenly; align-items: center; vertical-align: middle; position: relative; flex-direction: row; padding-top: 22px;">
                            <div class="form-group">
                                <div class="input-group" style="position: relative; margin-top:4px;">
                                    <button class="btn btn-primary" type="submit" title="Pesquisar">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group" style="position: relative; margin-top:4px;">
                                    <button class="btn btn-danger reset" type="button" title="Limpar campos">
                                        <i class="fa fa-eraser" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group" style="position: relative; margin-top:4px;">
                                    <button class="btn btn-warning" id="print-prof" type="button" title="Gerar PDF">
                                        <i class="fa fa-print" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group" style="position: relative; margin-top:4px;">
                                    <button class="btn btn-success" type="button" title="Gerar excel" onclick="gerarRelatorioExcel()">
                                        <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>

        <div class="w3-row-padding" style="margin-top:50px">
            <div class="container-fluid" style="overflow-x: scroll;">
                <table class="table table-striped display compact table-bordered table-hover table-responsive nowrap"
                    id="reports-table">
                    @include('Admin.reports.pedidosreportsv2.table')
                </table>
            </div>
        </div>
    </div>
@endsection
