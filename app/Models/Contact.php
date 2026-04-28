<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'type',
        'contact_date',
        'comment',
    ];
    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
