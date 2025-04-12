<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'num_fac',
        'date_facture',
        'montant_total',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
