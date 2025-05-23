<thead>
    <tr>
        <th orderable=false searchable=false rowspan="2" text-align="center">#</th>
        <th orderable=false rowspan="2" class="hidden">id</th>
        <th rowspan="2" style="text-align:center;vertical-align:inherit">Arr</th>
        <th rowspan="2" style="text-align:center;vertical-align:inherit">Client</th>
        <th rowspan="2" style="text-align:center;vertical-align:inherit">RNTS</th>
        <th rowspan="2" style="text-align:center;vertical-align:inherit">Bed N.</th>
        <th rowspan="2" style="text-align:center;vertical-align:inherit">Players N.</th>
        <th rowspan="2" style="text-align:center;vertical-align:inherit">ADR</th>
        <th rowspan="2" style="text-align:center;vertical-align:inherit">T.OPER</th>
        <th style="background-color:yellow; text-align:center; border-left:1px solid #000;" colspan="09">Money Received
        </th>
        @if (Route::current()->getName() == 'pedidos.v2.reports.index.ats' or Route::current()->getName() == 'pedidos.v2.reports.buscar.ats')
            <th style="background-color:yellow; text-align:center; color:red;" colspan="7">Money Paid</th>
        @endif
    </tr>
    <tr>
        <th style="background-color:#0078ff;text-align:center;vertical-align:inherit">ROOM</th>
        <th style="background-color:#1dcb4a;text-align:center;vertical-align:inherit">GOLF</th>
        <th style="background-color:yellow;text-align:center;vertical-align:inherit">TRA</th>
        <th style="background-color:#ffdd97;text-align:center;vertical-align:inherit">CAR</th>
        <th style="background-color:#2ea7ff;text-align:center;vertical-align:inherit">EXTRAS</th>
        <th style="background-color:#1259b4;text-align:center;vertical-align:inherit">K.BACK</th>
        <th style="background-color:yellow;text-align:center;vertical-align:inherit">TOTAL</th>
        <th style="background-color:yellow;text-align:center;vertical-align:inherit">V.PAID</th>
        <th style="background-color:yellow;text-align:center;vertical-align:inherit">UNPAID</th>
        @if (Route::current()->getName() == 'pedidos.v2.reports.index.ats' or Route::current()->getName() == 'pedidos.v2.reports.buscar.ats')
            <th class="ats_hidden" style="background-color:#0078ff;text-align:center;vertical-align:inherit">ROOM</th>
            <th class="ats_hidden" style="background-color:#1dcb4a;text-align:center;vertical-align:inherit">GOLF</th>
            <th class="ats_hidden" style="background-color:yellow;text-align:center;vertical-align:inherit">TRA</th>
            <th class="ats_hidden" style="background-color:#ffdd97;text-align:center;vertical-align:inherit">CAR</th>
            <th class="ats_hidden" style="background-color:#2ea7ff;text-align:center;vertical-align:inherit">EXTRAS</th>
            <th class="ats_hidden" style="background-color:yellow;text-align:center;vertical-align:inherit">TOTAL</th>
            <th class="ats_hidden" style="background-color:#1dcb4a;text-align:center;vertical-align:inherit">PROFIT</th>
        @endif
    </tr>
