<?php


use CutIt\Converter;
use PHPUnit\Framework\TestCase;

/**
 * Class ConverterTest
 */
class ConverterTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testRunToBin(): void
    {
        $this->assertEquals(Converter::run("a", $from = 16, $to = 2), "1010", "from {$from} to {$to}");
        $this->assertEquals(Converter::run("7", $from = 8, $to = 2), "111", "from {$from} to {$to}");
        $this->assertEquals(Converter::run("10", $from = 9, $to = 2), "1001", "from {$from} to {$to}");
    }

    /**
     * @throws Exception
     */
    public function testRunToOct(): void
    {
        $this->assertEquals(Converter::run("123a", $from = 16, $to = 8), "11072", "from {$from} to {$to}");
        $this->assertEquals(Converter::run("7", $from = 10, $to = 8), "7", "from {$from} to {$to}");
        $this->assertEquals(Converter::run("10", $from = 8, $to = 8), "10", "from {$from} to {$to}");
    }

    /**
     * @throws Exception
     */
    public function testRunToDec(): void
    {
        $this->assertEquals(Converter::run("a", $from = 16, $to = 10), "10", "from {$from} to {$to}");
        $this->assertEquals(Converter::run("1010", $from = 2, $to = 10), "10", "from {$from} to {$to}");
        $this->assertEquals(Converter::run("Aa0", $from = 62, $to = 10), "139004", "from {$from} to {$to}");
    }

    /**
     * @throws Exception
     */
    public function testRunToHex(): void
    {
        $this->assertEquals(Converter::run("1024", $from = 10, $to = 16), "400", "from {$from} to {$to}");
        $this->assertEquals(Converter::run("1011", $from = 2, $to = 16), "b", "from {$from} to {$to}");
        $this->assertEquals(Converter::run("aB8z", $from = 62, $to = 16), "268b57", "from {$from} to {$to}");
    }

    /**
     * @throws Exception
     */
    public function testRunRandom(): void
    {
        $attempts = 100;
        while ($attempts--) {
            $to = rand(10, 16);
            $number = rand(1111, 99999);
            $this->assertEquals(Converter::run($number, $from = 10, $to), base_convert($number, $from, $to), "from {$from} to {$to}");
        }

        $to = rand(2, 15);
        $number = bin2hex(random_bytes(3));

        $attempts = 100;
        while ($attempts--) {
            $this->assertEquals(Converter::run($number, $from = 16, $to), base_convert($number, $from, $to), "from {$from} to {$to}");
        }
    }
}
