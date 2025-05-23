<?php

namespace App\Services;

use App\PedidoGeral;
use Carbon\Carbon;

class ReportsService
{
  // Notice we remove the type hint so we can accept either type.
  public function getFilteredReports($dateInput, $userId = null)
  {
    // If $dateInput is an array, treat it as a [start, end] tuple.
    if (is_array($dateInput)) {
      $start = Carbon::parse($dateInput[0])->startOfDay()->toDateTimeString();
      $end   = Carbon::parse($dateInput[1])->endOfDay()->toDateTimeString();
    } else {
      $date  = Carbon::parse($dateInput); // if null, defaults to now()
      $start = $date->startOfDay()->toDateTimeString();
      $end   = $date->endOfDay()->toDateTimeString();
    }

    $query = PedidoGeral::query();

    if ($userId !== null) {
      $query->where('user_id', $userId);
    }

    $pedidos = $query->viewWithAllProd([])
      ->customDataFilters(['start' => $start, 'end' => $end])
      ->get();

    // Filter out PedidoGerals whose computed DataFirstServico doesn't fall in range.
    return $pedidos->filter(function ($pedido) use ($start, $end) {
      $serviceDate = $pedido->DataFirstServico;
      return $serviceDate && $serviceDate->between($start, $end);
    });
  }
}