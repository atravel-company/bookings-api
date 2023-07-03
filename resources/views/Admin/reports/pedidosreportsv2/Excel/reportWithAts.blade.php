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
            <th style="background-color:#ffdd97; text-align:center; border-left:1px solid #000;" colspan="9">
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
            <th style="background-color:#ffdd97;">TOTAL da reserva</th>
            <th style="background-color:#1dcb4a;">PROFIT</th>
            <th style="background-color:#1dcb4a;">TOTAL do PROFIT</th>
        </tr>
    </thead>
    <tbody>
        @php
        $pedidos = $pedidos->sortBy(function ($value) {
            return $value->DataFirstServico . $value->lead_name;
        });
        @endphp
        @foreach ($pedidos as $pedidoGeral)
            @php
                $totalValorReserva = 0;
                $totalProfitReserva = 0;
                $numItems = count($pedidoGeral->pedidoprodutos);
                $i = 0;
            @endphp
            @foreach ($pedidoGeral->pedidoprodutos->sortBy("FirstCheckin") as $pedidoproduto)
                @php
                    $rnts = 0;
                    $bednight = 0;
                    $valorPedidoProduto = 0;

                    $valores = [
                        'valortransfer' => 0,
                        'valorcar' => 0,
                        'valorgame' => 0,
                        'valorticket' => 0,
                        'valorquarto' => 0,
                    ];

                    $pedidoProdutoRelacaoPedido = 'pedido' . $pedidoproduto->tipoproduto;
                    $pedidoProdutoRelacaoValor = 'valor' . $pedidoproduto->tipoproduto;
                    $field = 'valor_';
                    $field .= $pedidoproduto->tipoproduto == 'game' ? 'golf' : $pedidoproduto->tipoproduto;
                    $totalPedidoProduto = 0;
                    $totalProfitProduto = 0;
                    
                    if ($pedidoproduto->$pedidoProdutoRelacaoValor) {
                        $totalProfitProduto = $pedidoproduto->$pedidoProdutoRelacaoValor->profit;
                        $valores[$pedidoProdutoRelacaoValor] = $pedidoproduto->$pedidoProdutoRelacaoValor->$field - $totalProfitProduto;
                        $totalPedidoProduto = $pedidoproduto->$pedidoProdutoRelacaoValor->total - $totalProfitProduto;
                        $totalValorReserva += $totalPedidoProduto;
                        $totalProfitReserva += $totalProfitProduto;
                    }

                    $datapedidoProduto = $pedidoproduto->$pedidoProdutoRelacaoPedido
                        ->sortBy(function ($p) {
                            if ($p->data) {
                                return \Carbon\Carbon::parse($p->data . ' ' . $p->hora);
                            }
                            if ($p->checkin) {
                                return \Carbon\Carbon::parse($p->checkin);
                            }
                            if ($p->pickup_data) {
                                return \Carbon\Carbon::parse($p->pickup_data . ' ' . $p->pickup_hora);
                            }
                            return $p->created_at;
                        })
                        ->first();

                    $cor = '';
                    if (++$i === $numItems) {
                        $cor = 'red';
                    }
                @endphp

                @forelse($pedidoproduto->pedidoquarto as $quarto)
                    @php $rnts += $quarto->rnts; @endphp
                    @php $bednight += $quarto->bednight; @endphp
                @empty
                    @php $rnts += 0; @endphp
                @endforelse

                <tr>
                    <td style="text-align:center"> {{ \Carbon\Carbon::parse($pedidoGeral->DataFirstServico)->format('d/m/Y') }} </td>
                    <td style="text-align:right;"> {{ $pedidoGeral->lead_name }} </td>
                    <td style="text-align: center">
                        {{ $rnts }}
                    </td>
                    <td style="text-align: center">
                        {{ $bednight }}
                    </td>
                    <td style="text-align: center">
                        @php $adr = 0; @endphp
                        @php $rnts = ($rnts == 0 ? 1 : $rnts); @endphp
                        @if ($pedidoproduto->valorquarto)
                            @php $adr = $pedidoproduto->valorquarto->valor_quarto / $rnts; @endphp
                        @endif
                        {{ floor($adr * 100) / 100 }}
                    </td>
                    <td style="text-align:right;"> {{ $pedidoGeral->user ? $pedidoGeral->user->name : "User Deleted" }} </td>
                    <td style="text-align:right;"> {{ $valores['valorquarto'] }} </td>
                    <td style="text-align:right;"> {{ $valores['valorgame'] }} </td>
                    <td style="text-align:right;"> {{ $valores['valortransfer'] }}</td>
                    <td style="text-align:right;"> {{ $valores['valorcar'] }} </td>
                    <td align="right" data-comentario="total extra ats">{{ $pedidoGeral->AtsTotalExtra }} </td>
                    <td align="right">
                    {{ number_format($totalPedidoProduto, 2, ',', '.') }}
                    </td>
                    <td style="text-align:right;color:{{ $cor }}" data-comentario="coluna TOTAL da reserva">
                        {{ number_format($totalValorReserva, 2, ',', '.') }}
                    </td>
                    <td style="text-align:right;">
                        {{ number_format($totalProfitProduto, 2, '.' , ',') }}
                    </td>
                    <td style="text-align:right;color:{{ $cor }}">
                        {{ number_format($totalProfitReserva, 2, '.' , ',') }}
                    </td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
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
