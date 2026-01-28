<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;

class Conversation extends Model
{
    protected $fillable = [
        'titre',
        'bien_id',
        'participant1_id',
        'participant2_id',
    ];

    // relations
    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at','asc');
    }

    public function participantOne()
    {
        return $this->belongsTo(User::class, 'participant1_id');
    }

    public function participantTwo()
    {
        return $this->belongsTo(User::class, 'participant2_id');
    }

    // Retourne l'autre participant pour l'utilisateur donné
    public function otherParticipant(User $user)
    {
        if ($this->participant1_id && $user->id == $this->participant1_id) {
            return $this->participantTwo;
        }
        if ($this->participant2_id && $user->id == $this->participant2_id) {
            return $this->participantOne;
        }
        // si l'utilisateur n'est pas dans la conversation, null
        return null;
    }

    // Scope pour récupérer les conversations impliquant un user
    public function scopeForUser(Builder $q, $userId)
    {
        return $q->where('participant1_id', $userId)
                 ->orWhere('participant2_id', $userId);
    }

    // Cherche une conversation entre 2 users (ordre A/B ou B/A)
    public static function between($userAId, $userBId)
    {
        return static::where(function($q) use ($userAId, $userBId) {
            $q->where('participant1_id', $userAId)->where('participant2_id', $userBId);
        })->orWhere(function($q) use ($userAId, $userBId) {
            $q->where('participant1_id', $userBId)->where('participant2_id', $userAId);
        })->first();
    }
}
