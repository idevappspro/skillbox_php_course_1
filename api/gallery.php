<?php

// Action routing
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case "index":
            index();
            break;
        case "delete":
            delete($_POST['payload']);
            break;
        case "upload":
            upload($_FILES['file']);
            break;
        default:
            http_response_code(404);
            echo json_encode([
                "error" => "Invalid request"
            ]);
    }
}

// Actions

function upload($payload): void
{
    $valid_extensions = ["jpg", "jpeg", "png"];
    $errors = [];
    $message = [];

    $count_of_files = count($payload['name']);

    if ($count_of_files === 0) {
        $errors[] = "Не выбран файл для загрузки. Выберите хотя бы один файл.";
    } elseif ($count_of_files > 5) {
        $errors[] = "Превышено допустимое число файлов для загрузки. Выберите не более 5.";
    } else {
        for ($i = 0; $i < $count_of_files; $i++) {
            // If file is not present
            if ($_FILES["file"]["error"][$i] > 0) {
                $errors[] = "Error: " . $_FILES["file"]["error"][$i];
            } else { // If file is present
                // Validate file extension
                $path = $_FILES['file']['name'][$i];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                if (in_array($ext, $valid_extensions)) {
                    // if extension is valid
                    if ($_FILES["file"]["size"][$i] > 2097152) {
                        $errors[] = 'Недопустимый размер файла (не более 2 Мб) : ' . $_FILES["file"]["name"][$i];
                    } else {
                        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/upload/' . $_FILES["file"]["name"][$i])) {
                            $errors[] = 'Файл с таким именем уже существует : ' . $_FILES["file"]["name"][$i];
                        } else { //
                            $new_file_name = "IMG_" . substr(sha1($_FILES["file"]["name"][$i]), 0, 5);
                            $file_name = preg_replace("/[^a-zA-Z0-9.]/", "_", $new_file_name . '.' . $ext);
                            move_uploaded_file($_FILES["file"]["tmp_name"][$i], $_SERVER['DOCUMENT_ROOT'] . '/upload/' . $file_name);
                            $message[] = 'Файл успешно загружен : ' . $file_name;
                        }
                    }
                } else {
                    $errors[] = 'Неверное расширение файла : ' . $_FILES["file"]["name"][$i];
                }
            }
        }
    }

    if (count($errors) > 0) {
        $response = [
            "errors" => $errors,
            "success" => false
        ];
    } else {
        $response = [
            "success" => true,
            "message" => $message
        ];
    }

    http_response_code(200);
    echo json_encode($response, JSON_PRETTY_PRINT);
}

function delete($files)
{
    $errors = [];
    $message = [];

    foreach ($files as $file) {
        // if file exists
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/upload/" . $file)) {
            // delete file
            if (unlink($_SERVER['DOCUMENT_ROOT'] . "/upload/" . $file)) {
                $message[] = "Файл " . $file . " удален.";
            } else {
                $errors[] = "Во время удаления файла " . $file . " возникла проблема.";
            }
        }
    }

    if (count($errors)) {
        $response = [
            'errors' => $errors,
            "success" => false
        ];
    } else {
        $response = [
            "success" => true,
            'message' => $message
        ];
    }

    http_response_code(200);
    echo json_encode($response, JSON_PRETTY_PRINT);
}

function index(): void
{
    $collection = array_slice(scandir($_SERVER['DOCUMENT_ROOT'] . '/upload/'), 2);
    $output = [];

    foreach ($collection as $file) {
        //Basename
        $file_path = $_SERVER['DOCUMENT_ROOT'] . "/upload/" . $file;
        $ext = pathinfo($file_path, PATHINFO_EXTENSION);
        $baseName = null;
        if ($file_path) {
            $baseName = basename($file_path, "." . $ext);
        }
        //Size
        $filesize = filesize($file_path);
        $measure = [
            ' b',
            ' Kb',
            ' Mb'
        ];
        if ($filesize < 10000) {
            $filesize = $filesize . $measure[0];
        } elseif ($filesize > 10000 && $filesize < 1000000) {
            $filesize = round($filesize / 1024) . $measure[1];
        } elseif ($filesize > 1000000) {
            $filesize = round($filesize / 1024 / 1024, 1) . $measure[2];
        }
        //Creation date
        $date_created = date("d.m.Y H:i", fileatime($file_path));

        //Collect meta-data to array
        $output[] = [
            "name" => $baseName . '.' . $ext,
            'baseName' => $baseName,
            'url' => '//' . $_SERVER['HTTP_HOST'] . '/upload/' . $file,
            'size' => $filesize,
            'created_at' => $date_created
        ];
    }
    //return meta-data as array of objects ib JSON
    http_response_code(200);
    echo json_encode([
        "images" => $output
    ], JSON_UNESCAPED_SLASHES, JSON_PRETTY_PRINT);
}