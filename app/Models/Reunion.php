<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reunion extends Model
{
    protected $table = 'reunions';

    protected $fillable = [
        'titre', 'description', 'date_debut', 'heure_debut', 'projet_id',
    ];

    public function projet()
    {
        return $this->belongsTo(Projet::class, 'projet_id');
    }


    public function participants()
    {
        return $this->belongsToMany(User::class, 'reunion_user', 'reunion_id', 'user_id')
                    ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'reunion_id');
    }
}
