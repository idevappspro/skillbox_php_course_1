<?php

function cutString($line, $length, $replace = "..."): string
{
    return mb_strimwidth($line, 0, $length, $replace);
}

function arraySort(array $array, $key, $sort): array
{
    $keys = array_column($array, $key);
    array_multisort($keys, $sort, $array);

    return $array;
}

function showMenu($menu, $ulClass = ''): void
{
    include $_SERVER['DOCUMENT_ROOT'] . '/template/include/menu.php';
}

function showPageHeader(): void
{
    include $_SERVER['DOCUMENT_ROOT'] . "/data/main_menu.php";

    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $page = getPage($menu, $path);
    $error = false;

    if (!empty($page)) {
        $page_title = $page['title'];
        $page_description = $page['description'];
    } else {
        $error = true;
        $page_title = "Ошибка 404";
        $page_description = "Запрошенная страница не найдена.";
    }

    include $_SERVER['DOCUMENT_ROOT'] . "/template/include/page_title.php";
}

function getPage($pages, $uriPath): array
{
    $page = [];
    foreach ($pages as $item) {
        if ($uriPath === $item['path']) {
            $page = $item;
            break;
        }
    }
    return $page;
}

function isCurrentUrl($url): bool
{
    static $currentUrl = null;

    if (empty($currentUrl)) {
        $currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    return $currentUrl == $url;
}