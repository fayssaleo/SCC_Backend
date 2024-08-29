namespace App\Modules\Verifications\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Consommations\Models\Consommations;

class Verification extends Model
{
    use HasFactory;

    protected $fillable = [
        'mois', // Le champ correct pour stocker la période de vérification
    ];

    // Relation : Une vérification peut avoir plusieurs consommations
    public function consommations()
    {
        return $this->hasMany(Consommations::class, 'verification_id'); // Associe les consommations à la vérification via 'verification_id'
    }
}
