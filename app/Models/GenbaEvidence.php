<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenbaEvidence extends Model
{
    protected $table = 'genba_evidences';
    
    protected $fillable = [
        'dealer_id', 'session_id', 'tanggal_kunjungan', 'foto', 'keterangan', 'uploaded_by'
    ];
    
    protected $casts = [
        'tanggal_kunjungan' => 'date',
    ];
    
    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }
    
    public function session()
    {
        return $this->belongsTo(GenbaSession::class, 'session_id');
    }
    
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}