<?php

namespace App\Utilities;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use SteveNay\MyanFont\MyanFont;

class LanguageOption
{
    protected $language;

    public function __construct($language)
    {
        $this->language = $language;
    }

    /**
     * Setter method
     */
    public function setLanguage(String $language)
    {
        $this->language = $language ?? "eng";

        return $this;
    }

    public function getWelcomeMessage($language)
    {
        $message = "";
        switch ($language) {
            case "zaw":
                $message = config('constants.welcome_my');
                break;
            case "uni":
                $message = MyanFont::convert(config('constants.welcome_my'));
                break;
            default:
                $message = config('constants.welcome_en');
                break;
        }

        return $message;
    }

    public function getText(String $translationKey, String $lan = null)
    {
        if (! empty($lan)) {
            $this->language = $lan;
        }

        switch ($this->language) {
            case "zaw":
            case "uni":
                App::setLocale("mm");
                $message = ($this->language == "uni")
                    ? __($translationKey) : MyanFont::uni2zg(__($translationKey));
                break;
            case "eng":
            default:
                App::setLocale("en");
                $message = __($translationKey);
                break;
        }

        return $message;
    }

    public function hasText(String $translationKey, String $lan = null)
    {
        if (! empty($lan)) {
            $this->language = $lan;
        }

        switch ($this->language) {
            case "zaw":
            case "uni":
                App::setLocale("mm");
                $hasText = Lang::has($translationKey);
                break;
            case "eng":
            default:
                App::setLocale("en");
                $hasText = Lang::has($translationKey);
                break;
        }

        return $hasText;
    }

    public static function __callStatic($methodName, $args)
    {
        switch ($methodName) {
            case 'getTextStatic':
                if (count($args) < 2) {
                    throw new InvalidArgumentException('Expecting at least 2 parameters');
                }
                try {
                    $self = new LanguageOption($args[1]);
                    $result = $self->getText($args[0]);
                } catch (Exception $e) {
                    throw $e;
                }

                return $result;
            default:
                throw new BadMethodCallException("Method [{$methodName}] does not exist");
        }
    }

    public function format(String $message, array $replaces)
    {
        foreach ($replaces as $key => $value) {
            $message = str_replace('{'.strtolower($key).'}', $value, $message);
        }

        return $message;
    }

    public static function decideLanguage(String $message)
    {
        $lan = "";

        // check by english words
        if (strpos($message, 'zawgyi') !== false) {
            $lan = "zaw";
        } else {
            if (strpos($message, 'unicode') !== false) {
                $lan = "uni";
            }
        }

        // check by burmese words
        if (empty($lan)) {
            $lan = MyanFont::fontDetect($message, 'zaw') ?? 'eng';
        }

        return substr($lan, 0, 3);
    }
}