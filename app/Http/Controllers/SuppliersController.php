<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supplier;
use App\SupplierBankAccount;
use App\SupplierBankAccountDestination;
use App\SupplierContact;
use App\SupplierLocation;
use App\Produto;
use App\Http\Requests\FotoLocal;
use App\Http\Requests\BankDestinationRequest;
use App\Http\Requests\BankAccountRequest;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\LocationRequest;
use Storage;
use App\Http\Requests\Supp;
use App\Http\Requests\UpdtSupp;

class SuppliersController extends Controller
{
    //

	public function search(Request $request){
		//$nome = 'renato';
		//return view('produtos', ['ola'=>$nome]);

		$suppliers = Supplier::all();

		//$contents = Storage::get('3.jpg');
		return response()->json(['result'=>$suppliers]);
	}



    public function index(){
		//$nome = 'renato';
		//return view('produtos', ['ola'=>$nome]);

		$suppliers = Supplier::orderBy('name')->get();

		//$contents = Storage::get('3.jpg');
		return view('Admin.suppliers.index',['suppliers'=>$suppliers]);
	}


	public function bank(Request $request) {
		if($request->ajax()){

			$supplier= Supplier::find($request->id);
			$account='';
			foreach ($supplier->destination as $key => $value) {
    			$destination[$key]=SupplierBankAccountDestination::find($supplier->destination[$key]->id);
    			$account[$key]=$destination[$key]->account;

			}
    		//return Response::json($request->nome);
    		//return response($request->nome);
    		return response()->json(['result'=>['destination'=>$supplier->destination, 'account'=>$account]]);
		}

	}


	public function bankcreate(BankDestinationRequest $request) {
		if($request->ajax()){
			$sup_dest=SupplierBankAccountDestination::create(['supplier_id'=>$request->id, 'destination'=>$request->destination]);
			//SupplierBankAccount::create(['supp_bank_acc_dest_id'=>$sup_dest->id,'type'=>$request->type,'account_number'=>$request->account_number]);

			$supplier= Supplier::find($request->id);
			$account='';
			foreach ($supplier->destination as $key => $value) {
    			$destination[$key]=SupplierBankAccountDestination::find($supplier->destination[$key]->id);
    			$account[$key]=$destination[$key]->account;

			}
    		//return Response::json($request->nome);
    		//return response($request->nome);*/
    		return response()->json(['result'=>['destination'=>$supplier->destination, 'account'=>$account]]);
		}

	}


	public function bankedit(Request $request){

		$destination = SupplierBankAccountDestination::find($request->IDdest);
		return response()->json(['result'=>['destination'=>$destination, 'account'=>$destination->account]]);
	}


	public function bankupdate(Request $request){
		$this->validate($request, [
        'destination' => 'required|max:32'
    	]);
		$dest= SupplierBankAccountDestination::find($request->IDdest)->update(['destination'=>$request->destination]);

		if($request->type!=null){
			$this->validate($request, [
        	'account_number' => 'required|max:64'
    		]);
			$acc = SupplierBankAccount::find($request->idacc)->update(['type'=>$request->type,'account_number'=>$request->account_number]);

		}
		$supplier= Supplier::find($request->id);
		$account='';
			foreach ($supplier->destination as $key => $value) {
    			$destination[$key]=SupplierBankAccountDestination::find($supplier->destination[$key]->id);
    			$account[$key]=$destination[$key]->account;

			}

    		return response()->json(['result'=>['destination'=>$supplier->destination, 'account'=>$account]]);
	}


	public function bankdestroy(Request $request){
		SupplierBankAccountDestination::find($request->IDdest)->delete();

		$supplier= Supplier::find($request->id);
		$account='';
			foreach ($supplier->destination as $key => $value) {
    			$destination[$key]=SupplierBankAccountDestination::find($supplier->destination[$key]->id);
    			$account[$key]=$destination[$key]->account;

			}

    		return response()->json(['result'=>['destination'=>$supplier->destination, 'account'=>$account]]);
	}


	public function bankaccountdestroy(Request $request){
		SupplierBankAccount::find($request->IdAccount)->delete();

		$supplier= Supplier::find($request->id);
		$account='';
			foreach ($supplier->destination as $key => $value) {
    			$destination[$key]=SupplierBankAccountDestination::find($supplier->destination[$key]->id);
    			$account[$key]=$destination[$key]->account;

			}

    		return response()->json(['result'=>['destination'=>$supplier->destination, 'account'=>$account]]);
	}

	public function bankcreateaccount(BankAccountRequest $request) {
		if($request->ajax()){

			SupplierBankAccount::create(['supp_bank_acc_dest_id'=>$request->id,'type'=>$request->type,'account_number'=>$request->account_number]);

			$supplier= Supplier::find($request->IDsup);
			$account='';
			foreach ($supplier->destination as $key => $value) {
    			$destination[$key]=SupplierBankAccountDestination::find($supplier->destination[$key]->id);
    			$account[$key]=$destination[$key]->account;

			}
    		//return Response::json($request->nome);
    		//return response($request->nome);*/
    		return response()->json(['result'=>['destination'=>$supplier->destination, 'account'=>$account]]);
		}

	}


