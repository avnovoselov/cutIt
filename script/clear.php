<?php

use CutIt\Application;

include dirname(__DIR__) . "/vendor/autoload.php";

$now = new DateTime();

Application::getInstance()->getStorage()->prepare(
    "DELETE FROM `link` WHERE `expire_at` < :now",
    [":now" => $now->format("c")]
);
