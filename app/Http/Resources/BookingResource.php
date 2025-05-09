<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray($request)
    {
        $type     = $this->tipoproduto === 'game' ? 'golf' : $this->tipoproduto;
        $checkin  = $this->FirstCheckin;
        $supplier = $this->produto->nome ?? null;

        // dynamically pick the right relation name:
        $rel = 'valor' . ($type === 'transfer' ? 'transfer' : $type);

        $svc   = $this->$rel->{"valor_{$type}"}   ?? 0;
        $extra = $this->$rel->valor_extra       ?? 0;
        $kick  = $this->$rel->kick              ?? 0;

        return [
            'type'     => $type,
            'checkin'  => $checkin,
            'supplier' => $supplier,
            'values'   => [
                'service'  => $svc,
                'extras'   => $extra,
                'kickback' => $kick,
                'total'    => $this->valor,
            ],
            'metrics'  => $this->calculateMetrics(),
        ];
    }


    /**
     * Calculate metrics for this booking.
     *
     * @return array
     */
    private function calculateMetrics(): array
    {
        $rnts     = $this->pedidoquarto->sum('rnts');
        $bednight = $this->pedidoquarto->sum('bednight');
        $players  = $this->pedidogame->sum(function($g) { 
            return $g->people + ($g->free ?? 0); 
        });
        $guests   = $this->pedidogame->sum('TotalPax');

        $lastValorQuarto = $this->valorquarto->valor_quarto ?? 0;
        $adr = floor(($lastValorQuarto / max($rnts, 1)) * 100) / 100;

        return compact('rnts','bednight','players','guests','adr');
    }
}
