<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'role_id', 'question', 'menu_program',
        'proses', 'prog_id', 'order', 'is_active'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}