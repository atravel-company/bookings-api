<?php

namespace App\Http\Controllers;

use App\PedidoGeral;
use App\Produto;
use App\Supplier;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;


class PedidosReportsV2Controller extends Controller
{

    public function index(Request $request)
    {
        $produtos = Produto::orderBy('nome')->get();
        $utilizadores = User::orderBy('name')->get();
        $suppliers = Supplier::get();
        $request->merge(['start' =>  Carbon::now()->format("Y-m-d")]);
        $pedidos = PedidoGeral::ViewWithAllProd($request->all());

        return view('Admin.reports.pedidosreportsv2.main', [
            'pedidos' => $pedidos,
            'suppliers' => $suppliers,
            'utilizadores' => $utilizadores,
            'produtos' => $produtos
        ]);
    }

    public function applyFilter(Request $request)
    {
        try {

            $produtos = Produto::orderBy('nome')->get();
            $utilizadores = User::orderBy('name')->get();
            $suppliers = Supplier::get();

            $pedidos = PedidoGeral::ViewWithAllProd($request->all());


            if ($request->has('pedidoid') and $request->get('pedidoid') !== null) {
                $pedidos->where('id', $request->get('pedidoid'));
            }

            if ($request->has('client') and $request->get('client') !== null) {
                $pedidos->where('lead_name', 'like', '%' . $request->get('client') . "%");
            }

            if ($request->has('hotel') and $request->get('hotel') !== null and $request->get('hotel') !== '0') {
                $pedidos = $pedidos->whereHas('pedidoprodutos', function ($q) use ($request) {
                    $q->where('produto_id', $request->get('hotel'));
                });
            }

            if ($request->has('operator') and $request->get('operator') !== null and $request->get('operator') !== '0') {
                $pedidos = $pedidos->whereHas('user', function ($q) use ($request) {
                    $q->where('id', $request->get('operator'));
                });
            }

            $pedidos = $pedidos->where('status', '!=', 'Cancelled');

            $pedidos = $pedidos->get()->filter(function ($value, $key) {
                return $value->status != 'Cancelled';
            });


            if ($request->get("start") != null and $request->get("end") == null) {
                $pedidos = $pedidos->filter(function ($value, $key) use ($request) {
                    $data = $value['DataFirstServico'];

                    $inicio = Carbon::createFromFormat("d/m/Y", $request->get('start'))->format('Y-m-d');
                    return $data >= $inicio;
                    return $data->gte($inicio);
                });
            }

            if ($request->get("start") == null and $request->get("end") != null) {
                $pedidos = $pedidos->filter(function ($value, $key) use ($request) {
                    $data = $value['DataFirstServico'];
                    $fim =  Carbon::createFromFormat("d/m/Y", $request->get('end'))->format('Y-m-d');
                    return $data <= $fim;
                    return $data->lte($fim);
                });
            }

            if ($request->get("start") != null and $request->get("end") != null) {
                $pedidos = $pedidos->filter(function ($value, $key) use ($request) {
                    $data = $value['DataFirstServico'];

                    $inicio =  Carbon::createFromFormat("d/m/Y", $request->get('start'))->format('Y-m-d');
                    $fim =  Carbon::createFromFormat("d/m/Y", $request->get('end'))->format('Y-m-d');

                    return $data >= $inicio && $data <= $fim;
                    return $data->gte($inicio) && $data->lte($fim);
                });
            }

            if ($request->wantsJson()) {
                return response()->json($pedidos->toArray());
            } else {
                return view('Admin.reports.pedidosreportsv2.main', [
                    'pedidos' => $pedidos, 'suppliers' => $suppliers, 'utilizadores' => $utilizadores, 'produtos' => $produtos
                ]);
            }
        } catch (ModelException $ex) {
            return response($ex);
        }
    }

    public function PrintPedido(Request $request)
    {
        try {

            $produtos = Produto::orderBy('nome')->get();
            $utilizadores = User::orderBy('name')->get();
            $suppliers = Supplier::get();

            $pedidos = PedidoGeral::ViewWithAllProd($request->all());


            if ($request->has('pedidoid') and $request->get('pedidoid') !== null) {
                $pedidos->where('id', $request->get('pedidoid'));
            }

            if ($request->has('client') and $request->get('client') !== null) {
                $pedidos->where('lead_name', 'like', '%' . $request->get('client') . "%");
            }

            if ($request->has('hotel') and $request->get('hotel') !== null and $request->get('hotel') !== '0') {
                $pedidos = $pedidos->whereHas('pedidoprodutos', function ($q) use ($request) {
                    $q->where('produto_id', $request->get('hotel'));
                });
            }

            if ($request->has('operator') and $request->get('operator') !== null and $request->get('operator') !== '0') {
                $pedidos = $pedidos->whereHas('user', function ($q) use ($request) {
                    $q->where('id', $request->get('operator'));
                });
            }

            $pedidos = $pedidos->where('status', '!=', 'Cancelled');

            $pedidos = $pedidos->get()->filter(function ($value, $key) {
                return $value->status != 'Cancelled';
            });



            if ($request->get("start") != null and $request->get("end") == null) {
                $pedidos = $pedidos->filter(function ($value, $key) use ($request) {
                    $data = $value['DataFirstServico'];

                    $inicio = Carbon::createFromFormat("d/m/Y", $request->get('start'))->format('Y-m-d');
                    return $data >= $inicio;
                    return $data->gte($inicio);
                });
            }

            if ($request->get("start") == null and $request->get("end") != null) {
                $pedidos = $pedidos->filter(function ($value, $key) use ($request) {
                    $data = $value['DataFirstServico'];
                    $fim =  Carbon::createFromFormat("d/m/Y", $request->get('end'))->format('Y-m-d');

                    return $data <= $fim;
                    return $data->lte($fim);
                });
            }

            if ($request->get("start") != null and $request->get("end") != null) {
                $pedidos = $pedidos->filter(function ($value, $key) use ($request) {
                    $data = $value['DataFirstServico'];

                    $inicio =  Carbon::createFromFormat("d/m/Y", $request->get('start'))->format('Y-m-d');
                    $fim =  Carbon::createFromFormat("d/m/Y", $request->get('end'))->format('Y-m-d');

                    return $data >= $inicio && $data <= $fim;
                });
            }

            return view('Admin.reports.pedidosreports.print')->with('pedidos', $pedidos);
        } catch (Exception $ex) {
            dd($ex);
        }
    }

    public function inverteData($data, $onlyEua = true)
    {
        if (count(explode("/", $data)) > 1) {
            return implode("-", array_reverse(explode("/", $data)));
        }


        if ($onlyEua == false) {
            if (count(explode("-", $data)) > 1) {
                return implode("/", array_reverse(explode("-", $data)));
            }
        }
    }
}
