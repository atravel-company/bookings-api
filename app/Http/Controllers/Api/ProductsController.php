<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Produto;

class ProductsController extends Controller
{
  /**
   * Display a listing of products.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function index()
  {
    $Produtos = Produto::select('id', 'nome')
      ->orderBy('nome')
      ->get();

    return response()->json(ProductResource::collection($Produtos));
  }
}