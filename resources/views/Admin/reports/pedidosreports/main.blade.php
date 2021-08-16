@extends('Admin.layouts.app')

@section('content')

@push('javascript')
<script type="text/javascript" src="{{ URL::asset('Admin/js/relatorio.js') }}" ></script>

@endpush

@push("css")

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

@php
$users_array = ['sales@atravel.pt', 'incoming@atravel.pt', 'transfers@atravel.pt', 'bookings@atravel.pt',
'accounts@atravel.pt'];
@endphp


<div class="w3-container">


    <script>
        var assetBaseUrl = "{{ asset('') }}";

    </script>



    <span class="w3-center">
        <h1>Payments Control
            @if(in_array(Auth::user()->email, $users_array))
            @if(Route::current()->getName() != "pedidos.reports.index.ats" and Route::current()->getName() !=
            "pedidos.reports.buscar.ats" )
                <a style="font-size:16px;" href="{{route('pedidos.reports.index.ats')}}">without ATS</a>
            @else
                <a style="font-size:16px;" href="{{route('pedidos.reports.index')}}">with ATS</a>
            @endif
            @endif
        </h1>
    </span>
    <div class="container-fluid">
        <div class="row" style="padding:30px 0;">
            @if(Route::current()->getName() != "pedidos.reports.index.ats" and Route::current()->getName() !=
            "pedidos.reports.buscar.ats" )
            {{ Form::open(array('route' => 'pedidos.reports.buscar', 'method' => 'post', 'id' => 'search')) }}
            @else
            {{ Form::open(array('route' => 'pedidos.reports.buscar.ats', 'method' => 'post', 'id' => 'search')) }}
            @endif
            <div class="col-lg-2 col-sm-6 col-md-2">
                <label class="ats-label">Operator</label>
                <!--<input value="{{session('operator')}}" type="text" class="form-control ats-border-color" id="operator" name="operator">-->
                <select width="100%" id="operator" name="operator" class="form-control ats-border-color select-simple">
                    <option value="0">Select</option>
                    @foreach($utilizadores as $operadores)
                    @if(request('operator') == $operadores->id)
                    <option selected value="{{$operadores->id}}">{{$operadores->name}}</option>
                    @else
                    <option value="{{$operadores->id}}">{{$operadores->name}}</option>
                    @endif
                    @endforeach
                </select>
            </div>

            <div class="col-lg-2 col-sm-6 col-md-2">
                <label class="ats-label">Supplier</label>
                <!--<input value="{{session('hotel')}}" type="text" class="form-control ats-border-color" id="hotel" name="hotel">-->
                <select width="100%" class="form-control ats-border-color select-simple" id="hotel" name="hotel">
                    <option value="0">Select</option>
                    @foreach($produtos as $produto)
                    @if(request('hotel') == $produto->id)
                    <option selected value="{{$produto->id}}">{{$produto->nome}}</option>
                    @else
                    <option value="{{$produto->id}}">{{$produto->nome}}</option>
                    @endif
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-sm-6 col-md-2">
                <label class="ats-label">Client</label>
                <div class="form-group">
                    <div class="input-group" style="position: relative;width: 100%">
                        <input value="{{request('client')}}" type="text" class="form-control ats-border-color" id="client" name="client" width="100%">
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6 col-md-2">
                <label class="ats-label">Start</label>
                <div class="form-group">
                    <div class="input-group" style="position: relative;">
                        @if(request('start') != null and request('start') != '' and request('start') != 'null')
                        @php
                        $data = request('start');
                        if(preg_match("/-/i", $data)){
                        $data = \Carbon\Carbon::parse($data)->format("d/m/Y");
                        }else{
                        $data = \Carbon\Carbon::createFromFormat("d/m/Y", $data)->format("d/m/Y");
                        }
                        @endphp
                        <input autocomplete="off" value="{{ $data  }}" type="text" class="form-control ats-border-color datepicker2" id="start" name="start" placeholder="Check-in">
                        @else
                        <input autocomplete="off" value="{{ Carbon\Carbon::now()->format('d/m/Y')}}" type="text" class="form-control ats-border-color datepicker2" id="start" name="start" placeholder="Check-In">
                        @endif
                        <span class="input-group-addon ats-border-color">
                            <span class="w3-large ats-text-color fa fa-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6 col-md-2">
                <label class="ats-label">End</label>
                <div class="form-group">
                    <div class="input-group" style="position: relative;">
                        @if(request('end') !== null and request('end') !== '')
                        @php
                        $data = request('end');
                        if(preg_match("/-/i", $data)){
                        $data = \Carbon\Carbon::parse($data)->format("d/m/Y");
                        }else{
                        $data = \Carbon\Carbon::createFromFormat("d/m/Y", $data)->format("d/m/Y");
                        }
                        @endphp
                        <input autocomplete="off" value="{{ $data }}" type="text" class="form-control ats-border-color datepicker2" id="end" name="end" placeholder="Check-Out">
                        @else
                        <input autocomplete="off" value="{{Carbon\Carbon::now()->format('d/m/Y')}}" type="text" class="form-control ats-border-color datepicker2" id="end" name="end" placeholder="Check-Out">
                        @endif
                        <span class="input-group-addon ats-border-color">
                            <span class="w3-large ats-text-color fa fa-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6 col-md-2">
                <label class="ats-label"></label>
                <div class="form-group">
                    <div class="input-group pull-right" style="position: relative; margin-top:4px;">
                        <button class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                        <div class="btn btn-primary" id="print-prof"><i class="fa fa-print" aria-hidden="true"></i>
                        </div>
                        <div class="btn btn-primary reset"><i class="fa fa-eraser" aria-hidden="true"></i></div>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>

    <div class="w3-row-padding" style="margin-top:50px">
        <div class="container-fluid" style="overflow-x: scroll;">
            <table class="table table-striped display compact table-bordered table-hover table-responsive nowrap" id="reports-table">


                @include("Admin.reports.pedidosreports.table")

            </table>
        </div>
    </div>

    @endsection



    @push('javascript')

    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js" ></script>
    <script src="//cdn.datatables.net/plug-ins/1.10.20/sorting/datetime-moment.js" defer></script>





    <script>

        $(document).ready(function() {

            $.fn.dataTable.moment( 'DD/MM/YYYY' );


            geraTabela();

            $(".datepicker").datepicker({
                dateFormat: 'dd/mm/yy'
            });
            $(".datepicker2").datepicker({
                dateFormat: 'dd/mm/yy'
            });

            $("select").select2({
                height: $("#client").innerHeight() + "px"
            });
            $("span.select2-selection--single").css("height", $("#client").innerHeight() + "px");
            var line = $(".select2-container--default .select2-selection--single .select2-selection__rendered").css('line-height');
            line = line.replace("px");
            line = parseInt(line) + 5;
            $(".select2-container--default .select2-selection--single .select2-selection__rendered").css('line-height', line + "px");

            /* metodo que altera o DataTable para poder filtrar datas no formato pt BR */
            // $.fn.dataTable.ext.type.order['dataser-pre'] = function ( a,b) {
            //     return ((a < b) ? -1 : ((a > b) ? 1 : 0));
            // };



            // jQuery.extend(jQuery.fn.dataTableExt.oSort, {
            //     "dataser-pre": function(a) {



            //         if (a == null || a == "") {
            //             return 0;
            //         }
            //         var brDatea = a.split('/');

            //         console.log(brDatea);
            //         return (brDatea[1] + brDatea[2] + brDatea[0]) * 1;
            //     }
            //     , "dataser-asc": function(a, b) {
            //         return ((a < b) ? -1 : ((a > b) ? 1 : 0));
            //     }
            //     , "dataser-desc": function(a, b) {
            //         return ((a < b) ? 1 : ((a > b) ? -1 : 0));
            //     }
            // });

        });


        function toFloat(num) {
            num = num.toString();
            dotPos = num.indexOf('.');
            commaPos = num.indexOf(',');
            if (dotPos < 0)
                dotPos = 0;

            if (commaPos < 0)
                commaPos = 0;

            if ((dotPos > commaPos) && dotPos)
                sep = dotPos;
            else {
                if ((commaPos > dotPos) && commaPos)
                    sep = commaPos;
                else
                    sep = false;
            }

            if (sep == false)
                return parseFloat(num.replace(/[^\d]/g, ""));

            return parseFloat(
                num.substr(0, sep).replace(/[^\d]/g, "") + '.' +
                num.substr(sep + 1, num.length).replace(/[^0-9]/, "")
            );

        }

        function geraTabela() {

            var table = $('#reports-table');

            //$.fn.dataTable.moment('MM/DD/YYYY HH:mm');

            /* Formatting function for row details */
            function fnFormatDetails(oTable, nTr) {
                var rowsTable = oTable.fnGetData(nTr);
                let MyPedido = new Reports(rowsTable[1]);
                var json = MyPedido.ajaxToHiddenData().responseJSON[0];
                var table = MyPedido.formatTable(json, rowsTable);
                return table;
            }

            var nCloneTd = document.createElement('td');
            nCloneTd.innerHTML = '<span class="row-details row-details-close fa fa-plus-circle"></span>';

            table.find('tbody tr').each(function() {
                this.insertBefore(nCloneTd.cloneNode(true), this.childNodes[0]);
            });

            var oTable = table.dataTable({

                dom: 'Bfrtip'
                , buttons: [

                    {
                        extend: "colvis"
                    },
                    {
                        extend: "excel"
                        , titleAttr: "Excel"
                        , exportOptions: {
                            columns: ":visible"
                        }
                    }
                    , {
                        extend: "pdf"
                        , titleAttr: "PDF"
                        , exportOptions: {
                            columns: ":visible"
                        }
                        , orientation: "landscape"
                        , pageSize: "LEGAL"
                    }
                    , {
                        extend: "print"
                        , titleAttr: "Imprimir"
                        , exportOptions: {
                            columns: ":visible"
                        }
                    }
                ]
                , format: 'dd/mm/yyyy'
                , "order": [
                    [2, 'asc']
                    , [16, 'asc']
                ]
                , pageLength: 20
                , autoWidth: true
                , resposive: false
                // , columnDefs: [{
                //     "orderable": true
                //     , "targets": 2
                //     , "type": 'date-pt'
                // }]
                , language: {
                    "aria": {
                        "sortAscending": ": activate to sort column ascending"
                        , "sortDescending": ": activate to sort column descending"
                    }
                    , "emptyTable": "Nenhum dado encontrado"
                    , "info": "Exibindo de _START_ à _END_ de _TOTAL_ linhas"
                    , "infoEmpty": "Nenhuma linha encontrada"
                    , "infoFiltered": "(Filtrado 1 de _MAX_ registros)"
                    , "lengthMenu": "Exibir _MENU_ linhas"
                    , "search": "Pesquisa:"
                    , "zeroRecords": "Nenhum dado encontrado"
                },

                footerCallback: function(row, data, start, end, display) {

                    $(".datepicker").datepicker({
                        dateFormat: 'dd/mm/yy'
                    });
                    $(".datepicker2").datepicker({
                        dateFormat: 'dd/mm/yy'
                    });

                    var api = this.api()
                        , data;
                    var intVal = function(i) {
                        if (typeof i === 'string') {
                            var number = i.replace(/[\$.]/g, '');
                            number = number.replace(",", "");
                            number *= 1;
                        } else if (typeof i === 'number') {
                            return i
                        } else {
                            return 0;
                        }

                        return number;
                    };

                    rnts = api.column(4).data().reduce(function(a, b) {
                        return toFloat(a) + toFloat(b);
                    }, 0);
                    bedn = api.column(5).data().reduce(function(a, b) {
                        return toFloat(a) + toFloat(b);
                    }, 0);
                    room = api.column(9).data().reduce(function(a, b) {
                        return toFloat(a) + toFloat(b);
                    }, 0);
                    golf = api.column(10).data().reduce(function(a, b) {
                        return toFloat(a) + toFloat(b);
                    }, 0);
                    trans = api.column(11).data().reduce(function(a, b) {
                        return toFloat(a) + toFloat(b);
                    }, 0);
                    car = api.column(12).data().reduce(function(a, b) {
                        return toFloat(a) + toFloat(b);
                    }, 0);
                    extras = api.column(13).data().reduce(function(a, b) {
                        return toFloat(a) + toFloat(b);
                    }, 0);
                    kback = api.column(14).data().reduce(function(a, b) {
                        return toFloat(a) + toFloat(b);
                    }, 0);


                    adr = parseFloat(room) / parseFloat(rnts);

                    rnts = parseFloat(rnts);
                    bedn = parseFloat(bedn);
                    adr = parseFloat(adr).toFixed(2);
                    room = parseFloat(room);
                    golf = parseFloat(golf);
                    trans = parseFloat(trans);
                    car = parseFloat(car);
                    extras = parseFloat(extras);
                    kback = parseFloat(kback);
                    total = $("#TableValorTotalTotal").attr("data-value");
                    vpaid = $("#ValorTotalPago").attr("data-value");
                    unpaid = $("#TableTotalValorNaoPago").attr("data-value");

                    $(api.column(3).footer()).html(isNaN(rnts) ? "0.00" : rnts.toLocaleString('de-DE'));
                    $(api.column(4).footer()).html(isNaN(bedn) ? "0.00" : bedn.toLocaleString('de-DE'));
                    $(api.column(5).footer()).html(isNaN(adr) ? "0.00" : adr.toLocaleString('pt-PT') + ' €');
                    $(api.column(6).footer()).html('-');
                    $(api.column(7).footer()).html('-');
                    $(api.column(8).footer()).html(room.toLocaleString('de-DE') + ' €');
                    $(api.column(9).footer()).html(golf.toLocaleString('de-DE') + ' €');
                    $(api.column(10).footer()).html(trans.toLocaleString('de-DE') + ' €');
                    $(api.column(11).footer()).html(car.toLocaleString('de-DE') + ' €');
                    $(api.column(12).footer()).html(extras.toLocaleString('de-DE') + ' €');
                    $(api.column(13).footer()).html(kback.toLocaleString('de-DE') + ' €');


                    $(api.column(14).footer()).html(total + ' €');
                    $(api.column(15).footer()).html(vpaid + ' €');
                    if (parseFloat(unpaid) > 0) {
                        $(api.column(16).footer()).html('<text style="color: green">' + unpaid + ' €</text>');
                    } else if (parseFloat(unpaid) < 0) {
                        $(api.column(16).footer()).html('<text style="color: red">' + unpaid + ' €</text>');
                    } else {
                        $(api.column(16).footer()).html('<text style="color: black">' + unpaid + ' €</text>');
                    }


                    if ($('#ats_profit').data('condition') == true) {

                        // unpaid nights total
                        room_ats = api.column(18).data().reduce(function(a, b) {
                            return toFloat(a) + toFloat(b);
                        }, 0);
                        // unpaid nights total
                        golf_ats = api.column(19).data().reduce(function(a, b) {
                            return toFloat(a) + toFloat(b);
                        }, 0);
                        // unpaid nights total
                        transfer_ats = api.column(20).data().reduce(function(a, b) {
                            return toFloat(a) + toFloat(b);
                        }, 0);
                        // unpaid nights total
                        car_ats = api.column(21).data().reduce(function(a, b) {
                            return toFloat(a) + toFloat(b);
                        }, 0);
                        // unpaid nights total
                        extras_ats = api.column(22).data().reduce(function(a, b) {
                            return toFloat(a) + toFloat(b);
                        }, 0);
                        // unpaid nights total
                        total_ats = api.column(23).data().reduce(function(a, b) {
                            return toFloat(a) + toFloat(b);
                        }, 0);
                        // unpaid nights total
                        profit_ats = api.column(24).data().reduce(function(a, b) {
                            return toFloat(a) + toFloat(b);
                        }, 0);

                        room_ats = parseFloat(room_ats);
                        golf_ats = parseFloat(golf_ats);
                        transfer_ats = parseFloat(transfer_ats);
                        car_ats = parseFloat(car_ats);
                        extras_ats = parseFloat(extras_ats);
                        total_ats = parseFloat(total_ats);
                        profit_ats = parseFloat(profit_ats);

                        $(api.column(17).footer()).html(room_ats.toFixed(2) + ' €');
                        $(api.column(18).footer()).html(golf_ats.toFixed(2) + ' €');
                        $(api.column(19).footer()).html(transfer_ats.toFixed(2) + ' €');
                        $(api.column(20).footer()).html(car_ats.toFixed(2) + ' €');
                        $(api.column(21).footer()).html(extras_ats.toFixed(2) + ' €');
                        $(api.column(22).footer()).html(total_ats.toFixed(2) + ' €');
                        $(api.column(23).footer()).html(profit_ats.toFixed(2) + ' €');
                    }

                } /* end footer callback */

            });

            var tableWrapper = $('#tableReports_wrapper');
            tableWrapper.find('.dataTables_length select').select2();

            table.on('click', ' tbody td .row-details', function() {

                var nTr = $(this).parents('tr')[0];

                if (oTable.fnIsOpen(nTr)) {
                    $(this).addClass("row-details-close").removeClass("row-details-open");
                    $(this).addClass("fa-plus-circle").removeClass("fa-minus-circle");
                    oTable.fnClose(nTr);
                } else {
                    $(this).addClass("row-details-open").removeClass("row-details-close");
                    $(this).addClass("fa-minus-circle").removeClass("fa-plus-circle");

                    oTable.fnOpen(nTr, fnFormatDetails(oTable, nTr), 'details');

                    // $(this).css('padding', 'inherit');
                    //$(".hiddenTable thead tr:first-child th:first-child").remove();

                    $(".hiddenTable thead th").removeAttr('orderable');
                    $(".hiddenTable thead th").removeAttr('searchable');
                    $(".hiddenTable thead tr").removeAttr('aria-controls');
                    $(".hiddenTable thead tr").removeAttr('style');
                    //$(".hiddenTable thead tr").removeAttr('class');

                    $(".hiddenTable th").filter(function() {
                        var t = $(this);
                        t.text() == "Money Paid" ? t.attr("colspan", 2) : null;
                        t.text() == "Money Received" ? t.attr("colspan", 10) : null;
                    }).closest("th");

                    if ($('#ats_profit').data('condition') == true) {
                        var h = $(".hiddenTable .ats_hidden");
                        jQuery(h.get(5)).html("EXTRA");
                        h.get(0).remove();
                        h.get(1).remove();
                        h.get(2).remove();
                        h.get(3).remove();
                        h.get(4).remove();
                    }
                }
            });
            $.fn.dataTable.moment( 'd M Y' );
        }

        /* CLASS */
        function Reports(pedidoid = 653) {

            this.pedidogeral_id = pedidoid;
        }

        Reports.prototype.formatTable = function(json, rowsTable) {

            console.log(json);
            console.log(rowsTable);
            // .innerHTML;

            var thead = $("#reports-table").find("thead").get(0);

            $(thead).children('tr:last-child').append('<th style="background-color:yellow; width:50px">MKP</th>');

            var html = "<table class='table hiddenTable display compact table-striped table-bordered nowrap'>";
            html += "<thead>";
            html += thead.innerHTML;
            html += "</thead>";
            html += "<tbody>";

            $(thead).children('tr:last-child').children('th:last-child').remove();

            $.each(json.pedidoprodutos, function(indice, pedidos) {

                var rnts = 0;
                var bednight = 0;

                var relacaoP = "pedido" + pedidos.tipoproduto;
                var relacao = "valor" + pedidos.tipoproduto;
                var field = "valor_" + relacao;

                if (pedidos.pedidoquarto.length > 0) {
                    $.each(pedidos.pedidoquarto, function(index, value) {
                        rnts += parseInt(value.rnts);
                        bednight += parseInt(value.bednight);
                    });
                } else {

                    $.each(pedidos[relacaoP], function(index, value) {
                        rnts += parseInt(value.TotalPax);
                    });

                    rnts += " pax";
                }

                var extraAtsRate = 0;
                $.each(pedidos.extras, function(index, value) {
                    extraAtsRate += value.ats_total_rate;
                });

                var adr = (pedidos.valorquarto != null ? pedidos.valorquarto.valor_quarto / rnts : 0);
                var valor_quarto = pedidos.valorquarto != null ? pedidos.valorquarto.valor_quarto : 0;
                var valor_golf = pedidos.valorgame != null ? pedidos.valorgame.valor_golf : 0;
                var valor_car = pedidos.valorcar != null ? pedidos.valorcar.valor_car : 0;
                var valor_transfer = pedidos.valortransfer != null ? pedidos.valortransfer.valor_transfer : 0;
                var valor_extras = pedidos[relacao] != null ? pedidos[relacao].valor_extra : 0;
                var valor_kickback = pedidos[relacao] != null ? pedidos[relacao].ValorKick : 0;
                var valorMarkup = pedidos[relacao] != null ? pedidos[relacao].ValorMarkup : 0;

                console.log(adr, valor_quarto, valor_golf, valor_car, valor_transfer, valor_extras);

                html += "<tr>";
                html += "<td> # </td>";
                html += "<td> " + moment(pedidos.FirstCheckin).format("DD/MM/YYYY") + "</td>";
                html += "<td> " + json.lead_name + "</td>";
                html += "<td> " + rnts + "</td>";
                html += "<td> " + parseFloat(bednight).toFixed(2) + "</td>";
                html += "<td> " + parseFloat(adr).toFixed(2) + "</td>";
                html += "<td> " + pedidos.produto.nome + "</td>";
                html += "<td> " + json.user.name + "</td>";
                html += "<td align='right'> " + parseFloat(valor_quarto).toFixed(2) + "</td>";
                html += "<td align='right'> " + parseFloat(valor_golf).toFixed(2) + "</td>";
                html += "<td align='right'> " + parseFloat(valor_transfer).toFixed(2) + "</td>";
                html += "<td align='right'> " + parseFloat(valor_car).toFixed(2) + "</td>";
                html += "<td align='right'> " + valor_extras + "</td>";
                html += "<td align='right'> " + valor_kickback + " € </td>";
                html += "<td align='right'> " + parseFloat(pedidos.valor).toFixed(2) + "</td>";
                html += "<td align='right'> " + rowsTable[16] + "</td>";
                html += "<td align='right'> " + rowsTable[17] + "</td>";
                html += "<td style='width:50px' align='right' data-teste=true> " + valorMarkup + "</td>";

                if ($('#ats_profit').data('condition') == true) {
                    html += "<td align='right'> " + parseFloat(extraAtsRate).toFixed(2) + "</td>";
                    html += "<td align='right'> " + parseFloat(pedidos.profit).toFixed(2) + "</td>";
                }

                html += "</tr>";
            });

            html += "</tbody>";
            html += "</table>";

            return html;
        }


        Reports.prototype.ajaxToHiddenData = function() {

            return $.ajax({
                type: 'post'
                , dataType: 'JSON'
                , async: false
                , url: "{{ route('pedidos.reports.buscar') }}"
                , data: {
                    'pedidoid': this.pedidogeral_id
                , }
            });
        }



        /* metodo que altera o DataTable para poder filtrar datas no formato pt BR */
        jQuery.extend(jQuery.fn.dataTableExt.oSort, {
            "date-br-pre": function(a) {
                if (a == null || a == "") {
                    return 0;
                }
                var brDatea = a.split('/');
                return (brDatea[2] + brDatea[0] + brDatea[1]) * 1;
            }
            , "date-br-asc": function(a, b) {
                return ((a < b) ? -1 : ((a > b) ? 1 : 0));
            }
            , "date-br-desc": function(a, b) {
                return ((a < b) ? 1 : ((a > b) ? -1 : 0));
            }
        });

    </script>

    @endpush
