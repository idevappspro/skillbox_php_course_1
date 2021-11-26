<?php $profile = getUserProfile(); ?>
<div class="row bg-light py-3">
    <div class="col-xs-12 col-sm-12 col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="/profile/" method="POST">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Имя пользователя</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" aria-describedby="full_name_help" require value="<?= $profile->full_name; ?>">
                        <!-- <div id="full_name_help" class="form-text">We'll never share your email with anyone else.</div> -->
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email" aria-describedby="email_help" require value="<?= $profile->email; ?>" placeholder="Адрес электронной почты">
                        <!-- <div id="email_help" class="form-text">We'll never share your email with anyone else.</div> -->
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Телефон</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Номер телефона" value="<?= $profile->phone; ?>">
                    </div>
                    <input type="hidden" name="update_profile" value="1">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-4">Группы пользователя</h5>
                <ul>
                    <?php foreach (getUserGroups($profile->user_id) as $key => $group) : ?>
                        <li><?= $group['name']; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
