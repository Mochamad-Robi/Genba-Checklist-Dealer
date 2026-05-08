<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GenbaSession extends Model
{
   protected $fillable = [
    'dealer_id', 'role_id', 'user_id',
    'auditee_name', 'honda_id',
    'is_behalf', 'behalf_user_id',
    'status', 'submitted_at'
];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(GenbaAnswer::class, 'session_id');
    }

    public function getScoreAttribute()
    {
        $total = $this->answers->whereNotNull('indicator')->count();
        $paham = $this->answers->where('indicator', '1')->count();
        return $total > 0 ? round(($paham / $total) * 100) : 0;
    }

    public function picas()
{
    return $this->hasMany(\App\Models\Pica::class, 'session_id');
}

public function behalfUser()
{
    return $this->belongsTo(User::class, 'behalf_user_id');
}
public function evidences()
{
    return $this->hasMany(GenbaEvidence::class, 'session_id');
}
}