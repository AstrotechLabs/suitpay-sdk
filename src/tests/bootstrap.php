<?php

declare(strict_types=1);

error_reporting(E_ALL & ~E_DEPRECATED);

$composerAutoload = __DIR__ . '/../../vendor/autoload.php';

if (is_file($composerAutoload)) {
    require_once $composerAutoload;
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

DG\BypassFinals::enable();
