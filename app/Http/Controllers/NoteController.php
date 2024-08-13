<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Ticket;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(Ticket $ticket)
    {
        if ($ticket->status != STATUT_PRINT) {
            if ($ticket->note != 1) {
                return view(
                    'notes.add',
                    [
                        'ticket' => $ticket,
                    ]
                );
            } else {
                return view(
                    'notes.add',
                    [
                        'status' => $ticket->note,
                        'ticket' => $ticket,
                    ]
                );
            }
        } else {
            return redirect('view/' . $ticket->id);
        }
    }

    public function create(Request $request, Ticket $ticket)
    {
        if ($ticket->note != 1) {
            $note = new Note();

            $note->note = $request->note;
            $note->commentaire = $request->commentaire;
            $note->ticket_id = $ticket->id;
            $note->user_id = $ticket->user_id;
            $note->service_id = $ticket->service_id;
            $note->structure_id = $ticket->structure_id;
            $note->status = STATUT_DO;

            if ($note->save()) {
                $ticket->note = 1;
                $ticket->save();
                return redirect('/thanks')->with('success', 'Merci pour votre contribution !');
            } else {
                return back()->with('error', "Une erreur s'est produite.");
            }
        } else {
            return redirect('/thanks')->with('success', 'Merci pour votre contribution !');
        }
    }

    public function thanks()
    {
        return view('notes.thanks');
    }
}
