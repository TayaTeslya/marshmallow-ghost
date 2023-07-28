<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title><?=htmlspecialchars($pageTitle)?></title>
</head>
<body>
    <header>
        <div class="logo">
            <a href="/">
                <div class="logo-ghost">
                    <img src="/assets/pictures/logotype.png" alt="Логотип">
                </div>
                <div class="logo-text">
                    <img src="/assets/pictures/marshmallowghost.png" alt="MARSHMELLOW GHOST">
                </div>
            </a>
        </div>
        <div class="nav-bar">
            <a href="<?=$href?>"><?=htmlspecialchars($hrefName)?></a>
        </div>
    </header>
        <main>
