<?php

namespace App\Jobs;

use App\Utilities\LanguageOption;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\Drivers\Facebook\Extensions\Element;
use BotMan\Drivers\Facebook\Extensions\ElementButton;
use BotMan\Drivers\Facebook\Extensions\GenericTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendGuidance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120;

    protected $bot;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($bot)
    {
        $this->bot = $bot;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        config(['global.request_from' => 'messenger']);

        $lan = retrieveChosenLanguage($this->bot);
        $languageUtil = new LanguageOption($lan);

        $this->bot->reply($languageUtil->getText("menu.ask_guidance_categories"));
        $this->bot->reply(
            GenericTemplate::create()
                ->addImageAspectRatio(GenericTemplate::RATIO_HORIZONTAL)
                ->addElements([
                    Element::create($languageUtil->getText("menu.guidance_1"))
                        ->subtitle($languageUtil->getText("menu.guidance_1_subtitle"))
                        ->image('https://i.ibb.co/1sD1mCW/self-quarantine.png')
                        ->addButton(
                            ElementButton::create($languageUtil->getText("menu.category_choose"))
                                ->url('https://coronahelpmm.org/faqs')
                                ->enableExtensions()
                        ),
                    Element::create($languageUtil->getText("menu.guidance_2"))
                        ->subtitle($languageUtil->getText("menu.guidance_2_subtitle"))
                        ->image('https://i.ibb.co/ZzKdG1v/faq.png')
                        ->addButton(
                            ElementButton::create($languageUtil->getText("menu.category_choose"))
                                ->url('https://coronahelpmm.org/archives/93')
                                ->enableExtensions()
                        ),
                ])
        );

        $question = Question::create($languageUtil->getText("menu.back_to_menu"))
            ->addButtons([
                Button::create($languageUtil->getText("menu.back_to_menu_button"))->value('menu')
                    ->image("https://ygnprice.digitxmyanmar.com/img/menu.png"),
            ]);

        $this->bot->reply($question);
    }
}