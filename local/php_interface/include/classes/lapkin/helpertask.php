<?php

namespace Lapkin;

/**
 * Class HelperTask is simple helper class to realize some functions
 *
 * @package Lapkin
 */
class HelperTask
{
    /**
     * This function inclines the word according to the number
     *
     * @param int $number Number before word
     * @param array $titles Array of word variations (3 types)
     * @return void
     * @access public
     */
    public static function declensions($number, $titles)
    {
        $cases = array (2, 0, 1, 1, 1, 2);
        $format = $titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];
        echo $number . ' ' . $format;
    }

    /**
     * This function wraps a variable in a tag <pre> and displays it
     *
     * @param mixed $var Variable to be deduced
     * @return void
     * @access public
     */
    public static function preTag($var)
    {
        echo '<pre>' . strval($var) . '</pre>';
    }
}