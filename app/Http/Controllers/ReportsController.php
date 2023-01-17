<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Destinos;
use App\PedidoCar;
use App\PedidoGame;
use App\PedidoGeral;
use App\PedidoQuarto;
use App\PedidoTicket;
use App\PedidoTransfer;
use App\Produto;
use App\User;
use App\ValorGolf;
use App\ValorQuarto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{

	// public function index()
	// {
	// 	$destinos = Destinos::all();
	// 	$categorias = Categoria::all();
	// 	$usuarios = User::all();
	// 	$produtosAlojamento = Produto::where([['alojamento', '=', '1']])->get();
	// 	$produtosGolf = Produto::where([['golf', '=', '1']])->get();
	// 	$usuario = Auth::user()->id;
	// 	$cristina = Auth::user()->find(6);
	// 	$extras =  DB::table('pedido_produto_extra')->join('extras', 'pedido_produto_extra.extra_id', '=', 'extras.id')->select('*', 'pedido_produto_extra.id')->get();
	// 	if ($usuario == 6) {
	// 		$pedidos = PedidoGeral::all();
	// 	} else {
	// 		$pedidos = PedidoGeral::where([['user_id', '=', $usuario]])->get();
	// 	}

	// 	$prod = array();
	// 	$quarto = array();
	// 	$game = array();
	// 	$transfer = array();
	// 	$car = array();
	// 	$ticket = array();
	// 	$valor = '';
	// 	/* dd($pedidos); */
	// 	foreach ($pedidos as $key => $pedido) {
	// 		$geral[$key] = $pedido;
	// 		foreach ($geral[$key]->produtoss as $key1 => $produtoss) {
	// 			$prod[$key][$key1] = $produtoss;
	// 			$pedido_prod_id = $prod[$key][$key1]->pivot->id;
	// 			$PedidoQuartos = PedidoQuarto::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();
	// 			$ValorQuartos = ValorQuarto::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();
	// 			$valor = null;
	// 			foreach ($ValorQuartos as $key2 => $ValorQuarto) {
	// 				$valor[$key][$key1] = $ValorQuarto;
	// 			}
	// 			foreach ($PedidoQuartos as $key2 => $PedidoQuarto) {
	// 				$quarto[$key][$key1][$key2] = $PedidoQuarto;
	// 			}
	// 			$PedidoGames = PedidoGame::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();
	// 			foreach ($PedidoGames as $key2 => $PedidoGame) {
	// 				$game[$key][$key1][$key2] = $PedidoGame;
	// 			}
	// 			$PedidoTransfers = PedidoTransfer::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();
	// 			foreach ($PedidoTransfers as $key2 => $PedidoTransfer) {
	// 				$transfer[$key][$key1][$key2] = $PedidoTransfer;
	// 			}
	// 			$PedidoCars = PedidoCar::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();
	// 			foreach ($PedidoCars as $key2 => $PedidoCar) {
	// 				$car[$key][$key1][$key2] = $PedidoCar;
	// 			}
	// 			$PedidoTickets = PedidoTicket::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();
	// 			foreach ($PedidoTickets as $key2 => $PedidoTicket) {
	// 				$ticket[$key][$key1][$key2] = $PedidoTicket;
	// 			}
	// 		}
	// 	}

	// 	return view('Admin.reports.index', ['usuarios' => $usuarios, 'produtosAlojamentos' => $produtosAlojamento, 'produtosGolfs' => $produtosGolf, 'categorias' => $categorias, 'destinos' => $destinos, 'pedidos' => $pedidos, 'produto' => $prod, 'quartos' => $quarto, 'valor' => $valor, 'golfs' => $game, 'transfers' => $transfer, 'cars' => $car, 'tickets' => $ticket, 'extras' => $extras]);
	// }



	// public function search(Request $request)
	// {

	// 	$dateIN = $request->inicial;
	// 	$nowIN = new \DateTime();
	// 	$dateObjectIN = $nowIN->createFromFormat('d/m/Y', $dateIN)->format('Y-m-d');

	// 	$dateOUT = $request->final;
	// 	$nowOUT = new \DateTime();
	// 	$dateObjectOUT = $nowOUT->createFromFormat('d/m/Y', $dateOUT)->format('Y-m-d');

	// 	$start    = (new \DateTime($dateObjectIN))->modify('first day of this month');

	// 	$end      = (new \DateTime($dateObjectOUT))->modify('first day of next month');
	// 	$interval = \DateInterval::createFromDateString('1 month');
	// 	$period   = new \DatePeriod($start, $interval, $end);

	// 	foreach ($period as $dt) {


	// 		$months[$dt->format("Y-m")] = array('month' => $dt->format("Y-m"));
	// 		$months[$dt->format("Y-m")]['bednights'] = 0;
	// 		$months[$dt->format("Y-m")]['room_nights'] = 0;
	// 		$months[$dt->format("Y-m")]['room_revenue'] = 0;
	// 		$months[$dt->format("Y-m")]['adr'] = 0;
	// 		$months[$dt->format("Y-m")]['extra'] = 0;
	// 		$months[$dt->format("Y-m")]['golf_revenue'] = 0;
	// 		$months[$dt->format("Y-m")]['extraGolf'] = 0;
	// 		$months[$dt->format("Y-m")]['total_revenue'] = 0;
	// 		$months[$dt->format("Y-m")]['canceled'] = 0;
	// 		$months[$dt->format("Y-m")]['another'] = 0;
	// 	}

	// 	$from = min($start, $end);
	// 	$till = max($start, $end);

	// 	$quarto = null;
	// 	$dias = null;
	// 	$prod = null;
	// 	$pedidos = null;
	// 	$canceled = null;
	// 	$tratamento = null;
	// 	$waitCliente = null;
	// 	$waitAts = null;

	// 	if ($request->usuarios == -1) {
	// 		$pedidos = null;
	// 		$canceled = null;
	// 		$tratamento = null;
	// 		$waitCliente = null;
	// 		$waitAts = null;
	// 	} else if ($request->usuarios == 0) {
	// 		$pedidos = PedidoGeral::where([['status', '=', 'Confirmed']])->whereBetween('created_at', array($from, $till))->get();
	// 		$canceled = PedidoGeral::where([['status', '=', 'Canceled']])->whereBetween('created_at', array($from, $till))->get();
	// 		$tratamento = PedidoGeral::where([['status', '=', 'In Treatment']])->whereBetween('created_at', array($from, $till))->get();
	// 		$waitCliente = PedidoGeral::where([['status', '=', 'Waiting Client Confirmation']])->whereBetween('created_at', array($from, $till))->get();
	// 		$waitAts = PedidoGeral::where([['status', '=', 'Waiting Confirmation']])->whereBetween('created_at', array($from, $till))->get();
	// 	} else {
	// 		$pedidos = PedidoGeral::where([['status', '=', 'Confirmed'], ['user_id', '=', $request->usuarios]])->whereBetween('created_at', array($from, $till))->get();

	// 		$canceled = PedidoGeral::where([['status', '=', 'Canceled'], ['user_id', '=', $request->usuarios]])->whereBetween('created_at', array($from, $till))->get();

	// 		$tratamento = PedidoGeral::where([['status', '=', 'In Treatment'], ['user_id', '=', $request->usuarios]])->whereBetween('created_at', array($from, $till))->get();

	// 		$waitCliente = PedidoGeral::where([['status', '=', 'Waiting Client Confirmation'], ['user_id', '=', $request->usuarios]])->whereBetween('created_at', array($from, $till))->get();

	// 		$waitAts = PedidoGeral::where([['status', '=', 'Waiting Confirmation'], ['user_id', '=', $request->usuarios]])->whereBetween('created_at', array($from, $till))->get();
	// 	}
	// 	foreach ($canceled as $key => $cancel) {
	// 		$months[date("Y-m", strtotime($cancel->created_at))]['canceled'] = $months[date("Y-m", strtotime($cancel->created_at))]['canceled'] + 1;
	// 	}
	// 	foreach ($tratamento as $key => $tratament) {
	// 		$months[date("Y-m", strtotime($tratament->created_at))]['another'] = $months[date("Y-m", strtotime($tratament->created_at))]['another'] + 1;
	// 	}
	// 	foreach ($waitCliente as $key => $waitClient) {
	// 		$months[date("Y-m", strtotime($waitClient->created_at))]['another'] = $months[date("Y-m", strtotime($waitClient->created_at))]['another'] + 1;
	// 	}
	// 	foreach ($waitAts as $key => $waitAt) {
	// 		$months[date("Y-m", strtotime($waitAt->created_at))]['another'] = $months[date("Y-m", strtotime($waitAt->created_at))]['another'] + 1;
	// 	}
	// 	foreach ($pedidos as $key => $pedido) {
	// 		$geral[$key] = $pedido;
	// 		foreach ($geral[$key]->produtoss as $key1 => $produtoss) {

	// 			$prod[$key][$key1] = $produtoss;
	// 			$pedido_prod_id = $prod[$key][$key1]->pivot->id;
	// 			if ($produtoss->id == $request->produtosAlojamentos || $request->produtosAlojamentos == 0) {
	// 				/*-------------------------------------tratamento alojamento--------------------------------*/
	// 				$PedidoQuartos = PedidoQuarto::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();
	// 				$ValorQuartos = ValorQuarto::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();
	// 				$valor = null;
	// 				foreach ($ValorQuartos as $key2 => $ValorQuarto) {
	// 					$valor[$key][$key1] = $ValorQuarto;
	// 				}
	// 				$extras = DB::table('pedido_produto_extra')->where([['pedido_produto_id', '=', $pedido_prod_id], ['tipo', '=', 'alojamento']])->get();
	// 				$valorExtra = 0;
	// 				foreach ($extras as $key2 => $extra) {
	// 					$months[date("Y-m", strtotime($pedido->created_at))]['extra'] = $extra->total + $months[date("Y-m", strtotime($pedido->created_at))]['extra'];
	// 				}
	// 				foreach ($PedidoQuartos as $key2 => $PedidoQuarto) {
	// 					$quarto[$key][$key1][$key2] = $PedidoQuarto;

	// 					$now = strtotime($PedidoQuarto->checkin); // or your date as well
	// 					$your_date = strtotime($PedidoQuarto->checkout);
	// 					$datediff = $your_date - $now;

	// 					$dias = round($datediff / (60 * 60 * 24));
	// 					$quarto[$key][$key1][$key2]['dias'] = $dias;
	// 					$quarto[$key][$key1][$key2]['bednights'] = $dias * $PedidoQuarto->people;
	// 					$quarto[$key][$key1][$key2]['room_nights'] = $dias * $PedidoQuarto->rooms;
	// 					$quarto[$key][$key1][$key2]['room_revenue'] = $dias * $PedidoQuarto->rooms * $PedidoQuarto->night;
	// 					$quarto[$key][$key1][$key2]['adr'] = $quarto[$key][$key1][$key2]['room_revenue'] / $quarto[$key][$key1][$key2]['room_nights'] / $PedidoQuarto->rooms;



	// 					$months[date("Y-m", strtotime($pedido->created_at))]['bednights'] = $quarto[$key][$key1][$key2]['bednights'] + $months[date("Y-m", strtotime($pedido->created_at))]['bednights'];
	// 					$months[date("Y-m", strtotime($pedido->created_at))]['room_nights'] = $quarto[$key][$key1][$key2]['room_nights'] + $months[date("Y-m", strtotime($pedido->created_at))]['room_nights'];
	// 					$months[date("Y-m", strtotime($pedido->created_at))]['room_revenue'] = $quarto[$key][$key1][$key2]['room_revenue'] + $months[date("Y-m", strtotime($pedido->created_at))]['room_revenue'];
	// 					$months[date("Y-m", strtotime($pedido->created_at))]['adr'] = $quarto[$key][$key1][$key2]['adr'] + $months[date("Y-m", strtotime($pedido->created_at))]['adr'];
	// 				}
	// 			}
	// 			/*-------------------------------------tratamento alojamento--------------------------------*/
	// 			/*----------------------------------------tratamento Golf-----------------------------------*/

	// 			if ($produtoss->id == $request->produtosGolfs || $request->produtosGolfs == 0) {
	// 				$PedidoGolfs = PedidoGame::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();

	// 				$extras = DB::table('pedido_produto_extra')->where([['pedido_produto_id', '=', $pedido_prod_id], ['tipo', '=', 'golf']])->get();

	// 				foreach ($extras as $key2 => $extra) {
	// 					$months[date("Y-m", strtotime($pedido->created_at))]['extraGolf'] = $extra->total + $months[date("Y-m", strtotime($pedido->created_at))]['extraGolf'];
	// 				}

	// 				foreach ($PedidoGolfs as $key2 => $PedidoGolf) {
	// 					$months[date("Y-m", strtotime($pedido->created_at))]['golf_revenue'] = $PedidoGolf->total + $months[date("Y-m", strtotime($pedido->created_at))]['golf_revenue'];
	// 				}
	// 			}
	// 			/*----------------------------------------tratamento Golf-----------------------------------*/
	// 		}
	// 		$months[date("Y-m", strtotime($pedido->created_at))]['total_revenue'] = $months[date("Y-m", strtotime($pedido->created_at))]['room_revenue'] + $months[date("Y-m", strtotime($pedido->created_at))]['extra'] + $months[date("Y-m", strtotime($pedido->created_at))]['golf_revenue'] + $months[date("Y-m", strtotime($pedido->created_at))]['extraGolf'];
	// 	}
	// 	return response()->json(['result' => ['pedidos' => $pedidos, 'period' => $months, 'produtos' => $prod, 'quartos' => $quarto, 'dias' => $dias]]);
	// }



	// public function report(Request $request)
	// {

	// 	$pedidos = $request->pedidos;
	// 	$produtos = $request->produtos;
	// 	$period = $request->period;
	// 	return view('reports.report', ['pedidos' => $pedidos, 'period' => $period, 'produtos' => $produtos, 'quartos' => $request->quartos]);
	// }
}
