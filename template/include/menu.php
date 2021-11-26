<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php foreach ($menu as $item) : ?>
            <li class="nav-item">
                <a class="nav-link <?= isCurrentUrl($item['path']) ? "active" : ""; ?>" aria-current="page" href="<?= $item['path']; ?>"><?= cutString($item['title'], 15); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
    <div class="d-flex">
        <?php if (isAuth()) : ?>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= getUserProfile()->full_name ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="/profile/"><i class="fas fa-user-circle me-2"></i>Профиль</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="/logout"><i class="fa fa-sign-out-alt me-2" aria-hidden="true"></i>Выйти</a></li>
                    </ul>
                </li>
            </ul>
        <?php else : ?>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active btn btn-secondary mx-auto" href="/?login=yes">
                        <i class="fas fa-sign-in-alt me-2" aria-hidden="true"></i>Войти
                    </a>
                </li>
            </ul>
        <?php endif; ?>
    </div>
</div>
