<?php


namespace CutIt;


/**
 * Class Application
 * @package CutIt
 */
final class Application
{
    use Scripts;

    /**
     * SQLite .db file name
     */
    private const STORAGE_FILE = "storage.db";

    /**
     * Path to project directory
     *
     * @var string
     */
    private $rootPath;

    /**
     * Storage instance
     *
     * @var Storage
     */
    private $storage;

    /**
     * @var Application
     */
    private static $instance;

    public static function getInstance(): self
    {
        return static::$instance = static::$instance ?: new self(dirname(__DIR__));
    }

    /**
     * Storage instance getter
     *
     * @return Storage
     */
    public function getStorage(): Storage
    {
        return $this->storage;
    }

    /**
     * Create and return Storage instance
     *
     * @param string $rootPath
     * @return Storage
     */
    private static function createStorage(string $rootPath): Storage
    {
        return new Storage($rootPath . "/var/" . self::STORAGE_FILE);
    }

    /**
     * Application constructor.
     *
     * @param string $rootPath
     */
    private function __construct(string $rootPath)
    {
        $this->rootPath = $rootPath;

        $this->storage = self::createStorage($this->rootPath);
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function __clone()
    {
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function __wakeup()
    {
    }
}
