<?php
namespace App\Http\Controllers;

use App\AvaliacaoProduto;
use App\Categoria;
use App\Destinos;
use App\Extra;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\FotoLocal;
use App\Http\Requests\Pdf;
use App\Http\Requests\ProdutoRequest;
use App\Produto;
use App\ProdutoImagem;
use App\ProdutoPdf;
use App\Supplier;
use App\SupplierContact;
use App\User;
use Backpack\PermissionManager\app\Models\Role;
use Exception;

use Illuminate\Http\Request;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Storage;

class ProdutosController extends Controller {

    public function coment( Request $request ) {
        if ( $request->ajax() ) {
            $produto = Produto::find( $request->id );
            foreach ( $produto->avaliacoes as $key => $value ) {
                $usuario[$key] = User::find( $produto->avaliacoes[$key]->user_id );
            }
            return response()->json( ['result'=>['coment'=>$produto->avaliacoes, 'usuario'=>$usuario]] );
        }
    }

    public function grava( Request $request ) {
        if ( $request->ajax() ) {
            AvaliacaoProduto::create( ['produto_id'=>$request->id, 'user_id'=>$request->usr, 'nota'=>10, 'comentario'=>$request->comentario] );
            $produto = Produto::find( $request->id );
            foreach ( $produto->avaliacoes as $key => $value ) {
                $usuario[$key] = User::find( $produto->avaliacoes[$key]->user_id );

            }
            return response()->json( ['result'=>['coment'=>$produto->avaliacoes, 'usuario'=>$usuario]] );
        }
    }

    public function index( Request $request ) {

        ini_set( 'memory_limit', '-1' );
        $extras = Extra::all()->sortBy( 'name' );
        $roles = Role::where( [ ['name', '!=', 'cria'], ['name', '!=', 'edita'], ['name', '!=', 'superuser'], ['name', '!=', 'apaga'], ['name', '!=', 'comenta'], ] )->get();


        if ( $request->has( 'nome' ) ) {
            $query = $request->nome;
            $produtos = Produto::where( [ ['nome', 'LIKE', "%$query%"]] )->orderBy( 'nome' )->paginate( 15 );
            return view( 'Admin.produtos.index', ['produtos'=>$produtos, 'extras'=>$extras, 'roles'=>$roles] );
        } else {
            $produtos = Produto::orderBy( 'nome' )->paginate( 15 );
            return view( 'Admin.produtos.index', ['produtos'=>$produtos, 'extras'=>$extras, 'roles'=>$roles] );
        }

    }

    public function create() {
        $categorias = Categoria::all();
        $destinos = Destinos::orderBy( 'name' )->get();
        $suppliers = Supplier::orderBy( 'name' )->get();
        return view( 'Admin.produtos.create', compact( 'categorias', 'destinos', 'suppliers' ) );
    }

    public function store( ProdutoRequest $request ) {
        $data = $request->get( 'ch' );
        if ( $request->descricao == null ) {
            $produto = Produto::create( ['nome'=>$request->nome, 'descricao'=>'  ', 'destino_id'=>$request->destino_id, 'supplier_id'=>$request->supplier_id, 'alojamento'=>$request->alojamento, 'golf'=>$request->golf, 'transfer'=>$request->transfer, 'car'=>$request->car, 'ticket'=>$request->ticket] );
        } else {
            $produto = Produto::create( ['nome'=>$request->nome, 'descricao'=>$request->descricao, 'destino_id'=>$request->destino_id, 'supplier_id'=>$request->supplier_id, 'alojamento'=>$request->alojamento, 'golf'=>$request->golf, 'transfer'=>$request->transfer, 'car'=>$request->car, 'ticket'=>$request->ticket] );
        }
        $produto->categorias()->attach( $data );
        return redirect()->route( 'produtos' );
    }

    public function destroy( $id ) {
        Produto::find( $id )->delete();
        return redirect()->route( 'produtos' );
    }

    public function edit( $id ) {
        $categorias = Categoria::all();
        $destinos = Destinos::orderBy( 'name' )->get();
        $suppliers = Supplier::orderBy( 'name' )->get();
        $produto = Produto::find( $id );

        $supplier_id = $produto->supplier_id;
        $contatos = SupplierContact::where( [['supplier_id', '=', $supplier_id], ['type', '=', 'Reservations']] )->get();
        return view( 'Admin.produtos.edit', compact( 'categorias', 'destinos', 'suppliers', 'produto', 'contatos' ) );
    }

