<?php

namespace App\Http\Controllers;

use App;
use App\Categoria;
use App\Destinos;
use App\Exports\RoomsListExport;
use App\Extra;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailServicosApiTGJob;
use App\Jobs\SendMailNovaReservaJob;
use App\PedidoCar;
use App\PedidoGame;
use App\PedidoGeral;
use App\PedidoGeralProfile;
use App\PedidoPayments;
use App\PedidoProduto;
use App\PedidoProdutoExtra;
use App\PedidoQuarto;
use App\PedidoQuartoRoom;
use App\PedidoQuartoRoomName;
use App\PedidoTicket;
use App\PedidoTransfer;
use App\Produto;
use App\User;
use App\ValorCar;
use App\ValorGolf;
use App\ValorQuarto;
use App\ValorTicket;
use App\ValorTransfer;
use Carbon\Carbon;
use Excel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;
use Mail;

class ProfilesController extends Controller
{

    public $optionsCurl;

    public function __construct()
    {

        $this->optionsCurl = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array("Content-Type: application/json", "Content-Type: text/plain"),
        ];
    }

    public function mail(Request $request)
    {
        try {

            $prod = collect(json_decode($request->get("prod"), true));
            $extras = DB::table('pedido_produto_extra')->where('pedido_produto_extra.deleted_at', null)->join('extras', 'pedido_produto_extra.extra_id', '=', 'extras.id')->select('*', 'pedido_produto_extra.id')->orderBy('extras.name')->get();
            $to = $request->email;
            $pedido = PedidoGeral::find($request->get("pedido_geral_id"));
            $usuario = User::find($pedido->user_id);
            $valor = '';
            $valorGolf = '';
            $valorTransfer = '';
            $valorCar = '';
            $valorTicket = '';
            //$prod = '';
            $quarto = '';
            $game = '';
            $transfer = '';
            $car = '';
            $ticket = '';

            $pedido_prod_id = $prod['pivot']['id'];
            $quarto = PedidoQuarto::where([['pedido_produto_id', '=', $pedido_prod_id]])->orderBy('checkin', 'asc')->get();
            $valor = ValorQuarto::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();

            $game = PedidoGame::where([['pedido_produto_id', '=', $pedido_prod_id]])->orderBy('hora', 'asc')->orderBy('data', 'asc')->get();
            $valorGolf = ValorGolf::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();

            $transfer = PedidoTransfer::where([['pedido_produto_id', '=', $pedido_prod_id]])->orderBy('hora', 'asc')->orderBy('data', 'asc')->get();
            $valorTransfer = ValorTransfer::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();

            $car = PedidoCar::where([['pedido_produto_id', '=', $pedido_prod_id]])->orderBy('pickup_hora', 'asc')->orderBy('pickup_data', 'asc')->get();
            $valorCar = ValorCar::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();

            $ticket = PedidoTicket::where([['pedido_produto_id', '=', $pedido_prod_id]])->orderBy('hora', 'asc')->orderBy('data', 'asc')->get();
            $valorTicket = ValorTicket::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();

            if (!$quarto) {
                $quarto = [];
            }

            if (!$extras) {
                $extras = [];
            }

            if (!$game) {
                $game = [];
            }

            if (!$transfer) {
                $transfer = [];
            }

            if (!$ticket) {
                $ticket = [];
            }

            if (!$car) {
                $car = [];
            }

            $mailData = [
                'pedido' => $pedido,
                'usuario' => $usuario,
                'extras' => $extras,
                'produto' => $prod,
                'quartos' => $quarto,
                'valor' => $valor,
                'valorGolf' => $valorGolf,
                'valorTransfer' => $valorTransfer,
                'valorCar' => $valorCar,
                'valorTicket' => $valorTicket,
                'golfs' => $game,
                'transfers' => $transfer,
                'cars' => $car,
                'tickets' => $ticket,
            ];

            SendMailNovaReservaJob::dispatchAfterResponse(
                $mailData,
                $pedido,
                $prod,
                $request->email
            );

            return response()->json('Email esta sendo processo. Serviço enviado com sucesso');
        } catch (Exception $ex) {

            return response()->json($ex);
        } catch (ModelNotFoundException $ex) {

            return response()->json($ex);
        }
    }

    /* Add By Netto*/
    public function getRoomNames(Request $request)
    {
        $cat = DB::table('pedido_quartos')->where('pedido_quarto_id', $request->quarto_id)
            ->join('pedido_quarto_rooms', 'pedido_quarto_rooms.pedido_quarto_id', '=', 'pedido_quartos.id')
            ->leftJoin('pedido_quarto_room_names', 'pedido_quarto_room_names.pedido_quarto_room_id', '=', 'pedido_quarto_rooms.id')
            ->select('pedido_quarto_room_names.id as id', 'pedido_quarto_room_names.name as name', 'pedido_quarto_room_names.pedido_quarto_room_id as room_id')
            ->orderBy('pedido_quarto_rooms.id', 'asc')->get();

        return json_encode($cat);
    }

    public function editRoomNames(Request $request)
    {

        if ($request->room_pax_name) {
            if ($request->room_pax_name_id == 'undefined' || $request->room_pax_name_id == null || $request->room_pax_name_id == "") {
                $room = new PedidoQuartoRoom;
                $room->pedido_quarto_id = $request->produto_id;
                $room->remark = "Adicionado posteriormente";
                $room->save();

                $room_nome = new PedidoQuartoRoomName;
                $room_nome->name = $request->room_pax_name;
                $room_nome->pedido_quarto_room_id = $room->id;
                $room_nome->save();

                //dd($room, $room_nome);

                $data = DB::table('pedido_quartos')->where([['pedido_produto_id', '=', $request->produto_id]])->update(['people' => $request->people_number]);
                return "Novo";
            } else {
                $room = PedidoQuartoRoomName::findOrFail($request->room_pax_name_id);
                $room->name = $request->room_pax_name;
                $room->save();

                return "Usado";
            }
        }
    }

    public function getNewRoomData(Request $request)
    {

        $rooms = PedidoQuartoRoom::where('pedido_quarto_id', $request->quarto_id)->with(['pedidoquartoroomname', 'pedidoquarto.produto.pedidogeral'])->get();

        $pedidoQuarto = PedidoQuarto::find($request->quarto_id);
        return response()->json(['rooms' => $rooms, 'originalPedidoQuarto' => $pedidoQuarto]);
    }

    public function updateRoomQtdAndPaxQtd(Request $request)
    {
        $p = PedidoQuarto::find($request->get('quarto_id'));
        $p->update(['rooms' => $request->get('totalRooms'), 'people' => $request->get('totalPeople')]);
        return response()->json('ok', 200);
    }

    public function newEditRoomNames(Request $request)
    {

        try {

            $quartos = $request->get('quarto');

            foreach ($quartos['room_id'] as $key => $pedidoQuartoId) {

                if ($pedidoQuartoId != 'new' and $pedidoQuartoId != '-1') {
                    $pedidoQuartoRoom[$key] = PedidoQuartoRoom::find($pedidoQuartoId);
                } else {
                    $pedidoQuartoRoom[$key] = PedidoQuartoRoom::create(['pedido_quarto_id' => $request->get('quartos_pedido_quarto_id'), 'remark' => 'adicionado posteriormente']);
                }

                ($quartos['room_name'][$key] ? $paxName = $quartos['room_name'][$key] : $paxName = 'null');

                if ($quartos['room_name_id'][$key] !== "null") {

                    // arruma os relacionamentos caso um PaxName estava para quarto x e foi mudado para quarto Y
                    App\PedidoQuartoRoomName::find($quartos['room_name_id'][$key])->update(['pedido_quarto_room_id' => $pedidoQuartoRoom[$key]->id]);

                    $pedidoQuartoRoom[$key]->pedidoquartoroomname()->find($quartos['room_name_id'][$key])->update(['name' => $paxName]);
                } else {
                    $pedidoQuartoRoom[$key]->pedidoquartoroomname()->create(['name' => $paxName]);
                }
                $roomName[$key] = PedidoQuartoRoomName::find($quartos['room_name_id'][$key]);
            }

            if ($request->has('delete_pedido_quarto_room_name_id')) {

                $roomNamesDelete = $request->get('delete_pedido_quarto_room_name_id');
                $temp = 0;

                foreach ($roomNamesDelete as $roomId) {

                    if ($roomId !== null and $roomId !== 'null') {
                        App\PedidoQuartoRoomName::find($roomId)->delete();
                    }

                    if ($roomId == null) {
                        $temp += 1;
                    }
                }

                $quarto = PedidoQuarto::find($request->get('quartos_pedido_quarto_id'));
                $totalPeople = 0;
                foreach ($quarto->pedidoquartoroom()->get() as $pedidoroom) {
                    $totalPeople += $pedidoroom->pedidoquartoroomname->count();
                }
                $quarto->update(['people' => $totalPeople]);
            }

            return response()->json(['quartoRoom' => $pedidoQuartoRoom, 'quartoRoomName' => $roomName]);
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage())->withInput();
        }
    }

    public function removeEmptyRooms(Request $request)
    {

        $quarto = PedidoQuarto::find($request->get('quarto_id'));

        foreach ($quarto->pedidoquartoroom()->get() as $key => $rooms) {

            if ($rooms->pedidoquartoroomname()->count() == 0) {

                $quarto->pedidoquartoroom[$key]->delete();
            }
        }

        $quarto->rooms = $quarto->pedidoquartoroom()->count();
        $quarto->update();

        return response()->json(['atualizado com sucesso']);
    }


    public function roomsList($id)
    {
        $pedido = PedidoGeral::find($id, 'id');
        foreach ($pedido->produtos as $p) {
            if ($p->quartos()->count() > 0) {
                foreach ($p->quartos()->get() as $q) {
                    $quartos[] = $q;
                }
            }
        }
        return view('Admin.profile.Excel.roomlist', compact('pedido'));
    }

    public function export(Request $request)
    {
        try {
            return Excel::download(new RoomsListExport($request->id), 'Export_' . Carbon::now()->format("Y-m-d H:i") . ".xls");
        } catch (Exception $th) {
            dd($th);
            throw new Exception($th, 500);
        }
    }

    public function removeProducts(Request $request)
    {
        // dd($request->pedido_geral_id, $request->product_id);
        $pedido_produto = PedidoProduto::where('id', $request->product_id)->first();

        $quartos = PedidoQuarto::where('pedido_produto_id', $request->product_id);
        $quartos_valor = ValorQuarto::where('pedido_produto_id', $request->product_id)->first();

        $golfs = PedidoGame::where('pedido_produto_id', $request->product_id);
        $golfs_valor = ValorGolf::where('pedido_produto_id', $request->product_id)->first();

        $transfers = PedidoTransfer::where('pedido_produto_id', $request->product_id);
        $transfers_valor = ValorTransfer::where('pedido_produto_id', $request->product_id)->first();

        $cars = PedidoCar::where('pedido_produto_id', $request->product_id);
        $cars_valor = ValorCar::where('pedido_produto_id', $request->product_id)->first();

        $tickets = PedidoTicket::where('pedido_produto_id', $request->product_id);
        $tickets_valor = ValorTicket::where('pedido_produto_id', $request->product_id)->first();

        if ($quartos->count()) {
            // dd($quartos, $quartos_valor, 'quarto');
            $pedido_produto->delete();
            $quartos->delete();
            $quartos_valor->delete();
        } elseif ($golfs->count()) {
            // dd($golfs, $golfs_valor, 'golf');
            $pedido_produto->delete();
            $golfs->delete();
            $golfs_valor->delete();
        } elseif ($transfers->count()) {

            if ($transfers) {
                $booking_id = $transfers->first()->transfergest_booking;
                $this->apagarTransferApi($booking_id);
            }

            $pedido_produto->delete();
            $transfers->delete();
            $transfers_valor->delete();
        } elseif ($cars->count()) {
            // dd($cars, $cars_valor, 'cars');
            $pedido_produto->delete();
            $cars->delete();
            $cars_valor->delete();
        } elseif ($tickets->count()) {
            // dd($tickets, $tickets_valor, 'tickets');
            $pedido_produto->delete();
            $tickets->delete();
            $tickets_valor->delete();
        } else {
            $pedido_produto->delete();
        }

        return json_encode($pedido_produto);
    }

    public function getProducts(Request $request)
    {
        if ($request->type == "alojamento") {
            $products = Produto::where('alojamento', '=', 1)->orderBy('nome')->get();
        }
        if ($request->type == "golf") {
            $products = Produto::where('golf', '=', 1)->orderBy('nome')->get();
        }

        if ($request->type == "transfer") {
            $products = Produto::where('transfer', '=', 1)->orderBy('nome')->get();
        }

        if ($request->type == "car") {
            $products = Produto::where('car', '=', 1)->orderBy('nome')->get();
        }

        if ($request->type == "ticket") {
            $products = Produto::where('ticket', '=', 1)->orderBy('nome')->get();
        }

        return json_encode($products);
    }

    public function createProducts(Request $request)
    {
        $pedido_geral_id = $request->pedido_geral_id;
        $produto_id = $request->produto_id;
        $qtd = (int) $request->qtd;
        $type = (string) $request->type;
        // dd($pedido_geral_id, $produto_id, $qtd, $type);

        $pedido_produto = PedidoProduto::where('pedido_geral_id', $pedido_geral_id)->where('produto_id', $produto_id)->first();
        if (!$pedido_produto) {
            $pedido_produto = new PedidoProduto;
            $pedido_produto->pedido_geral_id = $pedido_geral_id;
            $pedido_produto->produto_id = $produto_id;
            $pedido_produto->valor = 0.00;
            $pedido_produto->profit = 0.00;
            $pedido_produto->save();
        }

        if ($type == "alojamento") {
            for ($i = 1; $i <= $qtd; $i++) {
                $pedido_quartos = new PedidoQuarto;
                $pedido_quartos->pedido_produto_id = $pedido_produto->id;
                $pedido_quartos->checkin = date("Y-m-d H:i:s");
                $pedido_quartos->checkout = date("Y-m-d H:i:s");
                $pedido_quartos->type = "NEW PRODUCT";
                $pedido_quartos->save();

                $valor_quartos = valorQuarto::where('pedido_produto_id', $pedido_produto->id)->first();
                if (!$valor_quartos) {
                    $valor_quartos = new ValorQuarto;
                }
                $valor_quartos->pedido_produto_id = $pedido_produto->id;
                $valor_quartos->save();
            }
        }
        if ($type == "golf") {
            for ($i = 1; $i <= $qtd; $i++) {
                $pedido_golfs = new PedidoGame;
                $pedido_golfs->pedido_produto_id = $pedido_produto->id;
                $pedido_golfs->data = date("Y-m-d");
                $pedido_golfs->hora = date("H:i:s");
                $pedido_golfs->course = "NEW PRODUCT";
                $pedido_golfs->save();

                $valor_golf = ValorGolf::where('pedido_produto_id', $pedido_produto->id)->first();
                if (!$valor_golf) {
                    $valor_golf = new ValorGolf;
                }
                $valor_golf->pedido_produto_id = $pedido_produto->id;
                $valor_golf->save();
            }
        }

        if ($type == "transfer") {
            for ($i = 1; $i <= $qtd; $i++) {
                $pedido_transfers = new PedidoTransfer;
                $pedido_transfers->pedido_produto_id = $pedido_produto->id;
                $pedido_transfers->data = date("Y-m-d");
                $pedido_transfers->hora = date("H:i:s");
                $pedido_transfers->flight = "NEW PRODUCT";
                $pedido_transfers->save();

                $valor_transfer = ValorTransfer::where('pedido_produto_id', $pedido_produto->id)->first();
                if (!$valor_transfer) {
                    $valor_transfer = new ValorTransfer;
                }
                $valor_transfer->pedido_produto_id = $pedido_produto->id;
                $valor_transfer->save();
            }
        }

        if ($type == "car") {
            for ($i = 1; $i <= $qtd; $i++) {
                $pedido_cars = new PedidoCar;
                $pedido_cars->pedido_produto_id = $pedido_produto->id;
                $pedido_cars->pickup = "NEW PRODUCT";
                $pedido_cars->pickup_data = date("Y-m-d");
                $pedido_cars->pickup_hora = date("H:i:s");
                $pedido_cars->save();

                $valor_car = ValorCar::where('pedido_produto_id', $pedido_produto->id)->first();
                if (!$valor_car) {
                    $valor_car = new ValorCar;
                }
                $valor_car->pedido_produto_id = $pedido_produto->id;
                $valor_car->save();
            }
        }

        if ($type == "ticket") {
            for ($i = 1; $i <= $qtd; $i++) {
                $pedido_tickets = new PedidoTicket;
                $pedido_tickets->pedido_produto_id = $pedido_produto->id;
                $pedido_tickets->remark = "NEW PRODUCT";
                $pedido_tickets->data = date("Y-m-d");
                $pedido_tickets->hora = date("H:i:s");
                $pedido_tickets->save();

                $valor_ticket = ValorTicket::where('pedido_produto_id', $pedido_produto->id)->first();
                if (!$valor_ticket) {
                    $valor_ticket = new ValorTicket;
                }
                $valor_ticket->pedido_produto_id = $pedido_produto->id;
                $valor_ticket->save();
            }
        }

        return response()->json(['success' => 'Produto adicionado com sucesso!']);
    }

    public function editPedidoGeral(Request $request)
    {
        $pedido_geral_id = $request->pedido_geral_id;
        $referency = $request->referency;
        $lead_name = $request->lead_name;

        $pedido_produto = PedidoGeral::where('id', $pedido_geral_id)->update(['lead_name' => $lead_name, 'referencia' => $referency]);

        return response()->json(['success' => 'Edited!']);
    }

    public function sendRemark(Request $request)
    {
        try {

            if ($request->type == "room") {
                $remark_antigo = PedidoQuarto::where('id', '=', $request->pedido_quarto_id)->first()['remark'];
            } else if ($request->type == "golf") {
                $remark_antigo = PedidoGame::where('id', '=', $request->pedido_quarto_id)->first()['remark'];
            } else if ($request->type == "transfer") {
                $remark_antigo = PedidoTransfer::where('id', '=', $request->pedido_quarto_id)->first()['remark'];
            } else if ($request->type == "car") {
                $remark_antigo = PedidoCar::where('id', '=', $request->pedido_quarto_id)->first()['remark'];
            } else if ($request->type == "ticket") {
                $remark_antigo = PedidoTicket::where('id', '=', $request->pedido_quarto_id)->first()['remark'];
            }

            $remark = $remark_antigo;
            if ($request->operador == "ats") {
                $remark .= "<b class='ats_b'>ATS: </b>" . $request->remark . " </b> <br>";
            } else {
                $remark .= "<b class='agency_b'>Agency: </b>" . $request->remark . " </b> <br>";
            }

            if ($request->type == "room") {
                PedidoQuarto::where([['id', '=', $request->pedido_quarto_id]])->update(['remark' => $remark]);
            } else if ($request->type == "golf") {
                PedidoGame::where([['id', '=', $request->pedido_quarto_id]])->update(['remark' => $remark]);
            } else if ($request->type == "transfer") {
                PedidoTransfer::where([['id', '=', $request->pedido_quarto_id]])->update(['remark' => $remark]);
            } else if ($request->type == "car") {
                PedidoCar::where([['id', '=', $request->pedido_quarto_id]])->update(['remark' => $remark]);
            } else if ($request->type == "ticket") {
                PedidoTicket::where([['id', '=', $request->pedido_quarto_id]])->update(['remark' => $remark]);
            }
        } catch (\Throwable $th) {
            throw new Exception($th, 500);
        }
    }

    public function editRemark(Request $request)
    {
        $id = $request->pedido_id;
        if ($request->type == "room") {
            $arr = PedidoQuarto::find($id);
            $arr->update(['remark' => $request->remark]);

            $pedidos = App\PedidoQuarto::where('pedido_produto_id', $arr->pedido_produto_id)->get();

            foreach ($pedidos as $pedido) {
                if ($pedido->id != $id) {
                    $pedido->remark = '';
                    $pedido->update();
                }
            }
        } else if ($request->type == "golf") {
            $arr = App\PedidoGame::find($id);
            $arr->update(['remark' => $request->remark]);
            $pedidos = App\PedidoGame::where('pedido_produto_id', $arr->pedido_produto_id)->get();
            foreach ($pedidos as $pedido) {
                if ($pedido->id != $id) {
                    $pedido->remark = null;
                    $pedido->update();
                }
            }
        } else if ($request->type == "transfer") {
            $arr = PedidoTransfer::find($id);
            $arr->update(['remark' => $request->remark]);
            $pedidos = App\PedidoTransfer::where('pedido_produto_id', $arr->pedido_produto_id)->get();

            foreach ($pedidos as $pedido) {
                if ($pedido->id != $id) {
                    $pedido->remark = "";
                    $pedido->update();
                }
            }
        } else if ($request->type == "car") {
            $arr = PedidoCar::find($id);
            $arr->update(['remark' => $request->remark]);
            $pedidos = App\PedidoCar::where('pedido_produto_id', $arr->pedido_produto_id)->get();
            foreach ($pedidos as $pedido) {
                if ($pedido->id != $id) {
                    $pedido->remark = "";
                    $pedido->update();
                }
            }
        } else if ($request->type == "ticket") {
            $arr = PedidoTicket::find($id);
            $arr->update(['remark' => $request->remark]);
            $pedidos = App\PedidoTicket::where('pedido_produto_id', $arr->pedido_produto_id)->get();
            foreach ($pedidos as $pedido) {
                if ($pedido->id != $id) {
                    $pedido->remark = "";
                    $pedido->update();
                }
            }
        }
        return response()->json('ok');
    }

    // Envia e Edita os pagamentos dos serviços
    public function sendPayment(Request $request)
    {
        $pedido_geral_id = $request->pedido_geral_id;

        if ($request->getPayments) {
            // dd($pedido_payment);
            $all_payments = PedidoPayments::where('pedido_geral_id', $pedido_geral_id)->get();
            return json_encode($all_payments);
        } else {
            $value = $request->payment;
            $date = Carbon::parse($request->date)->format('Y-m-d');
            $pedido_payment_id = $request->pedido_payment_id;
            $pedido_payment = PedidoPayments::where('id', $pedido_payment_id)->first();
            if (!$pedido_payment) {
                $pedido_payment = new PedidoPayments;
            }
            $pedido_payment->payment = $value;
            $pedido_payment->date = $date;
            $pedido_payment->pedido_geral_id = $pedido_geral_id;
            $pedido_payment->save();

            $all_payments = PedidoPayments::where('pedido_geral_id', $pedido_payment->pedido_geral_id)->get();
            return json_encode($all_payments);
        }
    }

    public function removePayment(Request $request)
    {
        $pedido_payment = PedidoPayments::where('id', $request->id)->first();
        $pedido_geral_id = $pedido_payment->pedido_geral_id;
        $pedido_payment->delete();

        $pedido_payment = PedidoPayments::where('pedido_geral_id', $pedido_geral_id)->get();
        $valor = 0;
        foreach ($pedido_payment as $payment) {
            $valor += $payment->payment;
        }

        return response()->json(["success" => "Apagado com sucesso!", "valor" => $valor], 201);
    }
    /* Add By Netto END*/

    public function mailConf(Request $request)
    {
        $pedido_prod_id = $request->prod['pivot']['id'];
        DB::table('pedido_produto')->where([['id', '=', $pedido_prod_id]])->update(['email_check' => 'ok']);
        return response()->json(['result' => 'ok']);
    }

    public function voucher($pedido_id, $pedido_produto_id)
    {

        $pedidoGeral = PedidoGeral::find($pedido_id);
        $pedidoProduto = DB::table('pedido_produto')->find($pedido_produto_id);
        $usuario = User::find($pedidoGeral->user_id);
        $pedidoProduto = $pedidoProduto;
        $produto = Produto::find($pedidoProduto->produto_id);
        $pedidoQuartos = PedidoQuarto::where([['pedido_produto_id', '=', $pedido_produto_id]])->with('pedidoquartoroom.pedidoquartoroomname')->orderBy('checkin')->get();
        $PedidoGames = PedidoGame::where([['pedido_produto_id', '=', $pedido_produto_id]])->orderBy('data')->orderBy('hora')->get();
        $PedidoTransfers = PedidoTransfer::where([['pedido_produto_id', '=', $pedido_produto_id]])->orderBy('data')->orderBy('hora')->get();
        $PedidoCars = PedidoCar::where([['pedido_produto_id', '=', $pedido_produto_id]])->orderBy('pickup_data')->orderBy('pickup_hora')->get();
        $PedidoTickets = PedidoTicket::where([['pedido_produto_id', '=', $pedido_produto_id]])->orderBy('data')->orderBy('hora')->get();

        return view('Admin.profile.voucher', compact('produto', 'pedidoProduto', 'pedidoGeral', 'usuario', 'pedidoQuartos', 'PedidoGames', 'PedidoTransfers', 'PedidoCars', 'PedidoTickets'));
    }

    public function index()
    {

        ini_set('memory_limit', '-1');

        $users_array = ['sales@atravel.pt', 'incoming@atravel.pt', 'transfers@atravel.pt', 'bookings@atravel.pt', 'accounts@atravel.pt'];

        $destinos = Destinos::get();
        $categorias = Categoria::get();
        $produtos = Produto::withTrashed()->get();
        $usuario = Auth::user()->id;
        $utilizadores = User::orderBy('name')->get();
        $cristina = Auth::user()->find(6);
        $extras = DB::table('pedido_produto_extra')->where('pedido_produto_extra.deleted_at', null)->join('extras', 'pedido_produto_extra.extra_id', '=', 'extras.id')->select('*', 'pedido_produto_extra.tipo as tipo', 'pedido_produto_extra.id as id')->orderBy('extras.name', 'ASC')->get();

        $pedidos = PedidoGeralProfile::query();

        $from = Carbon::now()->format("Y-m-d");
        $till = Carbon::now()->add(1, 'year')->format("Y-m-d");
        $pedidos = $pedidos->whereBetween('dataCheckin', [$from, $till]);

        $pedidos = $pedidos->distinct();
        $pedidos = $pedidos->groupBy("id");
        $pedidos = $pedidos->orderByRaw("FIELD(status , 'In Progress', 'Waiting Confirmation', 'Edited', 'Confirmed', 'Cancelled') ASC")->orderBy("dataCheckin", 'DESC');

        if (!in_array(Auth::user()->email, $users_array)) {
            $pedidos = $pedidos->where("user_id", Auth::user()->id);
        }

        $valor = [];
        $valorGolf = [];
        $valorTransfer = [];
        $valorCar = [];
        $valorTicket = [];
        $prod = [];
        $quarto = [];
        $game = [];
        $transfer = [];
        $car = [];
        $ticket = [];
        $geral = [];

        $data = $pedidos->paginate(10);

        foreach ($data as $key => $pedido) {
            $geral[$key] = $pedido;
            $user = User::find($pedido->user_id);
            $geral[$key]['nome'] = $user['name'];

            foreach ($geral[$key]->produtoss as $key1 => $produtoss) {
                unset($produtoss->descricao);
                $prod[$key][$key1] = $produtoss;
                $pedido_prod_id = $prod[$key][$key1]->pivot->id;
                $PedidoQuartos = PedidoQuarto::where([['pedido_produto_id', '=', $pedido_prod_id]])->orderBy('checkin', 'ASC')->orderBy('checkout', 'ASC')->get();
                $ValorQuartos = ValorQuarto::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();

                foreach ($ValorQuartos as $key2 => $ValorQuarto) {
                    $valor[$key][$key1] = $ValorQuarto;
                }
                foreach ($PedidoQuartos as $key2 => $PedidoQuarto) {
                    $dateIN = $PedidoQuarto['checkin'];
                    if ($dateIN) {
                        $nowIN = new \DateTime();
                        $ini = $nowIN->createFromFormat('Y-m-d', $dateIN)->format('d/m/Y');
                    } else {
                        $ini = null;
                    }
                    $dateOUT = $PedidoQuarto['checkout'];
                    if ($dateOUT) {
                        $nowOUT = new \DateTime();
                        $saida = $nowOUT->createFromFormat('Y-m-d', $dateOUT)->format('d/m/Y');
                    } else {
                        $saida = null;
                    }
                    $quarto[$key][$key1][$key2] = $PedidoQuarto;
                    $quarto[$key][$key1][$key2]['ini'] = $ini;
                    $quarto[$key][$key1][$key2]['out'] = $saida;
                }

                $PedidoGames = PedidoGame::where([['pedido_produto_id', '=', $pedido_prod_id]])->orderBy('data', 'ASC')->orderBy('hora', 'ASC')->get();
                $ValorGos = ValorGolf::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();

                foreach ($ValorGos as $key2 => $ValorGo) {
                    $valorGolf[$key][$key1] = $ValorGo;
                }
                foreach ($PedidoGames as $key2 => $PedidoGame) {
                    $game[$key][$key1][$key2] = $PedidoGame;
                }
                $PedidoTransfers = PedidoTransfer::where([['pedido_produto_id', '=', $pedido_prod_id]])->orderBy('data', 'ASC')->orderBy('hora', 'ASC')->get();
                $ValorTrans = ValorTransfer::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();

                foreach ($ValorTrans as $key2 => $ValorTran) {
                    $valorTransfer[$key][$key1] = $ValorTran;
                }
                foreach ($PedidoTransfers as $key2 => $PedidoTransfer) {
                    $transfer[$key][$key1][$key2] = $PedidoTransfer;
                }
                $PedidoCars = PedidoCar::where([['pedido_produto_id', '=', $pedido_prod_id]])->orderBy('pickup_data', 'ASC')->orderBy('pickup_hora', 'ASC')->get();
                $ValorCas = ValorCar::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();

                foreach ($ValorCas as $key2 => $ValorCa) {
                    $valorCar[$key][$key1] = $ValorCa;
                }
                foreach ($PedidoCars as $key2 => $PedidoCar) {
                    $car[$key][$key1][$key2] = $PedidoCar;
                }
                $PedidoTickets = PedidoTicket::where([['pedido_produto_id', '=', $pedido_prod_id]])->orderBy('data', 'ASC')->orderBy('hora', 'ASC')->get();
                $ValorTics = ValorTicket::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();

                foreach ($ValorTics as $key2 => $ValorTic) {
                    $valorTicket[$key][$key1] = $ValorTic;
                }
                foreach ($PedidoTickets as $key2 => $PedidoTicket) {
                    $ticket[$key][$key1][$key2] = $PedidoTicket;
                }
            }
        }

        $tipos_extras = DB::table('extras')->where('produto_extra.deleted_at', null)->join('produto_extra', 'produto_extra.extra_id', '=', 'extras.id')->select('*', 'extras.id as id')->orderBy('extras.name')->get();
        $tipos_extras = $tipos_extras->sortBy("name")->all();

        return view('Admin.profile.index', [
            'geral' => $geral,
            'in' => null,
            'out' => null,
            'tipo' => "-1",
            'lead' => null,
            'operador_id' => null,
            'produtos' => $produtos,
            'utilizadores' => $utilizadores,
            'categorias' => $categorias,
            'destinos' => $destinos,
            'pedidos' => $data,
            'produto' => $prod,
            'quartos' => $quarto,
            'valor' => $valor,
            'valorGolf' => $valorGolf,
            'valorTransfer' => $valorTransfer,
            'valorCar' => $valorCar,
            'valorTicket' => $valorTicket,
            'golfs' => $game,
            'transfers' => $transfer,
            'cars' => $car,
            'tickets' => $ticket,
            'extras' => $extras,
            'tipos_extras' => $tipos_extras
        ]);
    }

    public function search(Request $request)
    {
        ini_set('memory_limit', '-1');

        $inicioPesquisa = Carbon::now();
        $users_array = ['sales@atravel.pt', 'incoming@atravel.pt', 'transfers@atravel.pt', 'bookings@atravel.pt', 'accounts@atravel.pt'];

        $in = $request->get("in");
        $out = $request->get("out");

        $utilizadores = User::get();
        $destinos = Destinos::all();
        $categorias = Categoria::all();
        $produtos = Produto::withTrashed()->get();
        $usuario = Auth::user()->id;

        $extras = DB::table('pedido_produto_extra')->whereNull('pedido_produto_extra.deleted_at')->join('extras', 'pedido_produto_extra.extra_id', '=', 'extras.id')->select('*', 'pedido_produto_extra.id', 'extras.id as id_extras')->get();
        $tipos_extras = DB::table('extras')->where('produto_extra.deleted_at', null)->join('produto_extra', 'produto_extra.extra_id', '=', 'extras.id')->select('*', 'extras.id as id')->orderBy('extras.name')->get();
        $status = ['In Progress', 'Waiting Confirmation', 'Edited', 'Confirmed', 'Cancelled'];

        $inicio = str_replace("%2F", "/", $request->get("in")) ?: null;
        $fim = str_replace("%2F", "/", $request->get("out")) ?: null;

        $pedidos = PedidoGeralProfile::query();

        if ($request->get("in") && $request->get("out") && $request->get("in") != "" && $request->get("out") != "") {

            $from = Carbon::createFromFormat("d/m/Y", $inicio)->format("Y-m-d");
            $till = Carbon::createFromFormat("d/m/Y", $fim)->format("Y-m-d");
            $pedidos = $pedidos->whereBetween('dataCheckin', [$from, $till]);
        }

        if ($request->get("lead") and $request->get("lead") != null and $request->get("lead") != "") {
            $lead = str_replace("%", " ", $request->get("lead"));
            $pedidos = $pedidos->where('lead_name', 'LIKE', "%$lead%");
        }

        if ($request->get("tipo") != "-1" && $request->get("tipo") != "0") {
            $tipo = str_replace("+", " ", $request->get("tipo"));
            $pedidos = $pedidos->where('status', '=', $tipo);
        }

        if ($request->get("operator_id") and $request->get("operator_id") != null and $request->get("operator_id") != "" and $request->get("operator_id") != 0) {
            $pedidos->where('user_id', $request->get("operator_id"));
        }

        if (!in_array(Auth::user()->email, $users_array)) {
            $pedidos = $pedidos->where("user_id", Auth::user()->id);
        }

        $pedidos = $pedidos->distinct();
        $pedidos = $pedidos->with("user", 'produtoss');
        $pedidos = $pedidos->groupBy("id");

        $pedidos = $pedidos->orderByRaw("FIELD(status , 'In Progress', 'Waiting Confirmation', 'Edited', 'Confirmed', 'Cancelled') ASC")->orderBy("dataCheckin", 'DESC');

        if ($request->get("debug")) {
            return response()->json($pedidos->get());
        }

        $pedidos = $pedidos->paginate(10);

        $inicio = str_replace("/", "%2F", $request->get("in")) ?: null;
        $fim = str_replace("/", "%2F", $request->get("out")) ?: null;

        $searchPath = 'search?in=' . $inicio . '&out=' . $fim . '&tipo=' . $request->tipo . '&lead=' . $request->lead . '&operator_id=' . $request->operator_id ?: '0';

        $pedidos = $pedidos->withPath($searchPath);

        $valor = [];
        $valorGolf = [];
        $valorTransfer = [];
        $valorCar = [];
        $valorTicket = [];
        $prod = [];
        $quarto = [];
        $game = [];
        $transfer = [];
        $car = [];
        $ticket = [];
        $geral = [];

        if (isset($pedidos)) {

            foreach ($pedidos as $key => $pedido) {

                $geral[] = $pedido;
                $user = $pedido["user"];
                $geral[$key]['nome'] = $user['name'];

                foreach ($geral[$key]['produtoss'] as $key1 => $produtoss) {
                    unset($produtoss->descricao);

                    $prod[$key][$key1] = $produtoss;

                    $pedido_prod_id = $prod[$key][$key1]['pivot']['id'];

                    $PedidoQuartos = PedidoQuarto::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();
                    $ValorQuartos = ValorQuarto::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();
                    foreach ($ValorQuartos as $key2 => $ValorQuarto) {
                        $valor[$key][$key1] = $ValorQuarto;
                    }
                    foreach ($PedidoQuartos as $key2 => $PedidoQuarto) {
                        $dateIN = $PedidoQuarto['checkin'];
                        if ($dateIN) {
                            $nowIN = new \DateTime();
                            $ini = $nowIN->createFromFormat('Y-m-d', $dateIN)->format('d/m/Y');
                        } else {
                            $ini = null;
                        }
                        $dateOUT = $PedidoQuarto['checkout'];
                        if ($dateOUT) {
                            $nowOUT = new \DateTime();
                            $saida = $nowOUT->createFromFormat('Y-m-d', $dateOUT)->format('d/m/Y');
                        } else {
                            $saida = null;
                        }

                        $quarto[$key][$key1][$key2] = $PedidoQuarto;
                        $quarto[$key][$key1][$key2]['ini'] = $ini;
                        $quarto[$key][$key1][$key2]['out'] = $saida;
                    }

                    $PedidoGames = PedidoGame::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();
                    $ValorGos = ValorGolf::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();
                    foreach ($ValorGos as $key2 => $ValorGo) {
                        $valorGolf[$key][$key1] = $ValorGo;
                    }
                    foreach ($PedidoGames as $key2 => $PedidoGame) {
                        $game[$key][$key1][$key2] = $PedidoGame;
                    }
                    $PedidoTransfers = PedidoTransfer::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();
                    $ValorTrans = ValorTransfer::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();
                    foreach ($ValorTrans as $key2 => $ValorTran) {
                        $valorTransfer[$key][$key1] = $ValorTran;
                    }
                    foreach ($PedidoTransfers as $key2 => $PedidoTransfer) {
                        $transfer[$key][$key1][$key2] = $PedidoTransfer;
                    }

                    $PedidoCars = PedidoCar::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();
                    $ValorCas = ValorCar::where([['pedido_produto_id', '=', $pedido_prod_id]])->get();
                    foreach ($ValorCas as $key2 => $ValorCa) {
                        $valorCar[$key][$key1] = $ValorCa;
                    }
                    foreach ($PedidoCars as $key2 => $PedidoCar) {
                        $car[$key][$key1][$key2] = $PedidoCar;
                    }

                    if ($produtoss->ticket == 1) {
                        $PedidoTickets = PedidoTicket::where('pedido_produto_id', $pedido_prod_id)->get();
                        $ValorTics = ValorTicket::where('pedido_produto_id', $pedido_prod_id)->get();

                        foreach ($ValorTics as $key2 => $ValorTic) {
                            $valorTicket[$key][$key1] = $ValorTic;
                        }
                        foreach ($PedidoTickets as $key2 => $PedidoTicket) {
                            $ticket[$key][$key1][$key2] = $PedidoTicket;
                        }
                    }
                }
            }
        } else {
            $pedidos = array();
        }

        return view('Admin.profile.index', [
            'geral' => $geral,
            'in' => $in,
            'out' => $out,
            'tipo' => $request->get("tipo"),
            'lead' => $request->get("lead"),
            'operador_id' => $request->get("operator_id"),
            'produtos' => $produtos,
            'utilizadores' => $utilizadores,
            'categorias' => $categorias,
            'destinos' => $destinos,
            'pedidos' => $pedidos,
            'produto' => $prod,
            'quartos' => $quarto,
            'valor' => $valor,
            'valorGolf' => $valorGolf,
            'valorTransfer' => $valorTransfer,
            'valorCar' => $valorCar,
            'valorTicket' => $valorTicket,
            'golfs' => $game,
            'transfers' => $transfer,
            'cars' => $car,
            'tickets' => $ticket,
            'extras' => $extras,
            'tipos_extras' => $tipos_extras,
            'status' => $status,
        ]);
    }

    public function save_data(Request $request)
    {

        try {

            $key_id = $request->key;
            /* CONVERTER REQUEST DOS QUARTOS EM ARRAY'S */
            $quartos = explode('&', $request->quartos);
            // dd($request);
            if ($request->quartos) {
                foreach ($quartos as $key => $value) {
                    $temp[$key] = explode('=', $value);
                    foreach ($temp[$key] as $key2 => $value2) {
                        if ($key2 == 0) {
                            $nome = $value2;
                        } else {
                            $dado = $value2;
                        }
                    }
                    $quarto[$nome] = $dado;
                }
                /* CONVERTER REQUEST DOS QUARTOS EM ARRAY'S */
                /* SEPARAR VARIÁVEIS DOS ARRAY'S DOS QUARTOS */
                $last_key = 0;
                foreach ($quarto as $key => $value) {
                    if (stristr($key, 'produto_id_') == true) {
                        $produto_key = intval(preg_replace('/[^0-9]+/', '', $key), 10);
                        $last_key = 0;
                    }

                    if ($key == 'produto_id_' . $produto_key) {
                        $produto[$produto_key]['id'] = $value;
                        $produto[$produto_key]['nome'] = 'quartos';
                    } elseif ($key == 'key_max_' . $produto_key) {
                        $key_max[$produto_key] = $value;
                    } elseif ($key == 'pedido_produto_id' . $produto_key) {
                        $pedido_produto_id[$produto_key] = $value;
                    } elseif ($key == stristr($key, 'quarto_id' . $key_id . '_' . $produto_key . '_')) {
                        $quarto_id[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'tipologia' . $key_id . '_' . $produto_key . '_')) {
                        $tipologia[$key_id][$produto_key][$last_key] = urldecode($value);
                    } elseif ($key == stristr($key, 'quantidade' . $key_id . '_' . $produto_key . '_')) {
                        $quantidade[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'init' . $key_id . '_' . $produto_key . '_')) {
                        $data = substr(urldecode($value), 0, 2) . '-' . substr(urldecode($value), 3, 2) . '-' . substr(urldecode($value), 6, 4);
                        $init[$key_id][$produto_key][$last_key] = Carbon::parse($data)->format('Y-m-d');
                    } elseif ($key == stristr($key, 'checkout' . $key_id . '_' . $produto_key . '_')) {
                        $data = substr(urldecode($value), 0, 2) . '-' . substr(urldecode($value), 3, 2) . '-' . substr(urldecode($value), 6, 4);
                        $checkout[$key_id][$produto_key][$last_key] = Carbon::parse($data)->format('Y-m-d');
                    } elseif ($key == stristr($key, 'pessoas' . $key_id . '_' . $produto_key . '_')) {
                        $pessoas[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'board' . $key_id . '_' . $produto_key . '_')) {
                        $board[$key_id][$produto_key][$last_key] = str_replace("%20", " ", $value);
                    } elseif ($key == stristr($key, 'real' . $key_id . '_' . $produto_key . '_')) {
                        $real[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'oferta' . $key_id . '_' . $produto_key . '_')) {
                        $oferta[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'desconto' . $key_id . '_' . $produto_key . '_')) {
                        $desconto[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'atsRate' . $key_id . '_' . $produto_key . '_')) {
                        $atsRate[$key_id][$produto_key][$last_key] = $value;
                        $last_key++;
                    }
                }
            }
            /* SEPARAR VARIÁVEIS DOS ARRAY'S DOS QUARTOS */

            /* CONVERTER REQUEST DOS GOLFES EM ARRAY'S */
            if ($request->golfe) {
                $golfes = explode('&', $request->golfe);

                foreach ($golfes as $key => $value) {
                    $temp[$key] = explode('=', $value);
                    foreach ($temp[$key] as $key2 => $value2) {
                        if ($key2 == 0) {
                            $nome = $value2;
                        } else {
                            $dado = $value2;
                        }
                    }
                    $golfe[$nome] = $dado;
                }
                /* CONVERTER REQUEST DOS GOLFES EM ARRAY'S */
                /* SEPARAR VARIÁVEIS DOS ARRAY'S DOS GOLFES */
                $last_key = 0;
                foreach ($golfe as $key => $value) {
                    if (stristr($key, 'produto_id_') == true) {
                        $produto_key = intval(preg_replace('/[^0-9]+/', '', $key), 10);
                        $last_key = 0;
                    }

                    if ($key == 'produto_id_' . $produto_key) {
                        $produto[$produto_key]['id'] = $value;
                        $produto[$produto_key]['nome'] = 'golfe';
                    } elseif ($key == 'key_max_' . $produto_key) {
                        $key_max[$produto_key] = $value;
                    } elseif ($key == 'pedido_produto_id' . $produto_key) {
                        $pedido_produto_id[$produto_key] = $value;
                    } elseif ($key == stristr($key, 'golfe_id' . $key_id . '_' . $produto_key . '_')) {
                        $golfe_id[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'golfe_data' . $key_id . '_' . $produto_key . '_')) {
                        $data = substr(urldecode($value), 0, 2) . '-' . substr(urldecode($value), 3, 2) . '-' . substr(urldecode($value), 6, 4);
                        $golfe_data[$key_id][$produto_key][$last_key] = $data;
                    } elseif ($key == stristr($key, 'golfe_hora' . $key_id . '_' . $produto_key . '_')) {
                        $golfe_hora[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'golfe_course' . $key_id . '_' . $produto_key . '_')) {
                        $golfe_course[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'golfe_people' . $key_id . '_' . $produto_key . '_')) {
                        $golfe_people[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'playersFree' . $key_id . '_' . $produto_key . '_')) {
                        $playersFree[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'realGolf' . $key_id . '_' . $produto_key . '_')) {
                        $realGolf[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'atsRateGolf' . $key_id . '_' . $produto_key . '_')) {
                        $atsRateGolf[$key_id][$produto_key][$last_key] = $value;
                        $last_key++;
                    }
                }
            }
            /* SEPARAR VARIÁVEIS DOS ARRAY'S DOS GOLFES */
            /* CONVERTER REQUEST DOS TRANSFERS EM ARRAY'S */
            if ($request->transfers) {

                $transfers = explode('&', $request->transfers);

                foreach ($transfers as $key => $value) {
                    $temp[$key] = explode('=', $value);
                    foreach ($temp[$key] as $key2 => $value2) {
                        if ($key2 == 0) {
                            $nome = $value2;
                        } else {
                            $dado = $value2;
                        }
                    }
                    $transfer[$nome] = $dado;
                }
                /* CONVERTER REQUEST DOS TRANSFERS EM ARRAY'S */
                /* SEPARAR VARIÁVEIS DOS ARRAY'S DOS TRANSFERS */
                $last_key = 0;
                foreach ($transfer as $key => $value) {
                    if (stristr($key, 'produto_id_') == true) {
                        $produto_key = intval(preg_replace('/[^0-9]+/', '', $key), 10);
                        $last_key = 0;
                    }

                    if ($key == 'produto_id_' . $produto_key) {
                        $produto[$produto_key]['id'] = $value;
                        $produto[$produto_key]['nome'] = 'transfers';
                    } elseif ($key == 'key_max_' . $produto_key) {
                        $key_max[$produto_key] = $value;
                    } elseif ($key == 'pedido_produto_id' . $produto_key) {
                        $pedido_produto_id[$produto_key] = $value;
                    } elseif ($key == stristr($key, 'transfer_id' . $key_id . '_' . $produto_key . '_')) {
                        $transfer_id[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'data' . $key_id . '_' . $produto_key . '_')) {
                        $data_tratamento = substr(urldecode($value), 0, 2) . '-' . substr(urldecode($value), 3, 2) . '-' . substr(urldecode($value), 6, 4);
                        $transfer_data[$key_id][$produto_key][$last_key] = $data_tratamento;
                    } elseif ($key == stristr($key, 'hora' . $key_id . '_' . $produto_key . '_')) {
                        $transfer_hora[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'adult' . $key_id . '_' . $produto_key . '_')) {
                        $adult[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'children' . $key_id . '_' . $produto_key . '_')) {
                        $children[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'babie' . $key_id . '_' . $produto_key . '_')) {
                        $babie[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'flight' . $key_id . '_' . $produto_key . '_')) {
                        $flight[$key_id][$produto_key][$last_key] = urldecode($value);
                    } elseif ($key == stristr($key, 'pickup' . $key_id . '_' . $produto_key . '_')) {
                        $pickup[$key_id][$produto_key][$last_key] = urldecode($value);
                    } elseif ($key == stristr($key, 'dropoff' . $key_id . '_' . $produto_key . '_')) {
                        $dropoff[$key_id][$produto_key][$last_key] = urldecode($value);
                    } elseif ($key == stristr($key, 'company' . $key_id . '_' . $produto_key . '_')) {
                        $company[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'realTransfer' . $key_id . '_' . $produto_key . '_')) {
                        $realtransfer[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'atsRateTransfer' . $key_id . '_' . $produto_key . '_')) {
                        $atsRateTransfer[$key_id][$produto_key][$last_key] = $value;
                        $last_key++;
                    }
                }
            }
            /* SEPARAR VARIÁVEIS DOS ARRAY'S DOS TRANSFERS */

            /* CONVERTER REQUEST DOS CARS EM ARRAY'S */
            if ($request->cars) {

                $cars = explode('&', $request->cars);

                foreach ($cars as $key => $value) {

                    $temp[$key] = explode('=', $value);
                    foreach ($temp[$key] as $key2 => $value2) {
                        if ($key2 == 0) {
                            $nome = $value2;
                        } else {
                            $dado = $value2;
                        }
                    }

                    $car[$nome] = $dado;
                }

                /* CONVERTER REQUEST DOS CARS EM ARRAY'S */
                /* SEPARAR VARIÁVEIS DOS ARRAY'S DOS CARS */
                $last_key = 0;

                foreach ($car as $key => $value) {

                    if (stristr($key, 'produto_id_') == true) {
                        $produto_key = intval(preg_replace('/[^0-9]+/', '', $key), 10);
                        $last_key = 0;
                    }

                    if ($key == 'produto_id_' . $produto_key) {
                        $produto[$produto_key]['id'] = $value;
                        $produto[$produto_key]['nome'] = 'cars';
                    } elseif ($key == 'key_max_' . $produto_key) {
                        $key_max[$produto_key] = $value;
                    } elseif ($key == 'pedido_produto_id' . $produto_key) {
                        $pedido_produto_id[$produto_key] = $value;
                    } elseif ($key == stristr($key, 'car_id' . $key_id . '_' . $produto_key . '_')) {
                        $car_id[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'car_pickup_name_' . $key_id . '_' . $produto_key . '_')) {
                        $car_pickup_name_[$key_id][$produto_key][$last_key] = urldecode($value);
                    } elseif ($key == stristr($key, 'car_pickup_date_' . $key_id . '_' . $produto_key . '_')) {
                        $data_tratamento = substr(urldecode($value), 0, 2) . '-' . substr(urldecode($value), 3, 2) . '-' . substr(urldecode($value), 6, 4);
                        $car_pickup_date_[$key_id][$produto_key][$last_key] = $data_tratamento;
                    } elseif ($key == stristr($key, 'car_pickup_hora_' . $key_id . '_' . $produto_key . '_')) {
                        $car_pickup_hora_[$key_id][$produto_key][$last_key] = urldecode($value);
                    } elseif ($key == stristr($key, 'car_pickup_flight_' . $key_id . '_' . $produto_key . '_')) {
                        $car_pickup_flight_[$key_id][$produto_key][$last_key] = urldecode($value);
                    } elseif ($key == stristr($key, 'car_pickup_country_' . $key_id . '_' . $produto_key . '_')) {
                        $car_pickup_country_[$key_id][$produto_key][$last_key] = urldecode($value);
                    } elseif ($key == stristr($key, 'car_pickup_airport_' . $key_id . '_' . $produto_key . '_')) {
                        $car_pickup_airport_[$key_id][$produto_key][$last_key] = urldecode($value);
                    } elseif ($key == stristr($key, 'car_dropoff_name_' . $key_id . '_' . $produto_key . '_')) {
                        $car_dropoff_name_[$key_id][$produto_key][$last_key] = urldecode($value);
                    } elseif ($key == stristr($key, 'car_dropoff_date_' . $key_id . '_' . $produto_key . '_')) {
                        $data_tratamento = substr(urldecode($value), 0, 2) . '-' . substr(urldecode($value), 3, 2) . '-' . substr(urldecode($value), 6, 4);
                        $car_dropoff_date_[$key_id][$produto_key][$last_key] = $data_tratamento;
                    } elseif ($key == stristr($key, 'car_dropoff_hora_' . $key_id . '_' . $produto_key . '_')) {
                        $car_dropoff_hora_[$key_id][$produto_key][$last_key] = urldecode($value);
                    } elseif ($key == stristr($key, 'car_dropoff_flight_' . $key_id . '_' . $produto_key . '_')) {
                        $car_dropoff_flight_[$key_id][$produto_key][$last_key] = urldecode($value);
                    } elseif ($key == stristr($key, 'car_group_' . $key_id . '_' . $produto_key . '_')) {
                        $car_group_[$key_id][$produto_key][$last_key] = urldecode($value);
                    } elseif ($key == stristr($key, 'car_model_' . $key_id . '_' . $produto_key . '_')) {
                        $car_model_[$key_id][$produto_key][$last_key] = urldecode($value);
                    } elseif ($key == stristr($key, 'realCar' . $key_id . '_' . $produto_key . '_')) {
                        $realCar[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'tax' . $key_id . '_' . $produto_key . '_')) {
                        $tax[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'atsRateCar' . $key_id . '_' . $produto_key . '_')) {
                        $atsRateCar[$key_id][$produto_key][$last_key] = $value;
                        $last_key++;
                    }
                }
            }

            if ($request->tickets) {
                $tickets = explode('&', $request->tickets);
                foreach ($tickets as $key => $value) {
                    $temp[$key] = explode('=', $value);
                    foreach ($temp[$key] as $key2 => $value2) {
                        if ($key2 == 0) {
                            $nome = $value2;
                        } else {
                            $dado = $value2;
                        }
                    }
                    $ticket[$nome] = $dado;
                }
                /* CONVERTER REQUEST DOS TICKETS EM ARRAY'S */
                /* SEPARAR VARIÁVEIS DOS ARRAY'S DOS TICKETS */
                $last_key = 0;
                foreach ($ticket as $key => $value) {
                    if (stristr($key, 'produto_id_') == true) {
                        $produto_key = intval(preg_replace('/[^0-9]+/', '', $key), 10);
                        $last_key = 0;
                    }

                    if ($key == 'produto_id_' . $produto_key) {
                        $produto[$produto_key]['id'] = $value;
                        $produto[$produto_key]['nome'] = 'tickets';
                    } elseif ($key == 'key_max_' . $produto_key) {
                        $key_max[$produto_key] = $value;
                    } elseif ($key == 'pedido_produto_id' . $produto_key) {
                        $pedido_produto_id[$produto_key] = $value;
                    } elseif ($key == stristr($key, 'ticket_id' . $key_id . '_' . $produto_key . '_')) {
                        $ticket_id[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'data' . $key_id . '_' . $produto_key . '_')) {
                        $data_tratamento = substr(urldecode($value), 0, 2) . '-' . substr(urldecode($value), 3, 2) . '-' . substr(urldecode($value), 6, 4);
                        $ticket_data[$key_id][$produto_key][$last_key] = $data_tratamento;
                    } elseif ($key == stristr($key, 'hora' . $key_id . '_' . $produto_key . '_')) {
                        $ticket_hora[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'ticket_adult' . $key_id . '_' . $produto_key . '_')) {
                        $ticket_adult[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'ticket_children' . $key_id . '_' . $produto_key . '_')) {
                        $ticket_children[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'ticket_babie' . $key_id . '_' . $produto_key . '_')) {
                        $ticket_babie[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'realTicket' . $key_id . '_' . $produto_key . '_')) {
                        $realTicket[$key_id][$produto_key][$last_key] = $value;
                    } elseif ($key == stristr($key, 'atsRateTicket' . $key_id . '_' . $produto_key . '_')) {
                        $atsRateTicket[$key_id][$produto_key][$last_key] = $value;
                        $last_key++;
                    }
                }
            }
            /* SEPARAR VARIÁVEIS DOS ARRAY'S DOS TICKETS */

            if (count($produto) < $produto_key + 1) {
                $numero_produtos = $produto_key + 1;
            } else {
                $numero_produtos = count($produto);
            }

            for ($i = 0; $i < $numero_produtos; $i++) {

                //$pedido_produto = DB::table('pedido_produto')->where('pedido_geral_id',$request->id)->where('produto_id',$produto[$i]['id'])->first();
                /* GUARDAR DADOS NOS PEDIDOS DE QUARTO */
                if (isset($produto[$i])) {
                    if ($produto[$i]['nome'] == 'quartos') {
                        for ($j = 0; $j < $key_max[$i]; $j++) {
                            if ($quarto_id[$key_id][$i][$j] != 0) {
                                $pedido_quartos = PedidoQuarto::findOrFail($quarto_id[$key_id][$i][$j]);
                            } else {
                                $pedido_quartos = new PedidoQuarto;
                                $pedido_quartos->pedido_produto_id = $pedido_produto_id[$i];
                            }
                            $pedido_quartos->type = $tipologia[$key_id][$i][$j];
                            $pedido_quartos->rooms = $quantidade[$key_id][$i][$j];
                            $pedido_quartos->checkin = $init[$key_id][$i][$j];
                            $pedido_quartos->checkout = $checkout[$key_id][$i][$j];
                            $pedido_quartos->people = $pessoas[$key_id][$i][$j];
                            $pedido_quartos->plan = $board[$key_id][$i][$j];
                            $pedido_quartos->night = $real[$key_id][$i][$j];
                            if ($oferta[$key_id][$i][$j] != null) {
                                $pedido_quartos->offer_name = urldecode($oferta[$key_id][$i][$j]);
                            }
                            if ($desconto[$key_id][$i][$j] != null) {
                                $pedido_quartos->offer = $desconto[$key_id][$i][$j];
                            }
                            $pedido_quartos->ats_rate = $atsRate[$key_id][$i][$j];
                            $pedido_quartos->save();
                        }
                        /* GUARDAR DADOS NOS PEDIDOS DE QUARTO */
                        /* GUARDAR DADOS NOS PEDIDOS DE GOLFE */
                    } elseif ($produto[$i]['nome'] == 'golfe') {

                        for ($j = 0; $j < $key_max[$i]; $j++) {

                            if ($golfe_id[$key_id][$i][$j] != 0) {
                                $pedido_golfes = PedidoGame::findOrFail($golfe_id[$key_id][$i][$j]);
                            } else {
                                // dd($pedido_produto_id[$i]);
                                $pedido_golfes = new PedidoGame;
                                $pedido_golfes->pedido_produto_id = $pedido_produto_id[$i];
                            }
                            $pedido_golfes->data = Carbon::parse($golfe_data[$key_id][$i][$j])->format('Y-m-d');
                            $pedido_golfes->hora = urldecode($golfe_hora[$key_id][$i][$j]);
                            $pedido_golfes->course = urldecode($golfe_course[$key_id][$i][$j]);
                            $pedido_golfes->people = intval($golfe_people[$key_id][$i][$j]);
                            $pedido_golfes->free = intval($playersFree[$key_id][$i][$j]);
                            $pedido_golfes->rate = doubleval($realGolf[$key_id][$i][$j]);
                            $pedido_golfes->ats_rate = doubleval($atsRateGolf[$key_id][$i][$j]);
                            $pedido_golfes->save();
                        }
                        /* GUARDAR DADOS NOS PEDIDOS DE GOLFE */
                        /* GUARDAR DADOS NOS PEDIDOS DE TRANSFERS */
                    } elseif ($produto[$i]['nome'] == 'transfers') {
                        for ($j = 0; $j < $key_max[$i]; $j++) {
                            if ($transfer_id[$key_id][$i][$j] != 0) {
                                $pedido_transfer = PedidoTransfer::findOrFail($transfer_id[$key_id][$i][$j]);
                            } else {
                                $pedido_transfer = new PedidoTransfer;
                                $pedido_transfer->pedido_produto_id = $pedido_produto_id[$i];
                            }
                            $pedido_transfer->data = Carbon::parse($transfer_data[$key_id][$i][$j])->format('Y-m-d');
                            $pedido_transfer->hora = urldecode($transfer_hora[$key_id][$i][$j]);
                            $pedido_transfer->adult = intval($adult[$key_id][$i][$j]);
                            $pedido_transfer->children = intval($children[$key_id][$i][$j]);
                            $pedido_transfer->babie = intval($babie[$key_id][$i][$j]);
                            if ($flight[$key_id][$i][$j]) {
                                $pedido_transfer->flight = urldecode($flight[$key_id][$i][$j]);
                            } else {
                                $pedido_transfer->flight = '';
                            }
                            $pedido_transfer->pickup = $pickup[$key_id][$i][$j];
                            $pedido_transfer->dropoff = $dropoff[$key_id][$i][$j];

                            $pedido_transfer->company = urldecode($company[$key_id][$i][$j]);
                            if ($realtransfer[$key_id][$i][$j]) {
                                $pedido_transfer->total = doubleval($realtransfer[$key_id][$i][$j]);
                            }

                            if ($atsRateTransfer[$key_id][$i][$j]) {
                                $pedido_transfer->ats_rate = doubleval($atsRateTransfer[$key_id][$i][$j]);
                            }
                            $pedido_transfer->save();
                        }
                        /* GUARDAR DADOS NOS PEDIDOS DE TRANSFERS */
                        /* GUARDAR DADOS NOS PEDIDOS DE CARS */
                    } elseif ($produto[$i]['nome'] == 'cars') {

                        /* Alterado de :
                        for ($j = 0; $j < $key_max[$i]; $j++) {
                        Para:
                        for ($j = 0; $j <= $key_max[$i]; $j++) {
                         */
                        for ($j = 0; $j <= $key_max[$i]; $j++) {

                            if (isset($car_id[$key_id][$i][$j])) {
                                if ($car_id[$key_id][$i][$j] != 0) {
                                    $pedido_cars = PedidoCar::findOrFail($car_id[$key_id][$i][$j]);
                                } else {
                                    $pedido_cars = new PedidoCar;
                                    $pedido_cars->pedido_produto_id = $pedido_produto_id[$i];
                                }
                                // dd($car_pickup_date_[$key_id][$i][$j], $car_dropoff_date_[$key_id][$i][$j]);
                                $pedido_cars->pickup_data = Carbon::parse($car_pickup_date_[$key_id][$i][$j])->format('Y-m-d');
                                $pedido_cars->pickup_hora = $car_pickup_hora_[$key_id][$i][$j];
                                $pedido_cars->pickup = $car_pickup_name_[$key_id][$i][$j];
                                $pedido_cars->pickup_flight = $car_pickup_flight_[$key_id][$i][$j];
                                $pedido_cars->pickup_country = $car_pickup_country_[$key_id][$i][$j];
                                $pedido_cars->pickup_airport = $car_pickup_airport_[$key_id][$i][$j];
                                $pedido_cars->dropoff_data = Carbon::parse($car_dropoff_date_[$key_id][$i][$j])->format('Y-m-d');
                                $pedido_cars->dropoff_hora = $car_dropoff_hora_[$key_id][$i][$j];
                                $pedido_cars->dropoff = $car_dropoff_name_[$key_id][$i][$j];
                                $pedido_cars->dropoff_flight = $car_dropoff_flight_[$key_id][$i][$j];
                                // $pedido_cars->dropoff_country    = urldecode($car_dropoff_country_[$key_id][$i][$j]);
                                // $pedido_cars->dropoff_airport    = urldecode($car_dropoff_airport_[$key_id][$i][$j]);
                                $pedido_cars->group = $car_group_[$key_id][$i][$j];
                                $pedido_cars->model = $car_model_[$key_id][$i][$j];
                                $pedido_cars->rate = $realCar[$key_id][$i][$j];
                                $pedido_cars->ats_rate = $atsRateCar[$key_id][$i][$j];
                                $pedido_cars->tax = $tax[$key_id][$i][$j];
                                $pedido_cars->save();
                            }
                        }
                        /* GUARDAR DADOS NOS PEDIDOS DE CARS */
                        /* GUARDAR DADOS NOS PEDIDOS DE TICKETS */
                    } elseif ($produto[$i]['nome'] == 'tickets') {
                        for ($j = 0; $j < $key_max[$i]; $j++) {
                            if ($ticket_id[$key_id][$i][$j] != 0) {
                                $pedido_tickets = PedidoTicket::findOrFail($ticket_id[$key_id][$i][$j]);
                            } else {
                                $pedido_tickets = new PediPedidoTicketdoGame;
                                $pedido_tickets->pedido_produto_id = $pedido_produto_id[$i];
                            }

                            $pedido_tickets->data = Carbon::parse($ticket_data[$key_id][$i][$j])->format('Y-m-d');
                            $pedido_tickets->hora = $ticket_hora[$key_id][$i][$j];
                            $pedido_tickets->adult = intval($ticket_adult[$key_id][$i][$j]);
                            $pedido_tickets->children = intval($ticket_children[$key_id][$i][$j]);
                            $pedido_tickets->babie = intval($ticket_babie[$key_id][$i][$j]);
                            $pedido_tickets->total = doubleval($realTicket[$key_id][$i][$j]);
                            $pedido_tickets->ats_rate = doubleval($atsRateTicket[$key_id][$i][$j]);
                            $pedido_tickets->save();
                        }
                    }
                }
            }

            return response()->json("Dados cadastrado com sucesso");
        } catch (Exception $ex) {

            dd($ex);
        }
    }

    public function create(Request $request)
    {

        try {

            if (($request->quartos) || ($request->golfe) || ($request->transfers) || ($request->cars) || ($request->tickets)) {
                $data_saved = $this->save_data($request);
            }

            $profit = $request->get('profit');
            PedidoGeral::find($request->id)->update(['valor' => $request->total, 'profit' => $profit, 'status' => $request->status]);
            return response()->json(['result' => ['valor' => 'nada', PedidoGeral::find($request->id)->toArray()]]);
        } catch (ModelNotFoundException $ex) {

            return response($ex);
        }
    }

    public function confirm(Request $request)
    {

        try {

            if (isset($request->cancel)) {
                if ($request->cancel == true) {
                    $pedido = PedidoGeral::find($request->id);
                    $usuario = User::find($pedido->user_id);
                }
            }

            if (($request->quartos) || ($request->golfe) || ($request->transfers) || ($request->cars) || ($request->tickets)) {
                $data_saved = $this->save_data($request);
            }
            if (isset($request->valor) && isset($request->profit)) {
                $pedido = PedidoGeral::find($request->id);

                if ($pedido) {
                    $pedido->update(['status' => $request->status, 'type' => 'New Booking', 'valor' => $request->valor, 'profit' => $request->profit]);
                }
            } else {
                $pedido = PedidoGeral::find($request->id);

                if ($pedido) {
                    $pedido->update(['status' => $request->status, 'type' => 'New Booking']);
                }
            }

            return response()->json(['result' => ['valor' => 'nada', 'pedido' => $pedido]]);
        } catch (Exception $ex) {
            dd($ex);
        }
    }

    public function createProduct(Request $request)
    {
        $pedidoProduct = DB::table('pedido_produto')->where([['id', '=', $request->id]]);
        if ($pedidoProduct) {
            $pedidoProduct->update(['valor' => $request->total, 'profit' => $request->profit]);
        }
        return response()->json(['result' => ['valor' => 'nada'], 'dataRequest' => $request->all()]);
    }

    public function createProductRooms(Request $request)
    {
        ValorQuarto::where([['pedido_produto_id', '=', $request->id]])->update(['valor_quarto' => $request->room, 'valor_extra' => $request->extra, 'kick' => $request->kick, 'markup' => $request->markup, 'total' => $request->total, 'profit' => $request->profit]);
        return response()->json(['result' => ['valor' => 'nada']]);
    }

    public function createProductRoomsEsp(Request $request)
    {

        $pedidoQuarto = PedidoQuarto::find($request->id);
        if ($pedidoQuarto) {
            $pedidoQuarto->update(['days' => $request->days, 'night' => $request->night, 'offer_name' => $request->offer_name, 'offer' => $request->offer, 'price' => $request->price, 'total' => $request->total, 'ats_rate' => $request->ats_rate, 'ats_total_rate' => $request->ats_total_rate, 'profit' => $request->profit]);
        }

        return response()->json(['result' => ['valor' => 'nada']]);
    }

    public function createProductGolfs(Request $request)
    {
        ValorGolf::where([['pedido_produto_id', '=', $request->id]])->update(['valor_golf' => $request->valor_golf, 'valor_extra' => $request->valor_extra, 'kick' => $request->kick, 'markup' => $request->markup, 'total' => $request->total, 'profit' => $request->profit]);
        return response()->json(['result' => ['valor' => 'nada']]);
    }

    public function createProductGolfsEsp(Request $request)
    {
        PedidoGame::find($request->id)->update(['free' => $request->free, 'rate' => $request->rate, 'total' => $request->total, 'ats_rate' => $request->ats_rate, 'ats_total_rate' => $request->ats_total_rate, 'profit' => $request->profit]);
        return response()->json(['result' => ['valor' => 'nada']]);
    }

    public function createProductTransfers(Request $request)
    {
        ValorTransfer::where([['pedido_produto_id', '=', $request->id]])->update(['valor_transfer' => $request->valor_transfer, 'valor_extra' => $request->valor_extra, 'kick' => $request->kick, 'markup' => $request->markup, 'total' => $request->total, 'profit' => $request->profit]);
        return response()->json(['result' => ['valor' => 'nada']]);
    }

    public function createProductTransfersEsp(Request $request)
    {
        try {
            PedidoTransfer::find($request->id)->update(['company' => $request->company, 'total' => $request->total, 'ats_rate' => $request->ats_rate, 'profit' => $request->profit]);
            //PedidoTransfer::find($request->id)->update($request->all());
            return response()->json(['result' => ['valor' => 'Atualizado']]);
        } catch (Exception $e) {

            return response()->json($e->getMessage());
        }
    }

    public function createProductCars(Request $request)
    {
        ValorCar::where([['pedido_produto_id', '=', $request->id]])->update(['valor_car' => $request->valor_car, 'valor_extra' => $request->valor_extra, 'kick' => $request->kick, 'markup' => $request->markup, 'total' => $request->total, 'profit' => $request->profit]);
        return response()->json(['result' => ['valor' => 'nada']]);
    }

    public function createProductCarsEsp(Request $request)
    {
        PedidoCar::find($request->id)->update(['rate' => $request->rate, 'days' => $request->days, 'tax' => $request->tax, 'total' => $request->total, 'ats_rate' => $request->ats_rate, 'ats_total_rate' => $request->ats_total_rate, 'profit' => $request->profit]);
        return response()->json(['result' => ['valor' => 'nada']]);
    }

    public function createProductTickets(Request $request)
    {
        ValorTicket::where([['pedido_produto_id', '=', $request->id]])->update(['valor_ticket' => $request->valor_ticket, 'valor_extra' => $request->valor_extra, 'kick' => $request->kick, 'markup' => $request->markup, 'total' => $request->total, 'profit' => $request->profit]);
        return response()->json(['result' => ['valor' => 'nada']]);
    }

    public function createProductTicketsEsp(Request $request)
    {
        PedidoTicket::find($request->id)->update(['total' => $request->total, 'ats_rate' => $request->ats_rate, 'profit' => $request->profit]);
        return response()->json(['result' => ['valor' => 'nada']]);
    }

    public function createProductExtra(Request $request)
    {
        // dd($tipo);
        if ($request->tipo == 1) {
            $tipo = "alojamento";
        } elseif ($request->tipo == 2) {
            $tipo = "golf";
        } elseif ($request->tipo == 3) {
            $tipo = "transfer";
        } elseif ($request->tipo == 4) {
            $tipo = "cars";
        } elseif ($request->tipo == 5) {
            $tipo = "tickets";
        }

        if ($request->id != 0) {
            $produtoExtra = PedidoProdutoExtra::where('id', $request->id)->first();

            if ($produtoExtra) {

                if ($request->extra_name) {

                    $produtoExtra->extra_id = $request->extra_name;
                }
                $produtoExtra->rate = $request->rate;
                $produtoExtra->tipo = $tipo;
                $produtoExtra->amount = $request->amount;
                $produtoExtra->total = $request->total;
                $produtoExtra->ats_rate = $request->ats_rate;
                $produtoExtra->ats_total_rate = $request->ats_total_rate;
                $produtoExtra->profit = $request->profit;
                $produtoExtra->save();
            }

            return response()->json(['result' => ['valor' => 'Extra editado com sucesso!']]);
        } else {
            $produtoExtra = new PedidoProdutoExtra;
            $produtoExtra->extra_id = $request->extra_name;
            $produtoExtra->rate = $request->rate;
            $produtoExtra->pedido_produto_id = $request->pedido_produto_id;
            $produtoExtra->tipo = $tipo;
            $produtoExtra->amount = $request->amount;
            $produtoExtra->total = $request->total;
            $produtoExtra->ats_rate = $request->ats_rate;
            $produtoExtra->ats_total_rate = $request->ats_total_rate;
            $produtoExtra->profit = $request->profit;
            $produtoExtra->save();

            return response()->json(['result' => ['valor' => 'Extra criado com sucesso!']]);
        }
    }

    public function destroy(Request $request)
    {
        $tipo = $request->tipo;
        switch ($tipo) {
            case "alojamento":
                $pedido_quartos_first = PedidoQuarto::where('id', $request->id)->first();
                $count = PedidoQuarto::where('pedido_produto_id', $pedido_quartos_first->pedido_produto_id)->get()->count();
                if ($count == 1) {
                    $id = $pedido_quartos_first->pedido_produto_id;
                    $pedidos_produtos = PedidoProduto::findOrFail($id);
                    $pedido_quartos = PedidoQuarto::findOrFail($request->id);
                    $valor_quarto = ValorQuarto::where('pedido_produto_id', $id);
                    $valor_quarto->delete();
                    $pedido_quartos->delete();
                    $pedidos_produtos->delete();
                } else {
                    // recupera o extra a ser deletado
                    $p = PedidoQuarto::findOrFail($request->id);
                    //Recupera o pedido_produto que é o 'cabecalho'
                    $pedidos_produtos = PedidoProduto::findOrFail($p->pedido_produto_id);
                    $p->delete(); // apaga o extra
                    // recupera todos os extras desse pedido_produto
                    $all = PedidoQuarto::where('pedido_produto_id', $pedidos_produtos->id)->get();
                    $total = 0;
                    foreach ($all as $a) {
                        $total += $a->total;
                    }
                    $pedidos_produtos->valor = $total;
                    $pedidos_produtos->update();
                }
                break;

            case "golf":
                $pedido_golf_first = PedidoGame::where('id', $request->id)->first();
                $count = PedidoGame::where('pedido_produto_id', $pedido_golf_first->pedido_produto_id)->get()->count();
                if ($count == 1) {
                    $id = $pedido_golf_first->pedido_produto_id;
                    $pedidos_produtos = PedidoProduto::findOrFail($id);
                    $pedido_golf = PedidoGame::findOrFail($request->id);
                    $valor_golf = ValorGolf::where('pedido_produto_id', $id);
                    $valor_golf->delete();
                    $pedido_golf->delete();
                    $pedidos_produtos->delete();
                } else {
                    // recupera o extra a ser deletado
                    $p = PedidoGame::findOrFail($request->id);
                    //Recupera o pedido_produto que é o 'cabecalho'
                    $pedidos_produtos = PedidoProduto::findOrFail($p->pedido_produto_id);
                    $p->delete(); // apaga o extra
                    // recupera todos os extras desse pedido_produto
                    $all = PedidoGame::where('pedido_produto_id', $pedidos_produtos->id)->get();
                    $total = 0;
                    foreach ($all as $a) {
                        $total += $a->total;
                    }
                    $pedidos_produtos->valor = $total;
                    $pedidos_produtos->update();
                }

                break;

            case "transfer":
                $pedido_trasnfers_first = PedidoTransfer::where('id', $request->id)->first();
                $count = PedidoTransfer::where('pedido_produto_id', $pedido_trasnfers_first->pedido_produto_id)->get()->count();
                if ($count == 1) {
                    $id = $pedido_trasnfers_first->pedido_produto_id;
                    $pedidos_produtos = PedidoProduto::findOrFail($id);
                    $pedido_trasnfers = PedidoTransfer::findOrFail($request->id);
                    $valor_transfer = ValorTransfer::where('pedido_produto_id', $id);
                    $valor_transfer->delete();
                    $pedido_trasnfers->delete();
                    $pedidos_produtos->delete();
                } else {

                    $p = PedidoTransfer::findOrFail($request->id);
                    //Recupera o pedido_produto que é o 'cabecalho'
                    $pedidos_produtos = PedidoProduto::findOrFail($p->pedido_produto_id);
                    $p->delete(); // apaga o extra
                    // recupera todos os extras desse pedido_produto
                    $all = PedidoTransfer::where('pedido_produto_id', $pedidos_produtos->id)->get();
                    $total = 0;
                    foreach ($all as $a) {
                        $total += $a->total;
                    }
                    $pedidos_produtos->valor = $total;
                    $pedidos_produtos->update();
                }

                $data = $this->apagarTransferApi($pedido_trasnfers_first->transfergest_id);
                return response()->json($data);
                break;
            case "car":
                $pedido_cars_first = PedidoCar::where('id', $request->id)->first();
                $count = PedidoCar::where('pedido_produto_id', $pedido_cars_first->pedido_produto_id)->get()->count();
                if ($count == 1) {
                    $id = $pedido_cars_first->pedido_produto_id;
                    $pedidos_produtos = PedidoProduto::findOrFail($id);
                    $pedido_cars = PedidoCar::findOrFail($request->id);
                    $valor_car = ValorCar::where('pedido_produto_id', $id);
                    $valor_car->delete();
                    $pedido_cars->delete();
                    $pedidos_produtos->delete();
                } else {
                    // recupera o extra a ser deletado
                    $p = PedidoCar::findOrFail($request->id);
                    //Recupera o pedido_produto que é o 'cabecalho'
                    $pedidos_produtos = PedidoProduto::findOrFail($p->pedido_produto_id);
                    $p->delete(); // apaga o extra
                    // recupera todos os extras desse pedido_produto
                    $all = PedidoCar::where('pedido_produto_id', $pedidos_produtos->id)->get();
                    $total = 0;
                    foreach ($all as $a) {
                        $total += $a->total;
                    }
                    $pedidos_produtos->valor = $total;
                    $pedidos_produtos->update();
                }
                break;

            case "tickets":

                $pedido_tickets_first = PedidoTicket::where('id', $request->id)->first();
                $count = PedidoTicket::where('pedido_produto_id', $pedido_tickets_first->pedido_produto_id)->get()->count();
                if ($count == 1) {
                    $id = $pedido_tickets_first->pedido_produto_id;
                    $pedidos_produtos = PedidoProduto::findOrFail($id);
                    $pedido_tickets = PedidoTicket::findOrFail($request->id);
                    $valor_ticket = ValorTicket::where('pedido_produto_id', $id);
                    $valor_ticket->delete();
                    $pedido_tickets->delete();
                    $pedidos_produtos->delete();
                } else {
                    // recupera o extra a ser deletado
                    $p = PedidoTicket::findOrFail($request->id);
                    //Recupera o pedido_produto que é o 'cabecalho'
                    $pedidos_produtos = PedidoProduto::findOrFail($p->pedido_produto_id);
                    $p->delete(); // apaga o extra
                    // recupera todos os extras desse pedido_produto
                    $all = PedidoTicket::where('pedido_produto_id', $pedidos_produtos->id)->get();
                    $total = 0;
                    foreach ($all as $a) {
                        $total += $a->total;
                    }
                    $pedidos_produtos->valor = $total;
                    $pedidos_produtos->update();
                }
                break;
            case "extra":
                $extra = PedidoProdutoExtra::findOrFail($request->id);
                $extra->delete();
                break;
        }

        return "Extra removido com sucesso!";
    }

    public function PrintPedido($id, $ats = false)
    {

        $pedido_geral = PedidoGeral::findOrFail($id);
        $pedidos_produtos = PedidoProduto::where('pedido_geral_id', $id)->get();
        $usuario = DB::table('pedido_gerals')
            ->join('users', 'users.id', '=', 'pedido_gerals.user_id')
            ->where('pedido_gerals.id', $id)
            ->whereNull('pedido_gerals.deleted_at')
            ->select('path_image', 'name')
            ->first();

        $quartos = DB::table('pedido_quartos')->select('pedido_quartos.id as PedidoItemId', 'pedido_produto.*', 'produtos.*', 'pedido_quartos.*')
            ->join('pedido_produto', 'pedido_produto.id', '=', 'pedido_quartos.pedido_produto_id')
            ->join('produtos', 'produtos.id', '=', 'pedido_produto.produto_id');

        $extras_quartos = DB::table('pedido_produto_extra')
            ->select(DB::raw("pedido_produto_extra.*"), DB::raw("extras.*"), DB::raw("pedido_produto_extra.deleted_at as ExtraDeleted"))
            ->join('extras', 'extras.id', '=', 'pedido_produto_extra.extra_id');

        $golfes = DB::table('pedido_games')->select('pedido_games.id as PedidoItemId', 'pedido_produto.*', 'produtos.*', 'pedido_games.*')
            ->join('pedido_produto', 'pedido_produto.id', '=', 'pedido_games.pedido_produto_id')
            ->join('produtos', 'produtos.id', '=', 'pedido_produto.produto_id');

        $extras_golfes = DB::table('pedido_produto_extra')
            ->select(DB::raw("pedido_produto_extra.*"), DB::raw("extras.*"), DB::raw("pedido_produto_extra.deleted_at as ExtraDeleted"))
            ->join('extras', 'extras.id', '=', 'pedido_produto_extra.extra_id');

        $transfers = DB::table('pedido_transfers')->select('pedido_transfers.id as PedidoItemId', 'pedido_produto.*', 'produtos.*', 'pedido_transfers.*')
            ->join('pedido_produto', 'pedido_produto.id', '=', 'pedido_transfers.pedido_produto_id')
            ->join('produtos', 'produtos.id', '=', 'pedido_produto.produto_id');

        $extras_transfers = DB::table('pedido_produto_extra')
            ->select(DB::raw("pedido_produto_extra.*"), DB::raw("extras.*"), DB::raw("pedido_produto_extra.deleted_at as ExtraDeleted"))
            ->join('extras', 'extras.id', '=', 'pedido_produto_extra.extra_id');

        $carros = DB::table('pedido_cars')->select('pedido_cars.id as PedidoItemId', 'pedido_produto.*', 'produtos.*', 'pedido_cars.*')
            ->join('pedido_produto', 'pedido_produto.id', '=', 'pedido_cars.pedido_produto_id')
            ->join('produtos', 'produtos.id', '=', 'pedido_produto.produto_id');

        $extras_carros = DB::table('pedido_produto_extra')
            ->select(DB::raw("pedido_produto_extra.*"), DB::raw("extras.*"), DB::raw("pedido_produto_extra.deleted_at as ExtraDeleted"))
            ->join('extras', 'extras.id', '=', 'pedido_produto_extra.extra_id');

        $bilhetes = DB::table('pedido_tickets')->select('pedido_tickets.id as PedidoItemId', 'pedido_produto.*', 'produtos.*', 'pedido_tickets.*')
            ->join('pedido_produto', 'pedido_produto.id', '=', 'pedido_tickets.pedido_produto_id')
            ->join('produtos', 'produtos.id', '=', 'pedido_produto.produto_id');

        $extras_bilhetes = DB::table('pedido_produto_extra')
            ->select(DB::raw("pedido_produto_extra.*"), DB::raw("extras.*"), DB::raw("pedido_produto_extra.deleted_at as ExtraDeleted"))
            ->join('extras', 'extras.id', '=', 'pedido_produto_extra.extra_id');

        $pedidosId = $pedidos_produtos->pluck("id")->toArray();

        $quartos->whereIn('pedido_produto_id', $pedidosId);
        $extras_quartos->whereIn('pedido_produto_id', $pedidosId)
            ->where('tipo', 'alojamento');

        $golfes->whereIn('pedido_produto_id', $pedidosId);
        $extras_golfes->whereIn('pedido_produto_id', $pedidosId)
            ->where('tipo', 'golf');

        $transfers->whereIn('pedido_produto_id', $pedidosId);
        $extras_transfers->whereIn('pedido_produto_id', $pedidosId)
            ->where('tipo', 'transfer');

        $carros->whereIn('pedido_produto_id', $pedidosId);
        $extras_carros->whereIn('pedido_produto_id', $pedidosId)
            ->whereIn('tipo', ['car', 'cars']);

        $bilhetes->whereIn('pedido_produto_id', $pedidosId);
        $extras_bilhetes->whereIn('pedido_produto_id', $pedidosId)
            ->where('tipo', 'tickets');

        $quartos = $quartos->orderBy('checkin')->orderBy('checkout')->orderBy('pedido_produto_id')->get();
        $extras_quartos = $extras_quartos->whereNull('pedido_produto_extra.deleted_at')->get();
        $extras_quartos = $extras_quartos->filter(function ($item, $key) {
            return $item->ExtraDeleted == null;
        });

        $golfes = $golfes->orderBy('data')->orderBy('hora')->orderBy('pedido_produto_id')->get();
        $extras_golfes = $extras_golfes->whereNull('pedido_produto_extra.deleted_at')->get();
        $extras_golfes = $extras_golfes->filter(function ($item, $key) {
            return $item->ExtraDeleted == null;
        });

        $transfers = $transfers->orderBy('data')->orderBy('hora')->orderBy('pedido_produto_id')->get();
        $extras_transfers = $extras_transfers->whereNull('pedido_produto_extra.deleted_at')->get();
        $extras_transfers = $extras_transfers->filter(function ($item, $key) {
            return $item->ExtraDeleted == null;
        });

        $carros = $carros->orderBy('pickup_data')->orderBy('pickup_hora')->orderBy('dropoff_data')->orderBy('dropoff_hora')->orderBy('pedido_produto_id')->get();
        $extras_carros = $extras_carros->whereNull('pedido_produto_extra.deleted_at')->get();
        $extras_carros = $extras_carros->filter(function ($item, $key) {
            return $item->ExtraDeleted == null;
        });

        $bilhetes = $bilhetes->orderBy('data')->orderBy('hora')->orderBy('pedido_produto_id')->get();
        $extras_bilhetes = $extras_bilhetes->whereNull('pedido_produto_extra.deleted_at')->get();
        $extras_bilhetes = $extras_bilhetes->filter(function ($item, $key) {
            return $item->ExtraDeleted == null;
        });

        $payments = PedidoPayments::where('pedido_geral_id', $id)->get();

        if ($ats == true) {
            $view = 'Admin.profile.printPedidoWithAts';
        } else {
            $view = 'Admin.profile.printPedido';
        }

        return view($view)
            ->with('pedido_geral', $pedido_geral)
            ->with('usuario', $usuario)
            ->with('quartos', $quartos)
            ->with('extras_quartos', $extras_quartos)
            ->with('golfes', $golfes)
            ->with('extras_golfes', $extras_golfes)
            ->with('transfers', $transfers)
            ->with('extras_transfers', $extras_transfers)
            ->with('carros', $carros)
            ->with('extras_carros', $extras_carros)
            ->with('bilhetes', $bilhetes)
            ->with('payments', $payments)
            ->with('extras_bilhetes', $extras_bilhetes);
    }

    public function PrintPedidoMarkup($id)
    {
        $pedido_geral = PedidoGeral::findOrFail($id);
        $pedidos_produtos = PedidoProduto::where('pedido_geral_id', $id)->get();
        $usuario = DB::table('pedido_gerals')
            ->join('users', 'users.id', '=', 'pedido_gerals.user_id')
            ->where('pedido_gerals.id', $id)
            ->whereNull('pedido_gerals.deleted_at')
            ->select('path_image', 'name')
            ->first();

        $quartos = DB::table('pedido_quartos')
            ->join('pedido_produto', 'pedido_produto.id', '=', 'pedido_quartos.pedido_produto_id')
            ->join('produtos', 'produtos.id', '=', 'pedido_produto.produto_id');

        $extras_quartos = DB::table('pedido_produto_extra')
            ->join('extras', 'extras.id', '=', 'pedido_produto_extra.extra_id');

        $golfes = DB::table('pedido_games')
            ->join('pedido_produto', 'pedido_produto.id', '=', 'pedido_games.pedido_produto_id')
            ->join('produtos', 'produtos.id', '=', 'pedido_produto.produto_id');

        $extras_golfes = DB::table('pedido_produto_extra')
            ->join('extras', 'extras.id', '=', 'pedido_produto_extra.extra_id');

        $transfers = DB::table('pedido_transfers')
            ->join('pedido_produto', 'pedido_produto.id', '=', 'pedido_transfers.pedido_produto_id')
            ->join('produtos', 'produtos.id', '=', 'pedido_produto.produto_id');

        $extras_transfers = DB::table('pedido_produto_extra')
            ->join('extras', 'extras.id', '=', 'pedido_produto_extra.extra_id');

        $carros = DB::table('pedido_cars')
            ->join('pedido_produto', 'pedido_produto.id', '=', 'pedido_cars.pedido_produto_id')
            ->join('produtos', 'produtos.id', '=', 'pedido_produto.produto_id');

        $extras_carros = DB::table('pedido_produto_extra')
            ->join('extras', 'extras.id', '=', 'pedido_produto_extra.extra_id');

        $bilhetes = DB::table('pedido_tickets')
            ->join('pedido_produto', 'pedido_produto.id', '=', 'pedido_tickets.pedido_produto_id')
            ->join('produtos', 'produtos.id', '=', 'pedido_produto.produto_id');

        $extras_bilhetes = DB::table('pedido_produto_extra')
            ->join('extras', 'extras.id', '=', 'pedido_produto_extra.extra_id');

        $i = 0;

        foreach ($pedidos_produtos as $pedido) {
            if ($i == 0) {
                $quartos->where('pedido_produto_id', $pedido->id);
                $extras_quartos->where('pedido_produto_id', $pedido->id)->where('tipo', 'alojamento');

                $golfes->where('pedido_produto_id', $pedido->id);
                $extras_golfes->where('pedido_produto_id', $pedido->id)->where('tipo', 'golf');

                $transfers->where('pedido_produto_id', $pedido->id);
                $extras_transfers->where('pedido_produto_id', $pedido->id)->where('tipo', 'transfer');

                $carros->where('pedido_produto_id', $pedido->id);
                $extras_carros->where('pedido_produto_id', $pedido->id)->where('tipo', 'car');

                $bilhetes->where('pedido_produto_id', $pedido->id);
                $extras_bilhetes->where('pedido_produto_id', $pedido->id)->where('tipo', 'tickets');
            } else {
                $quartos->orWhere('pedido_produto_id', $pedido->id);
                $extras_quartos->orWhere('pedido_produto_id', $pedido->id)->where('tipo', 'alojamento');

                $golfes->orWhere('pedido_produto_id', $pedido->id);
                $extras_golfes->orWhere('pedido_produto_id', $pedido->id)->where('tipo', 'golf');

                $transfers->orWhere('pedido_produto_id', $pedido->id);
                $extras_transfers->orWhere('pedido_produto_id', $pedido->id)->where('tipo', 'transfer');

                $carros->orWhere('pedido_produto_id', $pedido->id);
                $extras_carros->orWhere('pedido_produto_id', $pedido->id)->where('tipo', 'car');

                $bilhetes->orWhere('pedido_produto_id', $pedido->id);
                $extras_bilhetes->orWhere('pedido_produto_id', $pedido->id)->where('tipo', 'tickets');
            }
            $i++;
        }

        $quartos = $quartos->orderBy('pedido_produto_id')->orderBy('checkin')->orderBy('checkout')->get();
        $extras_quartos = $extras_quartos->get();

        $golfes = $golfes->orderBy('pedido_produto_id')->orderBy('data')->orderBy('hora')->get();
        $extras_golfes = $extras_golfes->get();

        $transfers = $transfers->orderBy('pedido_produto_id')->orderBy('data')->orderBy('hora')->get();
        $extras_transfers = $extras_transfers->get();

        $carros = $carros->orderBy('pedido_produto_id')->orderBy('pickup_data')->orderBy('pickup_hora')->orderBy('dropoff_data')->orderBy('dropoff_hora')->get();
        $extras_carros = $extras_carros->get();

        $bilhetes = $bilhetes->orderBy('pedido_produto_id')->orderBy('data')->orderBy('hora')->get();
        $extras_bilhetes = $extras_bilhetes->get();

        //$pdf = PDF::loadView('profile.printPedido',  compact('usuario', 'quartos', 'golfes','transfers', 'carros','bilhetes'));
        // return $pdf->stream('pedido-geral-'.$id.'.pdf');
        // dd($golfes);
        $payments = PedidoPayments::where('pedido_geral_id', $id)->get();

        return view('Admin.profile.printpedidomarkup')
            ->with('pedido_geral', $pedido_geral)
            ->with('usuario', $usuario)
            ->with('quartos', $quartos)
            ->with('extras_quartos', $extras_quartos)
            ->with('golfes', $golfes)
            ->with('extras_golfes', $extras_golfes)
            ->with('transfers', $transfers)
            ->with('extras_transfers', $extras_transfers)
            ->with('carros', $carros)
            ->with('extras_carros', $extras_carros)
            ->with('bilhetes', $bilhetes)
            ->with('payments', $payments)
            ->with('extras_bilhetes', $extras_bilhetes);
    }
    /*  SERVICOS API - DADOS E FUNCTIONS */

    /* Envia todoso os transfers do pedido */
    public function enviarPedidoGeralTransfergest(Request $request)
    {

        $data = PedidoGeral::where("id", $request->get("pedido_geral_id"))->with("reports")->with(["pedidoprodutos" => function ($query) {

            $query->with("produto");
            $query->with("pedidotransfer")->whereHas("pedidotransfer")->get();
        }])->first();

        return $this->makeAndSendTransfergestData($request, $data);
    }

    /* envia um transfer ( individual ) do pedido */
    public function enviarTransferTransfergest(Request $request)
    {

        $data = PedidoGeral::where("id", $request->get("pedido_geral_id"))->with("reports")->with(["pedidoprodutos" => function ($query) use ($request) {

            $query->with("produto");
            $query->with(["pedidotransfer" => function ($q) use ($request) {

                $q->where("id", $request->get("pedido_transfer_id"));
            }])->whereHas("pedidotransfer", function ($q) use ($request) {
                $q->where("id", $request->get("pedido_transfer_id"));
            })->get();
        }])->first();

        return $this->makeAndSendTransfergestData($request, $data);
    }

    public function makeAndSendTransfergestData(Request $request, $data)
    {

        try {

            $token = $this->getSetApiToken();

            if (!$data) {
                return response()->json(["error" => true, "msg" => "Falha ao buscar o pedido"]);
            }

            if (!isset($data["pedidoprodutos"][0])) {
                return response()->json(["error" => true, "msg" => "O pedido enviado não possui transfers ou nao foi possivel recuperar os transfer do pedido. Tente novamente"]);
            }

            $totalReserva = 0;

            $send_data_hora = $request->get("send_data_hora");
            $send_pax = $request->get("send_pax");
            $send_address = $request->get("send_address");
            $send_profit = $request->get("send_profit");

            $totalReservaEmail = 0;

            foreach ($data["pedidoprodutos"] as $i => $transfers) {

                foreach ($transfers['pedidotransfer'] as $j => $servico) {

                    if (($send_data_hora == "false" || $send_address == "false" || $send_profit == "false") && $servico["transfergest_id"] == null) {

                        return response()->json([
                            "error" => true,
                            "msg" => "You have a transfer that is not in a Transfergest system.Please , send this trasnfer manually",
                        ]);
                    }

                    $z = ($i + 1) . ($j + 1);

                    if ($servico["data"] == null or $servico["data"] == "") {
                        return response()->json([
                            "error" => true,
                            "msg" => "You cannot send transfer without data",
                            "data" => null,
                            "responseapi" => null,
                        ]);
                    }

                    if ($servico["hora"] == null or $servico["hora"] == "") {
                        return response()->json([
                            "error" => true,
                            "msg" => "You cannot send transfer without hora",
                            "data" => null,
                            "responseapi" => null,
                        ]);
                    }

                    $postdata["servicos"]["form_{$z}"] = [

                        'adultos' => $send_pax == "true" ? $servico['adult'] : null,
                        'criancas' => $send_pax == "true" ? $servico['children'] : null,
                        'bebes' => $send_pax == "true" ? $servico['babie'] : null,
                        'booster' => $send_pax == "true" ? $servico['babie'] : null,

                        'data' => $send_data_hora == "true" ? $servico['data'] : null,
                        'hora' => $send_data_hora == "true" ? $servico['hora'] : null,

                        'morada_origem' => $send_address == "true" ? $servico["pickup"] : null,
                        'morada_destino' => $send_address == "true" ? $servico["dropoff"] : null,

                        'numero_voo' => $servico['flight'],

                        'observacoes_geral' => strip_tags($servico["remark"], "<br>"),
                        'observacao_interna' => '',
                        'referencia' => $data["referencia"],
                        'tipo_servico' => null,
                        'estado_servico' => null,
                        'categoria_id' => null,
                        'grupo_id' => null,
                        'classe_id' => null,
                        'pedido_transfer_id' => $servico['id'], // usado para o callback da api , assim podemos aualizar o valor do transfer->id no banco ats
                        'pedido_transferzona_id' => $servico["transfergest_id"], // usado para buscar o servico TZ caso ja exista no transfergest
                        'pedido_produto_id' => $servico["pedido_produto_id"], // pedido produto id = grupo do pedido - usado para criar diferentes bookings
                        'transfergest_booking' => $servico["transfergest_booking"], // caso ja exista o booking para este transfer , envia junto
                        'valor_servico' => $send_profit == "true" ? $servico['ats_rate'] != null ? $servico['ats_rate'] : null : null,
                        'valor_reserva' => 0,
                        'operadorNome' => trim(strtolower($data->reports->operador)),
                    ];

                    $totalReservaEmail += $postdata["servicos"]["form_{$z}"]["valor_servico"];
                }
            }

            $postdata["cliente"] = [
                'operador_parceiro' => '',
                'nome' => $data['lead_name'],
                'telefone' => null,
                'email' => null,
                'operador_id' => null,
                'servico_parceiro_id' => null,
                'referencia' => $data["referencia"],
                'cliente_booking' => '',
                'valor_reserva' => '',
                'depositos' => '',
                'cliente_id' => '',
            ];

            $response = $this->enviaServicoApi($token, $postdata);

            if (isset($response["data"])) {
                foreach ($response["data"] as $d) {
                    PedidoTransfer::where("id", $d['transfer_id'])->update([
                        'transfergest_id' => $d['id'],
                        'transfergest_booking' => $d['cliente_booking_id'],
                    ]);
                }
                if ($request->get("send_email") == 1 or $request->get("send_email") == true or $request->get("send_email") == "true") {
                    $this->enviaEmailServicosApi($request, $data, $totalReservaEmail);
                }
                return response()->json([
                    "error" => false,
                    "msg" => "Serviços enviados com sucesso!",
                    "data" => $postdata,
                    "responseapi" => $response,
                ]);
            } else {
                return response()->json([
                    "error" => true,
                    "msg" => "Error to send transfers",
                    "data" => $postdata,
                    "responseapi" => $response,
                ]);
            }
        } catch (Exception $th) {
            throw new Exception($th, 500);
        }
    }

    public function buscarPagamentosTransfergest(Request $request)
    {

        try {

            $pedido = PedidoProduto::find($request->get("id"));
            $booking_id = $pedido->pedidotransfer()->first()->transfergest_booking;

            $token = $this->getSetApiToken();

            $curl = curl_init();
            $optionsCurl = $this->optionsCurl;
            $optionsCurl[CURLOPT_URL] = config("app.api_transfergest_host") . "api/v2/buscar-pagamentos-booking";
            $optionsCurl[CURLOPT_POSTFIELDS] = json_encode(["cliente_booking_id" => $booking_id]);
            $optionsCurl[CURLOPT_HTTPHEADER] = ["Content-Type: application/json", "Authorization: Bearer " . $token];
            curl_setopt_array($curl, $optionsCurl);

            $data = json_decode(curl_exec($curl), true);
            curl_close($curl);

            return $data;
        } catch (Exception $th) {
            throw new Exception($th, 500);
        }
    }

    public function addDepositoTransfergest(Request $request)
    {

        try {

            $postdata = [
                "id" => $request->get("id"),
                "valor_reserva" => $request->get("valor_reserva"),
                "valor_deposito" => $request->get("valor_deposito"),
            ];

            $token = $this->getSetApiToken();

            $curl = curl_init();
            $optionsCurl = $this->optionsCurl;
            $optionsCurl[CURLOPT_URL] = config("app.api_transfergest_host") . "api/v2/add-pagamentos";
            $optionsCurl[CURLOPT_POSTFIELDS] = json_encode($postdata);
            $optionsCurl[CURLOPT_HTTPHEADER] = ["Content-Type: application/json", "Authorization: Bearer " . $token];
            $data = json_decode(curl_exec($curl), true);
            curl_close($curl);

            return $data;
        } catch (Exception $th) {
            throw new Exception($th, 500);
        }
    }

    public function apagarBookingApi(Request $request)
    {
        try {
            $token = $this->getSetApiToken();
            $pedido = PedidoGeral::find($request->get("id"))->produtoss()->where("transfer", 1)->get();

            $postdata = [];

            foreach ($pedido as $p) {
                $produto = PedidoProduto::find($p->pivot->id);

                if ($produto) {
                    $transfers = $produto->pedidotransfer();
                    if ($transfers->count() > 0) {
                        $postdata["booking_id"][] = $transfers->first()->transfergest_booking;
                    }
                }
            }

            $response = $this->enviarDeleteBookingApi($token, $postdata);
            return response()->json($response);
        } catch (Exception $th) {
            throw new Exception($th, 500);
        }
    }

    public function enviarDeleteBookingApi($token, $postdata)
    {
        try {
            $curl = curl_init();
            $token = $this->getSetApiToken();

            $optionsCurl = $this->optionsCurl;
            $optionsCurl[CURLOPT_URL] = config("app.api_transfergest_host") . "api/v2/apagar-booking";
            $optionsCurl[CURLOPT_POSTFIELDS] = json_encode($postdata);
            $optionsCurl[CURLOPT_HTTPHEADER] = ["Content-Type: application/json", "Authorization: Bearer " . $token];
            curl_setopt_array($curl, $optionsCurl);
            $data = json_decode(curl_exec($curl), true);
            curl_close($curl);
            return $data;
        } catch (Exception $th) {
            throw new Exception($th, 500);
        }
    }

    public function apagarTransferApi($id)
    {
        try {
            $token = $this->getSetApiToken();
            $postdata["id"] = $id;
            $response = $this->enviarDeleteServicosApi($token, $postdata);

            return response()->json($response);
        } catch (Exception $th) {
            throw new Exception($th, 500);
        }
    }

    public function enviarDeleteServicosApi($token, $postdata)
    {
        try {
            $curl = curl_init();
            $token = $this->getSetApiToken();

            $optionsCurl = $this->optionsCurl;
            $optionsCurl[CURLOPT_URL] = config("app.api_transfergest_host") . "api/v2/apagar-servico";
            $optionsCurl[CURLOPT_POSTFIELDS] = json_encode($postdata);
            $optionsCurl[CURLOPT_HTTPHEADER] = ["Content-Type: application/json", "Authorization: Bearer " . $token];
            curl_setopt_array($curl, $optionsCurl);
            $data = json_decode(curl_exec($curl), true);
            curl_close($curl);

            return $data;
        } catch (Exception $th) {
            throw new Exception($th, 500);
        }
    }

    public function enviaServicoApi($token, $postdata)
    {
        try {
            $curl = curl_init();
            $token = $this->getSetApiToken();
            $optionsCurl = $this->optionsCurl;
            $optionsCurl[CURLOPT_URL] = config("app.api_transfergest_host") . "api/v2/criar-servico";
            $optionsCurl[CURLOPT_POSTFIELDS] = json_encode($postdata);
            $optionsCurl[CURLOPT_HTTPHEADER] = ["Content-Type: application/json", "Authorization: Bearer " . $token];
            curl_setopt_array($curl, $optionsCurl);

            Log::alert(
                "enviando servicos via api",
                [config("app.api_transfergest_host") . "api/v2/criar-servico"]
            );
            $data = json_decode(curl_exec($curl), true);
            curl_close($curl);
            return $data;
        } catch (Exception $th) {
            throw new Exception($th, 500);
        }
    }

    public function getToken()
    {
        try {
            $curl = curl_init();
            $login = ["grant_type" => "password", "client_id" => config("app.api_transfergest_cliente_id"), "client_secret" => config("app.api_transfergest_key"), "username" => config("app.api_transfergest_user"), "password" => config("app.api_transfergest_password")];
            $optionsCurl = $this->optionsCurl;
            $optionsCurl[CURLOPT_URL] = config("app.api_transfergest_host") . "oauth/token";
            $optionsCurl[CURLOPT_POSTFIELDS] = json_encode($login);

            curl_setopt_array($curl, $optionsCurl);
            $data = json_decode(curl_exec($curl), true);
            curl_close($curl);

            return $data;
        } catch (Exception $th) {
            throw new Exception($th, 500);
        }
    }

    public function getSetApiToken()
    {

        if (isset($_COOKIE["transfergest_restapikey"]) && $_COOKIE["transfergest_restapikey"] != null && $_COOKIE["transfergest_restapikey"] != "") {

            $token = $_COOKIE["transfergest_restapikey"];
            $validado = $this->validaToken($token);

            if ($validado == false or $validado == "false") {
                $token = $this->getToken()["access_token"];
                setcookie("transfergest_restapikey", $token, time() + (3600 + (24 + 7)), "/", "atsportugal.com");
            }
        } else {
            $token = $this->getToken()["access_token"];
            setcookie("transfergest_restapikey", $token, time() + (3600 + (24 + 7)), "/", "atsportugal.com");
        }

        return $token;
    }

    public function validaToken($token)
    {

        try {

            $curl = curl_init();
            $optionsCurl = $this->optionsCurl;
            $optionsCurl[CURLOPT_URL] = config("app.api_transfergest_host") . "api/v2/logado";
            $optionsCurl[CURLOPT_HTTPHEADER] = ["Content-Type: application/json", "Authorization: Bearer " . $token];

            curl_setopt_array($curl, $optionsCurl);
            $data = json_decode(curl_exec($curl), true);
            curl_close($curl);

            return $data;
        } catch (Throwable $th) {
            dd($th);
        }
    }

    public function enviaEmailServicosApi(Request $request, $data, $totalReservaEmail)
    {
        try {

            $data = PedidoGeral::where("id", $request->get("pedido_geral_id"))->with("reports")->with(["pedidoprodutos" => function ($query) use ($request) {
                $query->with("produto");
                $query->with(["pedidotransfer" => function ($q) use ($request) {
                    if ($request->has("pedido_transfer_id")) {
                        $q->where("id", $request->get("pedido_transfer_id"));
                    }
                }])->whereHas("pedidotransfer", function ($q) use ($request) {
                    if ($request->has("pedido_transfer_id")) {
                        $q->where("id", $request->get("pedido_transfer_id"));
                    }
                })->get();
            }])->first();

            $mailData = ['pedido' => $data, 'usuario' => Auth::user()];

            SendEmailServicosApiTGJob::dispatchAfterResponse(
                $mailData
            );

            return response()->json("Email enviado com sucesso");
        } catch (Exception | ModelNotFoundException $th) {
            dd($th);
        }
    }


    public function salvarRemarkInterno(Request $request)
    {

        try {
            $pedidoProdutoId = $request->get("pedidoProdutoId");
            $type = $request->get("tipoPedido");

            $remark = str_replace("\n", "", trim($request->get("remark")));

            if (!$pedidoProdutoId) {
                throw new \Exception("Falha ao identificar o produto", 500);
            }
            if (!$type) {
                throw new \Exception("Falha ao identificar o tipo de produto", 500);
            }
            if (!$remark or $remark == "") {
                throw new \Exception("Remark is empty", 500);
            }

            if ($type == "room") {
                $pedidos = App\PedidoQuarto::where('pedido_produto_id', $pedidoProdutoId)->get();
            } else if ($type == "golf") {
                $pedidos = App\PedidoGame::where('pedido_produto_id', $pedidoProdutoId)->get();
            } else if ($type == "transfer") {
                $pedidos = App\PedidoTransfer::where('pedido_produto_id', $pedidoProdutoId)->get();
            } else if ($type == "car") {
                $pedidos = App\PedidoCar::where('pedido_produto_id', $pedidoProdutoId)->get();
            } else if ($type == "ticket") {
                $pedidos = App\PedidoTicket::where('pedido_produto_id', $pedidoProdutoId)->get();
            }

            if (!$pedidos) {
                throw new \Exception("Falha ao identificar o produto", 500);
            }

            if ($request->get("isUpdate") == true) {
                foreach ($pedidos as $i => $pedido) {
                    $pedido->update(['remark_internal' => '']);
                }
            }

            foreach ($pedidos as $i => $pedido) {
                if ($pedido->remark_internal != null and $pedido->remark_internal != "") {
                    $remark .= "<br>" . $pedido->remark_internal;
                    $pedido->update(['remark_internal' => '']);
                }
            }

            $pedidos->first()->update(['remark_internal' => $remark]);

            return response()->json([
                'error' => false,
                'message' => 'remarks atualizados com sucesso',
                'remark' => $remark
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => true,
                "message" => $th->getMessage(),
            ], 500);
        }
    }
}
