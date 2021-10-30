let all_images = [];
let selected_images = [];
let btn_delete = $('#btn-delete');
let errors = [];
let alertBox = $("#alert");

// Functions
function deleteFiles() {
    let payload = new FormData();
    payload.append("action", "delete");
    selected_images.forEach((value) => {
        payload.append("payload[]", value);
    });

    const settings = {
        "url": "/api/gallery.php",
        "method": "POST",
        "contentType": false,
        "processData": false,
        "dataType": "json",
        "data": payload
    };
    $.ajax(settings).done(function (response) {
        initialize();
        if (response.success) {
            showMessage(response.message);
            disabledButton();
        } else {

            showMessage(response.message);
            disabledButton();
        }
        getImages();
    });
}

function disabledButton() {
    btn_delete.attr('disabled', 'disabled');
    btn_delete.addClass('disabled');
}

function enableButton() {
    btn_delete.removeAttr('disabled', 'disabled');
    btn_delete.removeClass('disabled');
}

function getImages() {
    let payload = new FormData();
    payload.append("action", "index");
    const settings = {
        "url": "/api/gallery.php",
        "method": "POST",
        "contentType": false,
        "processData": false,
        "dataType": "json",
        "data": payload
    };

    $.ajax(settings).done(function (response) {
        all_images = response.images
        renderGallery(all_images);
    });
}

function renderGallery(payload) {
    if (payload.length === 0) {
        $('#image-grid').hide();
        $("#gallery-cp").hide();
        $('#gallery-empty').show();
    } else {
        $('#image-grid').show();
        $('#gallery-empty').hide();
        let grid = $("#image-grid");
        let content = $('<div id="image-grid" class="image-grid"></div>');
        $(payload).each((i) => {
            content.append(
                '<div class="image-card" id="image-card-' + i + '">\n' +
                '    <a href="' + payload[i]['url'] + '" data-fancybox="gallery">\n' +
                '        <div\n' +
                '            style="background: url(' + payload[i]['url'] + ') center center no-repeat; background-size: cover; height: 140px; width: auto;"></div>\n' +
                '    </a>\n' +
                '    <div\n' +
                '        class="image-created_at">' + payload[i]['created_at'] + '</div>\n' +
                '    <div class="image-meta">\n' +
                '        <div class="image-title">' + payload[i]['baseName'] + '</div>\n' +
                '        <div class="image-size">' + payload[i]['size'] + '</div>\n' +
                '        <div class="image-control">\n' +
                '            <label for="image-selector-' + i + '">\n' +
                '                <input type="checkbox" class="checkbox" id="image-selector-' + i + '" name="images[]"\n' +
                '                       style="margin-right: 6px;" value="' + payload[i]['name'] + '" onclick="toggleCheckbox(' + i + ')">Удалить\n' +
                '            </label>\n' +
                '        </div>\n' +
                '    </div>\n' +
                '</div>'
            )
        })
        grid.replaceWith(content);
        $("#gallery-cp").show();
    }
}

function toggleCheckbox(id) {
    let checkbox = $("#image-selector-" + id);
    if (checkbox.is(":checked")) {
        selected_images.push(checkbox.val());
        enableButton();
    } else {
        selected_images.splice(selected_images.indexOf(checkbox.val()), 1);
        if (selected_images.length < 1) {
            disabledButton();
        }
        if (selected_images.length < all_images.length) {
            $("#checkAll").prop('checked', false)
        }
    }
}

function toggleCheckAll() {
    if ($("#checkAll").is(":checked")) {
        $('input:checkbox').prop('checked', true);
        all_images.forEach((value) => {
            selected_images.push(value['name']);
        });
    } else {
        $('input:checkbox').prop('checked', false);
        all_images.forEach((value) => {
            selected_images.splice(selected_images.indexOf(value['name']), 1);
        });
    }
    if (selected_images.length > 0) {
        enableButton();
    } else {
        disabledButton();
    }
}


function upload(payload) {
    initialize();
    const settings = {
        "url": "/api/gallery.php",
        "method": "POST",
        "contentType": false,
        "processData": false,
        "dataType": "json",
        "data": payload
    };

    $.ajax(settings).done(function (response) {
        if (response.success) {
            getImages();
            showMessage(response.message)
        } else {
            getImages();
            showErrors(response.errors);
        }
    });
}

function initialize() {
    errors = [];
    selected_images = [];
    alertBox.addClass("hidden");
    alertBox.html("");
}

function resetForm() {
    $('form[name="frmUpload"]')[0].reset();
}

function showErrors(errors) {
    alertBox.removeClass("success");
    alertBox.addClass("error");
    alertBox.removeClass("hidden");
    errors.forEach((error) => {
        alertBox.append('<div style="padding: 4px 0">' + error + '</div>');
    })
    resetForm();
}

function showMessage(payload) {
    alertBox.removeClass("error");
    alertBox.addClass("success");
    alertBox.removeClass("hidden");
    payload.forEach((message) => {
        alertBox.append('<div style="padding: 4px 0">' + message + '</div>');
    })
    resetForm();
}

function validateFiles() {
    initialize();
    let files = $('.form :input[type=file]').get(0).files;
    let extensions = ["jpg", "jpeg", "png"];
    // Validate file count
    if (files.length === 0 || files.length > 5) {
        errors.push("Недопустимое число загружаемых файлов (максимум 5). Выбрано: " + files.length);
        showErrors(errors);
        resetForm();
    } else {
        for (let i = 0; i < files.length; i++) {
            // Validate file size
            if (files[i].size > 2097152) {
                errors.push("Недопустимый размер файла (не более 2 Мб) : " + files[i]['name']);
                showErrors(errors);
                resetForm();
                break;
            }
            // Validate extension
            let ext = files[i].name.split(".");
            ext = ext[ext.length - 1].toLowerCase();
            if (extensions.lastIndexOf(ext) === -1) {
                errors.push("Недопустимое расширение файла (только jpg, jpeg, png) : " + files[i]['name']);
                showErrors(errors);
                resetForm();
                break;
            }
            // Validate if file name already exists
            all_images.forEach((image) => {
                if (files[i]['name'] === image['name']) {
                    errors.push("Файл с таким именем уже существует : " + files[i]['name']);
                    showErrors(errors);
                    resetForm();
                }
            });
        }
    }
}

// Script scenario
$(function () {
    // Get current URI_PATH
    let current_uri_path = location.pathname;
    // Gallery
    if (current_uri_path === "/gallery/") {
        // Initial Load
        getImages();
    }
    if (selected_images.length) {
        enableButton();
    } else {
        disabledButton();
    }
    $('#file').bind('change', function () {
        validateFiles();
    });
    $('form[name="frmUpload"]').on("submit", function (e) {
        e.preventDefault();
        // Validate file input
        upload(new FormData(this));

    });
});



