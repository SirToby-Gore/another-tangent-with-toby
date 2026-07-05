<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($showName) ?> | <?= htmlspecialchars($stationName) ?></title>

    <!-- Google Fonts for Modern Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&family=Space+Grotesk:wght@500;700&display=swap"
        rel="stylesheet">

    <link rel="shortcut icon" href="favicon.jpg" type="image/x-icon">

    <!-- Linked Stylesheet (Compiled from SCSS) -->
    <!-- <link rel="stylesheet" href="css/styles.css"> -->
    <style>
        <?= str_replace("\n", '', file_get_contents(__DIR__ . '/../css/styles.css')) ?>
    </style>
</head>

<body>

    <!-- Navigation Header -->
    <header>
        <div class="container nav-wrapper">
            <a class="logo" href="#">
                <strong class="orange">Another Tangent</strong> <span>with toby</span>
            </a>
            <nav class="nav-links">
                <a href="#segments">Segments</a>
                <a href="#catch-up">Catch Up</a>
                <a href="#hub">Studio Hub</a>
                <a href="<?= $stationUrl ?>" class="station-badge"
                    target="_blank"><?= htmlspecialchars($stationName) ?></a>
            </nav>
        </div>
    </header>