<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller
{
  /**
   * Display a listing of users.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function index()
  {
    $users = User::select('id', 'name')
      ->orderBy('name')
      ->get();

    return response()->json(UserResource::collection($users));
  }
}