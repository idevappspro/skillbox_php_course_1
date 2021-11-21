<?php include $_SERVER['DOCUMENT_ROOT'] . '/template/header.php' ?>
<table class="table">
    <tr>
        <td class="left-collum-index">
            <div class="row">
                <div class="col-md-12">
                    <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/template/include/message.php"; ?>
                    <div class="mb-4">
                        <!--Page header-->
                        <?php showPageHeader(); ?>
                    </div>
                </div>
            </div>
            <!--Page content-->
            <?php if (isCurrentUrl('/gallery/')) include_once $_SERVER['DOCUMENT_ROOT'] . '/template/include/gallery.php'; ?>
            <?php if (isCurrentUrl('/profile/')) include_once $_SERVER['DOCUMENT_ROOT'] . '/template/include/profile.php'; ?>
        </td>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/template/include/auth_form.php'; ?>
    </tr>
</table>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/template/footer.php' ?>
