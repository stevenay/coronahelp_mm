<?php

namespace App\Jobs;

use App\Utilities\LanguageOption;
use BotMan\Drivers\Facebook\Extensions\ButtonTemplate;
use BotMan\Drivers\Facebook\Extensions\ElementButton;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;

class SendMainMenu implements ShouldQueue
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
        try {
            config(['global.request_from' => 'messenger']);

            $lan = retrieveChosenLanguage($this->bot);
            $languageUtil = new LanguageOption($lan);

            $this->bot->reply(ButtonTemplate::create($languageUtil->getText("menu.welcome"))
                ->addButton(ElementButton::create($languageUtil->getText('menu.latest_news'))
                    ->type('postback')
                    ->payload('latest news')
                )
                ->addButton(ElementButton::create($languageUtil->getText('menu.guidance'))
                    ->type('postback')
                    ->payload('guidance')
                )
                ->addButton(ElementButton::create($languageUtil->getText('menu.contact'))
                    ->url('https://coronahelpmm.org/contact-list')
                    ->enableExtensions()
                )
            );

        } catch (\Exception $e) {
            Log::debug('Message ' . $e->getMessage());
        }
    }
}
