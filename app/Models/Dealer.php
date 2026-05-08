<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    protected $fillable = ['name', 'code', 'address', 'phone', 'is_active'];

    public function genbaSessions()
    {
        return $this->hasMany(GenbaSession::class);
    }
}