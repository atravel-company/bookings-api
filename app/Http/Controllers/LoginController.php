<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use App\UserBankAccountDestination;
use App\UserBankAccount;
use App\UserContact;
use App\UserLocation;
use App\Http\Requests\FotoLocal;
use App\Http\Requests\BankDestinationRequest;
use App\Http\Requests\BankAccountRequest;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\LocationRequest;
use App\Http\Requests\Supp;
use App\Http\Requests\UpdtSupp;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Backpack\PermissionManager\app\Models\Role;
use Illuminate\Support\Facades\Input;
use Storage;


use App\Http\Controllers\BaseController;

class LoginController extends BaseController
{
    use RegistersUsers;

    public function authenticate(){
        if (Auth::attempt(['email' => $email, 'password' => $password, 'active' => 1])) {
            // Authentication passed...
            return redirect()->intended('dashboard');
        }
    }

    public function index(){
        $users = User::where([ ['email', '!=', 'geral@oseubackoffice.com'],['email', '!=', 'renato@renato.com'],])->orderBy('name')->get();
        $roles = Role::where([ ['name', '!=', 'cria'],['name', '!=', 'edita'],['name', '!=', 'superuser'],['name', '!=', 'apaga'],['name', '!=', 'comenta'],])->get();

        return view('auth.index',['users'=>$users,'roles'=>$roles]);
    }

