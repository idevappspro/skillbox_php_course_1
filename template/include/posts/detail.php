<?php $postItem = $item ?>
<div class="row pb-3">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <div class="pb-1"><span class="me-2 fw-bold">От:</span><span><?= $postItem->sender; ?></span></div>
                        <div class="pb-1"><span class="me-2 fw-bold">Получено:</span><span><?= date_create($postItem->created_at)->format('d.m.Y в H:i'); ?></span></div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end align-items-center">
                        <a href="/posts/">Обратно к списку</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?= $postItem->title; ?></h5>
                <p class="card-text"><?= $postItem->content; ?></p>
            </div>
        </div>
        <?php if ($postItem->from_user_id !== $postItem->to_user_id) : ?>
            <div class="mt-2">
                <form action="/posts/" method="POST">
                    <input type="hidden" class="form-control" name="post_id" id="post_id" value="<?= $postItem->id; ?>">
                    <input type="hidden" class="form-control" name="to_user_id" id="to_user_id" value="<?= $postItem->from_user_id; ?>">
                    <input type="hidden" class="form-control" name="section_id" id="section_id" value="<?= $postItem->section_id; ?>">
                    <input type="hidden" class="form-control" name="title" id="title" value="<?= $postItem->title; ?>">
                    <input type="hidden" class="form-control" name="post_reply" id="post_reply" value="<?= true ?>">
                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Ваше сообщение" id="content" name="content" style="height: 60px" required autofocus></textarea>
                        <label for="content">Ваш ответ</label>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Отправить</button>
            </div>
            </form>
        <?php endif; ?>
    </div>
</div>
