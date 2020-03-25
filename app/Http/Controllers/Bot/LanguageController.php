<?php

namespace App\Http\Controllers\Bot;

use App\Jobs\SendLanguageChosen;
use App\Models\Person;
use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use App\Utilities\LanguageOption;
use BotMan\Drivers\Facebook\Extensions\ButtonTemplate;
use BotMan\Drivers\Facebook\Extensions\ElementButton;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LanguageController extends Controller
{
    public function handleZawgyiLanguageChosen($bot)
    {
        $this->handleLanguageChosen($bot, 'zaw');
    }

    public function handleUnicodeLanguageChosen($bot)
    {
        $this->handleLanguageChosen($bot, 'uni');
    }

    public function handleEnglishLanguageChosen($bot)
    {
        $this->handleLanguageChosen($bot, 'eng');
    }

    /**
     * Request the user to choose 'english', 'zawgyi', 'unicode'
     */
    public function handleLanguageOption($bot)
    {
        SendLanguageChosen::dispatch($bot);

        return response("", 200);
    }

    /**
     * if the user choose one language between english, zawgyi, or unicode
     */
    public function handleLanguageChosen($bot, $lan)
    {
        $bot->types();
        $user = getCurrentPerson($bot);
        if ($user) {
            $user->language = $lan;
            $user->save();

            config(['bot.user_language' => $user->language]);
        }

        $generalController = app('App\Http\Controllers\Bot\GeneralController');
        $generalController->handleIntroduction($bot);
    }
}