    public function create(Request $request){

        try{



            Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'path_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ])->validate();

            $data = $request->all();

            $img = $this->imgUpload($request->file('path_image') );

            if($img)
                $data['path_image'] = $img;
            else
                $data['path_image'] = "";

            $data['password'] = bcrypt($data['password']);

            $user = User::create($data);

            return response()->json(['result'=> [ 'destination' => $user ] ] );

        }catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage())->withInput();
        }
    }

    public function edit(Request $request){

        try{


            Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
            ])->validate();

            $user = User::find($request->id);

            if($request->has('password') and $request->get("password") !== null and $request->get("password") !== "null" and $request->get("password") !== "" and $request->get("password") !== " "){
                $formData = $request->except('path_image');
                $formData['password'] = bcrypt($formData['password']);
            }else{
                $formData = $request->except('password','path_image');
            }

            if( $request->get('path_image') != 'nulo' ){

                Validator::make($request->all(), [
                    'path_image' => 'image|mimes:jpeg,png,jpg|max:2048',
                ]);

                $formData['path_image']  =  $this->imgUpload($request->file('path_image'));
            }
            $user->update($formData);


            //$supplier =  $user->update($formData);
            $user = User::find($request->id);

            return response()->json(['result'=> [ 'destination' => $user ] ] );

        }catch (ModelNotFoundException $exception) {

            return response()->json($exception->getMessage())->withInput();
        }
    }


    public function destroy($id){

        User::find($id)->delete();

        return redirect()->back();
    }


    public function bank(Request $request) {
        if($request->ajax()){

            $user= User::find($request->id);
            $account='';
            foreach ($user->destination as $key => $value) {
                $destination[$key]=UserBankAccountDestination::find($user->destination[$key]->id);
                $account[$key]=$destination[$key]->account;

            }
            //return Response::json($request->nome);
            //return response($request->nome);
            return response()->json(['result'=>['destination'=>$user->destination, 'account'=>$account]]);
        }

    }



    public function bankcreate(BankDestinationRequest $request) {
        if($request->ajax()){
            $sup_dest=UserBankAccountDestination::create(['user_id'=>$request->id, 'destination'=>$request->destination]);
            //UserBankAccount::create(['usr_bank_acc_dest_id'=>$sup_dest->id,'type'=>$request->type,'account_number'=>$request->account_number]);

            $supplier= User::find($request->id);
            $account='';
            foreach ($supplier->destination as $key => $value) {
                $destination[$key]=UserBankAccountDestination::find($supplier->destination[$key]->id);
                $account[$key]=$destination[$key]->account;

            }
            //return Response::json($request->nome);
            //return response($request->nome);*/
            return response()->json(['result'=>['destination'=>$supplier->destination, 'account'=>$account]]);
        }

    }


    public function bankedit(Request $request){

        $destination = UserBankAccountDestination::find($request->IDdest);
        return response()->json(['result'=>['destination'=>$destination, 'account'=>$destination->account]]);
    }


    public function bankupdate(Request $request){
        $this->validate($request, [
        'destination' => 'required|max:32'
        ]);
        $dest= UserBankAccountDestination::find($request->IDdest)->update(['destination'=>$request->destination]);

        if($request->type!=null){
            $this->validate($request, [
            'account_number' => 'required|max:64'
            ]);
            $acc = UserBankAccount::find($request->idacc)->update(['type'=>$request->type,'account_number'=>$request->account_number]);

        }
        $supplier= User::find($request->id);
        $account='';
            foreach ($supplier->destination as $key => $value) {
                $destination[$key]=UserBankAccountDestination::find($supplier->destination[$key]->id);
                $account[$key]=$destination[$key]->account;

            }

            return response()->json(['result'=>['destination'=>$supplier->destination, 'account'=>$account]]);
    }


    public function bankdestroy(Request $request){
        UserBankAccountDestination::find($request->IDdest)->delete();

        $supplier= User::find($request->id);
        $account='';
            foreach ($supplier->destination as $key => $value) {
                $destination[$key]=UserBankAccountDestination::find($supplier->destination[$key]->id);
                $account[$key]=$destination[$key]->account;

            }

            return response()->json(['result'=>['destination'=>$supplier->destination, 'account'=>$account]]);
    }


    public function bankaccountdestroy(Request $request){
        UserBankAccount::find($request->IdAccount)->delete();

        $supplier= User::find($request->id);
        $account='';
            foreach ($supplier->destination as $key => $value) {
                $destination[$key]=UserBankAccountDestination::find($supplier->destination[$key]->id);
                $account[$key]=$destination[$key]->account;

            }

            return response()->json(['result'=>['destination'=>$supplier->destination, 'account'=>$account]]);
    }

    public function bankcreateaccount(BankAccountRequest $request) {
        if($request->ajax()){

            UserBankAccount::create(['usr_bank_acc_dest_id'=>$request->id,'type'=>$request->type,'account_number'=>$request->account_number]);

            $supplier= User::find($request->IDsup);
            $account='';
            foreach ($supplier->destination as $key => $value) {
                $destination[$key]=UserBankAccountDestination::find($supplier->destination[$key]->id);
                $account[$key]=$destination[$key]->account;

            }
            //return Response::json($request->nome);
            //return response($request->nome);*/
            return response()->json(['result'=>['destination'=>$supplier->destination, 'account'=>$account]]);
        }

    }


    public function contact(Request $request) {
        if($request->ajax()){

            $supplier= User::find($request->id);

            return response()->json(['result'=>['contact'=>$supplier->contact]]);
        }

    }


    public function createcontact(ContactRequest $request) {
        if($request->ajax()){
            $sup_dest=UserContact::create(['user_id'=>$request->id, 'type'=>$request->type, 'name'=>$request->name, 'telephone'=>$request->phone, 'mobile'=>$request->mobile, 'email'=>$request->email]);
            //UserBankAccount::create(['usr_bank_acc_dest_id'=>$sup_dest->id,'type'=>$request->type,'account_number'=>$request->account_number]);


            $contact=UserContact::where([ ['user_id', '=', $request->id]])->get();



            //return Response::json($request->nome);
            //return response($request->nome);*/
            return response()->json(['result'=>['contact'=>$contact]]);
        }

    }

    public function destroycontact(Request $request){

        UserContact::find($request->id)->delete();

        $contact=UserContact::where([ ['user_id', '=', $request->supID]])->get();



            //return Response::json($request->nome);
            //return response($request->nome);*/
            return response()->json(['result'=>['contact'=>$contact]]);
    }


    public function contactedit(Request $request){

        $contact=UserContact::where([ ['user_id', '=', $request->supID],['type', '=', $request->type],])->get();



            //return Response::json($request->nome);
            //return response($request->nome);*/
            return response()->json(['result'=>['contact'=>$contact]]);
    }


    public function contactupdate(ContactRequest $request){
        if($request->name!=null and $request->phone!=null and $request->email!=null and $request->mobile!=null){
            $acc = UserContact::find($request->idcontact)->update(['name'=>$request->name,'telephone'=>$request->phone,'email'=>$request->email,'mobile'=>$request->mobile]);
        }
        $contact=UserContact::where([ ['user_id', '=', $request->id]])->get();
        return response()->json(['result'=>['contact'=>$contact]]);
    }


    public function location(Request $request) {
        if($request->ajax()){

            $supplier= User::find($request->id);

            return response()->json(['result'=>['location'=>$supplier->location]]);
        }

    }


    public function createlocal(LocationRequest $request) {
        if($request->ajax()){
            $sup_dest=UserLocation::create(['user_id'=>$request->id, 'location'=>$request->location, 'address'=>$request->address, 'zip_code'=>$request->zip, 'city'=>$request->city, 'country'=>$request->country]);



            $supplier= User::find($request->id);

            return response()->json(['result'=>['location'=>$supplier->location]]);
        }

    }


    public function destroylocal(Request $request){

        UserLocation::find($request->locId)->delete();

            $supplier= User::find($request->id);

            return response()->json(['result'=>['location'=>$supplier->location]]);
    }


    public function localtedit(Request $request) {
        if($request->ajax()){

            $location= UserLocation::find($request->id);

            return response()->json(['result'=>['location'=>$location]]);
        }

    }


    public function localupdt(LocationRequest $request){
        if($request->location!=null){
            UserLocation::find($request->locIds)->update(['location'=>$request->location,'address'=>$request->address,'zip_code'=>$request->zip,'city'=>$request->city,'country'=>$request->country]);
        }
        $supplier= User::find($request->id);

            return response()->json(['result'=>['location'=>$supplier->location]]);
    }


    public function search(Request $request){
        //$nome = 'renato';
        //return view('produtos', ['ola'=>$nome]);


        $suppliers = User::all();

        //$contents = Storage::get('3.jpg');
        return response()->json(['result'=>$suppliers]);
    }

    public function update(Request $request, $id){
        $data = Input::get('ch');
        $user = User::find($id);
        if($user->hasAnyRole(['superuser','cria','edita','apaga','comenta'])){
            $user->roles()->sync($data);
            $user->assignRole('superuser');
            $user->assignRole('cria');
            $user->assignRole('edita');
            $user->assignRole('apaga');
            $user->assignRole('comenta');
        }
        else{
            $user->roles()->sync($data);
        }
        //return redirect('produtos/'.$id.'/edit');
        return redirect()->route('users');
    }

}
