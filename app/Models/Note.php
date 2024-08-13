<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $table = 'notes';

    public function ticket()
    {
        return $this->belongsTo(Service::class, 'ticket_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function structure()
    {
        return $this->belongsTo(Struture::class, 'structure_id');
    }
}
