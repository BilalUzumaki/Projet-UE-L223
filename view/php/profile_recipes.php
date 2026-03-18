<?php
$pageTitle = $title;
require_once 'header.php';
?>

<div class="container">

    <h2><?= $title ?></h2>

    <div class="recipes-list">
        <?php if (!empty($recipes)): ?>
            <?php foreach ($recipes as $r): ?>
                <div class="recipe-card">
                    <h3><?= htmlspecialchars($r['title']) ?></h3>

                    <div class="card-actions">
                        <a href="index.php?page=recipe&id=<?= $r['id'] ?>">View</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center;color:gray;">No recipes found</p>
        <?php endif; ?>
    </div>

    <!-- BACK BUTTON -->
    <div style="margin-top:30px;">
        <a href="index.php?page=profile" class="btn">
            ← Back to profile
        </a>
    </div>

</div>

<?php require_once 'footer.php'; ?>