    public function update( ProdutoRequest $request, $id ) {
        try {
            $produto = Produto::find( $id );
            $data = $request->get( 'ch' );
            if ( $request->yesno == 'no' ) {
                if ( $request->descricao == null ) {
                    $produto->update( ['nome'=>$request->nome, 'descricao'=>'  ', 'destino_id'=>$request->destino_id, 'supplier_id'=>$request->supplier_id, 'alojamento'=>$request->alojamento, 'golf'=>$request->golf, 'transfer'=>$request->transfer, 'car'=>$request->car, 'ticket'=>$request->ticket, 'email_type'=>$request->yesno, 'email'=>$request->emailsup] );
                } else {
                    $produto->update( ['nome'=>$request->nome, 'descricao'=>$request->descricao, 'destino_id'=>$request->destino_id, 'supplier_id'=>$request->supplier_id, 'alojamento'=>$request->alojamento, 'golf'=>$request->golf, 'transfer'=>$request->transfer, 'car'=>$request->car, 'ticket'=>$request->ticket, 'email_type'=>$request->yesno, 'email'=>$request->emailsup] );
                }
            } elseif ( $request->yesno == 'yes' ) {
                if ( $request->descricao == null ) {
                    $produto->update( ['nome'=>$request->nome, 'descricao'=>'  ', 'destino_id'=>$request->destino_id, 'supplier_id'=>$request->supplier_id, 'alojamento'=>$request->alojamento, 'golf'=>$request->golf, 'transfer'=>$request->transfer, 'car'=>$request->car, 'ticket'=>$request->ticket, 'email_type'=>$request->yesno, 'email'=>$request->mail] );
                } else {
                    $produto->update( ['nome'=>$request->nome, 'descricao'=>$request->descricao, 'destino_id'=>$request->destino_id, 'supplier_id'=>$request->supplier_id, 'alojamento'=>$request->alojamento, 'golf'=>$request->golf, 'transfer'=>$request->transfer, 'car'=>$request->car, 'ticket'=>$request->ticket, 'email_type'=>$request->yesno, 'email'=>$request->mail] );
                }
            } else {
                if ( $request->descricao == null ) {
                    $produto->update( ['nome'=>$request->nome, 'descricao'=>'  ', 'destino_id'=>$request->destino_id, 'supplier_id'=>$request->supplier_id, 'alojamento'=>$request->alojamento, 'golf'=>$request->golf, 'transfer'=>$request->transfer, 'car'=>$request->car, 'ticket'=>$request->ticket, 'email_type'=>null, 'email'=>null] );
                } else {
                    $produto->update( ['nome'=>$request->nome, 'descricao'=>$request->descricao, 'destino_id'=>$request->destino_id, 'supplier_id'=>$request->supplier_id, 'alojamento'=>$request->alojamento, 'golf'=>$request->golf, 'transfer'=>$request->transfer, 'car'=>$request->car, 'ticket'=>$request->ticket, 'email_type'=>null, 'email'=>null] );
                }

            }
            $produto->categorias()->sync( $data );
        } catch ( \Throwable $th ) {
            throw new Exception( $th, 500 );
        }

        return redirect()->route( 'produtos' );
    }

    public function foto( Request $request ) {
        $path = Storage::putFile( 'public', $request->file( 'image' ) );
        $path_image = str_replace( 'public/', '', $path );
        $produto_id = $request->id;
        $produto = ProdutoImagem::create( ['produto_id'=>$produto_id, 'title'=>'foto', 'description'=>'Teste', 'path_image'=>$path_image] );
        return response()->json( ['result'=>['destination'=>$produto]] );
    }

    public function fotodel( Request $request ) {

        $produto = ProdutoImagem::find( $request->id )->delete();
        return response()->json( ['result'=>['destination'=>$produto]] );
    }

    public function fotoprincipal( Request $request ) {

        $produto = ProdutoImagem::find( $request->id )->update( ['title'=>$request->title] );
        ProdutoImagem::where( [ ['id', '!=', $request->id], ['produto_id', '=', $request->prod], ] )->update( ['title'=>'foto'] );

        return response()->json( ['result'=>['destination'=>$produto]] );
    }

