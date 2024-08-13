<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Struture;

class StructureController extends Controller
{
    //
    public function create(Request $request)
    {
        Controller::he_can('Structure', 'creat');

        $structure = new Struture();

        $structure->libelle = $request->libelle;
        $structure->description = $request->description;
        $structure->responsable = $request->responsable;
        $structure->email = $request->email;
        $structure->telephone = $request->telephone;
        $structure->status = $request->status;
        $structure->user_id = auth()->user()->id;

        if ($structure->save()) {

            return redirect('admin/list-structures')->with('success', "La structure a bien été créée !");
        } else {

            return back()->with('error', "Une erreur s'est produite.");
        }
    }

    public function update(Request $request, Struture $structure)
    {
        Controller::he_can('Structure', 'updat');

        if (isset($_POST['delete'])) {
            if ($structure->delete()) {
                return redirect('admin/list-fees')->with('success', "La structure a bien été supprimée !");
            } else {
                return back()->with('error', "Une erreur s'est produite.");
            }
        } else {
            $structure->libelle = $request->libelle;
            $structure->description = $request->description;
            $structure->responsable = $request->responsable;
            $structure->email = $request->email;
            $structure->telephone = $request->telephone;
            $structure->status = $request->status;
            if ($structure->save()) {
                return redirect('admin/list-cards')->with('success', "La structure a bien été mis à jour !");
            } else {
                return back()->with('error', "Une erreur s'est produite.");
            }
        }
    }
}
