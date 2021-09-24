<?php if ($error): ?>
    <h1><?= $page_title ?>. <?= $page_description ?></h1>
    <img src="/i/404.png" alt="<?= $page_title ?> - <?= $page_description ?>" height="200">
    <p>Перейти на <a href="/">главную</a></p>
<?php else: ?>
    <h1><?= $page_title ?> &mdash;</h1>
    <p><?= $page_description ?></p>
<?php endif; ?>