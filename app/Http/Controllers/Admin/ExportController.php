<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Carbon\Carbon;
use Maatwebsite\Excel\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;

class ExportController extends Controller
{

    private $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    //
    public function index(Request $request)
    {
        $day = Carbon::now();

        if ($request->type == 'User') {
            $type = "Utilisateur";
            if ($request->extension == 'EXCEL') {
                return $this->excel->download(new UsersExport($request->begin, $request->end), $type . '-' . $day . '.xlsx');
            } elseif ($request->extension == 'CSV') {
                return $this->excel->download(new UsersExport($request->begin, $request->end), $type . '-' . $day . '.csv');
            }
        }
    }

    public function generate(Ticket $ticket)
    {
        $ticket->load(['structure', 'service']);
        $date = new \DateTime();
        $url = url('view/' . $ticket->id);
        $qrcode = QrCode::encoding("UTF-8")->format('png')->size(200)->generate($url, public_path('images/qrcode.png'));

        $data = [
            'ticket' => $ticket,
            'qrcode' => $qrcode,
            'date' => $date->format('d-m-Y'),
        ];

        $pdf = PDF::loadView('tickets.ticket-pdf', $data)->setPaper('a5', 'portrait');

        return $pdf->download('Ticket-' . $ticket->numero . '.pdf');
    }
}
