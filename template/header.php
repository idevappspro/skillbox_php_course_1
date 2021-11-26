<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css" />
    <link href="/vendor/fontawesome/css/fontawesome.min.css" rel="stylesheet">
    <link href="/vendor/fontawesome/css/brands.min.css" rel="stylesheet">
    <link href="/vendor/fontawesome/css/solid.css" rel="stylesheet">
    <link href="/styles.css" rel="stylesheet">
    <title>Project - <?= showPageAttr('title'); ?></title>
</head>

<body>
    <nav class="navbar navbar-expand-lg pt-0 pb-0">
        <div class="container navbar-dark bg-dark py-3">
            <a class="navbar-brand" href="/"><img src="/i/logo.png" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <?php showMenu(); ?>
        </div>
    </nav>
