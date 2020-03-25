<?php

use App\Models\Person;
use Illuminate\Support\Facades\Log;

/**
 * Created by SteveNay.
 * User: Dell
 * Date: 4/6/2019
 * Time: 10:10 PM
 */

function getCurrentPerson($bot)
{
    $user_id = $bot->getMessage()->getSender();
    $person = \App\Models\MessengerUser::where('messenger_id', $user_id)->first();

    if ($person) {
        return $person;
    }
//    if ($person instanceof Person) {
//        return $person;
//    }

    return null;
}

function retrieveChosenLanguage($bot)
{
    $user_id = $bot->getMessage()->getSender();

    // Retrieve the chosen language
    if (!empty($user_id) &&
        $facebookUser = App\Models\MessengerUser::where('messenger_id', $user_id)->first()) {
        return $facebookUser->language;
    }

    return 'zaw';

}

function IsBotInStopState($bot)
{
    if ($currentUser = getCurrentPerson($bot)) {
        if (Cache::has($currentUser->fb_id)) {
            if ($bot->getDriver()->isPostback()) {

                $language = \App\Utilities\MessengerUtility::retrieveChosenLanguage($bot);
                $text = "";
                if ($language == "eng")
                    $text = "The chatbot is currently is in Stop State. You can type or click \"stop live chat\" to end admin chat session.";
                else {
                    $text = "လူကြီးမင်းက Happy Biscuit admin တွေနဲ့ စကားပြောနေလို့ Chatbot ကို ခဏ ရပ်ထားပါတယ်။ Chatbot ကို ပြန်သုံးခြင်တယ်ဆိုရင် အောက်က \"stop live chat\" ခလုတ်လေးကို နှိပ်လိုက်ပါဗျ။";
                    if ($language == "zaw")
                        $text = MyanFont::uni2zg($text);
                }

                $buttonTemplate = ButtonTemplate::create($text)
                    ->addButton(ElementButton::create('Stop Live Chat')
                        ->type('postback')
                        ->payload('stop live chat'));

                $bot->reply($buttonTemplate);
            }

            return true;
        }

        return false;
    }
}

function getMonthNumberFromMyanmarName($monthName)
{
    $myanmarMonths = ["ဇန်နဝါရီ", "ဖေဖော်ဝါရီ", "မတ်", "ဧပြီ", "မေ", "ဇွန်", "ဇူလိုင်", "သြဂုတ်", "စက်တင်ဘာ", "အောက်တိုဘာ", "နိုဝင်ဘာ", "ဒီဇင်ဘာ"];
    $key = array_search("ဇန်နဝါရီ", $myanmarMonths);

    if ($key === false) {
        throw new Exception('Element not found for ' . $monthName);
    }

    return $key + 1;
}

// TODO Refactoring
function getAstrologyCategoryInBurmese($category)
{
    $astrologyTypeTranslations = [
        'GeneralAstrology' => 'အထွေထွေ',
        'BabyGivingNameAstrology' => 'ကလေးအမည်ပေး',
        'BusinessGivingNameAstrology' => 'လုပ်ငန်းအမည်ပေး',
        'DayChoosingAstrology' => 'ရက်ကောင်းရက်မြတ်ရွေး',
        'CompanionAstrology' => 'မိတ်ဖက်ရန်သူ',
    ];

    return $astrologyTypeTranslations[$category];
}

// TODO Refactoring
// Deprecated
/*function getQuestionStatusInBurmese($status)
{
    $astrologyTypeTranslations = [
        'new_arrival' => 'အသစ်ရောက်ရှိသော',
        'question_auditing' => 'ပြန်လည်ဆက်သွယ်ထားသော',
        'question_confirmed' => 'မေးခွန်းအတည်ပြုပြီး',
        'answer_confirmed' => 'အဖြေအတည်ပြုပြီး',
        'answering' => 'ဖြေနေဆဲ',
        'sent_to_audit' => 'စီစစ်နေဆဲ'
    ];

    return $astrologyTypeTranslations[$status];
}*/

function activeFullUrl($fullUrl)
{
    // get only path from Full Url
    $path = parse_url($fullUrl, PHP_URL_PATH);
    $path = substr($path, 1);

    return request()->is($path);
}

function activePath($pathName)
{
    return (strpos(Request::path(), $pathName) !== false);
}

function getOneMonthDatesArray($month, $year)
{
    $aDates = array();
    $month = str_pad($month, 2, '0', STR_PAD_LEFT);
    $oStart = new DateTime("$year-$month-01");
    $oEnd = clone $oStart;
    $oEnd->add(new DateInterval("P1M"));

    while ($oStart->getTimestamp() < $oEnd->getTimestamp()) {
        $aDates[] = $oStart->format('Y-m-d');
        $oStart->add(new DateInterval("P1D"));
    }

    return $aDates;
}

function convertToObject($array)
{
    $object = new stdClass();
    foreach ($array as $key => $value) {
        $object->$key = $value;
    }

    return $object;
}