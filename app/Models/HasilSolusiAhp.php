<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\DataGuru;

class HasilSolusiAhp extends Model
{
    use HasFactory;

    protected $table = "hasil_solusi_ahp";
    protected $fillable = ['nilai', 'dataguru_id'];

    public function guru()
    {
        return $this->belongsTo(DataGuru::class, "dataguru_id", "id");
    }
}
