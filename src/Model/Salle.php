<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    protected $table = 'salles';

    protected $fillable = [
        'nom',
        'batiment',
        'capacite',
        'type_id',
        'active',
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