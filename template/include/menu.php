<ul class="main-menu <?= $ulClass; ?>">
    <?php foreach ($menu as $item): ?>
        <li>
            <a class="<?= isCurrentUrl($item['path']) ? "active" : ""; ?>"
               href="<?= $item['path']; ?>"><?= cutString($item['title'], 15); ?></a>
        </li>
    <?php endforeach; ?>
</ul>