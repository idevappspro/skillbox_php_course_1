<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/core.php";

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requested_page = getPage($menu, $path);

include $_SERVER['DOCUMENT_ROOT'] . "/template/layouts/app.php";
