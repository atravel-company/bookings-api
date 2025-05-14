<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray($request)
    {
        $children = $this->pedidoprodutos->map(function ($item) {
            return $this->formatItem($item, $this->lead_name, $this->user->name);
        });

        if ($children->count() === 1) {
            return $children->first();
        }

        // Sum up child totals and metrics
        $groupTotals = $children->pluck('totals')
            ->reduce(function ($carry, $totals) {
                foreach ($totals as $key => $value) {
                    $carry[$key] = ($carry[$key] ?? 0) + $value;
                }
                return $carry;
            }, []);

        // Preserve root 'sum' instead of summing child sums
        $groupTotals['sum'] = $this->valor;

        $groupMetrics = $children->pluck('metrics')
            ->reduce(function ($carry, $metrics) {
                foreach ($metrics as $key => $value) {
                    $carry[$key] = ($carry[$key] ?? 0) + $value;
                }
                return $carry;
            }, []);

        // Recalculate adr from summed metrics if needed (override sum of adr)
        if (!empty($groupMetrics['rnts']) && $groupMetrics['rnts'] > 0) {
            $groupMetrics['adr'] = floor(($groupMetrics['adr'] * $groupMetrics['rnts']) / $groupMetrics['rnts'] * 100) / 100;
        }

        return [
            'id' => $this->id,
            'startDate' => $this->DataFirstServico,
            'clientName' => $this->lead_name,
            'operatorName' => $this->user->name,
            'supplierName' => null,
            'totals' => $groupTotals,
            'metrics' => $groupMetrics,
            'children' => $children->all(),
        ];
    }

    protected function formatItem($product, string $client, string $operator): array
    {
        $type = $product->tipoproduto === 'game' ? 'golf' : $product->tipoproduto;

        return [
            'id' => $product->id,
            'type' => $type,
            'clientName' => $client,
            'operatorName' => $operator,
            'startDate' => $product->FirstCheckin,
            'supplier' => $product->produto->nome ?? null,
            'totals' => $this->calculateBookingTotals($product, $type),
            'metrics' => $this->calculateBookingMetrics($product),
        ];
    }

    private function calculateBookingTotals($product, string $type): array
    {
        $rel = 'valor' . ($type === 'golf' ? 'game' : $type);
        $svc = $product->$rel->{"valor_{$type}"} ?? 0;
        $extra = $product->$rel->valor_extra ?? 0;
        $kick = $product->$rel->kick ?? 0;

        return [
            $type => $svc,
            'extras' => $extra,
            'kickback' => $kick,
            'sum' => $product->valor,
        ];
    }

    private function calculateBookingMetrics($product): array
    {
        $rnts = $product->pedidoquarto->sum('rnts');
        $bednight = $product->pedidoquarto->sum('bednight');
        $players = $product->pedidogame->sum(function ($g) {
            return ($g->people ?? 0) + ($g->free ?? 0);
        });
        $guests = $product->pedidogame->sum('TotalPax');

        $lastValor = $product->valorquarto->valor_quarto ?? 0;
        $adr = $rnts > 0 ? floor(($lastValor / $rnts) * 100) / 100 : 0;

        return compact('rnts', 'bednight', 'players', 'guests', 'adr');
    }
}
