<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['name'];

    /**
     * Define a relação com o modelo User.
     * Um papel pode ter muitos usuários.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
