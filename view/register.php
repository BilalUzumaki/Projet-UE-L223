<?php
$pageTitle = "Register — Cook n' Share";
require_once 'header.php';
?>

<div class="form-page">
    <div class="form-card">
        <h2>Create account</h2>
        <p>Join Cook n' Share and start sharing your recipes.</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                    placeholder="chefmario">
            </div>

            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" required
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    placeholder="you@example.com">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Choose a strong password">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Create account →</button>
        </form>

        <p class="form-footer">
            Already have an account?<a href="<?= SITE_URL ?>/index.php?page=login">Sign in</a>
        </p>
    </div>
</div>

<?php require_once 'footer.php'; ?>