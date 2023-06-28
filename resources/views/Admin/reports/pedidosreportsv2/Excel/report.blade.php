<table border='1' class="table table-striped display compact table-bordered table-hover table-responsive nowrap"
    style="width:100%; border-collapse: collapse!important;">
    <thead>
        <tr>
            <th rowspan="2">Arr</th>
            <th rowspan="2">Client</th>
            <th rowspan="2">RNTS</th>
            <th rowspan="2">Bed N.</th>
            <th rowspan="2">ADR</th>
            <th rowspan="2">Supplier</th>
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
            <th style="background-color:#1259b4;">K.BACK</th>
            <th style="background-color:#ffdd97;">TOTAL</th>
        </tr>
    </thead>
    <tbody>
        @php
            $pedidos = $pedidos->sortBy(function ($value) {
                return $value->DataFirstServico . $value->lead_name;
            });
        @endphp
        @foreach ($pedidos as $pedidogeral)
            @foreach ($pedidogeral->pedidoprodutos->sortBy("produto_id") as $pedidoproduto)
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

                    if ($pedidoproduto->$pedidoProdutoRelacaoValor) {
                        $valores[$pedidoProdutoRelacaoValor] = $pedidoproduto->$pedidoProdutoRelacaoValor->$field;
                        $totalPedidoProduto = $pedidoproduto->$pedidoProdutoRelacaoValor->total;
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
                @endphp

                @forelse($pedidoproduto->pedidoquarto as $quarto)
                    @php $rnts += $quarto->rnts; @endphp
                    @php $bednight += $quarto->bednight; @endphp
                @empty
                    @php $rnts += 0; @endphp
                @endforelse

                <tr>
                    <td>
                        @if (isset($datapedidoProduto->data))
                            {{ \Carbon\Carbon::parse($datapedidoProduto->data)->format('d/m/Y') }}
                        @elseif(isset($datapedidoProduto->pickup_data))
                            {{ \Carbon\Carbon::parse($datapedidoProduto->pickup_data)->format('d/m/Y') }}
                        @elseif(isset($datapedidoProduto->checkin))
                            {{ \Carbon\Carbon::parse($datapedidoProduto->checkin)->format('d/m/Y') }}
                        @endif
                    </td>
                    <td style="white-space:nowrap; padding: 0 5px 0 3px;">{{ $pedidogeral->lead_name }}</td>
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
                    <td style="white-space:nowrap; padding: 0 5px 0 3px;">
                        {{ $pedidoproduto->produto->nome }}
                    </td>
                    <td style="white-space:nowrap; padding: 0 5px 0 3px;"> {{ $pedidogeral->user->name }}
                    </td>
                    <td style="text-align:right;"> {{ $valores['valorquarto'] }} </td>
                    <td style="text-align:right;"> {{ $valores['valorgame'] }} </td>
                    <td style="text-align:right;"> {{ $valores['valortransfer'] }}</td>
                    <td style="text-align:right;"> {{ $valores['valorcar'] }} </td>
                    <td style="text-align:right;"> {{ $pedidoproduto->extras->sum('total') }} </td>
                    <td style="text-align:right;"> {{ $pedidogeral->ValorTotalKickBack }} </td>
                    <td style="text-align:right;" data-comentario="coluna TOTAL">
                        {{ number_format($totalPedidoProduto, 2, ',', '.') }}
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
