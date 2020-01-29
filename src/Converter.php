<?php


namespace CutIt;

use Exception;
use GMP;

/**
 * Class Converter
 *
 * @package CutIt
 */
final class Converter
{
    /**
     * 71 base dictionary
     */
    private const BASE_DICTIONARY = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_';

    /**
     * Convert number from $from to $to
     *
     * @param string $number - number to convert
     * @param int $from - from base
     * @param int $to - to base
     *
     * @return string converted number
     * @throws Exception
     */
    public static function run(string $number, int $from, int $to)
    {
        static::validate($number, $from, $to);

        $dec = static::toDec($number, $from);

        return static::fromDec($dec, $to);
    }

    /**
     * Return dictionary length
     *
     * @return int
     */
    public static function getDictionaryLength(): int
    {
        return strlen(static::BASE_DICTIONARY);
    }

    /**
     * Validate params
     *
     * @param string $number
     * @param int $from
     * @param int $to
     *
     * @throws Exception
     */
    private static function validate(string $number, int $from, int $to)
    {
        $defaultBase = static::getDictionaryLength();

        if ($from < 2) {
            throw new Exception("\$fromBase < 2");
        }
        if ($to < 2) {
            throw new Exception("\$toBase < 2");
        }
        if ($from > $defaultBase) {
            throw new Exception("\$from greater then BASE_DICTIONARY. You need complete the dictionary");
        }
        if ($to > $defaultBase) {
            throw new Exception("\$to greater then BASE_DICTIONARY. You need complete the dictionary");
        }

        $dictionary = substr(self::BASE_DICTIONARY, 0, $from);
        $array = str_split($number);
        foreach ($array as $index => $char) {
            if (strpos($dictionary, $char) === false) {
                throw new Exception("Char {$char} at position {$index} not available in {$from} base");
            }
        }
    }

    /**
     * Convert $number to decimal
     *
     * @param string $number
     * @param int $base
     *
     * @return GMP
     */
    private static function toDec(string $number, int $base): GMP
    {
        // Conversion $number($base) to $dec(10) result
        $dec = gmp_init(0);
        // length of number to convert
        $length = strlen($number);
        // $number to array conversion
        $array = str_split($number);

        foreach ($array as $index => $char) {
            // position of char in dictionary (ex.: 3 == 3, A == 10, F == 16)
            $position = strpos(self::BASE_DICTIONARY, $char);
            // $base ^ $exponent
            $exponent = $length - $index - 1;
            // sum
            $dec = gmp_add($dec, gmp_mul($position, gmp_pow($base, $exponent)));
        }

        return $dec;
    }

    /**
     * Convert decimal $number to $base base
     *
     * @param GMP $number
     * @param int $base
     * @return string
     */
    private static function fromDec(GMP $number, int $base): string
    {
        // Conversion $number(10) to $result($base)
        $result = '';

        // while $number >= $base
        while (gmp_cmp($number, $base) >= 0) {
            // $number = floor($number / $base)
            // $remainder = $number % $base
            list($number, $remainder) = gmp_div_qr($number, $base);
            // concat reverted
            $result .= self::BASE_DICTIONARY[(int)$remainder];
        }
        // last remainder == $number
        $result .= self::BASE_DICTIONARY[(int)$number];

        return strrev($result);
    }
}
