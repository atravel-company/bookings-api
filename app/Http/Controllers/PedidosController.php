<?php

namespace App\Http\Controllers;

use App\PedidoCar;
use App\PedidoGame;
use App\PedidoGeral;
use App\PedidoQuarto;
use App\PedidoQuartoRoom;
use App\PedidoQuartoRoomName;
use App\PedidoTicket;
use App\PedidoTransfer;
use App\Produto;
use App\ValorCar;

use App\ValorGolf;
use App\ValorQuarto;
use App\ValorTicket;
use App\ValorTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidosController extends Controller
{


	public function __construct(Request $request){

		$header = request()->headers;
		$referer = null;
		$url = url()->full();

		if($header->get('referer')){
			$referer = $header->get('referer');
		}
		
		\Log::channel("pedidos_request")->notice("Acessanto URL {$url}", [
			"referer" => $referer,
			"request" => $request->all(),
		]);

	}

	public function bag()
	{

		return view('Admin.layouts.bag', ['produtos' => 'abobrinha']);
	}
	public function mostra(Request $request)
	{

		$produto = $request;
		return view('Admin.layouts.detalhes', ['produto' => $produto]);
	}

	public function alojamento(Request $request)
	{


		if (empty($request->referencia)) {
			$characters = '0123456789ABCDEFGHIJKLMNOP';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < 5; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			$referencia = "ATS_" . $randomString;
			$pedido_geral = PedidoGeral::where('referencia', $referencia)->first();

			if ($pedido_geral) {
				for ($i = 0; $i < 5; $i++) {
					$randomString .= $characters[rand(0, $charactersLength - 1)];
				}
				$referencia = "ATS_" . $randomString;
			}
		} else {
			$referencia = $request->referencia;
		}

		$type = $request->type ? $request->type : "New Booking";
		$lead_name = $request->lead_name ? $request->lead_name : "Blank Leadname";
		$responsavel = $request->responsavel ? $request->responsavel : "Blank Responsável";

		$tot = $request->produtos;
		$pedido = PedidoGeral::create(['type' => $type, 'lead_name' => $lead_name, 'responsavel' => $responsavel, 'referencia' => $referencia, 'user_id' => $request->user_id, 'status' => 'In Progress']);


		foreach ($tot as $numero => $pedid) {

			$obj[$numero] = $pedid;
			$produ = $obj[$numero];
			$array = $produ['array'];
			$product = $produ['produto'];

			$produto = Produto::find($product);

			$pedido->produtoss()->attach($produto);
			$pedidoID = DB::table('pedido_produto')->orderBy('id', 'desc')->first();
			$pediProd[$numero] = $pedidoID->id;
			if ($produto->alojamento == 1) {
				ValorQuarto::create(['pedido_produto_id' => $pediProd[$numero]]);
			}
			if ($produto->golf == 1) {
				ValorGolf::create(['pedido_produto_id' => $pediProd[$numero]]);
			}
			if ($produto->transfer == 1) {
				ValorTransfer::create(['pedido_produto_id' => $pediProd[$numero]]);
			}
			if ($produto->car == 1) {
				ValorCar::create(['pedido_produto_id' => $pediProd[$numero]]);
			}
			if ($produto->ticket == 1) {
				ValorTicket::create(['pedido_produto_id' => $pediProd[$numero]]);
			}

			if (!empty($produ['extras'])) {
				$extras = $produ['extras'];
				foreach ($extras as $key => $extra) {

					DB::table('pedido_produto_extra')->insert(['pedido_produto_id' => $pediProd[$numero], 'extra_id' => $extra['valor'], 'tipo' => $extra['tipo'], 'amount' => $extra['quantidade'], 'created_at' =>  \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()]);
				}
			}

			if (!empty($produ['remarkalojamento'])) {
				$remark = "<b class='agency_b'>Agency: </b>" . $produ['remarkalojamento'] . " </b> <br>";
				$remarkalojamento = $remark;
			} else {
				$remarkalojamento = null;
			}
			if (!empty($produ['remarkgolf'])) {
				$remark = "<b class='agency_b'>Agency: </b>" . $produ['remarkgolf'] . " </b> <br>";
				$remarkgolf = $remark;
			} else {
				$remarkgolf = null;
			}
			if (!empty($produ['remarktransfer'])) {
				$remark = "<b class='agency_b'>Agency: </b>" . $produ['remarktransfer'] . " </b> <br>";
				$remarktransfer = $remark;
			} else {
				$remarktransfer = null;
			}
			if (!empty($produ['remarkcar'])) {
				$remark = "<b class='agency_b'>Agency: </b>" . $produ['remarkcar'] . " </b> <br>";
				$remarkcar = $remark;
			} else {
				$remarkcar = null;
			}
			if (!empty($produ['remarkticket'])) {
				$remark = "<b class='agency_b'>Agency: </b>" . $produ['remarkticket'] . " </b> <br>";
				$remarkticket = $remark;
			} else {
				$remarkticket = null;
			}

			foreach ($array as $key => $total) { //AQUI SAO OS FORMS
				$obj0[$numero][$key] = $total;
				$alojamento = $obj0[$numero][$key];
				if ($alojamento['form'] == 'alojamento') {
					if (isset($alojamento['in'])) {
						$dateIN = $alojamento['in'];
						$nowIN = new \DateTime();
						$dateObjectIN = $nowIN->createFromFormat('d/m/Y', $dateIN)->format('Y-m-d');
					} else {
						$dateObjectIN = $alojamento['in'];
					}
					if (isset($alojamento['out'])) {
						$dateOUT = $alojamento['out'];
						$nowOUT = new \DateTime();
						$dateObjectOUT = $nowOUT->createFromFormat('d/m/Y', $dateOUT)->format('Y-m-d');
					} else {
						$dateObjectOUT = $alojamento['out'];
					}

					$quartoID = PedidoQuarto::create(['pedido_produto_id' => $pediProd[$numero], 'checkin' => $dateObjectIN, 'checkout' => $dateObjectOUT, 'type' => $alojamento['type'], 'rooms' => (int)$alojamento['room'], 'plan' => $alojamento['plan'], 'people' => (int)$alojamento['people'], 'remark' => $remarkalojamento]);
					//ENCERRA AQUI OS FORMS
					if (isset($alojamento['quartos'])) {
						$PedidoQuartoID[$numero][$key] = $quartoID->id;
						$obj1[$numero][$key] = $alojamento['quartos'];
						$nome = $obj1[$numero][$key];
						foreach ($nome as $key1 => $total1) {
							$remarks[$numero][$key][$key1] = $total1['roomremark'];
							$quartoRoomID = PedidoQuartoRoom::create(['pedido_quarto_id' => $PedidoQuartoID[$numero][$key], 'remark' => $remarks[$numero][$key][$key1]]);
							// 	$quartos[$key1]=
							// }
							if (isset($total1['nomes'])) {
								$pedidoQuartoRoomID[$numero][$key][$key1] = $quartoRoomID->id;
								$obj2[$numero][$key][$key1] = $total1['nomes'];
								$final = $obj2[$numero][$key][$key1];
								foreach ($final as $key2 => $total2) {
									$name[$numero][$key][$key1][$key2] = $total2['roomname'];
									PedidoQuartoRoomName::create(['pedido_quarto_room_id' => $pedidoQuartoRoomID[$numero][$key][$key1], 'name' => $name[$numero][$key][$key1][$key2]]);
								}
							} else {
								$obj2[$numero][$key][$key1] = 'nao existe nome.';
							}
						}
					} else {
						$obj1[$numero][$key] = 'nao existe.';
						$obj2[$numero][$key][0] = 'nao existe nome.';
						$remarks[$numero][$key][0] = 'only rooms, not exist in alojamento';
					}
				}
				if ($alojamento['form'] == 'golf') {
					if (isset($alojamento['datagolf'])) {
						$dateIN = $alojamento['datagolf'];
						$nowIN = new \DateTime();
						$dateObjectIN = $nowIN->createFromFormat('d/m/Y', $dateIN)->format('Y-m-d');
					} else {
						$dateObjectIN = $alojamento['datagolf'];
					}

					PedidoGame::create(['pedido_produto_id' => $pediProd[$numero], 'data' => $dateObjectIN, 'hora' => $alojamento['horagolf'], 'course' => $alojamento['coursegolf'], 'people' => (int)$alojamento['peoplegolf'], 'remark' => $remarkgolf]);


					$obj1[$numero][$key] = 'golf';
					$obj2[$numero][$key][0] = 'golfsss';
					$remarks[$numero][$key][0] = 'only rooms, not exist in golf';
				}
				if ($alojamento['form'] == 'transfer') {
					if (isset($alojamento['datatransfer'])) {
						$transfer_data = explode(" ", $alojamento['datatransfer']);
						$dateIN = $transfer_data[0];
						$horaIN = $transfer_data[1];
						$nowIN = new \DateTime();
						$dateObjectIN = $nowIN->createFromFormat('d/m/Y', $dateIN)->format('Y-m-d');
					} else {
						/*$dateObjectIN = $alojamento['datatransfer'];*/
						$dateIN = '01/01/2019';
						$horaIN = '00:00';
						$nowIN = new \DateTime();
						$dateObjectIN = $nowIN->createFromFormat('d/m/Y', $dateIN)->format('Y-m-d');
					}

					PedidoTransfer::create(['pedido_produto_id' => $pediProd[$numero], 'data' => $dateObjectIN, 'hora' => $horaIN, 'adult' => (int)$alojamento['adultstransfer'], 'children' => (int)$alojamento['childrenstransfer'], 'babie' => (int)$alojamento['babiestransfer'], 'flight' => $alojamento['flighttransfer'], 'pickup' => $alojamento['pickuptransfer'], 'dropoff' => $alojamento['dropofftransfer'], 'remark' => $remarktransfer]);


					$obj1[$numero][$key] = 'transfer';
					$obj2[$numero][$key][0] = 'transfersss';
					$remarks[$numero][$key][0] = 'only rooms, not exist in transfers';
				}
				if ($alojamento['form'] == 'car') {
					if (isset($alojamento['pickupdatacar'])) {
						$dateIN = $alojamento['pickupdatacar'];
						$nowIN = new \DateTime();
						$dateObjectIN = $nowIN->createFromFormat('d/m/Y', $dateIN)->format('Y-m-d');
					} else {
						$dateObjectIN = $alojamento['pickupdatacar'];
					}

					if (isset($alojamento['dropoffdatacar'])) {
						$dateOUT = $alojamento['dropoffdatacar'];
						$nowOUT = new \DateTime();
						$dateObjectOUT = $nowOUT->createFromFormat('d/m/Y', $dateOUT)->format('Y-m-d');
					} else {
						$dateObjectOUT = $alojamento['dropoffdatacar'];
					}




					PedidoCar::create(['pedido_produto_id' => $pediProd[$numero], 'pickup' => $alojamento['pickupcar'], 'pickup_data' => $dateObjectIN, 'pickup_hora' => $alojamento['pickuphoracar'], 'pickup_flight' => $alojamento['pickupflightcar'], 'pickup_country' => $alojamento['pickupcountrycar'], 'pickup_airport' => $alojamento['pickupairportcar'], 'dropoff' => $alojamento['dropoffcar'], 'dropoff_data' => $dateObjectOUT, 'dropoff_hora' => $alojamento['dropoffhoracar'], 'dropoff_flight' => $alojamento['dropoffflightcar'], 'dropoff_country' => $alojamento['dropoffcountrycar'], 'dropoff_airport' => $alojamento['dropoffairportcar'], 'remark' => $remarkcar, 'group' => $alojamento['group'], 'model' => $alojamento['model']]);


					$obj1[$numero][$key] = 'car';
					$obj2[$numero][$key][0] = 'carssss';
					$remarks[$numero][$key][0] = 'only rooms, not exist in cars';
				}
				if ($alojamento['form'] == 'ticket') {
					if (isset($alojamento['dataticket'])) {
						$dateIN = $alojamento['dataticket'];
						$nowIN = new \DateTime();
						$dateObjectIN = $nowIN->createFromFormat('d/m/Y', $dateIN)->format('Y-m-d');
					} else {
						$dateObjectIN = $alojamento['dataticket'];
					}

					PedidoTicket::create(['pedido_produto_id' => $pediProd[$numero], 'data' => $dateObjectIN, 'hora' => $alojamento['horaticket'], 'adult' => (int)$alojamento['adultsticket'], 'children' => (int)$alojamento['childrensticket'], 'babie' => (int)$alojamento['babiesticket'], 'remark' => $remarkticket]);


					$obj1[$numero][$key] = 'ticket';
					$obj2[$numero][$key][0] = 'ticketsss';
					$remarks[$numero][$key][0] = 'only rooms, not exist in tickets';
				}
			}
		}

		return response()->json(['result' => ['valor' => 'nada', 'obj' => $obj, 'obj1' => $obj1, 'obj2' => $obj2, 'dataa' => $remarks]]);
	}
}
