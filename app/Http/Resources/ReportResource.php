<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->pedidoprodutos->count() == 1)
            return $this->fromPedidoProduto(
                $this->pedidoprodutos->first(),
                $this->lead_name,
                $this->user->name
            );

        return [
            'id' => $this->id,
            'clientName' => $this->lead_name,
            'operatorName' => $this->user->name,
            'startDate' => $this->DataFirstServico,
            'supplier' => null,
            'totals' => $this->calculateTotals($request),
            'metrics' => $this->calculateMetrics(),
            'children' => $this->pedidoprodutos->map(function ($p) {
                return $this->fromPedidoProduto(
                    $p,
                    $this->lead_name,
                    $this->user->name
                );
            }),
        ];
    }

    public function fromPedidoProduto($product, $clientName, $operatorName): array
    {
        $type = $product->tipoproduto === 'game' ? 'golf' : $product->tipoproduto;

        return [
            'id' => $product->id,
            'type' => $type,
            'clientName' => $clientName,
            'operatorName' => $operatorName,
            'startDate' => $product->FirstCheckin,
            'supplier' => $product->produto->nome ?? null,
            'totals' => $this->calculatePedidoProdutoTotals($product, $type),
            'metrics' => $this->calculatePedidoProdutoMetrics($product),
        ];
    }

    /**
     * Calculate group metrics.
     *
     * @return array
     */
    private function calculateMetrics(): array
    {
        $rnts = $this->pedidoprodutos
            ->pluck('pedidoquarto')      // each is a Collection or null
            ->flatten()                  // merge all into one flat Collection
            ->sum('rnts');               // sum the rnts column

        $bednight = $this->pedidoprodutos
            ->pluck('pedidoquarto')
            ->flatten()
            ->sum('bednight');

        $players = $this->pedidoprodutos
            ->pluck('pedidogame')
            ->flatten()
            ->sum(function ($game) {
                return $game->people + $game->free;
            });

        $lastValorQuarto = $this->pedidoprodutos
            ->pluck('valorquarto.valor_quarto')  // will give [null, 120.00, ...]
            ->filter()                           // drop any null / falsey
            ->last()                             // take the last non-null value
            ?? 0;

        $adjustedRnts = $rnts == 0 ? 1 : $rnts;
        $adr = $lastValorQuarto !== null ? $lastValorQuarto / $adjustedRnts : 0;
        $adr = floor($adr * 100) / 100.;

        return [
            'rnts' => $rnts,
            'bednight' => $bednight,
            'players' => $players,
            'adr' => $adr,
        ];
    }

    /**
     * Calculate group totals by aggregating each booking's totals.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    private function calculateTotals($request): array
    {
        $calculatedTotals = [
            'quarto' => 0,
            'golf' => 0,
            'transfer' => 0,
            'extras' => 0,
            'kickback' => 0,
            'sum' => $this->valor, // Preserved from the group.
        ];

        foreach ($this->pedidoprodutos as $produto) {
            // Create a BookingResource instance and get its transformed array.
            $bookingData = (new BookingResource($produto, $this->id))->toArray($request);
            $type = $bookingData['type'] ?? null;

            // For quarto, golf or transfer, add its service value.
            if (in_array($type, ['quarto', 'golf', 'transfer'])) {
                $calculatedTotals[$type] += $bookingData['totals'][$type];
            }

            // Extras and kickback are summed across all bookings.
            $calculatedTotals['extras'] += $bookingData['totals']['extras'];
            $calculatedTotals['kickback'] += $bookingData['totals']['kickback'];
        }

        return $calculatedTotals;
    }

    /**
     * Calculate totals for a PedidoProduto booking.
     *
     * @param string $type
     * @return array
     */
    private function calculatePedidoProdutoTotals($product, $type): array
    {
        $rel   = 'valor' . ($type === 'golf' ? 'game' : $type);
        $svc   = $product->$rel->{"valor_{$type}"} ?? 0;
        $extra = $product->$rel->valor_extra         ?? 0;
        $kick  = $product->$rel->kick                ?? 0;

        return [
            $type   => $svc,
            'extras'   => $extra,
            'kickback' => $kick,
            'sum'      => $product->valor,
        ];
    }

    /**
     * Calculate metrics for a PedidoProduto booking.
     *
     * @return array
     */
    private function calculatePedidoProdutoMetrics($product): array
    {
        $rnts     = $product->pedidoquarto->sum('rnts');
        $bednight = $product->pedidoquarto->sum('bednight');
        $players  = $product->pedidogame->sum(function ($g) {
            return $g->people + ($g->free ?? 0);
        });
        $guests   = $product->pedidogame->sum('TotalPax');

        $lastValorQuarto = $product->valorquarto->valor_quarto ?? 0;
        $adr = floor(($lastValorQuarto / max($rnts, 1)) * 100) / 100;

        return compact('rnts', 'bednight', 'players', 'guests', 'adr');
    }
}