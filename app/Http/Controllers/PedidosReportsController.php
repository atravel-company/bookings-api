<?php

namespace App\Http\Controllers;

use App\User;
use DateTime;
use App\Produto;
use App\Supplier;
use Carbon\Carbon;
use App\PedidoGeral;
use App\PedidoReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

class PedidosReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $pedidos_reports = [];
        $produtos = Produto::orderBy('nome')->get();
        $utilizadores = User::orderBy('name')->get();
        $suppliers = Supplier::get();

        //return view('reports.pedidosreports.index', ['pedidos_reports'=>$pedidos_reports, 'suppliers' => $suppliers, 'utilizadores' => $utilizadores, 'produtos' => $produtos, 'operador_id' => null, 'tipo' => null, 'produto_id' => null]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $pedidos_reports = [];
        $produtos = Produto::orderBy('nome')->get();
        $utilizadores = User::orderBy('name')->get();
        $suppliers = Supplier::get();
        $produto_id = null;
        $tipo = "";
        if ($request->start) {
            $checkin = str_replace('/', '-', $request->start);
            $start = Carbon::parse($checkin)->format('Y-m-d');
            session(['start' => $start]);
        } else {
            session(['start' => ""]);
        }
        if ($request->end) {
            $checkout = str_replace('/', '-', $request->end);
            $end = Carbon::parse($checkout)->format('Y-m-d');
            session(['end' => $end]);
        } else {
            session(['end' => ""]);
        }

        if ($request->client) {
            session(['client' => $request->client]);
        } else {
            session(['client' => ""]);
        }
        if ($request->hotel) {
            session(['hotel' => $request->hotel]);
            $prod = Produto::where('id', $request->hotel)->first();

            if ($prod->alojamento == 1) {
                $tipo = 'alojamento';
            } elseif ($prod->golf == 1) {
                $tipo = 'golf';
            } elseif ($prod->transfer == 1) {
                $tipo = 'transfer';
            } elseif ($prod->car == 1) {
                $tipo = 'car';
            } elseif ($prod->ticket == 1) {
                $tipo = 'ticket';
            } else {
                $tipo = null;
            }
        } else {
            session(['hotel' => ""]);
        }
        if ($request->operator) {
            session(['operator' => $request->operator]);
        } else {
            session(['operator' => ""]);
        }

        if ($tipo) {
            session(['tipo' => $tipo]);
        } else {
            session(['tipo' => null]);
        }

        if ($request->hotel) {
            session(['produto_id' => $request->hotel]);
        } else {
            session(['produto_id' => null]);
        }

        if ($request->hotel) {
            $produto_id = $request->hotel;
            $vw_pedido_produto = DB::table('vw_pedido_produto')->where('produto_id', '=', $request->hotel)->groupBy('pedido_geral_id')->get();
            $pedidos_gerals = array();
            foreach ($vw_pedido_produto as $pedidos) {
                array_push($pedidos_gerals, $pedidos->pedido_geral_id);
            }
        }
        /* dd($pedidos_gerals, $request->hotel); */
        //dd($pedidos_gerals);
        //dd($request->operator, $request->hotel);


        $pedidos_reports = PedidoReport::with('payments');
        if ($request->start && $request->end) {
            $pedidos_reports->whereBetween('checkin', [$start, $end]);
        }
        if ($request->start && $request->end == null) {
            $pedidos_reports->where('checkin', $start);
        }
        if ($request->client) {
            $pedidos_reports->where('client', 'LIKE', '%'.$request->client.'%');
        }
        if ($request->hotel) {
            $pedidos_reports->whereIn('pedido_geral_id', $pedidos_gerals);
        }
        if ($request->operator) {
            $pedidos_reports->where('operador_id', '=', $request->operator);
        }

        $pedidos_reports = $pedidos_reports->where('status', '!=', 'Cancelled')->select('*')->get();


        //dd($pedidos_reports->toSql(), $pedidos_reports->get()->toArray() );

        //return view('reports.pedidosreports.index', ['pedidos_reports' => $pedidos_reports, 'suppliers' => $suppliers, 'utilizadores' => $utilizadores, 'produtos' => $produtos, 'operador_id' => null, 'tipo' => $tipo, 'produto_id' => $produto_id]);
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
                $pedidos->where('lead_name', 'like', '%'.$request->get('client')."%");
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
                    // $fim = Carbon::parse($this->inverteData($request->get("end")));


                    return $data >= $inicio;
                    return $data->gte($inicio);
                    //return $data->gte($inicio) && $data->lte($fim);
                });
            }
            if ($request->get("start") == null and $request->get("end") != null) {
                $pedidos = $pedidos->filter(function ($value, $key) use ($request) {
                    $data = $value['DataFirstServico'];

                    //$inicio = Carbon::parse($this->inverteData($request->get("start")));
                    $fim =  Carbon::createFromFormat("d/m/Y", $request->get('end'))->format('Y-m-d');
                    //return $data->gte($inicio);


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

    public function newIndex(Request $request, $name, $debug = false)
    {


        ini_set('memory_limit', '-1');



        $produtos = Produto::orderBy('nome')->get();
        $utilizadores = User::orderBy('name')->get();
        $suppliers = Supplier::get();
        $request->merge(['start' =>  Carbon::now()->format("Y-m-d") ]);
        $pedidos = PedidoGeral::ViewWithAllProd($request->all());

        // $pedidos = $pedidos->get()->filter(function ($value, $key) {
        //     return $value->status != 'Cancelled';
        // });

        return view('Admin.reports.pedidosreports.main', [
            'pedidos'=> $pedidos, 'suppliers' => $suppliers, 'utilizadores' => $utilizadores, 'produtos' => $produtos
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
                $pedidos->where('lead_name', 'like', '%'.$request->get('client')."%");
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
                    // $fim = Carbon::parse($this->inverteData($request->get("end")));


                    return $data >= $inicio;
                    return $data->gte($inicio);
                    //return $data->gte($inicio) && $data->lte($fim);
                });
            }
            if ($request->get("start") == null and $request->get("end") != null) {
                $pedidos = $pedidos->filter(function ($value, $key) use ($request) {
                    $data = $value['DataFirstServico'];

                    //$inicio = Carbon::parse($this->inverteData($request->get("start")));
                    $fim =  Carbon::createFromFormat("d/m/Y", $request->get('end'))->format('Y-m-d');
                    //return $data->gte($inicio);


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
                return view('Admin.reports.pedidosreports.main', [
                'pedidos'=>$pedidos, 'suppliers' => $suppliers, 'utilizadores' => $utilizadores, 'produtos' => $produtos]);
            }
        } catch (ModelException $ex) {
            return response($ex);
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
