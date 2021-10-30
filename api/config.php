<?php

const UPLOAD_MAX_SIZE = 2097152;
const UPLOAD_MIME_TYPES = [
    "image/png",
    "image/jpeg",
];
const UPLOAD_MIN_COUNT = 1;
const UPLOAD_MAX_COUNT = 5;

define('UPLOAD_PATH', $_SERVER['DOCUMENT_ROOT'] . '/upload/');

