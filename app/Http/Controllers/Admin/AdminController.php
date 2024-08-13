<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Service;
use App\Models\Struture;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    //
    public function index()
    {
        if (Auth::user()->security_role_id == 1) {
            $structures = Struture::all()->count();
            $services = Service::all()->count();
            $tickets = Ticket::all()->count();
            $notes = Note::all()->count();

            $notes_all = Note::all();

            $happy = 0;
            $unhappy = 0;

            if ($notes > 0) {
                foreach ($notes_all as $note) {
                    if ($note->note >= 3) {
                        $happy++;
                    } else {
                        $unhappy++;
                    }
                }

                $happy = ($happy / $notes) * 100;
                $unhappy = ($unhappy / $notes) * 100;
            }

            $jan = 0;
            $fev = 0;
            $mar = 0;
            $avr = 0;
            $mai = 0;
            $jui = 0;
            $jul = 0;
            $aou = 0;
            $sep = 0;
            $oct = 0;
            $nov = 0;
            $dec = 0;

            $tickets_all = Ticket::all();

            foreach ($tickets_all as $ticket) {
                $date = new \DateTime($ticket->created_at);
                $mois = $date->format('m');
                (int)$mois;
                switch ($mois) {
                    case 1:
                        $jan++;
                        break;
                    case 2:
                        $fev++;
                        break;
                    case 3:
                        $mar++;
                        break;
                    case 4:
                        $avr++;
                        break;
                    case 5:
                        $mai++;
                        break;
                    case 6:
                        $jui++;
                        break;
                    case 7:
                        $jul++;
                        break;
                    case 8:
                        $aou++;
                        break;
                    case 9:
                        $sep++;
                        break;
                    case 10:
                        $oct++;
                        break;
                    case 11:
                        $nov++;
                        break;
                    case 12:
                        $dec++;
                        break;
                }
            }


            return view('admin.dashboard', [
                'structures' => $structures,
                'services' => $services,
                'tickets' => $tickets,
                'notes' => $notes,
                'happy' => $happy,
                'unhappy' => $unhappy,
                'jan' => $jan,
                'fev' => $fev,
                'mar' => $mar,
                'avr' => $avr,
                'mai' => $mai,
                'jui' => $jui,
                'jul' => $jul,
                'aou' => $aou,
                'sep' => $sep,
                'oct' => $oct,
                'nov' => $nov,
                'dec' => $dec,
            ]);
        } else {
            $structures = Struture::all()->where('structure_id', Auth::user()->structure_id)->count();
            $services = Service::all()->where('structure_id', Auth::user()->structure_id)->count();
            $tickets = Ticket::all()->where('structure_id', Auth::user()->structure_id)->count();
            $notes = Note::all()->where('structure_id', Auth::user()->structure_id)->count();

            $notes_all = Note::all()->where('structure_id', Auth::user()->structure_id);

            $happy = 0;
            $unhappy = 0;

            if ($notes > 0) {
                foreach ($notes_all as $note) {
                    if ($note->note >= 3) {
                        $happy++;
                    } else {
                        $unhappy++;
                    }
                }

                $happy = ($happy / $notes) * 100;
                $unhappy = ($unhappy / $notes) * 100;
            }

            return view('admin.dashboard', [
                'structures' => $structures,
                'services' => $services,
                'tickets' => $tickets,
                'notes' => $notes,
                'happy' => $happy,
                'unhappy' => $unhappy,
            ]);
        }
    }

    public function listStructures()
    {
        Controller::he_can('Structures', 'look');
        $structures = Struture::all();
        return view('admin.structures.list', ['structures' => $structures,]);
    }

    public function listServices()
    {
        Controller::he_can('Services', 'look');
        $services = Service::all();
        $structures = Struture::all();
        return view('admin.services.list', ['services' => $services, 'structures' => $structures,]);
    }

    public function listTickets($day = null)
    {
        Controller::he_can('Tickets', 'look');
        $tickets = Ticket::all();
        return view('admin.tickets.list', ['tickets' => $tickets,]);
    }

    public function listNotes($day = null)
    {
        Controller::he_can('Notes', 'look');
        $notes = Note::all();
        return view('admin.notes.list', ['notes' => $notes,]);
    }
}
