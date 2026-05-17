<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['username', 'password', 'name'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['password' => 'hashed'];

    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'author_id');
    }
}
