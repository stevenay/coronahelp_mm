<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use App\Jobs\SendConsultation;
use App\Jobs\SendEmergencyContact;
use App\Jobs\SendGuidance;
use App\Jobs\SendLatestNews;
use App\Jobs\SendMainMenu;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function handleIntroduction($bot)
    {
        $this->replyMainMenu($bot);
    }

    public function replyLatestNews($bot)
    {
        $bot->types();

        SendLatestNews::dispatch($bot);

        return response("", 200);
    }

    public function replyGuidance($bot)
    {
        $bot->types();

        SendGuidance::dispatch($bot);

        return response("", 200);
    }

    public function replyConsultation($bot)
    {
        $bot->types();

        SendConsultation::dispatch($bot);

        return response("", 200);
    }

    public function replyEmergencyContact($bot)
    {
        $bot->types();

        SendEmergencyContact::dispatch($bot);

        return response("", 200);
    }

    public function replyMainMenu($bot)
    {
        $bot->types();

        SendMainMenu::dispatch($bot);

        return response("", 200);
    }
}
