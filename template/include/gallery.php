<?php $gallery = array_slice(scandir($_SERVER['DOCUMENT_ROOT'] . '/upload/'), 2); ?>
<div class="card hidden" id="alert"></div>
<div class="card form">
    <div class="form-title">Загрузить файлы в галерею</div>
    <form method="post" id="frmUpload" name="frmUpload" enctype="multipart/form-data">
        <div class="form-control">
            <label for="files">
                <input type="file" id="file" name="file[]" multiple="multiple" required>
            </label>
            <input type="hidden" name="action" value="upload">
        </div>
        <div class="form-control">
            <input type="submit" id="btn-submit-upload" value="Загрузить">
        </div>
    </form>
</div>
<div id="gallery">
    <div class="image-grid" id="image-grid"></div>
    <div id="gallery-empty" class="hidden">Галерея пуста. Загрузите изображения воспользовавшись формой загрузки.</div>
    <div style="margin-top: 5px; padding: 8px;" id="gallery-cp" class="hidden">
        <label for="all_checkbox" style="margin-right: 10px;">
            <input type="checkbox" id="checkAll" name="delete_all" onclick="toggleCheckAll()">Все</label>
        <button id="btn-delete" class="btn disabled" type="button" onclick="deleteFiles()"
                style="padding: 6px 10px; border-radius: 3px; background-color: red; border: 1px red; color: white; cursor: pointer;"
                disabled="disabled">
            Удалить
        </button>
    </div>
</div>