<?php if (isset($_GET['login']) && $_GET['login'] === "yes" && !isAuth()) : ?>
    <div class="row justify-content-md-center bg-secondary py-4">
        <div class="col-md-4">
            <div class="card">
                <form action="/?login=yes" method="POST" class="card-body">
                    <div class="mb-3">
                        <label for="login_id" class="form-label">Логин</label>
                        <?php if (empty(last_login_get())) : ?>
                            <input type="text" class="form-control" name="login" id="login_id" aria-describedby="login_help" required>
                        <?php else : ?>
                            <input type="text" class="form-control" id="login_id" name="login" aria-describedby="login_help" value="<?= last_login_get(); ?>">
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="password_id" class="form-label">Пароль</label>
                        <input type="password" name="password" class="form-control" id="password_id" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Check me out</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Войти</button>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
