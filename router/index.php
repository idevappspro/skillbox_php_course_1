<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/core.php";
$post = $_POST;

init_session();

if (isset($post)) {
    if (!empty($post['login']) && !empty($post['password'])) {
        Authenticate(htmlspecialchars(trim($post['login'])), htmlspecialchars(trim($post['password'])));
    }
}

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
    include $_SERVER['DOCUMENT_ROOT'] . "/template/layouts/app.php";
} else {
    if (isCurrentUrl("/") || isCurrentUrl("/?login=yes")) {
        include $_SERVER['DOCUMENT_ROOT'] . "/template/layouts/app.php";
    } else {
        header("Location: /?login=yes");
    }
}

if (isCurrentUrl("/logout")) {
    logout();
}
