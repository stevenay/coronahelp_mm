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
                    Element::create($languageUtil->getText("menu.home_protection"))
                        ->subtitle($languageUtil->getText("menu.home_protection_subtitle"))
                        ->image('https://naylinaung.me/img/bot_covers/home_protection.png')
                        ->addButton(
                            ElementButton::create($languageUtil->getText("menu.category_choose"))
                                ->url('https://coronahelpmm.org/archives/270')
                                ->enableExtensions()
                        ),
                    Element::create($languageUtil->getText("menu.go_out"))
                        ->subtitle($languageUtil->getText("menu.go_out_subtitle"))
                        ->image('https://naylinaung.me/img/bot_covers/go_out.png')
                        ->addButton(
                            ElementButton::create($languageUtil->getText("menu.category_choose"))
                                ->url('https://coronahelpmm.org/archives/272')
                                ->enableExtensions()
                        ),
                    Element::create($languageUtil->getText("menu.misconception"))
                        ->subtitle($languageUtil->getText("menu.misconception_subtitle"))
                        ->image('https://naylinaung.me/img/bot_covers/misconceptions.png')
                        ->addButton(
                            ElementButton::create($languageUtil->getText("menu.category_choose"))
                                ->url('https://coronahelpmm.org/archives/274')
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
                    Element::create($languageUtil->getText("menu.guidance_3"))
                        ->subtitle($languageUtil->getText("menu.guidance_3_subtitle"))
                        ->image('https://naylinaung.me/img/bot_covers/guidelines.png')
                        ->addButton(
                            ElementButton::create($languageUtil->getText("menu.for_hotel_restaurant"))
                                ->url('https://coronahelpmm.org/archives/112')
                                ->enableExtensions()
                        )->addButton(
                            ElementButton::create($languageUtil->getText("menu.for_quarantine_centers"))
                                ->url('https://coronahelpmm.org/archives/108')
                                ->enableExtensions()
                        )->addButton(
                            ElementButton::create($languageUtil->getText("menu.for_hospitals"))
                                ->url('https://coronahelpmm.org/archives/103')
                                ->enableExtensions()
                        ),
                    // Element::create($languageUtil->getText("menu.guidance_3"))
                    //     ->subtitle($languageUtil->getText("menu.guidance_3_subtitle"))
                    //     ->image('https://naylinaung.me/img/bot_covers/hotel_restaurant.png')
                    //     ->addButton(
                    //         ElementButton::create($languageUtil->getText("menu.category_choose"))
                    //             ->url('https://coronahelpmm.org/archives/112')
                    //             ->enableExtensions()
                    //     ),
                    // Element::create($languageUtil->getText("menu.guidance_4"))
                    //     ->subtitle($languageUtil->getText("menu.guidance_4_subtitle"))
                    //     ->image('https://naylinaung.me/img/bot_covers/quarantine_centers.png')
                    //     ->addButton(
                    //         ElementButton::create($languageUtil->getText("menu.category_choose"))
                    //             ->url('https://coronahelpmm.org/archives/108')
                    //             ->enableExtensions()
                    //     ),
                    // Element::create($languageUtil->getText("menu.guidance_5"))
                    //     ->subtitle($languageUtil->getText("menu.guidance_5_subtitle"))
                    //     ->image('https://naylinaung.me/img/bot_covers/hospital.png')
                    //     ->addButton(
                    //         ElementButton::create($languageUtil->getText("menu.category_choose"))
                    //             ->url('https://coronahelpmm.org/archives/103')
                    //             ->enableExtensions()
                    //     ),
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
