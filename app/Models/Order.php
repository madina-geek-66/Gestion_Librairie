<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'total',
        'statut',
        'date_commande'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class);
    }

    public function facture()
    {
        return $this->hasOne(Facture::class);
    }
}
