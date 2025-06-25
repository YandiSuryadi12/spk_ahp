<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataGuru extends Model
{
    use HasFactory;

    protected $table = "data_gurus";
    protected $fillable = ['nama', 'nip', 'keterangan'];
}
