<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Calculate the RNTS and Bednight totals.
        $rnts = 0;
        $bednight = 0;
        foreach ($this->pedidoprodutos as $produto) {
            if (isset($produto->pedidoquarto)) {
                foreach ($produto->pedidoquarto as $quarto) {
                    $rnts += $quarto->rnts;
                    $bednight += $quarto->bednight;
                }
            }
        }

        // Calculate Players Quantity.
        $players = 0;
        foreach ($this->pedidoprodutos as $produto) {
            if (isset($produto->pedidogame)) {
                foreach ($produto->pedidogame as $game) {
                    $players += $game->people + $game->free;
                }
            }
        }

        // Calculate ADR.
        // If RNTS is zero, make it 1 to avoid division by zero.
        $adjustedRnts = ($rnts == 0 ? 1 : $rnts);
        $adr = 0;
        foreach ($this->pedidoprodutos as $produto) {
            if (isset($produto->valorquarto) && $produto->valorquarto) {
                // Override ADR if multiple exist - this follows the original behavior.
                $adr = $produto->valorquarto->valor_quarto / $adjustedRnts;
            }
        }
        // Format the ADR to 2 decimal places.
        $adr = floor($adr * 100) / 100;

        return [
            'id' => $this->id,
            'clientName' => $this->lead_name,
            'operatorName' => $this->user->name,
            'startDate' => $this->DataFirstServico,
            'total' => $this->valor,
            'productsTotals' => [
                'quarto' => $this->valortotalquarto,
                'golf' => $this->valortotalgolf,
                'transfer' => $this->valortotaltransfer,
                'car' => $this->valortotalcar,
                'extras' => $this->valortotalextras,
                'kickback' => $this->valortotalkickback,
            ],
            'rnts' => $rnts,
            'bednight' => $bednight,
            'players' => $players,
            'adr' => $adr,
        ];
    }
}