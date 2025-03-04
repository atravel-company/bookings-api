<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;
use App\ProdutoCategoria;
use App\Http\Requests\FotoLocal;
use Storage;
class CategoriesController extends Controller
{
    //
    public function index(){
		//$nome = 'renato';
		//return view('produtos', ['ola'=>$nome]);


		$categorias = Categoria::all();

		//$contents = Storage::get('3.jpg');
		return view('Admin.categories.index',['categorias'=>$categorias]);
	}

	public function create(){

		return view('Admin.categories.create');
	}


	public function store(FotoLocal $request){

		//$path_name = $request->name.'.'.'jpeg';
		//$path_name = $request->user()->id.'.'.'jpeg';
		$path = Storage::putFile('public', $request->file('path_image'));

		$DbName =str_replace("public/", "", $path);
		//$path = $request->file('path_image')->storeAs('public', $path_name);
		$name = $request->name;
		$description = $request->description;
		Categoria::create(['name'=>$name, 'description'=>$description, 'path_image'=>$DbName]);
		return redirect()->route('categories');
	}


	public function destroy($id){

		$produto_categoria = ProdutoCategoria::where('categoria_id', $id)->first();
		if($produto_categoria){
			return redirect()->back()->withErrors(array('Failed to delete. There are products associated with this Category.'));
		}else{
			Categoria::find($id)->delete();
		}
		return redirect()->route('categories');
	}

	public function edit($id){

		$categoria = Categoria::find($id);
		return view('Admin.categories.edit', compact('categoria'));
	}

	public function update(FotoLocal $request, $id){
		$name = $request->name;
		$description = $request->description;
		if($request->file('path_image')){
			$path = Storage::putFile('public', $request->file('path_image'));
			$DbName =str_replace("public/", "", $path);
			Categoria::find($id)->update(['name'=>$name, 'description'=>$description, 'path_image'=>$DbName]);
		} else{
			Categoria::find($id)->update(['name'=>$name, 'description'=>$description]);
		}

		return redirect()->route('categories');
	}
}
