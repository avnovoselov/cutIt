<?php


namespace CutIt;


use Exception;

/**
 * Class Link
 * @package CutIt
 */
final class Link
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $hash;

    /**
     * Return link hash
     *
     * @param string $string
     * @return string
     */
    private static function hash(string $string)
    {
        return hash('crc32', $string, false);
    }

    /**
     * Return short link
     *
     * @return string
     * @throws Exception
     */
    public function getShortLink(): string
    {
        $id = Converter::run($this->hash, 16, Converter::getDictionaryLength());
        return "https://cut-it.io/?q={$id}";
    }

    /**
     * Hash constructor.
     *
     * @param string $link
     */
    public function __construct(string $link)
    {
        $this->url = $link;
        $this->hash = static::hash($this->url);
    }
}
