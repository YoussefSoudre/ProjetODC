<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FileController;
use App\Models\SecurityRole;
use App\Models\Struture;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    //
    public function profil(User $user)
    {
        $user = Auth::user();
        $role = SecurityRole::find($user->security_role_id);
        $role->load(['object']);

        return view('admin.users.profil', [
            'user' => $user,
            'role' => $role,
        ]);
    }

    public function list(User $user)
    {
        $users = User::all();
        $roles = SecurityRole::all();
        $structures = Struture::all();
        $user->load(
            ['SecurityRole']
        );

        return view('admin.users.list', [
            'users' => $users,
            'roles' => $roles,
            'structures' => $structures,
        ]);
    }

    public function register()
    {
        Controller::he_can('Users', 'creat');
        $roles = SecurityRole::all();
        return view('admin.users.add', [
            'roles' => $roles,
        ]);
    }

    public function create(Request $request)
    {
        $user = new User();

        $user->name = $request->name;
        $email_exist = User::where('email', $request->email)->count();
        if ($email_exist > 0) {
            return back()->with('error', "Cette email existe déjà.")->withInput();
        } else {
            $user->email = $request->email;
        }
        if (Controller::formatPhone($request->phone) != false) {
            $user->phone = Controller::formatPhone($request->phone);
        } else {
            return back()->withErrors("Numéro de Téléphone incorrect");
        }
        $user->security_role_id = $request->security_role_id;

        if ($request->file('picture')) {
            $picture = FileController::picture($request->file('picture'));
            if ($picture['state'] == false) {
                return back()->withErrors($picture['message']);
            }

            $user->picture = $picture['url'];
        }

        if ($request->structure_id != "null") {
            $user->structure_id = $request->structure_id;
        }

        $user->save();

        return redirect('admin/list-users');
    }

    public function update(Request $request, User $user)
    {
        if (isset($_POST['delete'])) {
            if ($user->delete()) {
                return back()->with('success', "L'utilisateur a été supprimé.");
            } else {
                return back()->with('error', "L'utilisateur  n'a pas été supprimé.");
            }
        } else {
            $user->name = $request->name;
            $user->email = $request->email;
            if (Controller::formatPhone($request->phone) != false) {
                $user->phone = Controller::formatPhone($request->phone);
            } else {
                return back()->withErrors("Numéro de Téléphone incorrect");
            }

            $picture = FileController::picture($request->file('picture'));
            if ($picture['state'] == false) {
                return back()->withErrors($picture['message']);
            }

            $user->picture = $picture['url'];

            if ($request->structure_id != "null") {
                $user->structure_id = $request->structure_id;
            }


            $user->save();
            return redirect('/admin-profil');
        }
    }

    public function updateRole(Request $request, User $user)
    {

        $user->security_role_id = $request->security_role_id;

        $user->save();
        return redirect('admin/list-users');
    }

    public function updatePassword(Request $request, User $user)
    {
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect('/admin-profil');
    }
}
