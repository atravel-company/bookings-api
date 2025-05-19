<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SupplierResource;
use Illuminate\Http\Request;
use App\Supplier;

class SuppliersController extends Controller
{
  /**
   * Display a listing of the suppliers.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function index()
  {
    $suppliers = Supplier::select('id', 'name')
      ->orderBy('name')
      ->get();

    return response()->json(SupplierResource::collection($suppliers));
  }
}