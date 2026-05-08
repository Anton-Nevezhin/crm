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

    public function getStatusNameAttribute()
    {
        return match($this->status) {
            'new' => 'Новая',
            'in_progress' => 'В работе',
            'closed' => 'Закрыта',
            'lost' => 'Потеряна',
            default => $this->status,
        };
    }
}