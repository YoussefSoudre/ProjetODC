<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function structure()
    {
        return $this->belongsTo(Struture::class, 'structure_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'service_id');
    }

    public function notes()
    {
        return $this->hasMany(User::class, 'service_id');
    }
}
