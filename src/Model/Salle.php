<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    protected $table = 'salles';

    protected $fillable = [
        'nom',
        'capacite',
        'active',
        'type_salle_id',
    ];

    protected $casts = [
        'active'     => 'boolean',
        'capacite'   => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'salle_id');
    }
}