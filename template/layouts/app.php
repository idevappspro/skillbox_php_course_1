<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/template/header.php' ?>
    <table class="table">
        <tr>
            <td class="left-collum-index">
                <?php if ($isAuth) include $_SERVER['DOCUMENT_ROOT'] . "/template/include/auth_message.php" ?>
                <?php showPageHeader(); ?>
            </td>
            <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/template/include/auth_form.php'; ?>
        </tr>
    </table>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/template/footer.php' ?>