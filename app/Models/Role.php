<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'type', 'order', 'is_active'];

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function users()
{
    return $this->belongsToMany(User::class, 'user_roles');
}
}