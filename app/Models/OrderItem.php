<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'livre_id', 'quantite', 'prix'];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    public function livre()
    {
        return $this->belongsTo(Livre::class);
    }
}
