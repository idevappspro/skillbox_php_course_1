<?php

// Get configuration
require_once __DIR__ . "/config.php";

// Routes --> Actions
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
    $errors = [];
    $uploaded_files = [];
    $count_of_files = count($payload['name']);

    if ($count_of_files < UPLOAD_MIN_COUNT) {
        $errors[] = "Не выбран файл для загрузки. Выберите хотя бы один файл.";
    } elseif ($count_of_files > UPLOAD_MAX_COUNT) {
        $errors[] = "Превышено допустимое число файлов для загрузки. Выберите не более " . UPLOAD_MAX_COUNT . '.';
    }

    for ($i = 0; $i < $count_of_files; $i++) {
        // Validate file's presence
        if ($_FILES["file"]["error"][$i] > 0) {
            $errors[] = "Error: " . $_FILES["file"]["error"][$i];
            continue;
        }
        // Validate file's extension
        $tmp_file = $_FILES['file']['tmp_name'][$i];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $tmp_file);
        finfo_close($finfo);
        if (!in_array($mime_type, UPLOAD_MIME_TYPES)) {
            $errors[] = 'Неверное расширение файла : ' . $_FILES["file"]["name"][$i];
            continue;
        }
        // Validate file size
        if ($_FILES["file"]["size"][$i] > UPLOAD_MAX_SIZE) {
            $errors[] = 'Недопустимый размер файла (не более 2 Мб) : ' . $_FILES["file"]["name"][$i];
            continue;
        }
        // Validate file duplicate by name
        if (file_exists(UPLOAD_PATH . $_FILES["file"]["name"][$i])) {
            $errors[] = 'Файл с таким именем уже существует : ' . $_FILES["file"]["name"][$i];
            continue;
        }
        // Upload validated file'
        $path = $_FILES['file']['name'][$i];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $new_file_name = "IMG_" . substr(sha1($_FILES["file"]["name"][$i]), 0, 5);
        $file_name = strtoupper(preg_replace("/[^a-zA-Z0-9.]/", "_", $new_file_name)) . '.' . $ext;
        move_uploaded_file($_FILES["file"]["tmp_name"][$i], UPLOAD_PATH . $file_name);
        $uploaded_files[] = "&laquo;" . $file_name . "&raquo;";
    }

    if (count($errors) > 0) {
        $response = [
            "errors" => $errors,
            "success" => false
        ];
    } else {
        if (count($uploaded_files) > 1) {
            $message = "Файлы успешно загружены: " . implode(', ', $uploaded_files);
        } else {
            $message = "Файл загружен: &laquo;" . $uploaded_files[0] . "&raquo;";
        }
        $response = [
            "success" => true,
            "message" => $message
        ];
    }
    echo json_encode($response, JSON_PRETTY_PRINT);
}

function delete($files): void
{
    $errors = [];
    $deleted_files = [];
    $imageList = array_slice(scandir(UPLOAD_PATH), 2);

    foreach ($files as $file) {
        //Validate file is in upload folder
        $filePath = realpath(UPLOAD_PATH . $file);
        if (in_array($file, $imageList) && (strpos(realpath($filePath), realpath(UPLOAD_PATH)) === 0)) {
            unlink($filePath);
            $deleted_files[] = "&laquo;" . $file . "&raquo;";
        } else {
            $errors[] = "Попытка удаления несуществующего файла: " . $file;
        }
    }

    if (count($errors)) {
        $response = [
            'errors' => $errors,
            "success" => false
        ];
    } else {
        if (count($deleted_files) > 1) {
            $message = "Файлы успешно удалены: " . implode(", ", $deleted_files);
        } else {
            $message = "Файл успешно удален: " . $deleted_files[0];
        }
        $response = [
            "success" => true,
            'message' => $message
        ];
    }
    echo json_encode($response, JSON_PRETTY_PRINT);
}

function index(): void
{
    $collection = array_slice(scandir(UPLOAD_PATH), 2);
    $output = [];

    foreach ($collection as $file) {
        //Basename
        $file_path = UPLOAD_PATH . $file;
        $ext = pathinfo($file_path, PATHINFO_EXTENSION);
        $baseName = basename($file_path, "." . $ext);

        //Creation date
        $date_created = date("d.m.Y H:i", fileatime($file_path));

        //Collect meta-data to array
        $output[] = [
            "name" => $baseName . '.' . $ext,
            'baseName' => $baseName,
            'url' => '/upload/' . $file,
            'size' => getFileSize(filesize($file_path)),
            'created_at' => $date_created
        ];
    }

    // Return meta-data as array of objects to JSO
    echo json_encode([
        "images" => $output
    ], JSON_UNESCAPED_SLASHES, JSON_PRETTY_PRINT);
}

// Helper Functions
function getFileSize($filesize): string
{
    $measure = [' b', ' Kb', ' Mb'];
    if ($filesize < 10000) {
        $filesize = $filesize . $measure[0];
    } elseif ($filesize > 10000 && $filesize < 1000000) {
        $filesize = round($filesize / 1024) . $measure[1];
    } else {
        $filesize = round($filesize / 1024 / 1024, 1) . $measure[2];
    }
    return $filesize;
}