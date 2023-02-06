<?php

namespace App\Http\Controllers;

use App\Exports\PedidoGeralReport;
use App\Http\Controllers\Controller;
use App\PedidoGeral;
use App\Produto;
use App\Supplier;
use App\User;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;



class PedidosReportsV2Controller extends Controller
{

    public function index(Request $request)
    {
        $produtos = Produto::orderBy('nome')->get();
        $utilizadores = User::orderBy('name')->get();
        $suppliers = Supplier::get();
        $request->merge(['start' =>  Carbon::now()->format("Y-m-d")]);

        return view('Admin.reports.pedidosreportsv2.main', [
            'pedidos' => [],
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

            $pedidos = $this->filterRequest($request);

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

    public function filterRequest(Request $request)
    {
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
            })->with(['pedidoprodutos' => function ($q) use ($request) {
                $q->where('produto_id', $request->get('hotel'));
            }]);
        }

        /** usado no AJAX para a tabela de info dentro dos produtos */
        if ($request->has('suplier_id') and $request->get('suplier_id') !== null and $request->get('suplier_id') !== '0') {
            $pedidos = $pedidos->whereHas('pedidoprodutos', function ($q) use ($request) {
                $q->where('produto_id', $request->get('suplier_id'));
            })->with(['pedidoprodutos' => function ($sql) use ($request) {
                $sql->where('produto_id', $request->get('suplier_id'));
                $sql->with('extras');
                $sql->with('valorquarto');
                $sql->with('pedidoquarto');
                $sql->with('valortransfer');
                $sql->with('pedidotransfer');
                $sql->with('valorgame');
                $sql->with('pedidogame');
                $sql->with('valorcar');
                $sql->with('pedidocar');
                $sql->with('valorticket');
                $sql->with('pedidoticket');
                $sql->with('produto');
            }]);
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

        return $pedidos;
    }

    public function reportPDF(Request $request)
    {
        try {

            $pedidos = $this->filterRequest($request);

            return view('Admin.reports.pedidosreports.print')->with('pedidos', $pedidos);
        } catch (Exception $ex) {
            dd($ex);
        }
    }

    public function reportExcel(Request $request)
    {
        try {
            $pedidos = $this->filterRequest($request);

            // return view('Admin.reports.pedidosreportsv2.Excel.report')->with('pedidos', $pedidos);

            return Excel::download(new PedidoGeralReport($pedidos), 'Export_' . Carbon::now()->format("Y-m-d H:i") . ".xls");
        } catch (Exception $ex) {
            dd($ex);
        }
    }
}
