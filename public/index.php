<?php

use CutIt\Link;

include dirname(__DIR__) . "/vendor/autoload.php";

$hash = new Link("https://yandex.ru2");

try {
    var_dump($hash->getShortLink());
} catch (Exception $e) {
    var_dump("Exception");
}
