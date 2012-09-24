<?php

/**
 * A simple language router
 */
class LanguageRouter
{

    /**
     * Determine language to serve app in
     */
    public function route()
    {
        $CI =& get_instance();
        $accept = $CI->input->system('HTTP_ACCEPT_LANGUAGE');
        $exploded = explode(',', $accept);
        $code = $exploded[0];

        $language = $this->name_language($code);
        var_dump($language);
        $CI->config->set_item('language', $language);
    }

    public function name_language($code)
    {
        if (!$code) return;

        $codes = array(
            'english' => array('en-US', 'en-us', 'en-gb'),
            'russian' => array('ru', 'ru-ru')
        );

        $needle = strtolower($code);
        foreach ($codes as $language => $codes) {
            if (!is_array($codes)) {
                $codes = array($codes);
            } // if the code is a string we make it into an array

            if (in_array($needle, $codes)) {
                return $language;
            }
        }
        return 'english';
    }

}
