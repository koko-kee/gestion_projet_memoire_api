<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $table = 'users';
    public $timestamps = false;

    protected $casts = [
        'role' => 'int',
        'create_date' => 'datetime'
    ];

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'role',
        'create_date'
    ];

    protected $hidden = [
        'mot_de_passe',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role');
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class, 'id_utilisateur');
    }

    public function invitation()
    {
        return $this->hasOne(Invitation::class, 'id_utilisateur');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'id_utilisateur');
    }

    public function projets()
    {
        return $this->hasMany(Projet::class, 'id_responsable');
    }

    public function taches()
    {
        return $this->hasMany(Tache::class, 'id_assigne');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }
}
