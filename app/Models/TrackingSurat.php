<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingSurat extends Model
{
    use HasFactory;

    // Baris ini yang wajib ada agar terhindar dari MassAssignmentException
    protected $fillable = [
        'surat_keluar_id', 
        'aktor_id', 
        'aksi', 
        'catatan',
        'file_lampiran'
    ];
    
    // Relasi ke tabel user untuk mengetahui siapa aktornya
    public function aktor()
    {
        return $this->belongsTo(User::class, 'aktor_id');
    }
}
