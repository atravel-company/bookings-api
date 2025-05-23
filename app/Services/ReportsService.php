<?php

namespace App\Services;

use App\PedidoGeral;
use Carbon\Carbon;

class ReportsService
{
  // Notice we remove the type hint so we can accept either type.
  public function getFilteredReports($dateInput, $users = null, $products = null)
  {
    // If $dateInput is an array, treat it as a [start, end] tuple.
    if (is_array($dateInput)) {
      $start = Carbon::parse($dateInput[0])->startOfDay()->toDateTimeString();
      $end = Carbon::parse($dateInput[1])->endOfDay()->toDateTimeString();
    } else {
      $date = Carbon::parse($dateInput); // if null, defaults to now()
      $start = $date->startOfDay()->toDateTimeString();
      $end = $date->endOfDay()->toDateTimeString();
    }

    $query = PedidoGeral::query();

    if ($users !== null) {
      if (is_array($users)) {
        $query->whereIn('user_id', $users);
      } else {
        $query->where('user_id', $users);
      }
    }

    if ($products !== null) {
      $query->whereHas('pedidoprodutos', function ($q) use ($products) {
        if (is_array($products) && !empty($products)) {
          $q->whereIn('produto_id', $products);
        } elseif (!is_array($products) && $products) {
          $q->where('produto_id', $products);
        }
      });
    }


    $pedidos = $query->viewWithAllProd([], $products)
      ->customDataFilters(['start' => $start, 'end' => $end])
      ->get();

    // Filter out PedidoGerals whose computed DataFirstServico doesn't fall in range.
    return $pedidos->filter(function ($pedido) use ($start, $end) {
      $serviceDate = Carbon::parse($pedido->DataFirstServico);
      return $serviceDate && $serviceDate->between($start, $end);
    });
  }
}