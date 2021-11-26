<?php

use DateTime as DateTime;
use PDO as PDO;
use PDOException as PDOException;

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
            $pdo = new PDO(
                "mysql:host=localhost; dbname=sql_homework_ide",
                "sql_homework_ide",
                "KnMhtadTpGwYCTb6",
                $options
            );
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
    }
    return '';
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

    if (!empty($credentials) && password_verify($password, $credentials['password'])) {
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
        session_flash_set('message', 'С возвращением, ' . getUserProfile()->full_name . '!');
        header("Location: /");
        exit();
    } else {
        init_session();
        session_flash_set('type', 'danger');
        session_flash_set('message', 'Ошибка авторизации. Неправильный логин или пароль.');
    }
}

function getUserProfile(): object
{
    $user_id = $_SESSION['USER_ID'];
    $pdo = connectDB();
    $stmt = $pdo->prepare('SELECT u.login, `user_id`, `email`, `full_name`, `phone` 
                            FROM `users` AS u LEFT JOIN `profiles` p 
                            ON u.id = p.user_id WHERE `user_id` = ? LIMIT 1');
    $stmt->execute([$user_id]);
    return $stmt->fetchObject();
}

function getUserGroups(int $user_id): array
{
    $pdo = connectDB();
    $sql = "SELECT `name`, `description` FROM `user_group` 
            LEFT JOIN `groups` g ON g.id = user_group.group_id WHERE `user_id` = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function userHasRole($alias): bool
{
    $user_id = $_SESSION['USER_ID'];
    $pdo = connectDB();
    $sql = "SELECT gr.alias FROM user_group LEFT JOIN `groups` gr ON group_id = gr.id WHERE user_id = :user_id AND gr.alias = :alias";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $alias]);
    $result = $stmt->fetchObject();
    if ($result->alias === $alias) {
        return true;
    }
    session_flash_set('type', 'danger');
    session_flash_set('message', 'Доступ ограничен. Отсутствуют необходимые разрешения.');
    header("Location: /");
    exit();
}

function updateUserProfile($payload): void
{
    $pdo = connectDB();
    $sql = "UPDATE `profiles` SET `full_name` = :full_name, `email` = :email, `phone` = :phone 
            WHERE `user_id` = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':full_name' => $payload['full_name'],
        ':email' => $payload['email'],
        ':phone' => $payload['phone'],
        ':user_id' => $payload['user_id']
    ]);
    session_flash_set('type', 'success');
    session_flash_set('message', 'Профиль успешно обновлен');

    if ($stmt->rowCount() === 0) {
        session_flash_set('type', 'danger');
        session_flash_set('message', 'Пожалуйста введите новые данные для сохранения.');
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
    session_flash_set('type', 'warning');
    session_flash_set('message', 'Сеанс успешно завершен');
    header("Location: /");
    exit();
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
    $stmt = $pdo->prepare("SELECT * FROM menu");
    $stmt->execute();
    return $stmt->fetchAll();
}

function showMenu($ulClass = ''): void
{
    $menu = getMenu();

    for ($i = 0; $i <= count($menu); $i++) {
        if (is_null($menu[$i]['show'])) {
            unset($menu[$i]);
        }
    }

    if (!isAuth()) {
        unset($menu[7]);
        unset($menu[8]);
        unset($menu[9]);
        unset($menu[10]);
    }

    $menu = arraySort($menu, 'sort', SORT_ASC);
    if ($ulClass === "bottom") {
        $menu = arraySort($menu, 'title', SORT_DESC);
    }
    include $_SERVER['DOCUMENT_ROOT'] . '/template/include/menu.php';
}

function showPageHeader(): void
{
    $menu = getMenu();
    $payload = getPage($menu);
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

function getNewPosts()
{
    $pdo = connectDB();
    $self = (int)$_SESSION['USER_ID'];
    $sql = "SELECT posts.id, title, content, created_at, read_at, p.full_name AS 'sender', ps.name AS 'post_section' 
            FROM posts LEFT JOIN profiles p ON posts.from_user_id = p.user_id 
            LEFT JOIN post_sections ps on ps.id = posts.section_id 
            WHERE to_user_id = :to_user_id AND read_at IS NULL ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':to_user_id' => $self]);
    return $stmt->fetchAll();
}

function getReadPosts()
{
    $pdo = connectDB();
    $self = (int)$_SESSION['USER_ID'];
    $sql = "SELECT posts.id, title, content, created_at, read_at, s.full_name AS 'sender', r.full_name AS 'recipient', ps.name AS 'post_section' 
            FROM posts
            LEFT JOIN profiles s ON posts.from_user_id = s.user_id 
            LEFT JOIN profiles r ON posts.to_user_id = r.user_id 
            LEFT JOIN post_sections ps on ps.id = posts.section_id 
            WHERE to_user_id = :to_user_id AND read_at IS NOT NULL 
            ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':to_user_id' => $self]);
    return $stmt->fetchAll();
}

function readPost($id): object
{
    $self_id = $_SESSION['USER_ID'];
    $pdo = connectDB();
    $sql = "SELECT posts.id, posts.title, posts.content, posts.section_id, posts.created_at, 
            posts.read_at, posts.to_user_id, posts.from_user_id, p.full_name
            AS 'sender', r.full_name AS 'recipient', ps.name AS 'post_section' 
            FROM posts
            LEFT JOIN profiles p ON posts.from_user_id = p.user_id 
            LEFT JOIN profiles r ON posts.to_user_id = r.user_id 
            LEFT JOIN post_sections ps on ps.id = posts.section_id 
            WHERE posts.id = :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $post = $stmt->fetchObject();

    if (empty($post)) {
        session_flash_set('type', 'danger');
        session_flash_set('message', 'Ошибка 404. Сообщение не существует');
        header("Location: /posts/");
        exit();
    }

    if ($post->to_user_id !== $self_id) {
        session_flash_set('type', 'danger');
        session_flash_set('message', 'Доступ к сообщению ограничен');
        header("Location: /posts/");
        exit();
    }

    if (is_null($post->read_at)) {
        $read_at = new DateTime();
        $sql = "UPDATE posts SET read_at = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$read_at->format("Y-m-d H:i:s"), $id]);
    }
    return $post;
}

function submitPost($payload): void
{
    if (!count($payload)) {
        session_flash_set('type', 'danger');
        session_flash_set('message', 'Сообщение не отправлено. Сообщите вашему администратору');
        header("Location: /posts/");
        exit();
    }
    $pdo = connectDB();
    $sql = "INSERT INTO `posts` (`title`,`content`,`from_user_id`,`to_user_id`,`section_id`,`post_id`) 
            VALUES (:title, :content, :from_user_id, :to_user_id, :section_id, :post_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($payload);
    session_flash_set('type', 'success');
    session_flash_set('message', 'Сообщение отправлено');
    header("Location: /posts/");
    exit();
}

function getPostRecipients(): array
{
    $self_id = $_SESSION['USER_ID'];
    $pdo = connectDB();
    $sql = "SELECT u.id AS `user_id`, pr.full_name AS 'full_name' FROM users u
            LEFT JOIN `profiles` pr ON u.id = pr.user_id
            LEFT JOIN `user_group` ugr ON ugr.user_id = u.id
            LEFT JOIN `groups` gr ON ugr.group_id = gr.id
            WHERE gr.alias LIKE 'post_%' AND u.id != :self_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$self_id]);
    return $stmt->fetchAll();
}

function getPostSections(): array
{
    $pdo = connectDB();
    $sql = "SELECT `id`, `name` FROM `post_sections`";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

$pdo = null;
