<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 4/19/2019
 * Time: 3:12 AM
 */

namespace App\Http\Middleware\Bot;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Heard;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Illuminate\Support\Facades\Log;

class LanguageDecider implements Heard
{
    /**
     * Handle a message that was successfully heard, but not processed yet.
     *
     * @param IncomingMessage $message
     * @param callable $next
     * @param BotMan $bot
     *
     * @return mixed
     */
    public function heard(IncomingMessage $message, $next, BotMan $bot)
    {
        $person = getCurrentPerson($bot);
        if ($person) {
            config(['bot.user_language' => $person->language]);
        }

        return $next($message);
    }
}