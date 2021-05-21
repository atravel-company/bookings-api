<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Backpack\PermissionManager\app\Models\Role;
use App\User;
use DB;
use Spatie\Permission\Traits\HasRoles;
class RolesController extends Controller
{
    //
    // $pedidoID=DB::table('pedido_produto')->orderBy('id', 'desc')->first();

    public function index(){


        // $roles = Role::orderBy('name')->get();
        $roles = Role::where([ ['name', '!=', 'cria'],['name', '!=', 'edita'],['name', '!=', 'superuser'],['name', '!=', 'apaga'],['name', '!=', 'comenta'] ])
        ->orderBy("name")->get();
    	//$users = User::orderBy('name')->get();
    	$users = User::where([ ['email', '!=', 'geral@oseubackoffice.com'],['email', '!=', 'renato@renato.com'],])->orderBy('name')->get();
        //$contents = Storage::get('3.jpg');
        return view('Admin.groups.index',['roles'=>$roles, 'users'=>$users]);


    }

    public function create(){

		return view('Admin.groups.create');
	}

	public function store(Request $request){


		$name = $request->name;

		Role::create(['name'=>$name]);
		return redirect()->route('groups');
	}
	public function linka($role,$user){

		$user=User::find($user);
		if($user->hasRole($role)){
			return redirect()->route('groups');
		}
		else{
			$user->assignRole($role);
			return redirect()->route('groups');
		}
	}

	public function delinka($role,$user){
		//dd($role, $user);
		//$user=User::find($user);
		//Role::find($id)->delete();
		$delete = DB::table('role_users')->where('role_id', $role)->where('user_id', $user)->delete();

		return redirect()->route('groups');

	}

	public function destroy($id){

		Role::find($id)->delete();
		return redirect()->route('groups');
	}

	public function edit($id){

		$role = Role::find($id);

		return view('Admin.groups.edit', compact('role'));
	}

	public function update(Request $request, $id){

		$name = $request->name;
		Role::find($id)->update(['name'=>$name]);
		return redirect()->route('groups');
	}

}
