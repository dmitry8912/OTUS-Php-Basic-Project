<?php
declare(strict_types=1);

function getConfig(): array {
    if(!file_exists($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'config.ini'))
        die("config.ini not found!");
    return parse_ini_file($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'config.ini', true);
}
