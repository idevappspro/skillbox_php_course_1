<?php include $_SERVER['DOCUMENT_ROOT'] . '/template/header.php' ?>
    <table class="table">
        <tr>
            <td class="left-collum-index">
                <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/template/include/message.php"; ?>
                <?php showPageHeader(); ?>
                <?php if (isCurrentUrl('/gallery/')) include_once $_SERVER['DOCUMENT_ROOT'] . '/template/include/gallery.php'; ?>
            </td>
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/template/include/auth_form.php'; ?>
        </tr>
    </table>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/template/footer.php' ?>
