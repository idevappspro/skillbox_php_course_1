<?php $profile = getUserProfile(); ?>
<div class="row">
    <div class="col-md-6">
        <form action="/profile/" class="card form" method="post">
            <input type="hidden" name="update_profile" value="1">
            <div class="row" style="padding-bottom: 12px;">
                <div class="col-md-12">
                    <div class="form-control">
                        <label for="full_name">Ф.И.О.</label>
                        <input type="text" name="full_name" id="full_name" placeholder="Фамилия Имя Отчество"
                               value="<?= $profile->full_name ?>" required>
                    </div>
                </div>
            </div>
            <div class="row" style="padding-bottom: 12px;">
                <div class="col-md-12">
                    <div class="form-control">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="Адрес электронной почты"
                               value="<?= $profile->email ?>">
                    </div>
                </div>
            </div>
            <div class="row" style="padding-bottom: 12px;">
                <div class="col-md-12">
                    <div class="form-control">
                        <label for="phone">Телефон</label>
                        <input type="text" name="phone" id="phone" placeholder="Номер телефона"
                               value="<?= $profile->phone ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <button type="submit" class="btn bg-green">Сохранить</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-6">
        <div class="card bg-whitesmoke">
            <h3 class="mb-4">Пользовательские группы</h3>
            <ul>
                <?php foreach (getUserGroups($profile->user_id) as $key => $group): ?>
                    <li><?= $group['name']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>