	public function contact(Request $request) {
		if($request->ajax()){

			$supplier= Supplier::find($request->id);

    		return response()->json(['result'=>['contact'=>$supplier->contact]]);
		}

	}


	public function createcontact(ContactRequest $request) {
		if($request->ajax()){
			$sup_dest=SupplierContact::create(['supplier_id'=>$request->id, 'type'=>$request->type, 'name'=>$request->name, 'telephone'=>$request->phone, 'mobile'=>$request->mobile, 'email'=>$request->email]);
			//SupplierBankAccount::create(['supp_bank_acc_dest_id'=>$sup_dest->id,'type'=>$request->type,'account_number'=>$request->account_number]);


    		$contact=SupplierContact::where([ ['supplier_id', '=', $request->id]])->get();



    		//return Response::json($request->nome);
    		//return response($request->nome);*/
    		return response()->json(['result'=>['contact'=>$contact]]);
		}

	}

	public function destroycontact(Request $request){

		SupplierContact::find($request->id)->delete();

		$contact=SupplierContact::where([ ['supplier_id', '=', $request->supID]])->get();



    		//return Response::json($request->nome);
    		//return response($request->nome);*/
    		return response()->json(['result'=>['contact'=>$contact]]);
	}


	public function contactedit(Request $request){

		$contact=SupplierContact::where([ ['supplier_id', '=', $request->supID],['type', '=', $request->type],])->get();



    		//return Response::json($request->nome);
    		//return response($request->nome);*/
    		return response()->json(['result'=>['contact'=>$contact]]);
	}


	public function contactupdate(ContactRequest $request){
		if($request->name!=null and $request->phone!=null and $request->email!=null and $request->mobile!=null){
			$acc = SupplierContact::find($request->idcontact)->update(['name'=>$request->name,'telephone'=>$request->phone,'email'=>$request->email,'mobile'=>$request->mobile]);
		}
		$contact=SupplierContact::where([ ['supplier_id', '=', $request->id]])->get();
		return response()->json(['result'=>['contact'=>$contact]]);
	}


	public function location(Request $request) {
		if($request->ajax()){

			$supplier= Supplier::find($request->id);

    		return response()->json(['result'=>['location'=>$supplier->location]]);
		}

	}


	public function createlocal(LocationRequest $request) {
		if($request->ajax()){
			$sup_dest=SupplierLocation::create(['supplier_id'=>$request->id, 'location'=>$request->location, 'address'=>$request->address, 'zip_code'=>$request->zip, 'city'=>$request->city, 'country'=>$request->country]);



    		$supplier= Supplier::find($request->id);

    		return response()->json(['result'=>['location'=>$supplier->location]]);
		}

	}


	public function destroylocal(Request $request){

		SupplierLocation::find($request->locId)->delete();

			$supplier= Supplier::find($request->id);

    		return response()->json(['result'=>['location'=>$supplier->location]]);
	}


	public function localtedit(Request $request) {
		if($request->ajax()){

			$location= SupplierLocation::find($request->id);

    		return response()->json(['result'=>['location'=>$location]]);
		}

	}


	public function localupdt(LocationRequest $request){
		if($request->location!=null){
			SupplierLocation::find($request->locIds)->update(['location'=>$request->location,'address'=>$request->address,'zip_code'=>$request->zip,'city'=>$request->city,'country'=>$request->country]);
		}
		$supplier= Supplier::find($request->id);

    		return response()->json(['result'=>['location'=>$supplier->location]]);
	}


	public function create(Supp $request){


		$path = Storage::putFile('public', $request->file('path_image'));
		$path_image = str_replace("public/", "", $path);



		$name = $request->name;
		$social_denomination = $request->social_denomination;
		$fiscal_number = $request->fiscal_number;
		$web = $request->web;
		$remarks = $request->remarks;




		$supplier= Supplier::create(['name'=>$name, 'social_denomination'=>$social_denomination, 'path_image'=>$path_image,'fiscal_number'=>$fiscal_number, 'remarks'=>$remarks, 'web'=>$web]);
		return response()->json(['result'=>['destination'=>$supplier]]);
	}


	public function destroy($id){
		$produtos = Produto::where('supplier_id', $id)->first();
		if($produtos){
			return redirect()->back()->withErrors(array('Failed to delete. There are products associated with this Supplier.'));
		}else{
			Supplier::find($id)->delete();
		}
		return redirect()->route('suppliers');
	}

	public function edit(UpdtSupp $request){
		if($request->path_image=='nulo'){
			$field=Supplier::find($request->id);
			$path_image=$field->path_image;
		}
		else{
			$this->validate($request, [
       			'path_image' => 'mimes:jpeg,png',
     		]);
			//$idTexto=(string)$request->id;
			$path = Storage::putFile('public', $request->file('path_image'));
			$path_image =str_replace("public/", "", $path);
		}
		$name = $request->name;
		$social_denomination = $request->social_denomination;
		$fiscal_number = $request->fiscal_number;
		$web = $request->web;
		$remarks = $request->remarks;
		$supplier= Supplier::find($request->id)->update(['name'=>$name, 'social_denomination'=>$social_denomination, 'path_image'=>$path_image,'fiscal_number'=>$fiscal_number, 'remarks'=>$remarks, 'web'=>$web]);
		return response()->json(['result'=>['destination'=>$supplier]]);
	}
}
