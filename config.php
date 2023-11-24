<?php
    class Config {
        public const MIN_CAPTCHA_LENGTH = 6;
        public const MAX_CAPTCHA_LENGTH = 12;

        public const CAPTCHA_IMG_HEIGHT = 50;
        public const FONT_SIZE = 20;
        public const FONT_FOLDER = './assets/fonts/';

        public const INITIAL_X = 10;
        public const INITIAL_Y = 40;

        public static function getCaptchaLength($length = null) {
            if ($length !== null && is_int($length) && $length >= self::MIN_CAPTCHA_LENGTH && $length <= self::MAX_CAPTCHA_LENGTH) {
                return $length;
            } else {
                return mt_rand(self::MIN_CAPTCHA_LENGTH, self::MAX_CAPTCHA_LENGTH);
            }
        }
    }
?>