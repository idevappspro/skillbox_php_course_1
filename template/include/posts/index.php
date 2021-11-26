<?php
$unread_posts = getNewPosts();
$read_posts = getReadPosts();
?>
<div class="row bg-light py-3">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <a href="/posts/add/" target="_self">
            <button class="btn btn-primary" type="button">Создать сообщение</button>
        </a>
    </div>
</div>
<!--Unread posts-->
<div class="row bg-light mb-3">
    <div class="col-sm-12 col-xs-12 col-md-12">
        <div class="card shadow-sm <?= count($unread_posts) > 0 ? '' : 'alert-default' ?>">
            <div class="card-body">
                <h5 class="<?= count($unread_posts) > 0 ? 'mb-3' : 'mb-0' ?>">
                    <span><?= count($unread_posts) > 0 ? 'Новых сообщений: ' : 'Новых сообщений нет' ?></span>
                    <?php if (count($unread_posts) > 0) : ?>
                        <span class="align-middle ms-1"><i class="fa fa-envelope text-warning" aria-hidden="true"></i>
                            <?= count($unread_posts); ?>
                        </span>
                    <?php endif; ?>
                </h5>
                <?php if (count($unread_posts) > 0) : ?>
                    <div class="table-responsive table-hover">
                        <table class="table table-hover">
                            <caption>Сортировка по дате создания / отправления по убыванию</caption>
                            <thead>
                                <tr>
                                    <th class="text-left" style="width: 40px;">#</th>
                                    <th class="text-left">От</th>
                                    <th class="text-left" style="width: 300px;">Тема</th>
                                    <th>Отправлено</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($unread_posts as $key => $post) : ?>
                                    <tr>
                                        <td class="text-left py-3"><?= $key + 1 ?></td>
                                        <td class="text-left py-3" style="width: 200px;"><?= $post['sender'] ?></td>
                                        <td class="text-left py-3">
                                            <a href="/posts/detail.php?id=<?= $post['id'] ?>">
                                                <?= $post['post_section'] . ': ' . $post['title'] ?>
                                            </a>
                                        </td>
                                        <td class="float-right py-3">
                                            <?= date_create($post['created_at'])->format('d.m.Y H:i:s') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<!--Read posts-->
<?php if (count($read_posts) > 0) : ?>
    <div class="row bg-light mb-3">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="<?= count($read_posts) > 0 ? 'mb-4' : 'mb-0 pb-0' ?>">Все сообщения</h5>
                    <?php if (count($read_posts)) : ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <caption>Сортировка по дате создания / отправления по убыванию</caption>
                                <thead>
                                    <tr>
                                        <th class="text-left" style="width: 40px;">#</th>
                                        <th class="text-left" style="width: 200px;">От</th>
                                        <th class="text-left" style="width: 300px;">Тема</th>
                                        <th class="text-center">Отправлено</th>
                                        <th class="text-center">Прочитано</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($read_posts as $key => $post) : ?>
                                        <tr>
                                            <td class="text-left py-3"><?= $key + 1 ?></td>
                                            <td class="text-left py-3"><?= $post['sender'] ?></td>
                                            <td class="text-left py-3">
                                                <a href="/posts/detail.php?id=<?= $post['id'] ?>">
                                                    <?= $post['post_section'] . ': ' . $post['title'] ?>
                                                </a>
                                            </td>
                                            <td class="py-3 text-center">
                                                <?= date_create($post['created_at'])->format('d.m.Y H:i:s') ?>
                                            </td>
                                            <td class="py-3 text-center">
                                                <span><?= !is_null($post['read_at']) ? date_create($post['read_at'])->format('d.m.Y H:i:s') : '' ?></span>
                                                <span><?php if (is_null($post['read_at'])): ?><i class="fas fa-envelope text-warning"></i><?php endif; ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