</thead>
<tbody>
    @php
        $ValorTotalTotal = 0;
        $ValorTotalPago = 0;
        $TotalValorNaoPago = 0;
        $ValorTotalNaoPago = 0;
    @endphp

    @forelse($pedidos as $pedidogeral)

        @php
            $ValorTotalTotal += floatval(preg_replace('/[^\d.]/', '', $pedidogeral->valor));
            $ValorTotalPago += floatval(preg_replace('/[^\d.]/', '', $pedidogeral->TotalPagamento));
            $ValorTotalNaoPago = $ValorTotalPago - $ValorTotalTotal;
        @endphp

        <tr>
            <td class="hidden" data-status="{{ $pedidogeral->status }}">{{ $pedidogeral->id }}</td>
            <td style="text-align:center"> {{ $pedidogeral->DataFirstServico->format('d/m/Y') }} </td>
            <td style="text-align:right;"> {{ $pedidogeral->lead_name }} </td>
            <td  style="text-align:center;">
                @php $rnts = 0; @endphp
                @foreach ($pedidogeral->pedidoprodutos as $pedidoprodutos)
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
                @foreach ($pedidogeral->pedidoprodutos as $pedidoprodutos)
                    @forelse($pedidoprodutos->pedidoquarto as $quarto)
                        @php $bednight += $quarto->bednight; @endphp
                    @empty
                        @php $bednight += 0; @endphp
                    @endforelse
                @endforeach
                {{ $bednight }}
            </td>
            <td style="text-align:center;">
               @php $players = 0; @endphp
               @foreach ($pedidogeral->pedidoprodutos as $pedidoprodutos)
                  @forelse($pedidoprodutos->pedidogame as $game)
                      @php $players += $game->people + $game->free; @endphp
                  @empty
                      @php $players += 0; @endphp
                  @endforelse
               @endforeach
               {{ $players }}
           </td>
            <td style="text-align:right;">
                @php $adr = 0; @endphp
                @php $rnts = ($rnts == 0 ? 1 : $rnts); @endphp
                @foreach ($pedidogeral->pedidoprodutos as $pedidoprodutos)
                    @if ($pedidoprodutos->valorquarto)
                        @php $adr = $pedidoprodutos->valorquarto->valor_quarto / $rnts; @endphp
                    @endif
                @endforeach

                {{ floor($adr * 100) / 100 }} €
            </td>
            <td style="text-align:right;"> {{ $pedidogeral->user ? $pedidogeral->user->name : "User Deleted" }} </td>
            <td style="text-align:right;">
                {{ number_format(str_replace(',', '', $pedidogeral->pedidoprodutos->sum("valorquarto.valor_quarto") ), 2, '.' , ',') }} </td>
            <td style="text-align:right;">
                {{ number_format(str_replace(',', '', $pedidogeral->pedidoprodutos->sum("valorgame.valor_golf") ), 2, '.' , ',') }} </td>
            <td style="text-align:right;">
                {{ number_format(str_replace(',', '', $pedidogeral->pedidoprodutos->sum("valortransfer.valor_transfer") ), 2, '.' , ',') }}</td>
            <td style="text-align:right;">
                {{ number_format(str_replace(',', '', $pedidogeral->pedidoprodutos->sum("valorcar.valor_car") ), 2, '.' , ',') }} </td>
            <td style="text-align:right;">
                {{ number_format(str_replace(',', '', $pedidogeral->valortotalextras), 2, '.' , ',') }} </td>
            <td style="text-align:right;">
                {{ number_format(str_replace(',', '', $pedidogeral->ValorTotalKickBack), 2, '.' , ',') }} </td>
            <td style="background-color:yellow;text-align:right;" data-comentario="coluna TOTAL">
                {{ number_format(floor($pedidogeral->valor * 100) / 100, 2, '.' , ',') }} </td>
            <td style="background-color:yellow;text-align:right;" data-comentario="coluna vPaid">
                {{ $pedidogeral->TotalPagamento }}
            </td>

            @if ($pedidogeral->ValorNaoPago > 0)
                <td style="background-color:yellow;text-align:right;color:green !important; font-weight: 600 "
                    data-comentario="coluna unpaid">
                    {{ $pedidogeral->ValorNaoPago }}
                </td>
            @elseif($pedidogeral->ValorNaoPago < 0) <td
                    style="background-color:yellow;text-align:right;color:red !important; font-weight: 600 "
                    data-comentario="coluna unpaid">
                    {{ $pedidogeral->ValorNaoPago }}
                    </td>
                @else
                    <td style="background-color:yellow;text-align:right;" data-comentario="coluna unpaid">
                        {{ $pedidogeral->ValorNaoPago }}
                    </td>
            @endif

            @if (Route::current()->getName() == 'pedidos.v2.reports.index.ats' or Route::current()->getName() == 'pedidos.v2.reports.buscar.ats')
                <td align="right"> {{ $pedidogeral->AtsTotalQuarto }} </td>
                <td align="right">{{ $pedidogeral->AtsTotalGolf }} </td>
                <td align="right">{{ $pedidogeral->AtsTotalTransfer }} </td>
                <td align="right">{{ $pedidogeral->AtsTotalCar }} </td>
                <td align="right" data-comentario="total extra ats">{{ $pedidogeral->AtsTotalExtra }} </td>
                <td align="right">
                    {{ number_format(floor(($pedidogeral->valor - $pedidogeral->profit) * 100) / 100, 2, '.' , ',') }}
                </td>
                <td align="right">
                    {{ number_format(floor($pedidogeral->profit * 100) / 100, 2, '.' , ',') }}
                </td>
            @endif
        </tr>
        @empty

        @endforelse
    </tbody>
    <tfoot>
           <tr>
               <th colspan="3" style="text-align:right">Total:</th>
               <th style="text-align:center;"></th>
               <th style="text-align:center;"></th>
               <th style="text-align:center;"></th>
               <th style="text-align:right;"></th>
               <th></th>
               <th style="text-align:right;"></th>
               <th style="text-align:right;"></th>
               <th style="text-align:right;"></th>
               <th style="text-align:right;"></th>
               <th style="text-align:right;"></th>
               <th style="text-align:right;"></th>
               <th style="text-align:right;" id="TableValorTotalTotal"
                   data-value="{{ number_format(floor($ValorTotalTotal * 100) / 100, 2, '.' , ',') }}"></th>
               <th style="text-align:right;" id="ValorTotalPago"
                   data-value="{{ number_format(floor($ValorTotalPago * 100) / 100, 2, '.' , ',') }}"></th>
               <th style="text-align:right;" id="TableTotalValorNaoPago"
                   data-value=" {{ number_format(floor($ValorTotalNaoPago * 100) / 100, 2, '.' , ',') }}"></th>
               @if (Route::current()->getName() == 'pedidos.v2.reports.index.ats' or Route::current()->getName() == 'pedidos.v2.reports.buscar.ats')
                   <th id="ats_profit" data-condition="true" style="text-align:right;"></th>
                   <th style="text-align:right;"></th>
                   <th style="text-align:right;"></th>
                   <th style="text-align:right;"></th>
                   <th style="text-align:right;"></th>
                   <th style="text-align:right;"></th>
                   <th style="text-align:right;"></th>
               @endif
           </tr>
    </tfoot>
