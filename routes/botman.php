<?php

use App\Http\Controllers\Bot\GeneralController;
use App\Http\Controllers\Bot\LanguageController;
use App\Http\Controllers\BotManController;
use App\Http\Middleware\Bot\LanguageDecider;
use App\Http\Middleware\Bot\UserRecording;

$botman = resolve('botman');
$botman->middleware->heard(new UserRecording);
$botman->middleware->heard(new LanguageDecider());

// choose language
$botman->hears('GET_STARTED|Get Started|Language', LanguageController::class . "@handleLanguageOption");

// User choose Zawgyi font to use
// Welcome Message
$botman->hears('ျမန္မာ (ေဇာ္ဂ်ီ)|zawgyi', LanguageController::class . "@handleZawgyiLanguageChosen");
$botman->hears('မြန်မာ|unicode', LanguageController::class . "@handleUnicodeLanguageChosen");
$botman->hears('english|English', LanguageController::class . "@handleEnglishLanguageChosen");

// Main Menu
$botman->hears('^Hi$|^Help$|^Menu$|^back_to_menu$|^Hello$|^Lucky Draw$', GeneralController::class . "@replyMainMenu");
$botman->hears('guidance', GeneralController::class . "@replyGuidance");
$botman->hears('latest news', GeneralController::class . "@replyLatestNews");
$botman->hears('consult', GeneralController::class . "@replyConsultation");
$botman->hears('contact', GeneralController::class . "@replyEmergencyContact");

//FallbackRoute
$botman->fallback(\App\Http\Controllers\Bot\FallbackController::class.'@handleFallback');
