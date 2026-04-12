<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'name',
        'amount',
        'status',
        'description'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}