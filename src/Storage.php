<?php


namespace CutIt;


use Exception;
use PDO;
use PDOStatement;

/**
 * Class Storage
 *
 * @package CutIt
 */
class Storage
{
    private const CREATE_TABLE_QUERIES = [
        "link" => "CREATE TABLE `link` (`id` INT(10) NOT NULL, `link` VARCHAR(2000) NOT NULL, `created_at` DATETIME NOT NULL, `expire_at` DATETIME NOT NULL, PRIMARY KEY (`id`))",
    ];

    private const DROP_TABLE_QUERIES = [
        "link" => "DROP TABLE IF EXISTS `link`",
    ];

    /**
     * @var string
     */
    private $file;

    /**
     * @var PDO
     */
    private $pdo;

    /**
     * Create application tables
     *
     * @throws Exception
     */
    public function prepareDB(): void
    {
        list($sql) = $this->prepare("SELECT * FROM `sqlite_master`");

        while ($a = $sql->fetch(PDO::FETCH_ASSOC)) {
            if (array_key_exists($a["tbl_name"], self::CREATE_TABLE_QUERIES)) {
                throw new Exception("Table {$a["tbl_name"]} already exists");
            }
        }

        foreach (self::CREATE_TABLE_QUERIES as $table => $query) {
            if ($this->exec($query) !== false) {
                echo PHP_EOL . "Table {$table} created";
            } else {
                throw new Exception("Table {$table} not created");
            }
        }
    }

    /**
     * Drop application tables
     *
     * @throws Exception
     */
    public function clearDB(): void
    {
        foreach (self::DROP_TABLE_QUERIES as $table => $query) {
            if ($this->exec($query) !== false) {
                echo PHP_EOL . "Table {$table} dropped";
            } else {
                throw new Exception("Table {$table} not dropped");
            }
        }
    }

    /**
     * Provide PDO::prepare & PDO::execute
     *
     * @param string $query
     * @param array $param
     *
     * @return array<PDOStatement, bool>
     */
    public function prepare(string $query, array $param = []): array
    {
        $sql = $this->pdo->prepare($query);

        return [$sql, $sql->execute($param)];
    }

    /**
     * Provide PDO::exec
     *
     * @param string $query
     *
     * @return int|bool
     */
    public function exec(string $query)
    {
        return $this->pdo->exec($query);
    }

    /**
     * Storage constructor.
     *
     * @param string $file
     */
    public function __construct(string $file)
    {
        $this->file = $file;

        $this->pdo = new PDO("sqlite:{$file}");
        $this->pdo->exec('PRAGMA encoding="UTF-8"');
    }
}
