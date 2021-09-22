<html>
<meta name="viewport" content="width=device-width, initial-scale=1" charset="utf-8">
<style>
    @media print {
        tr.head {
            background-color: #24AEC9 !important;
            color: white !important;
            font-size: 14px !important;
            -webkit-print-color-adjust: exact;
        }

        .align-center {
            text-align: center !important;
        }

        .table-body {
            border-bottom: 1px #4e4e4e solid;
            font-size: 10px !important;
        }

        tr.head th {
            padding: 3px !important;
        }

        .footer {
            font-size: 10px !important;
            line-height: 1;
        }
    }

    tr.head {
        background-color: #24AEC9;
        color: white;
        font-size: 14px !important;
    }

    tr.head th {
        padding: 5px !important;
    }

    .align-center {
        text-align: center !important;
    }

    .table-body td {
        border-bottom: 1px #4e4e4e solid;
        font-size: 10px !important;
    }

    .footer {
        font-size: 10px !important;
        line-height: 1;
    }

</style>@php
    $path = 'https://atsportugal.com/storage/LogotipoAtravelCor.png';
@endphp
<link href="./css/w3.css" rel="stylesheet">

<head></head>

<body>
    <div class="w3-row w3-padding">
        <table class="w3-table w3-striped w3-centered" {{-- style="max-width: 100%" --}}>
            <tr>
                <th>&nbsp;</th>
                <th style="float:right; clear:right; text-align: right ; margin-bottom: 20px">
                    <img width=190 src="{{ $message->embed($path) }}">
                </th>
            </tr>
        </table>
    </div>
    <div class="w3-row w3-padding">
        Caros colegas,<br>
        Em representação do operador:
        <b>{{ $usuario->name }}</b>
        Informamos que foi enviado para o sistema <a
            href="http://osb2018.ddns.net/atstransfergest/demo">Transfergest-ATS</a> os seguintes transfers
    </div> <br>
    <div class="w3-row w3-padding">
        <span><b>Lead Name: </b> {{ $pedido->lead_name }}</span>
    </div>
    <div class="w3-row w3-padding">
        <table class="w3-table w3-striped w3-centered">
            <tr>
                <th style="text-align: left;">
                    Transfer
                </th>
            </tr>
        </table>
    </div>
    <div class="w3-row w3-padding">
        <table class="w3-table w3-striped w3-centered">
            <thead>
                <tr class="head" style="background-color: #24AEC9; color: white;">
                    <th>Date</th>
                    <th>Adults</th>
                    <th>Children</th>
                    <th>Babies</th>
                    <th>Pick-up</th>
                    <th>Flight Nº</th>
                    <th>Pick-up Time</th>
                    <th>Drop Off</th>
                    <th>ATS Rate</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedido['pedidoprodutos'] as $i => $transfers)
                    @foreach ($transfers->pedidotransfer()->get()->sortBy('data')
    as $j => $servico)
                        @if ($servico['data'] and $servico['pickup'] and $servico['hora'] and $servico['dropoff'])
                            <tr class="table-body">
                                <td>{{ \Carbon\Carbon::parse($servico['data'])->format('d/m/Y') }}</td>
                                <td align="center" class="align-center">{{ $servico['adult'] }}</td>
                                <td align="center" class="align-center">{{ $servico['children'] }}</td>
                                <td align="center" class="align-center">{{ $servico['babie'] }}</td>
                                <td>{{ $servico['pickup'] }}</td>
                                <td>{{ $servico['flight'] }}</td>
                                <td align="center" class="align-center">
                                    {{ Carbon\Carbon::parse($servico['hora'])->format('H:i') }}
                                </td>
                                <td>{{ $servico['dropoff'] }}</td>
                                <td>{{ $servico['ats_rate'] }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td> Total reserva </td>
                    <td> {{ $transfers->pedidotransfer()->sum('ats_rate') }} </td>
                </tr>
            </tfoot>
        </table>
    </div>
