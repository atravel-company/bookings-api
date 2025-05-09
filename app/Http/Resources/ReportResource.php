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
        return [
            'id'           => $this->id,
            'clientName'   => $this->lead_name,
            'operatorName' => $this->user->name,
            'startDate'    => $this->DataFirstServico,
            'bookings'     => [
                'totals'  => $this->calculateTotals($request),
                'metrics' => $this->calculateMetrics(),
                'array'   => BookingResource::collection($this->pedidoprodutos),
            ],
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
            ->sum(function($game) {
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
            'rnts'     => $rnts,
            'bednight' => $bednight,
            'players'  => $players,
            'adr'      => $adr,
        ];
    }

    /**
     * Calculate group totals by aggregating each booking's values.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    private function calculateTotals($request): array
    {
        $calculatedTotals = [
            'quarto'   => 0,
            'golf'     => 0,
            'transfer' => 0,
            'extras'   => 0,
            'kickback' => 0,
            'sum'      => $this->valor, // Preserved from the group.
        ];

        foreach ($this->pedidoprodutos as $produto) {
            // Create a BookingResource instance and get its transformed array.
            $bookingData = (new BookingResource($produto))->toArray($request);
            $type = $bookingData['type'] ?? null;

            // For quarto, golf or transfer, add its service value.
            if (in_array($type, ['quarto', 'golf', 'transfer'])) {
                $calculatedTotals[$type] += $bookingData['values']['service'];
            }

            // Extras and kickback are summed across all bookings.
            $calculatedTotals['extras']   += $bookingData['values']['extras'];
            $calculatedTotals['kickback'] += $bookingData['values']['kickback'];
        }

        return $calculatedTotals;
    }
}