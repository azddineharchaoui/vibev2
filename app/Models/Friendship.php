<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'friend_id', 'accepted'];

    // Relation pour l'utilisateur qui envoie la demande
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation pour l'utilisateur qui reçoit la demande
    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
}