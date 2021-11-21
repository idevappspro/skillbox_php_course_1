<?php

function connectDB(): PDO
{
    static $pdo;

    if (null === $pdo) {
        try {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $pdo = new PDO("mysql:host=localhost; dbname=sql_homework_ide", "sql_homework_ide", "KnMhtadTpGwYCTb6", $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    return $pdo;
}

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

function Authenticate(string $login, string $password): void
{
    $pdo = connectDB();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE `login` = :login LIMIT 1');
    $stmt->execute(['login' => $login]);
    $credentials = $stmt->fetch();

    if (!empty($credentials['login']) && password_verify($password, $credentials['password'])) {
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
        $_SESSION['USER_ID'] = $credentials['id'];
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

function getUserProfile(): object
{
    $user_id = $_SESSION['USER_ID'];
    $pdo = connectDB();
    $stmt = $pdo->prepare('SELECT u.login, `user_id`, `email`, `full_name`, `phone` from `users` AS u LEFT JOIN `profiles` p ON u.id = p.user_id WHERE `user_id` = ? LIMIT 1');
    $stmt->execute([$user_id]);
    return $stmt->fetchObject();
}

function getUserGroups(int $user_id): array
{
    $pdo = connectDB();
    $sql = "SELECT `name`, `description` FROM `user_group` LEFT JOIN `groups` g ON g.id = user_group.group_id WHERE `user_id` = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function updateUserProfile($payload): void
{
    $pdo = connectDB();
    $sql = "UPDATE `profiles` SET `full_name` = :full_name, `email` = :email, `phone` = :phone WHERE `user_id` = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':full_name' => $payload['full_name'],
        ':email' => $payload['email'],
        ':phone' => $payload['phone'],
        ':user_id' => $payload['user_id']
    ]);
    if ($stmt->rowCount() === 0) {
        session_flash_set('type', 'error');
        session_flash_set('message', 'Пожалуйста введите измененные данные.');
    } else {
        session_flash_set('type', 'success');
        session_flash_set('message', 'Профиль успешно обновлен');
    }
}

function logout()
{
    if (isset($_SESSION)) {
        unset($_SESSION['USER_ID']);
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
    $pdo = connectDB();
    $stmt = $pdo->prepare('SELECT * FROM menu');
    $stmt->execute();
    $menu = $stmt->fetchAll();
    if (!isAuth()) {
        unset($menu[7]);
    }
    return $menu;
}

function showMenu($ulClass = ''): void
{
    $menu = getMenu();
    if ($ulClass === "bottom") {
        $menu = arraySort($menu, 'title', SORT_DESC);
    } else {
        $menu = arraySort($menu, 'sort', SORT_ASC);
    }
    include $_SERVER['DOCUMENT_ROOT'] . '/template/include/menu.php';
}

function showPageHeader(): void
{
    $menu = getMenu();
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
    $menu = getMenu();
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

$pdo = null;
