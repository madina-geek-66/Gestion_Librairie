<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livre extends Model
{
    use HasFactory;
    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function auteur()
    {
        return $this->belongsTo(Auteur::class, 'auteur_id');
    }

    protected $fillable = [

        'titre',
        'description',
        'auteur',
        'prix',
        'image',
        'qte_stock',
        'categorie_id',
        'is_archived'
    ];

    // Scope pour filtrer les livres non archivés
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    // Scope pour filtrer les livres archivés
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }
}
