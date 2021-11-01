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
    $page = getPage($menu);
    $error = true;
    $page_title = "Ошибка 404";
    $page_description = "Запрошенная страница не найдена.";

    if (!empty($page)) {
        $error = false;
        $page_title = $page['title'];
        $page_description = $page['description'];
    }
    include $_SERVER['DOCUMENT_ROOT'] . "/template/include/page_title.php";
}

function getPage($pages): array
{
    $page = [];
    foreach ($pages as $item) {
        if (isCurrentUrl($item['path'])) {
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

function showPageAttr($attr = 'title'): string
{
    include $_SERVER['DOCUMENT_ROOT'] . "/data/main_menu.php";
    $page = getPage($menu);
    return $page[$attr] ?? "404 - Страница не найдена";
}

function getFileBasename($file): string
{
    $file_path = $_SERVER['DOCUMENT_ROOT'] . "/upload/" . $file;
    $ext = pathinfo($file_path, PATHINFO_EXTENSION);
    $output = null;
    if ($file_path) {
        $output = basename($file_path, "." . $ext);
    }
    return $output;
}

function getFileSize($file): string
{
    $output = "";
    $path = $_SERVER['DOCUMENT_ROOT'] . "/upload/" . $file;
    $filesize = filesize($path);
    $measure = [
        ' b',
        ' Kb',
        ' Mb'
    ];
    if ($filesize < 10000) {
        $output = $filesize . $measure[0];
    } elseif ($filesize > 10000 && $filesize < 1000000) {
        $output = round($filesize / 1024) . $measure[1];
    } elseif ($filesize > 1000000) {
        $output = round($filesize / 1024 / 1024, 1) . $measure[2];
    }

    return $output;
}