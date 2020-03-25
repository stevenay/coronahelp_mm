<?php

namespace App\Http\Middleware\Bot;

use App\Models\MessengerUser;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Heard;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Illuminate\Support\Facades\Log;

class UserRecording implements Heard
{
    /**
     * Handle an incoming message.
     *
     * @param IncomingMessage $message
     * @param callable $next
     * @param BotMan $bot
     *
     * @return mixed
     */
    public function heard(IncomingMessage $message, $next, BotMan $bot)
    {
        $senderId = $bot->getMessage()->getSender();
        $userAlreadyExisted = MessengerUser::where('messenger_id', $senderId)->exists();

//        if (!$userAlreadyExisted) {
            $user = $bot->getUser();

            // if we can get the user from the incoming message,
            // we saved it.
            if ($user instanceof \BotMan\Drivers\Facebook\Extensions\User) {
                MessengerUser::createFromIncomingMessage($user);
            }
//        }

        return $next($message);
    }
}