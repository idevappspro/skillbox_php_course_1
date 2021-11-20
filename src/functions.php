<?php

function init_session(): void
{
    if (session_status() == PHP_SESSION_NONE) {
        session_set_cookie_params([
            'path' => '/',
            'domain' => $_SERVER['HTTP_HOST'],
            'secure' => true,
            'httponly' => true,
            'samesite' => 'lax'
        ]);
        session_start();
    }
}

function last_login_get(): ?string
{
    if (isset($_COOKIE["LOGIN"])) {
        return htmlspecialchars($_COOKIE["LOGIN"]);
    }

    return null;
}

function session_flash_set($key, $val): void
{
    $_SESSION['_flash'][$key] = $val;
}

function session_flash_get($key): string
{
    if (isset($_SESSION['_flash'][$key])) {
        $data = $_SESSION['_flash'][$key];
        unset($_SESSION['_flash'][$key]);
        return $data;
    } else {
        return '';
    }
}

function isAuth(): bool
{
    if (isset($_SESSION['AUTH']) && $_SESSION['AUTH'] === true) {
        return true;
    }
    return false;
}

function Authenticate($login, $password): void
{
    $users = include $_SERVER['DOCUMENT_ROOT'] . '/data/users.php';
    $passwords = include $_SERVER['DOCUMENT_ROOT'] . '/data/passwords.php';

    $i = array_search($login, $users);
    if ($password === $passwords[$i]) {
        init_session();
        $arr_cookie_options = [
            'expires' => time() + 60 * 60 * 24 * 31,
            'path' => '/',
            'domain' => $_SERVER['HTTP_HOST'],
            'secure' => true,
            'httponly' => true,
            'samesite' => 'lax',
        ];
        $_SESSION['AUTH'] = true;
        $_SESSION['LOGIN'] = $_POST['login'];
        $_SESSION['LAST_REQUEST_TIME'] = time() + 1800;
        setcookie('LOGIN', $_SESSION['LOGIN'], $arr_cookie_options);
        session_flash_set('type', 'success');
        session_flash_set('message', 'Вы успешно авторизовались');
        header("Location: /");
        exit();
    } else {
        if (isset($_SESSION)) {
            session_unset();
            session_destroy();
        }
        init_session();
        session_flash_set('type', 'error');
        session_flash_set('message', 'Ошибка авторизации. Неправильный логин или пароль.');
    }
}

function logout()
{
    if (isset($_SESSION)) {
        session_unset();
        session_destroy();
    }
    init_session();
    session_flash_set('type', 'success');
    session_flash_set('message', 'Сеанс успешно завершен');
    header("Location: /");
}

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

function getMenu(): array
{
    return include $_SERVER['DOCUMENT_ROOT'] . '/data/main_menu.php';
}

function showMenu($ulClass = ''): void
{
    if ($ulClass === "bottom") {
        $menu = arraySort(getMenu(), 'title', SORT_DESC);
    } else {
        $menu = arraySort(getMenu(), 'sort', SORT_ASC);
    }

    include $_SERVER['DOCUMENT_ROOT'] . '/template/include/menu.php';
}

function showPageHeader(): void
{
    $menu = include $_SERVER['DOCUMENT_ROOT'] . '/data/main_menu.php';

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
    return $currentUrl === $url;
}

function showPageAttr($attr = 'title'): string
{
    $menu = include $_SERVER['DOCUMENT_ROOT'] . '/data/main_menu.php';
    $page = getPage($menu);
    return $page[$attr] ?? "404 - Страница не найдена";
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
