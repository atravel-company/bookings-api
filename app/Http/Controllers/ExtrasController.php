<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Extra;
use App\Categoria;
use App\Destinos;
use App\Supplier;
use App\PedidoProdutoExtra;

class ExtrasController extends Controller
{
    //

    public function index(){
		//$nome = 'renato';
		//return view('produtos', ['ola'=>$nome]);


		$extras = Extra::orderBy('name')->get();
		return view('Admin.extras.index',['extras'=>$extras]);
	}

	public function create(){
		$categorias = Categoria::all();
		$destinos = Destinos::all();
		$suppliers= Supplier::all();
		return view('Admin.extras.create',compact('categorias','destinos','suppliers'));
	}

	public function store(Request $request){
		$this->validate($request, [
        'name' => 'required|min:4'
    	]);
		$input = $request->all();
		Extra::create($input);
		return redirect()->route('extras');
	}

	public function destroy($id){
		$pedido_produto_extra = PedidoProdutoExtra::where('extra_id', $id)->first();
		if($pedido_produto_extra){
			return redirect()->back()->withErrors(array('Failed to delete. There are products associated with this Extra.'));
		}else{
			$extras = Extra::find($id)->delete();
		}
		return redirect()->route('extras');
	}

	public function edit($id){

		$extra = Extra::find($id);
		return view('Admin.extras.edit', compact('extra'));
	}

	public function update(Request $request, $id){

		$this->validate($request, [
        'name' => 'required|min:4'
    	]);

		$name = $request->name;
		$description = $request->description;
		Extra::find($id)->update(['name'=>$name, 'description'=>$description]);
		return redirect()->route('extras');
	}
}
