<?php
namespace Grafema;

/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE.md
 */
class I18n
{
    /**
     * Local from HTTP.
     *
     * @since 1.0.0
     */
    public static $locale;

    /**
     * Translation.
     *
     * @return mixed|string
     *
     * @since 1.0.0
     */
    public static function __(string $string)
    {
        $locale = self::getLocale();
        $file_path = GRFM_PATH . 'dashboard/i18n/' . $locale . '.json';
        if (is_file($file_path)) {
            $json = file_get_contents($file_path);
            $json = json_decode($json, 1);
            if (isset($json[$string])) {
                return $json[$string];
            }
        }
        return $string;
    }

    /**
     * Get local from HTTP.
     *
     * @since 1.0.0
     */
    public static function getLocale(string $default = 'en_US')
    {
        if ( ! isset(self::$locale) && function_exists('locale_accept_from_http')) {
            self::$locale = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']);
        }
        return self::$locale ?? $default;
    }

    public static function locale(string $default = 'en_US')
    {
        echo self::getLocale($default);
    }

    /**
     * Translation.
     *
     * @since 1.0.0
     */
    public static function e(string $string): void
    {
        echo self::__($string);
    }
}
