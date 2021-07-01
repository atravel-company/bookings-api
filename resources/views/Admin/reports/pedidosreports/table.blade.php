 <thead>
    <tr>
        <th orderable=false searchable=false rowspan="2" text-align="center">#</th>
        <th orderable=false rowspan="2" class="hidden">id</th>
        <th rowspan="2">Arr</th>
        <th rowspan="2">Client</th>
        <th rowspan="2">RNTS</th>
        <th rowspan="2">Bed N.</th>
        <th rowspan="2">ADR</th>
        <th rowspan="2">Supplier</th>
        <th rowspan="2">T.OPER</th>
        <th style="background-color:yellow; text-align:center; border-left:1px solid #000;" colspan="10">Money Received
        </th>
        @if(Route::current()->getName() == "pedidos.reports.index.ats" or Route::current()->getName() ==
        "pedidos.reports.buscar.ats")
        <th style="background-color:yellow; text-align:center; color:red;" colspan="7">Money Paid</th>
        @endif
    </tr>
    <tr>
        <th style="background-color:#0078ff;">ROOM</th>
        <th style="background-color:#1dcb4a;">GOLF</th>
        <th style="background-color:yellow;">TRA</th>
        <th style="background-color:#ffdd97;">CAR</th>
        <th style="background-color:#2ea7ff;">EXTRAS</th>
        <th style="background-color:#1259b4;">K.BACK</th>
        <th style="background-color:yellow;">TOTAL</th>
        <th style="background-color:yellow;">V.PAID</th>
        <th style="background-color:yellow;">UNPAID</th>
        @if(Route::current()->getName() == "pedidos.reports.index.ats" or Route::current()->getName() ==
        "pedidos.reports.buscar.ats")
        <th class="ats_hidden" style="background-color:#0078ff;">ROOM</th>
        <th class="ats_hidden" style="background-color:#1dcb4a;">GOLF</th>
        <th class="ats_hidden" style="background-color:yellow;">TRA</th>
        <th class="ats_hidden" style="background-color:#ffdd97;">CAR</th>
        <th class="ats_hidden" style="background-color:#2ea7ff;">EXTRAS</th>
        <th class="ats_hidden" style="background-color:yellow;">TOTAL</th>
        <th class="ats_hidden" style="background-color:#1dcb4a;">PROFIT</th>
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
    $ValorTotalTotal += floatval(preg_replace('/[^\d.]/', '', ($pedidogeral->valor)));
    $ValorTotalPago += floatval(preg_replace('/[^\d.]/', '', ($pedidogeral->TotalPagamento)));
    $ValorTotalNaoPago = $ValorTotalPago - $ValorTotalTotal;

    @endphp

    <tr>
        <td class="hidden" data-status="{{$pedidogeral->status}}">{{ $pedidogeral->id }}</td>
        <td> {{ \Carbon\Carbon::parse($pedidogeral->DataFirstServico)->format('d/m/Y') }} </td>
        <td style="text-align:left;"> {{ $pedidogeral->lead_name }} </td>
        <td>
            @php $rnts = 0; @endphp
            @foreach($pedidogeral->pedidoprodutos as $pedidoprodutos)
            @forelse($pedidoprodutos->pedidoquarto as $quarto)
            @php $rnts += $quarto->rnts; @endphp
            @empty
            @php $rnts += 0; @endphp
            @endforelse
            @endforeach
            {{ $rnts }}
        </td>
        <td>
            @php $bednight = 0; @endphp
            @foreach($pedidogeral->pedidoprodutos as $pedidoprodutos)
            @forelse($pedidoprodutos->pedidoquarto as $quarto)
            @php $bednight += $quarto->bednight; @endphp
            @empty
            @php $bednight += 0; @endphp
            @endforelse
            @endforeach
            {{ $bednight }}
        </td>
        <td>
            @php $adr = 0; @endphp
            @php $rnts = ($rnts == 0 ? 1 : $rnts); @endphp
            @foreach($pedidogeral->pedidoprodutos as $pedidoprodutos)
            @if($pedidoprodutos->valorquarto)
            @php $adr = $pedidoprodutos->valorquarto->valor_quarto / $rnts; @endphp
            @endif
            @endforeach

            {{ floor($adr * 100)/100 }}
        </td>
        <td style="text-align:left;">

            @if(request('hotel') !== '0' and request('hotel') !== null )

            {{ $pedidogeral->pedidoprodutos()->where('produto_id', request('hotel')  )->first()->produto->nome }}
            @else
            {{ $pedidogeral->pedidoprodutos()->first()->produto->nome }}
            @endif

        </td>
        {{-- {{ number_format(floor($pedidogeral->valortotalquarto * 100)/100,2,",",".") }} --}}
        <td style="text-align:left;"> {{ $pedidogeral->user->name }} </td>
        <td style="text-align:right;"> {{ number_format(str_replace(',','',$pedidogeral->valortotalquarto),2,",",".")}} </td>
        <td style="text-align:right;"> {{ number_format(str_replace(',','',$pedidogeral->valortotalgolf),2,",",".")  }} </td>
        <td style="text-align:right;"> {{ number_format(str_replace(',','',$pedidogeral->valortotaltransfer),2,",",".")  }}</td>
        <td style="text-align:right;"> {{ number_format(str_replace(',','',$pedidogeral->valortotalcar),2,",",".")  }} </td>
        <td style="text-align:right;"> {{ number_format(str_replace(',','',$pedidogeral->valortotalextras),2,",",".")  }} </td>
        <td style="text-align:right;"> {{ number_format(str_replace(',','',$pedidogeral->ValorTotalKickBack),2,",",".")  }} </td>
        <td style="background-color:yellow;text-align:right;" data-comentario="coluna TOTAL">
            {{ number_format(floor($pedidogeral->valor * 100)/100, 2, ",", ".") }} </td>
        <td style="background-color:yellow;text-align:right;" data-comentario="coluna vPaid">
            {{ $pedidogeral->TotalPagamento }} </td>
        <td style="background-color:yellow;text-align:right;" data-comentario="coluna unpaid">
            @if($pedidogeral->ValorNaoPago > 0)
            <span style='color:green !important; font-weight: 600 '> {{$pedidogeral->ValorNaoPago }} </span>
            @elseif($pedidogeral->ValorNaoPago < 0) <span style='color:red !important; font-weight: 600 '>
                {{$pedidogeral->ValorNaoPago }}
                </span>
                @else
                {{ $pedidogeral->ValorNaoPago }}
                @endif
        </td>

        @if(Route::current()->getName() == "pedidos.reports.index.ats" or Route::current()->getName() ==
        "pedidos.reports.buscar.ats")

        <td align="right"> {{ $pedidogeral->AtsTotalQuarto }} </td>
        <td align="right">{{ $pedidogeral->AtsTotalGolf }} </td>
        <td align="right">{{ $pedidogeral->AtsTotalTransfer }} </td>
        <td align="right">{{ $pedidogeral->AtsTotalCar }} </td>
        <td align="right" data-comentario="total extra ats">{{ $pedidogeral->AtsTotalExtra }} </td>
        <td align="right">
            {{ number_format(floor(($pedidogeral->valor - $pedidogeral->profit) * 100)/100, 2 , ",", ".")  }} </td>
        <td align="right">{{ number_format( floor($pedidogeral->profit*100)/100 , 2 , ",",".")  }} </td>

        @endif

    </tr>
    @empty

    @endforelse
</tbody>
<tfoot>
    <tr>
        <th colspan="3" style="text-align:right">Total:</th>
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
        <th style="text-align:right;" id="TableValorTotalTotal" data-value="{{ number_format( floor($ValorTotalTotal * 100)/100 , 2 , ",", ".") }}"></th>
        <th style="text-align:right;" id="ValorTotalPago" data-value="{{ number_format( floor($ValorTotalPago * 100)/100 , 2 , ",", ".") }}"></th>
        <th style="text-align:right;" id="TableTotalValorNaoPago" data-value=" {{ number_format( floor($ValorTotalNaoPago * 100)/100 , 2 , ",", ".") }}"></th>


        @if(Route::current()->getName() == "pedidos.reports.index.ats" or Route::current()->getName() ==
        "pedidos.reports.buscar.ats")
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
