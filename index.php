<?php
include_once ('src/core.php');
if (!isAuthorized()) {
    header('Location: /authorization/');
}

echo 'главная страница';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>