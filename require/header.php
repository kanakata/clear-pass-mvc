<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Welcome to school clearance site. Clearance simplified and tailored to meet your needs">
    <link rel="shortcut icon" href="./assets/school.png" type="image/x-icon">
    <title><?= $page_title . "-pegpem" ?></title>
    <link rel="stylesheet" href="./css/style.css" type="text/css">
</head>
<body style="overflow: <?= isset($_SESSION['login_success']) ? "hidden" : "none" ?>;">

