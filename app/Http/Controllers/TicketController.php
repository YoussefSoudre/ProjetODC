<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use PDF;

class TicketController extends Controller
{
    public function __construct()
    {
        //

    }

    public function index(Ticket $ticket)
    {
        if ($ticket->status == STATUT_PRINT) {
            $today = (new \DateTime())->setTime(0, 0);
            $day = new \DateTime($ticket->created_at);
            $ticket_pending = Ticket::where('created_at', '>', $today->format('Y-m-d H:s:i'))->where('structure_id', $ticket->structure_id)->where('status', STATUT_PRINT)->orderBy('created_at', 'desc')->first();
            $heure = $day->format('H : s');
            $ticket_restant = Ticket::where('created_at', '>', $today->format('Y-m-d H:s:i'))->where('structure_id', $ticket->structure_id)->where('status', STATUT_PRINT)->orderBy('created_at', 'desc')->count();

            return view('tickets.view', [
                'ticket' => $ticket,
                'ticket_pending' => $ticket_pending,
                'ticket_restant' => $ticket_restant,
                'heure' => $heure,
            ]);
        } else {
            return redirect('rating/' . $ticket->id);
        }
    }

    public function print(Service $service)
    {

        $ticket = new Ticket();
        $today = (new \DateTime())->setTime(0, 0);

        $nbre_ticket = Ticket::where('created_at', '>', $today->format('Y-m-d H:s:i'))->where('status', STATUT_PRINT)->where('service_id', $service->id)->count();
        $service->load(['structure']);

        if ($nbre_ticket == 0) {
            $ticket->numero = 1;
            $ticket->nbre_ticket_avant = $nbre_ticket;
        } else {
            $ticket_prev = Ticket::where('created_at', '>', $today->format('Y-m-d H:s:i'))->where('status', STATUT_PRINT)->where('service_id', $service->id)->orderBy('created_at', 'desc')->first();
            $ticket->numero = $ticket_prev->numero + 1;
            $ticket->nbre_ticket_avant = $nbre_ticket;
        }

        $ticket->status = STATUT_PRINT;
        $ticket->service_id = $service->id;
        $ticket->structure_id = $service->structure_id;

        $ticket->save();

        return redirect('printer/' . $ticket->id);
    }

    public function agent()
    {
        $services = Service::all()->where('structure_id', Auth::user()->structure_id);
        $list_service = [];
        $i = 0;
        foreach ($services as $service) {
            $today = (new \DateTime())->setTime(0, 0);
            $last_ticket = Ticket::where('created_at', '>', $today->format('Y-m-d H:s:i'))->where('status', STATUT_PRINT)->where('service_id', $service->id)->orderBy('created_at', 'desc')->first();

            $list_service[$i]['service'] = $service;
            $list_service[$i]['last_ticket'] = $last_ticket;
        }

        return view('admin.tickets.agent', ['list_service' => $list_service,]);
    }

    public function next($action, Ticket $ticket)
    {
        if ($action == "do") {
            $ticket->status = STATUT_DO;
            $ticket->user_id = Auth::user()->id;
            $ticket->save();

            return redirect('admin/agent/')->with('success', "Ticket Archivé");
        } elseif ($action == "absent") {
            $ticket->status = STATUT_ABSENT;
            $ticket->user_id = Auth::user()->id;
            $ticket->save();

            return redirect('admin/agent/')->with('success', "Ticket Archivé");
        }
    }
}
