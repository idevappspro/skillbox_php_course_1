<?php
$isAuth = false;
$message = "";
$old_login = "";
$old_password = "";

if (!empty($_POST)) {
    $users = include $_SERVER['DOCUMENT_ROOT'] . '/data/users.php';
    $passwords = include $_SERVER['DOCUMENT_ROOT'] . '/data/passwords.php';
    $old_login = htmlspecialchars($_POST['login']);
    $old_password = htmlspecialchars($_POST['password']);
    for ($i = 0, $len = count($users); $i < $len; $i++) {
        if ($users[$i] === htmlspecialchars($_POST['login']) && $passwords[$i] === htmlspecialchars($_POST['password'])) {
            $isAuth = true;
            $message = "Вы успешно авторизовались";
            break;
        } else {
            $message = "Неверный логин или пароль";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="/styles.css" rel="stylesheet">
    <title>Project - <?= showPageAttr('title'); ?></title>
</head>

<body>

<div class="header">
    <div class="logo"><img src="/i/logo.png" width="68" height="23" alt="Project"></div>
    <div class="clearfix"></div>
</div>

<div class="clear">
    <?php showMenu(arraySort($menu, 'sort', SORT_ASC));?>
</div>