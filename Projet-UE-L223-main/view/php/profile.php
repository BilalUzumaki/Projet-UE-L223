<?php
$pageTitle = "Profile";
require_once 'header.php';
$user = $_SESSION['user'];
?>

<div class="container">

    <div class="profile-header-card">

        <div class="avatar">
            <?php if (!empty($user['avatar'])): ?>
                <img src="<?= SITE_URL . $user['avatar'] ?>" class="avatar-img">
            <?php else: ?>
                👩‍🍳
            <?php endif; ?>
        </div>

        <h2><?= htmlspecialchars($user['username']) ?></h2>
        <p><?= htmlspecialchars($user['email']) ?></p>

    </div>

    <!-- MENU -->
    <div class="profile-menu-vertical">

        <a href="index.php?page=profile_recipes&type=favorites" class="profile-item">
            ❤️ Favorites
        </a>

        <a href="index.php?page=profile_recipes&type=my_recipes" class="profile-item">
            📖 My recipes
        </a>

        <a href="index.php?page=profile_recipes&type=history" class="profile-item">
            🕒 History
        </a>

    </div>

    <!-- ACTIONS -->
    <div class="profile-actions">
        <a href="index.php?page=edit_profile" class="btn btn-primary">Edit</a>
        <a href="index.php?page=logout" class="btn btn-danger">Log out</a>
    </div>

</div>

<?php require_once 'footer.php'; ?>