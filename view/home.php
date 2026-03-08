<?php
$pageTitle = "Cook n' Share — Discover & Share Recipes";

$recipes = Recipe::latest(5);

$categoryIcons = [
    'Pasta'      => '🍝', 'Vegetarian' => '🥗', 'Dessert'  => '🍰',
    'Soup'       => '🍲', 'Seafood'    => '🦞', 'Meat'     => '🥩',
    'Breakfast'  => '🥞', 'Salad'      => '🥙',
];

include 'header.php';
?>

<!-- Hero -->
<section class="hero">
    <div class="hero-decoration">🍽️</div>
    <span class="hero-badge">✨ Community recipes</span>
    <h1>Discover & Share<br><em>Delicious Recipes</em></h1>
    <p>Join our community of passionate home cooks. Find inspiration, share your creations, and explore dishes from around the world.</p>
    <div class="hero-actions">
        <a href="<?= SITE_URL ?>/index.php?page=recipes" class="btn btn-primary">Browse Recipes →</a>
        <a href="<?= SITE_URL ?>/index.php?page=add_recipe" class="btn btn-ghost" style="color:#fff;border-color:rgba(255,255,255,.3);">Share a Recipe</a>
    </div>
</section>

<!-- Latest recipes -->
<div class="section-heading">
    <h2>Latest Recipes</h2>
    <a href="<?= SITE_URL ?>/index.php?page=recipes">View all →</a>
</div>

<?php if (empty($recipes)): ?>
    <div class="empty-state">
        <span class="empty-icon">🍳</span>
        <h3>No recipes yet</h3>
        <p>Be the first to <a href="<?= SITE_URL ?>/index.php?page=add_recipe">share a recipe</a>!</p>
    </div>
<?php else: ?>
    <div class="latest-recipes">
        <?php foreach ($recipes as $recipe):
            $icon = $categoryIcons[$recipe['category']] ?? '🍽️';
            $totalTime = ($recipe['prep_time'] ?? 0) + ($recipe['cook_time'] ?? 0);
        ?>
        <a href="<?= SITE_URL ?>/index.php?page=recipe&id=<?= $recipe['id'] ?>" class="index-recipe-card">
            <div class="index-card-icon"><?= $icon ?></div>
            <div class="index-card-info">
                <div class="index-card-title"><?= htmlspecialchars($recipe['title']) ?></div>
                <div class="index-card-meta">
                    <?= htmlspecialchars($recipe['category']) ?>
                    <?php if ($totalTime > 0): ?> · <?= $totalTime ?> min<?php endif; ?>
                    · by <?= htmlspecialchars($recipe['username']) ?>
                </div>
            </div>
            <span class="index-card-arrow">→</span>
        </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>
