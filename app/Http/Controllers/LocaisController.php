<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Destinos;
use App\Produto;
use App\Http\Requests\FotoLocal;
use Storage;
class LocaisController extends Controller
{
    //
    public function index(){
		$destinos = Destinos::orderBy('name')->get();
		return view('Admin.locais.index',['destinos'=>$destinos]);
	}

	public function create(){

		return view('Admin.locais.create');
	}


	public function store(FotoLocal $request){

		//$path_name = $request->name.'.'.'jpeg';
		//$path_name = $request->user()->id.'.'.'jpeg';
		if($request->file('path_image')){
			$path = Storage::putFile('public', $request->file('path_image'));
			$DbName =str_replace("public/", "", $path);
		} else{
			$DbName = "empty";
		}
		//$path = $request->file('path_image')->storeAs('public', $path_name);
		$name = $request->name;
		$description = $request->description;
		Destinos::create(['name'=>$name, 'description'=>$description, 'path_image'=>$DbName]);
		return redirect()->route('locais');
	}


	public function destroy($id){
		$produtos = Produto::where('destino_id', $id)->first();
		if($produtos){
			return redirect()->back()->withErrors(array('Failed to delete. There are products associated with this Destination.'));
		}else{
			Destinos::find($id)->delete();
		}

		return redirect()->route('locais');
	}

	public function edit($id){
		$local = Destinos::find($id);
		return view('Admin.locais.edit', compact('local'));
	}

	public function update(FotoLocal $request, $id){
		$name = $request->name;
		$description = $request->description;
		if($request->file('path_image')){
			$path = Storage::putFile('public', $request->file('path_image'));
			$DbName =str_replace("public/", "", $path);
			$local = Destinos::find($id)->update(['name'=>$name, 'description'=>$description, 'path_image'=>$DbName]);
		} else{
			$local = Destinos::find($id)->update(['name'=>$name, 'description'=>$description]);
		}
		return redirect()->route('locais');
	}
}
