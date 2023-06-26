<table border='1' class="table table-striped display compact table-bordered table-hover table-responsive nowrap"
    style="width:100%; border-collapse: collapse!important;">
    <thead>
        <tr>
            <th rowspan="2">Arr</th>
            <th rowspan="2">Client</th>
            <th rowspan="2">RNTS</th>
            <th rowspan="2">Bed N.</th>
            <th rowspan="2">ADR</th>
            <th rowspan="2">T.OPER</th>
            <th style="background-color:#ffdd97; text-align:center; border-left:1px solid #000;" colspan="7">
                Money
            </th>
        </tr>
        <tr>
            <th style="background-color:#0078ff;">ROOM</th>
            <th style="background-color:#1dcb4a;">GOLF</th>
            <th style="background-color:yellow;">TRA</th>
            <th style="background-color:#ffdd97;">CAR</th>
            <th style="background-color:#2ea7ff;">EXTRAS</th>
            <th style="background-color:yellow;">TOTAL</th>
            <th style="background-color:#1dcb4a;">PROFIT</th>
        </tr>
    </thead>
    <tbody>
        
    </tbody>
        @forelse($pedidos as $pedidoGeral)
            <tr>
                <td style="text-align:center"> {{ \Carbon\Carbon::parse($pedidoGeral->DataFirstServico)->format('d/m/Y') }} </td>
                <td style="text-align:right;"> {{ $pedidoGeral->lead_name }} </td>
                <td  style="text-align:center;">
                    @php $rnts = 0; @endphp
                    @foreach ($pedidoGeral->pedidoprodutos as $pedidoprodutos)
                        @forelse($pedidoprodutos->pedidoquarto as $quarto)
                            @php $rnts += $quarto->rnts; @endphp
                        @empty
                            @php $rnts += 0; @endphp
                        @endforelse
                    @endforeach
                    {{ $rnts }}
                </td>
                <td style="text-align:center;">
                    @php $bednight = 0; @endphp
                    @foreach ($pedidoGeral->pedidoprodutos as $pedidoprodutos)
                        @forelse($pedidoprodutos->pedidoquarto as $quarto)
                            @php $bednight += $quarto->bednight; @endphp
                        @empty
                            @php $bednight += 0; @endphp
                        @endforelse
                    @endforeach
                    {{ $bednight }}
                </td>
                <td style="text-align:right;">
                    @php $adr = 0; @endphp
                    @php $rnts = ($rnts == 0 ? 1 : $rnts); @endphp
                    @foreach ($pedidoGeral->pedidoprodutos as $pedidoprodutos)
                        @if ($pedidoprodutos->valorquarto)
                            @php $adr = $pedidoprodutos->valorquarto->valor_quarto / $rnts; @endphp
                        @endif
                    @endforeach

                    {{ floor($adr * 100) / 100 }} €
                </td>
                <td style="text-align:right;"> {{ $pedidoGeral->user ? $pedidoGeral->user->name : "User Deleted" }} </td>
                <td align="right"> {{ $pedidoGeral->AtsTotalQuarto }} </td>
                <td align="right">{{ $pedidoGeral->AtsTotalGolf }} </td>
                <td align="right">{{ $pedidoGeral->AtsTotalTransfer }} </td>
                <td align="right">{{ $pedidoGeral->AtsTotalCar }} </td>
                <td align="right" data-comentario="total extra ats">{{ $pedidoGeral->AtsTotalExtra }} </td>
                <td align="right">
                    {{ number_format(floor(($pedidoGeral->valor - $pedidoGeral->profit) * 100) / 100, 2, '.' , ',') }}
                </td>
                <td align="right">
                    {{ number_format(floor($pedidoGeral->profit * 100) / 100, 2, '.' , ',') }}
                </td>
            </tr>
            @empty
        @endforelse
    <tfoot>
        <tr>
            <th colspan="2" style="text-align:center">Total:</th>
            <th style="text-align:center;"></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th style="text-align:right;"></th>
            <th style="text-align:right;"></th>
            <th style="text-align:right;"></th>
            <th style="text-align:right;"></th>
            <th style="text-align:right;"></th>
            <th style="text-align:right;"></th>
            <th style="text-align:right;"></th>
        </tr>
    </tfoot>

</table>
