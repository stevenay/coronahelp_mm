<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessengerUser extends Model
{
    protected $guarded = [];

    public static function createFromIncomingMessage(\BotMan\Drivers\Facebook\Extensions\User $user)
    {
        MessengerUser::updateOrCreate(['messenger_id' => $user->getId()], [
            'messenger_id' => $user->getId(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'profile_pic' => $user->getProfilePic(),
            'locale' => $user->getLocale(),
            'gender' => $user->getGender(),
            'source' => 'Messenger',
            'sessions' => 1,
            'last_seen_date' => date('Y-m-d H:i:s')
        ]);
    }

    public function getFullNameAttribute() {
        return "{$this->first_name} {$this->last_name}";
    }
}