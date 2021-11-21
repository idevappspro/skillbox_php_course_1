<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/core.php";

$post = $_POST;
init_session();

if (isAuth()) {
    if ($_SESSION['LAST_REQUEST_TIME'] < time()) {
        logout();
    }
    $_SESSION['LAST_REQUEST_TIME'] = time() + 1800;
    setcookie('LOGIN', $_SESSION['LOGIN'], [
        'expires' => time() + 60 * 60 * 24 * 31,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => true,
        'httponly' => true,
        'samesite' => 'lax',
    ]);

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
    }
    include $_SERVER['DOCUMENT_ROOT'] . "/template/layouts/app.php";
} else {
    if (isset($post) && !empty($post)) {
        if (!empty($post['login']) && !empty($post['password'])) {
            Authenticate(htmlspecialchars(trim($post['login'])), htmlspecialchars(trim($post['password'])));
        }
    }
    if (isCurrentUrl("/") || isCurrentUrl("/?login=yes")) {
        include $_SERVER['DOCUMENT_ROOT'] . "/template/layouts/app.php";
    } else {
        header("Location: /?login=yes");
    }
}

if (isCurrentUrl("/logout")) {
    logout();
}
