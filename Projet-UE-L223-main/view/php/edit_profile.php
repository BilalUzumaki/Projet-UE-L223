<?php
$pageTitle = "Edit Profile";
require_once 'header.php';
$user = $_SESSION['user'];
?>

<div class="container">

    <div class="form-card">
        <h2>Edit Profile</h2>

        <?php if (!empty($success)): ?>
            <p class="success">Profile updated ✅</p>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <label>Profile picture</label>
            <input type="file" name="avatar">

            <label>Name *</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

            <label>Email *</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <label>Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">

            <button class="btn btn-primary">Save</button>
        </form>

        <a href="index.php?page=profile" class="btn">← Back</a>
    </div>

</div>

<?php require_once 'footer.php'; ?>