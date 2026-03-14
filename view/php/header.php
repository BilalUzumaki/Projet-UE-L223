<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : "Cook n' Share" ?></title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <link rel="stylesheet" href="<?= SITE_URL ?>/view/assets/css/style.css">
    </head>

    <body>

        <nav class="navbar">

            <a href="<?= SITE_URL ?>/index.php" class="logo">
            🍳 Cook n' Share
            </a>

            <div class="nav-links">
                <a href="<?= SITE_URL ?>/index.php">Home</a>
                <a href="<?= SITE_URL ?>/index.php?page=recipes">Browse</a>
                <a href="<?= SITE_URL ?>/index.php?page=add_recipe">Add Recipe</a>

                <?php if (isset($_SESSION['user'])): ?>
                    <a href="<?= SITE_URL ?>/index.php?page=logout">Log out</a>
                <?php else: ?>
                    <a href="<?= SITE_URL ?>/index.php?page=login">Login</a>
                    <a href="<?= SITE_URL ?>/index.php?page=register" class="btn-nav">Sign Up</a>
                <?php endif; ?>
            </div>

        </nav>

        <div class="container">