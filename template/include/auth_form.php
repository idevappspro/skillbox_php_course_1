<td class="right-collum-index">
    <div class="project-folders-menu">
        <ul class="project-folders-v">
            <?php if (isAuth()): ?>
                <li class="project-folders-v-active">
                    <a href="/logout">Выйти</a>
                </li>
            <?php else: ?>
                <li class="project-folders-v-active">
                    <a href="/?login=yes">Авторизация</a>
                </li>
                <li><a href="#">Регистрация</a></li>
                <li><a href="#">Забыли пароль?</a></li>
            <?php endif; ?>
        </ul>
        <div class="clearfix"></div>
    </div>
    <?php if (isset($_GET['login']) && $_GET['login'] === "yes" && !isAuth()): ?>
        <div class="auth-form">
            <form action="/?login=yes" method="POST">
                <table class="table">
                    <tr>
                        <td class="iat">
                            <?php if (empty(last_login_get())): ?>
                                <label for="login_id">Логин:</label>
                                <input type="text" id="login_id" size="30" name="login" required>
                            <?php else: ?>
                                <label for="login_id">Логин: <?= last_login_get(); ?></label>
                                <input type="hidden" id="login_id" size="30" name="login"
                                       value="<?= last_login_get(); ?>">
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="iat">
                            <label for="password_id" style="padding-bottom: 5px;">Ваш пароль:</label>
                            <input id="password_id" size="30" name="password" type="password" required>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Войти"></td>
                    </tr>
                </table>
            </form>
        </div>
    <?php endif; ?>
</td>
