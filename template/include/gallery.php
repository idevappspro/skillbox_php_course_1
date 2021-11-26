<?php $gallery = array_slice(scandir($_SERVER['DOCUMENT_ROOT'] . '/upload/'), 2); ?>
<div class="hidden" id="alert" role="alert"></div>
<div class="row">
    <div class="col-md-6">
        <div class="pb-3">
            <form method="post" id="frmUpload" name="frmUpload" enctype="multipart/form-data">
                <div class="input-group">
                    <input type="file" class="form-control" id="file" name="file[]" aria-describedby="btn-submit-upload" aria-label="Upload" multiple="multiple" required>
                    <input type="hidden" name="action" value="upload">
                    <button class="btn btn-primary" type="submit" id="btn-submit-upload">Загрузить</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="gallery" class="row bg-secondary py-3">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div id="image-grid" class="row row-cols-5 g-3"></div>
        <div id="gallery-empty" class="d-none hidden">Галерея пуста. Загрузите изображения воспользовавшись формой загрузки.</div>
    </div>
</div>
<div class="row" id="gallery-cp">
    <div class="col-md-12">
        <div class="py-3 px-4 align-items-center">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="checkAll" name="delete_all" onclick="toggleCheckAll()">
                <label class="form-check-label" for="all_checkbox">
                    Удалить все
                </label>
            </div>
            <button id="btn-delete" class="btn btn-danger disabled" type="button" onclick="deleteFiles()" style="padding: 6px 10px; border-radius: 3px; background-color: red; border: 1px red; color: white; cursor: pointer;" disabled="disabled">
                Удалить
            </button>
        </div>
    </div>
</div>
