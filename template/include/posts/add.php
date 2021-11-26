<?php
$recipients = getPostRecipients();
$post_sections = getPostSections();
?>
<!--New Post form-->
<div class="row mb-3">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <a href="/posts/">Перейти к списку сообщений</a>
            </div>
            <div class="card-body">
                <form action="/posts/add/" method="POST">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="title" class="form-label">Тема</label>
                                <input type="text" class="form-control" name="title" id="title" aria-describedby="title-helpId" placeholder="Введите тему сообщения" required autofocus>
                                <!-- <small id="title-helpId" class="form-text text-muted">Help text</small> -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="to_user_id" class="form-label">Получатель</label>
                                <select class="form-control" name="to_user_id" id="to_user_id" required>
                                    <option selected>Выбрать</option>
                                    <?php foreach ($recipients as $person) : ?>
                                        <option value="<?= $person['user_id'] ?>"><?= $person['full_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="section_id" class="form-label">Раздел</label>
                                <select class="form-select" name="section_id" id="section_id" required>
                                    <option selected>Выбрать</option>
                                    <?php foreach ($post_sections as $section) : ?>
                                        <option value="<?= $section['id'] ?>"><?= $section['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" name="post_reply" id="post_submitted" value="<?= true ?>">
                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Ваше сообщение" id="content" name="content" style="height: 60px" required></textarea>
                        <label for="content">Текст сообщения</label>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Отправить</button>
                </form>
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
</div>
