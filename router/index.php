<?php

include $_SERVER['DOCUMENT_ROOT'] . "/src/functions.php";

init_session();
$post = $_POST;
$get = $_GET;
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (isAuth()) {
    $_SESSION['LAST_REQUEST_TIME'] = time() + 1800;
    setcookie('LOGIN', $_SESSION['LOGIN'], [
        'expires' => time() + 60 * 60 * 24 * 31,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => true,
        'httponly' => true,
        'samesite' => 'lax',
    ]);

    if ($_SESSION['LAST_REQUEST_TIME'] < time()) {
        logout();
    }
    if (isCurrentUrl("/logout")) {
        logout();
    }

    // POST requests
    if (isset($post) && !empty($post)) {
        if (isset($post['update_profile'])) {
            if (!empty($post['full_name']) && !empty($post['email']) && !empty($post['phone'])) {
                $payload = [
                    'full_name' => htmlspecialchars(trim($post['full_name'])),
                    'email' => htmlspecialchars(trim($post['email'])),
                    'phone' => htmlspecialchars(trim($post['phone'])),
                    'user_id' => $_SESSION['USER_ID'],
                ];
                updateUserProfile($payload);
            }
        }
        if (isset($post['post_reply']) || isset($post['post_submitted'])) {
            $payload = [
                'title' => (string) $post['title'],
                'content' => (string) $post['content'],
                'from_user_id' => (int) $_SESSION['USER_ID'],
                'to_user_id' => (int) $post['to_user_id'],
                'section_id' => (int) $post['section_id'],
            ];
            if (isset($post['post_id']) && !empty($post['post_id'])) {
                $payload['post_id'] = (int) $post['post_id'];
            } else {
                $payload['post_id'] = null;
            }
            submitPost($payload);
        }
    }
    switch ($uri) {
        case "/":
        case "/about/":
        case "/catalog/":
        case "/news/":
        case "/sitemap/":
        case "/contacts/":
            if (isCurrentUrl($uri)) {
                $page = "";
            }
            break;
        case "/profile/":
            if (isCurrentUrl($uri)) {
                $page = $_SERVER['DOCUMENT_ROOT'] . "/template/include/profile.php";
            }
            break;
        case "/gallery/":
            if (isCurrentUrl($uri)) {
                $page = $_SERVER['DOCUMENT_ROOT'] . "/template/include/gallery.php";
            }
            break;
        case "/posts/":
            if (isCurrentUrl($uri) && userHasRole("post_author")) {
                $page = $_SERVER['DOCUMENT_ROOT'] . "/template/include/posts/index.php";
            }
            break;
        case "/posts/add/":
            if (isCurrentUrl($uri) && userHasRole("post_author")) {
                $page = $_SERVER['DOCUMENT_ROOT'] . "/template/include/posts/add.php";
            }
            break;
        case "/posts/detail.php":
            if (isCurrentUrl($uri) && userHasRole("post_author")) {
                $item = readPost($_GET['id']);
                $page = $_SERVER['DOCUMENT_ROOT'] . "/template/include/posts/detail.php";
            }
            break;
        default:
            $page = $_SERVER['DOCUMENT_ROOT'] . "/template/include/404.php";
            break;
    }
    include $_SERVER['DOCUMENT_ROOT'] . "/template/layouts/app.php";
} else {
    if (isset($post)) {
        if (!empty($post['login']) && !empty($post['password'])) {
            Authenticate(htmlspecialchars(trim($post['login'])), htmlspecialchars(trim($post['password'])));
        }
    }
    if (isCurrentUrl("/") || isCurrentUrl("/?login=yes")) {
        $page = $_SERVER['DOCUMENT_ROOT'] . "/template/include/auth_form.php";
        include $_SERVER['DOCUMENT_ROOT'] . "/template/layouts/app.php";
    } else {
        header("Location: /?login=yes");
    }
}
