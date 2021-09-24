<?php if (!$isAuth): ?>
    <td class="right-collum-index">
        <div class="project-folders-menu">
            <ul class="project-folders-v">
                <li class="project-folders-v-active">
                    <a href="/?login=yes">Авторизация</a>
                </li>
                <li><a href="#">Регистрация</a></li>
                <li><a href="#">Забыли пароль?</a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <?php if (isset($_GET['login']) && $_GET['login'] === "yes"): ?>
            <div class="index-auth">
                <form action="/?login=yes" method="POST">
                    <table class="table">
                        <?php if (!empty($_POST)): ?>
                            <tr>
                                <td>
                                    <?php include $_SERVER['DOCUMENT_ROOT'] . '/template/include/auth_message.php'; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td class="iat">
                                <label for="login_id">Ваш e-mail:</label>
                                <input id="login_id" size="30" name="login"
                                       value="<?= $old_login ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="iat">
                                <label for="password_id">Ваш пароль:</label>
                                <input id="password_id" size="30" name="password" type="password"
                                       value="<?= $old_password ?>">
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
<?php endif; ?>