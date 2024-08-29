<?php

namespace App\Modules\Consommations\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Consommateurs\Models\Consommateurs;

class Consommations extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'consommateur_id',
        'date',
        'heure',
        'anomalie',
        'absence',
        'date_controle',
        'qte',
        'description',
        'mt_sub',
    ];

    /**
     * Les attributs à convertir en types natifs.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'anomalie' => 'boolean',
        'absence' => 'boolean',
    ];

    /**
     * Relation avec le modèle Consommateurs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function consommateur()
    {
        return $this->belongsTo(Consommateurs::class, 'consommateur_id');
    }

    // Si vous avez besoin d'autres relations ou méthodes, vous pouvez les ajouter ici.
}
