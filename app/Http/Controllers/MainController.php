<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Produto;
use App\Categoria;
use App\Destinos;
use App\Supplier;
use App\PedidoGeral;
use App\PedidoQuarto;
use App\PedidoQuartoRoom;
use App\PedidoQuartoRoomName;
use App\PedidoGame;
use App\PedidoTransfer;
use App\PedidoCar;
use App\PedidoTicket;
use App\ProdutoPdf;
use Auth;

use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    //


public function index(){
		$destinos = Destinos::orderBy('name')->get();
		$categorias = Categoria::orderBy('name')->get();
		$produtos = Produto::all();
		return view('Admin.main.index',['produtos'=>$produtos, 'categorias'=>$categorias, 'destinos'=>$destinos]);
	}

public function search(Request $request){

	    if($request->destino_id){
			session(['location' => $request->destino_id]);
        }

        if($request->categoria_id){
			session(['categoria_id' => $request->categoria_id]);
        }

		if($request->categoria_id ==0 and $request->destino_id ==0 ){
			return response()->json(['result'=>['produtos'=>'Vazio!', 'suppliers'=>'Vazio!', 'destinos'=>'Vazio!']]);
        }

		else{
			if($request->destino_id ==0){
				$categoria = Categoria::find($request->categoria_id);
				$produtos=$categoria->produtos;

				if($produtos!="[]"){

					foreach ($produtos as $key=>$produto) {
								$suppliers[$key]= Supplier::find($produto->supplier_id);
								if(!isset($suppliers[$key])){
									$suppliers[$key]= json_encode([]);
								}
								$destinos[$key]= Destinos::find($produto->destino_id);
								if(!isset($destinos[$key])){
									$destinos[$key]= json_encode([]);
								}
							}
					return response()->json(['result'=>['produtos'=>$produtos, 'suppliers'=>$suppliers, 'destinos'=>$destinos]]);
				}
				else{
					return response()->json(['result'=>['produtos'=>$produtos, 'suppliers'=>'Vazio!', 'destinos'=>'Vazio!']]);
				}
			}
			else if($request->categoria_id ==0){
				$produtos = Produto::where([ ['destino_id', '=', $request->destino_id],])->where('estado', '=', 1)->orderBy('nome')->get();
				if($produtos!="[]"){
					foreach ($produtos as $key=>$produto) {
								$suppliers[$key]= Supplier::find($produto->supplier_id);
								if(!isset($suppliers[$key])){
									$suppliers[$key]= json_encode([]);
								}
								$destinos[$key]= Destinos::find($produto->destino_id);
								if(!isset($destinos[$key])){
									$destinos[$key]= json_encode([]);
								}
							}
					return response()->json(['result'=>['produtos'=>$produtos, 'suppliers'=>$suppliers, 'destinos'=>$destinos]]);
				}
				else{
					return response()->json(['result'=>['produtos'=>$produtos, 'suppliers'=>'Vazio!', 'destinos'=>'Vazio!']]);
				}
			}
			else{
				$categoria = Categoria::find($request->categoria_id);
				$produtosC=$categoria->produtos;

					$produtos=$produtosC->where('destino_id', '=', $request->destino_id)->where('estado', '=', 1);

				if($produtos!="[]"){

						foreach ($produtos as $key=>$produto) {
							$suppliers[$key]= Supplier::find($produto->supplier_id);
							if(!isset($suppliers[$key])){
								$suppliers[$key]= json_encode([]);
							}
							$destinos[$key]= Destinos::find($produto->destino_id);
							if(!isset($destinos[$key])){
								$destinos[$key]= json_encode([]);
							}
						}
						return response()->json(['result'=>['produtos'=>$produtos, 'suppliers'=>$suppliers, 'destinos'=>$destinos]]);
				}
				else{
					return response()->json(['result'=>['produtos'=>$produtos, 'suppliers'=>'Vazio!', 'destinos'=>'Vazio!']]);
				}
			}

		}

	}

	public function product($id,$cat,$dest){
		$user_id = Auth::user()->id;
		if($cat ==0){
			$categoria = 0;
			$destino = Destinos::find($dest);
		}
		elseif ($dest ==0) {
			$categoria = Categoria::find($cat);
			$destino = 0;
		}
		else{
			$categoria = Categoria::find($cat);
			$destino = Destinos::find($dest);
		}
		$produto = Produto::find($id);

		$produtoPdf = ProdutoPdf::with('regras')->where('produto_id', $id)->orderBy('title')->get();

		return view('Admin.main.product', compact('produto','categoria','destino', 'produtoPdf', 'user_id'));
	}


	public function form($id){
		$produto = Produto::find($id);
		$categoriasGolf = Categoria::find('4');//4
		$categoriasTransfer = Categoria::find('7');//7
		$categoriasCar = Categoria::find('5');//5
		$categoriasTicket = Categoria::find('21');//21
		return view('Admin.main.form',['produto'=>$produto,'categoriasGolf'=>$categoriasGolf,'categoriasTransfer'=>$categoriasTransfer,'categoriasCar'=>$categoriasCar,'categoriasTicket'=>$categoriasTicket]);
	}

	public function alojamento(Request $request){

		$tot = $request->produtos;
		/* $pedido = PedidoGeral::create(['type'=>'cotation', 'lead_name'=>'Renato', 'responsavel'=>'Maria', 'referencia'=>'FP4950']); */
		foreach ($tot as $numero=>$pedid) {

		$obj[$numero]=$pedid;
		$produ=$obj[$numero];
		$array=$produ['array'];
		$product=$produ['produto'];

		$produto = Produto::find($product);
		/* $pedido->produtoss()->attach($produto); */
		$pedidoID=DB::table('pedido_produto')->orderBy('id', 'desc')->first();
		$pediProd[$numero]=$pedidoID->id;
		if(!empty($produ['extras'])){
			$extras=$produ['extras'];
				foreach ($extras as $key=>$extra) {

						DB::table('pedido_produto_extra')->insert(['pedido_produto_id'=>$pediProd[$numero], 'extra_id'=>$extra['valor'], 'tipo'=>$extra['tipo'], 'created_at' =>  \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()]);

				}
		}

		if(!empty($produ['remarkalojamento'])){
			$remarkalojamento=$produ['remarkalojamento'];
		}
		else{
			$remarkalojamento = null;
		}
		if(!empty($produ['remarkgolf'])){
			$remarkgolf=$produ['remarkgolf'];
		}
		else{
			$remarkgolf= null;
		}
		if(!empty($produ['remarktransfer'])){
			$remarktransfer=$produ['remarktransfer'];
		}
		else{
			$remarktransfer=null;
		}
		if(!empty($produ['remarkcar'])){
			$remarkcar=$produ['remarkcar'];
		}
		else{
			$remarkcar=null;
		}
		if(!empty($produ['remarkticket'])){
			$remarkticket=$produ['remarkticket'];
		}
		else{
			$remarkticket=null;
		}
		// foreach ($pedido->produtos as $num=>$prod) {
	// 				$pediProd[$numero] = $prod->pivot->id;
		// }
	    // $pediProd=$pedido->produtos()->latest()->first()->pivot->id;
	    // $pediProd = $pedido->produtoss;
			foreach ($array as $key=>$total) {//AQUI SAO OS FORMS
				$obj0[$numero][$key]= $total;
				$alojamento= $obj0[$numero][$key];
				if($alojamento['form']=='alojamento'){
					if(isset($alojamento['in'])){
						$dateIN = $alojamento['in'];
						$nowIN = new \DateTime();
						$dateObjectIN =$nowIN->createFromFormat('d/m/Y', $dateIN)->format('Y-m-d');
					}
					else{
						$dateObjectIN = $alojamento['in'];
					}
					if(isset($alojamento['out'])){
						$dateOUT = $alojamento['out'];
						$nowOUT = new \DateTime();
						$dateObjectOUT =$nowOUT->createFromFormat('d/m/Y', $dateOUT)->format('Y-m-d');
					}
					else{
						$dateObjectOUT = $alojamento['out'];
					}

					 $quartoID= PedidoQuarto::create(['pedido_produto_id'=>$pediProd[$numero], 'checkin'=>$dateObjectIN, 'checkout'=>$dateObjectOUT, 'type'=>$alojamento['type'],'rooms'=>(int)$alojamento['room'], 'plan'=>$alojamento['plan'],'people'=>(int)$alojamento['people'], 'remark'=>$remarkalojamento]);
					 //ENCERRA AQUI OS FORMS
					if(isset($alojamento['quartos'])){
						$PedidoQuartoID[$numero][$key]=$quartoID->id;
						$obj1[$numero][$key]=$alojamento['quartos'];
						$nome=$obj1[$numero][$key];
						foreach ($nome as $key1=>$total1){
							$remarks[$numero][$key][$key1]=$total1['roomremark'];
							$quartoRoomID=PedidoQuartoRoom::create(['pedido_quarto_id'=>$PedidoQuartoID[$numero][$key], 'remark'=>$remarks[$numero][$key][$key1]]);
						// 	$quartos[$key1]=
						// }
							if(isset($total1['nomes'])){
								$pedidoQuartoRoomID[$numero][$key][$key1]=$quartoRoomID->id;
								$obj2[$numero][$key][$key1]=$total1['nomes'];
								$final=$obj2[$numero][$key][$key1];
								foreach ($final as $key2=>$total2){
									$name[$numero][$key][$key1][$key2] =$total2['roomname'];
									PedidoQuartoRoomName::create(['pedido_quarto_room_id'=>$pedidoQuartoRoomID[$numero][$key][$key1], 'name'=>$name[$numero][$key][$key1][$key2]]);
								}
						// foreach ($obj1 as $key1=>$total1){
						// 	$quartos[$key1]=
						// }
							}
							else{
								$obj2[$numero][$key][$key1]='nao existe nome.';
							}
						}
					}
					else{
						$obj1[$numero][$key]='nao existe.';
						$obj2[$numero][$key][0]='nao existe nome.';
						$remarks[$numero][$key][0]='only rooms, not exist in alojamento';
					}

					// foreach ($alojamento as $key1=>$total1){
					// 	$obj1= $total1;
					// 	// $quartos=$obj1[$key1];
					// 	// $quart = $quartos->quartos;
					// }


				}
				if($alojamento['form']=='golf'){
					if(isset($alojamento['datagolf'])){
						$dateIN = $alojamento['datagolf'];
						$nowIN = new \DateTime();
						$dateObjectIN =$nowIN->createFromFormat('d/m/Y', $dateIN)->format('Y-m-d');
					}
					else{
						$dateObjectIN = $alojamento['datagolf'];
					}

					PedidoGame::create(['pedido_produto_id'=>$pediProd[$numero], 'data'=>$dateObjectIN, 'hora'=>$alojamento['horagolf'], 'course'=>$alojamento['coursegolf'],'people'=>(int)$alojamento['peoplegolf'], 'remark'=>$remarkgolf]);


					$obj1[$numero][$key]='golf';
					$obj2[$numero][$key][0]='golfsss';
					$remarks[$numero][$key][0]='only rooms, not exist in golf';
				}
				if($alojamento['form']=='transfer'){
					if(isset($alojamento['datatransfer'])){
						$dateIN = $alojamento['datatransfer'];
						$nowIN = new \DateTime();
						$dateObjectIN =$nowIN->createFromFormat('d/m/Y', $dateIN)->format('Y-m-d');
					}
					else{
						$dateObjectIN = $alojamento['datatransfer'];
					}

					PedidoTransfer::create(['pedido_produto_id'=>$pediProd[$numero], 'data'=>$dateObjectIN, 'hora'=>$alojamento['horatransfer'], 'adult'=>(int)$alojamento['adultstransfer'],'children'=>(int)$alojamento['childrenstransfer'], 'babie'=>(int)$alojamento['babiestransfer'], 'flight'=>$alojamento['flighttransfer'], 'pickup'=>$alojamento['pickuptransfer'], 'dropoff'=>$alojamento['dropofftransfer'], 'remark'=>$remarktransfer]);


					$obj1[$numero][$key]='transfer';
					$obj2[$numero][$key][0]='transfersss';
					$remarks[$numero][$key][0]='only rooms, not exist in transfers';
				}
				if($alojamento['form']=='car'){
					if(isset($alojamento['pickupdatacar'])){
						$dateIN = $alojamento['pickupdatacar'];
						$nowIN = new \DateTime();
						$dateObjectIN =$nowIN->createFromFormat('d/m/Y', $dateIN)->format('Y-m-d');
					}
					else{
						$dateObjectIN = $alojamento['pickupdatacar'];
					}

					if(isset($alojamento['dropoffdatacar'])){
						$dateOUT = $alojamento['dropoffdatacar'];
						$nowOUT = new \DateTime();
						$dateObjectOUT =$nowOUT->createFromFormat('d/m/Y', $dateOUT)->format('Y-m-d');
					}
					else{
						$dateObjectOUT = $alojamento['dropoffdatacar'];
					}




					PedidoCar::create(['pedido_produto_id'=>$pediProd[$numero], 'pickup'=>$alojamento['pickupcar'], 'pickup_data'=>$dateObjectIN, 'pickup_hora'=>$alojamento['pickuphoracar'], 'pickup_flight'=>$alojamento['pickupflightcar'],'pickup_country'=>$alojamento['pickupcountrycar'], 'pickup_airport'=>$alojamento['pickupairportcar'], 'dropoff'=>$alojamento['dropoffcar'], 'dropoff_data'=>$dateObjectOUT, 'dropoff_hora'=>$alojamento['dropoffhoracar'], 'dropoff_flight'=>$alojamento['dropoffflightcar'],'dropoff_country'=>$alojamento['dropoffcountrycar'], 'dropoff_airport'=>$alojamento['dropoffairportcar'], 'remark'=>$remarkcar]);


					$obj1[$numero][$key]='car';
					$obj2[$numero][$key][0]='carssss';
					$remarks[$numero][$key][0]='only rooms, not exist in cars';
				}
				if($alojamento['form']=='ticket'){
					if(isset($alojamento['dataticket'])){
						$dateIN = $alojamento['dataticket'];
						$nowIN = new \DateTime();
						$dateObjectIN =$nowIN->createFromFormat('d/m/Y', $dateIN)->format('Y-m-d');
					}
					else{
						$dateObjectIN = $alojamento['dataticket'];
					}

					PedidoTicket::create(['pedido_produto_id'=>$pediProd[$numero], 'data'=>$dateObjectIN, 'hora'=>$alojamento['horaticket'], 'adult'=>(int)$alojamento['adultsticket'],'children'=>(int)$alojamento['childrensticket'], 'babie'=>(int)$alojamento['babiesticket'], 'remark'=>$remarkticket]);


					$obj1[$numero][$key]='ticket';
					$obj2[$numero][$key][0]='ticketsss';
					$remarks[$numero][$key][0]='only rooms, not exist in tickets';
				}


			}
		}

		return response()->json(['result'=>[ 'valor'=>'nada', 'obj'=>$obj, 'obj1'=>$obj1, 'obj2'=>$obj2, 'dataa'=>$remarks]]);
	}

}
