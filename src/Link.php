<?php


namespace CutIt;


use DateInterval;
use DateTime;
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
    private static function hash(string $string): string
    {
        return hash('crc32', $string, false);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function save(): bool
    {
        $id = Converter::run($this->hash, 16, 10);
        $created_at = new DateTime();
        $expire_at = new DateTime();
        $expire_at->add(new DateInterval("P2D"));

        /** @noinspection PhpUnusedLocalVariableInspection */
        list($sql, $result) = Application::getInstance()->getStorage()->prepare("INSERT INTO `link` (`id`, `link`, `created_at`, `expire_at`) VALUES (:id, :link, :created_at, :expire_at)", [
            ":id"         => $id,
            ":link"       => $this->url,
            ":created_at" => $created_at->format("c"),
            ":expire_at"  => $expire_at->format("c"),
        ]);

        return $result;
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
