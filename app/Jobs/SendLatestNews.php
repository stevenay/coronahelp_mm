<?php

namespace App\Jobs;

use App\Utilities\LanguageOption;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\Drivers\Facebook\Extensions\Element;
use BotMan\Drivers\Facebook\Extensions\ElementButton;
use BotMan\Drivers\Facebook\Extensions\GenericTemplate;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SendLatestNews implements ShouldQueue
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
        $url = "";

        // Create a client with a base URI
        $client = new Client(['base_uri' => 'https://coronahelpmm.org/wp-json/wp/v2/']);
        $response = $client->get('posts?categories=14&filter[orderby]=date&order=desc&per_page=1');
        // Check if a header exists.
        if ($response->hasHeader('content-type')) {
            $body = $response->getBody();
            $postList = json_decode($body, false);
            if (count($postList)) {
                $url = "https://coronahelpmm.org/archives/" . $postList[0]->id;
            }
        }

        if (empty($url)) {
            $this->bot->reply($languageUtil->getText("menu.no_latest_news"));
            return;
        }

        $lan = retrieveChosenLanguage($this->bot);
        $languageUtil = new LanguageOption($lan);
        $todayDate = date("d M Y");
        $this->bot->reply(
            GenericTemplate::create()
                ->addImageAspectRatio(GenericTemplate::RATIO_HORIZONTAL)
                ->addElements([
                    Element::create($languageUtil->getText("menu.news"))
                        ->subtitle($languageUtil->getText("menu.news_subtitle") . $todayDate)
                        ->image('https://i.ibb.co/YhGWWgg/news.png')
                        ->addButton(
                            ElementButton::create($languageUtil->getText("menu.category_choose"))
                                ->url($url)
                                ->enableExtensions()
                        ),
                ])
                ->addElements([
                    Element::create($languageUtil->getText("menu.patient_news"))
                        ->subtitle($languageUtil->getText("menu.patient_news_subtitle"))
                        ->image('https://naylinaung.me/img/bot_covers/patient_news.png')
                        ->addButton(
                            ElementButton::create($languageUtil->getText("menu.category_choose"))
                                ->url('https://coronahelpmm.org/patient-lists')
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