    public function fotoedit( Request $request ) {

        $path = Storage::putFile( 'public', $request->file( 'image' ) );
        $path_image = str_replace( 'public/', '', $path );

        $produto = ProdutoImagem::find( $request->id )->update( ['path_image'=>$path_image] );
        return response()->json( ['result'=>['destination'=>$produto]] );
    }

    public function pdf( Pdf $request ) {
        try {

            $data = array_map( 'intval', explode( ',', $request->check ) );


            $path = Storage::putFile( 'public/pdfs/'.$request->type, $request->file( 'path_image' ) );

            $path_image = str_replace( 'public/pdfs/'.$request->type.'/', '', $path );

            $produto_id = $request->id;
            $title = $request->title;
            $type = $request->type;

            $produto = ProdutoPdf::create( ['produto_id'=>$produto_id, 'title'=>$title, 'description'=> 'TestePDF', 'type'=>$type, 'path_image'=>$path_image] );

            if ( $data[0] != 0 ) {
                $produto->regras()->attach( $data );
            }

            return response()->json( ['result'=>['destination'=>$produto, 'data'=>$data]] );
        } catch ( Exception $th ) {
            throw new Exception($th, 500);

           return response()->json($th->getMessage(), 500);
        }
    }

    public function pdfedit( Request $request ) {
        $data = array_map( 'intval', explode( ',', $request->check ) );

        $title = $request->title;

        $produto = ProdutoPdf::find( $request->pdfid )->update( ['title'=>$title] );
        $produto = ProdutoPdf::find( $request->pdfid );

        $produto_apagar = DB::table( 'pdf_role' )->where( 'produto_pdf_id', $request->pdfid )->whereNotIn( 'role_id', $data );
        if ( $produto_apagar ) {
            $produto_apagar->delete();
        }

        $produto->regras()->sync( $data );
        //verificar aqui
        return response()->json( ['result'=>['destination'=>$produto, 'data'=>$data]] );
    }

    public function pdfdel( Request $request ) {

        $produto = ProdutoPdf::find( $request->id )->delete();
        return response()->json( ['result'=>['destination'=>$produto]] );
    }

    public function addextra( Request $request ) {

        $produto = Produto::find( $request->produto_id );
        $extra = Extra::find( $request->extra_id );
        $resultado = $produto->extras()->where( [ ['produto_id', '=', $request->produto_id], ['extra_id', '=', $request->extra_id], ] )->get();
        if ( !empty( $resultado[0] ) ) {
            return response()->json( ['result'=>['valor'=>$resultado[0], 'resultado'=>'Existe']] );
        } else {
            $resultado = $produto->extras()->attach( $extra, ['formulario' => $request->formulario] );
            return response()->json( ['result'=>['valor'=>$resultado, 'resultado'=>'NÃO Existe']] );
        }
    }

    public function editextra( Request $request ) {

        $produto = Produto::find( $request->produto_id );
        $extra = Extra::find( $request->new_extra_id );
        $resultado = $produto->extras()->where( [ ['produto_id', '=', $request->produto_id], ['extra_id', '=', $request->new_extra_id], ] )->get();
        if ( !empty( $resultado[0] ) ) {
            $resultado[0]->pivot->update( ['formulario' => $request->formulario] );

            return response()->json( ['result'=>['valor'=>$resultado[0], 'resultado'=>'Existe']] );
        } else {
            $resultado = $produto->extras()->updateExistingPivot( $request->extra_id, ['extra_id'=>$request->new_extra_id, 'formulario' => $request->formulario] );

            return response()->json( ['result'=>['valor'=>$resultado, 'resultado'=>'NÃO Existe']] );
        }
    }

    public function extradel( Request $request ) {

        $produto = Produto::find( $request->produto_id );
        $extra = Extra::find( $request->extra_id );
        $resultado = $produto->extras()->detach( $extra );

        return response()->json( ['result'=>['valor'=>$resultado, 'resultado'=>'NÃO Existe']] );
    }

    public function changeState( Request $request ) {
        $produto_id = $request->produto_id;
        $estado = $request->estado;
        $produto = Produto::findOrFail( $produto_id );
        $produto->estado = $estado;
        $produto->save();

        return response()->json( ['success' => 'State successfully changed!.'], 201 );
    }
}
