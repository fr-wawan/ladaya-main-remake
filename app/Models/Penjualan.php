<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $guarded = [];

    protected $casts = [
        'harga' => 'double',
        'kuantiti' => 'integer',
        'total' => 'double',
    ];

    public function tiket(){
        return $this->belongsTo(Tiket::class);
    }

    public function bank(){
        return $this->belongsTo(Bank::class);
    }
}
