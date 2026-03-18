<?php
$pageTitle = "Recipe — Cook n' Share";
require_once 'header.php';

$totalFavs = Recipe::countFavorites($recipe['id'] ?? 0);

$userFavored = false;
if(isset($_SESSION['user'])) {
    $stmt = Database::getInstance()->prepare("
        SELECT 1 FROM recipe_favorites WHERE user_id=? AND recipe_id=?
    ");
    $stmt->execute([$_SESSION['user']['id'], $recipe['id'] ?? 0]);
    $userFavored = (bool)$stmt->fetchColumn();
}

$isOwner = isset($_SESSION['user']) && $_SESSION['user']['id'] === $recipe['user_id'];
?>

<div class="container">

    <div class="recipe-card">

        <!-- HEADER -->
        <div class="card-header">
            <div class="card-avatar"></div>
            <div>
                <div class="card-user"><?= htmlspecialchars($recipe['username']) ?></div>
                <div style="font-size:12px;color:gray;">
                    <?= $icon ?> <?= htmlspecialchars($recipe['category']) ?>
                </div>
            </div>
        </div>

        <!-- TITLE -->
        <div class="card-title" style="margin-top:10px;">
            <?= htmlspecialchars($recipe['title']) ?>
        </div>

        <!-- DESCRIPTION -->
        <?php if ($recipe['description']): ?>
            <div class="card-desc">
                <?= htmlspecialchars($recipe['description']) ?>
            </div>
        <?php endif; ?>

        <!-- META -->
        <div class="card-meta">
            <span>⏱ <?= $totalTime ?> min</span>
            <span><?= ucfirst($recipe['difficulty']) ?></span>
            <?php if ($recipe['servings']): ?>
                <span>🍽 <?= $recipe['servings'] ?></span>
            <?php endif; ?>
        </div>

        <!-- ❤️ FAVORITES -->
        <div class="card-actions">
            <a href="<?= SITE_URL ?>/index.php?page=toggle_favorite&id=<?= $recipe['id'] ?>"
               style="color:<?= $userFavored ? 'red' : '#555' ?>; text-decoration:none;">
                ❤️ <?= $totalFavs ?>
            </a>
        </div>

        <!-- EDIT / DELETE -->
        <?php if($isOwner): ?>
            <div class="card-actions">
                <a href="index.php?page=edit_recipe&id=<?= $recipe['id'] ?>">✏️ Edit</a>
                <a href="index.php?page=delete_recipe&id=<?= $recipe['id'] ?>" onclick="return confirm('Delete?')">🗑 Delete</a>
            </div>
        <?php endif; ?>

    </div>

    <!-- INGREDIENTS -->
    <div class="recipe-card">
        <h3>Ingredients</h3>

        <?php if ($ingredients): ?>
            <ul class="ingredient-list">
                <?php foreach ($ingredients as $item): ?>
                    <li><?= htmlspecialchars($item) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p style="color:gray;">No ingredients</p>
        <?php endif; ?>
    </div>

    <!-- INSTRUCTIONS -->
    <div class="recipe-card">
        <h3>Instructions</h3>

        <?php if ($instructions): ?>
            <ol class="instruction-list">
                <?php foreach ($instructions as $step): ?>
                    <li><?= htmlspecialchars($step) ?></li>
                <?php endforeach; ?>
            </ol>
        <?php else: ?>
            <p style="color:gray;">No instructions</p>
        <?php endif; ?>
    </div>

    <!-- BACK -->
    <div style="margin-top:20px;">
        <a href="<?= SITE_URL ?>/index.php?page=recipes" class="btn">
            ← Back
        </a>
    </div>

</div>

<?php require_once 'footer.php'; ?>