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

class SendLanguageChosen implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
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
        $this->bot->types();
        $person = getCurrentPerson($this->bot);

        // reply language options
        $this->bot->reply(ButtonTemplate::create("Hello {$person->full_name}. \n\n🇺🇸 Please choose the language below to use me. 
🇲🇲 မိမိႏွစ္သက္ရာ ဘာသာစကားကို ေရြးပါ။ 
🇲🇲 မိမိနှစ်သက်ရာ ဘာသာစကားကို ရွေးပါ။")
            ->addButton(ElementButton::create('English')->type('postback')->payload('language_english'))
            ->addButton(ElementButton::create('ျမန္မာ (ေဇာ္ဂ်ီ)')->type('postback')->payload('language_zawgyi'))
            ->addButton(ElementButton::create('မြန်မာ (ယူနီကုဒ်)')->type('postback')->payload('language_unicode')));
    }
}
