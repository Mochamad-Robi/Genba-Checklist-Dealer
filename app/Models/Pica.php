<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pica extends Model
{
    protected $fillable = [
    'user_id', 'dealer_id', 'session_id', 'question_id',
    'pic', 'masalah', 'analisa', 'tindakan',
    'target_date', 'status', 'keterangan', 'indikator'
];

    protected $casts = [
        'target_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'open' => 'Open',
            'on_progress' => 'On Progress',
            'closed' => 'Closed',
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'open' => 'bg-red-100 text-red-700',
            'on_progress' => 'bg-yellow-100 text-yellow-700',
            'closed' => 'bg-green-100 text-green-700',
        };
    }

    public function session()
{
    return $this->belongsTo(GenbaSession::class, 'session_id');
}

public function question()
{
    return $this->belongsTo(\App\Models\Question::class);
}

}