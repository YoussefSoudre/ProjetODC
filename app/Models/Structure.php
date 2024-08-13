<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Struture extends Model
{
    use HasFactory;

    protected $table = 'structures';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'structure_id');
    }

    public function notes()
    {
        return $this->hasMany(User::class, 'structure_id');
    }

    public function services()
    {
        return $this->hasMany(Struture::class, 'structure_id');
    }
}
