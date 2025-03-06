<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Role extends Model
{
    use HasFactory;
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
