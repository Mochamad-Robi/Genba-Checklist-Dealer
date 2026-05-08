<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GenbaAnswer extends Model
{
    protected $fillable = ['session_id', 'question_id', 'indicator', 'keterangan'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function session()
    {
        return $this->belongsTo(GenbaSession::class, 'session_id');
    }
}