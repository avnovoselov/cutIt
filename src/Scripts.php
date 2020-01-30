<?php


namespace CutIt;


use Exception;

/**
 * Trait Scripts
 *
 * @method static Storage createStorage(string $rootPath)
 *
 * @package CutIt
 */
trait Scripts
{
    /**
     * Prepare database.
     * Create tables
     *
     * Run by composer script "post-install-cmd"
     *
     * @throws Exception
     */
    public static function install(): void
    {
        self::createStorage(dirname(__DIR__))->prepareDB();
    }

    /**
     * Clear database
     * Drop created tables
     *
     * Run by "/> composer application-clear"
     *
     * @throws Exception
     */
    public static function clear(): void
    {
        self::createStorage(dirname(__DIR__))->clearDB();
    }
}
