<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Service;

class ServiceController extends Controller
{
    //

    public function create(Request $request)
    {
        Controller::he_can('Service', 'creat');

        $service = new Service();

        $service->libelle = $request->libelle;
        $service->description = $request->description;
        $service->position = $request->position;
        $service->status = $request->status;
        $service->structure_id = $request->structure_id;
        $service->user_id = auth()->user()->id;

        if ($service->save()) {
            return redirect('admin/list-services')->with('success', "Le service a bien été créée !");
        } else {

            return back()->with('error', "Une erreur s'est produite.");
        }
    }

    public function update(Request $request, Service $service)
    {
        Controller::he_can('Service', 'updat');

        if (isset($_POST['delete'])) {
            if ($service->delete()) {
                return redirect('admin/list-fees')->with('success', "La service a bien été supprimée !");
            } else {
                return back()->with('error', "Une erreur s'est produite.");
            }
        } else {
            $service->libelle = $request->libelle;
            $service->description = $request->description;
            $service->position = $request->position;
            $service->structure_id = $request->structure_id;
            $service->status = $request->status;
            if ($service->save()) {
                return redirect('admin/list-services')->with('success', "Le service a bien été mis à jour !");
            } else {
                return back()->with('error', "Une erreur s'est produite.");
            }
        }
    }
}
