<?php include $_SERVER['DOCUMENT_ROOT'] . '/template/header.php';?>
<div class="container bg-white">
    <div class="row">
        <div class="col-md-12">
            <!--Page header-->
            <?php showPageHeader(); ?>
        </div>
    </div>
    <!-- Alert message -->
    <div class="row">
        <div class="col-md-12">
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/template/include/message.php"; ?>
        </div>
    </div>
    <?php if (!empty($page)) : ?>
        <div class="row">
            <div class="col-md-12">
                <!--Page content-->
                <?php if ($page != "") {
                    include $page;
                } ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/template/footer.php' ?>
