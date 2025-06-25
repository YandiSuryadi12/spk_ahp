<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MatriksPenilaianAkhirAhp extends Model
{
    use HasFactory;

    protected $primaryKey = "id";
    public $incrementing = "true";
    public $timestamps = "true";
    protected $fillable = [
        "data_gurus_id",
        "nilai",
        "kriteria_id",
    ];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    public function guru()
    {
        return $this->belongsTo(DataGuru::class, "data_gurus_id", "id");
    }
}
