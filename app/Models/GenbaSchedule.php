<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GenbaSchedule extends Model
{
    protected $fillable = [
        'dealer_id', 'user_id', 'tanggal', 'catatan', 'is_done'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'is_done' => 'boolean',
    ];

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}