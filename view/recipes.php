<?php
$pageTitle = "Recipes — Cook n' Share";
require_once 'header.php';
?>

<div class="recipes-page">

    <div class="recipes-page-header">
        <div>
            <h1>All Recipes</h1>
            <p class="text-muted"><?= count($recipes) ?> recipe<?= count($recipes) !== 1 ? 's' : '' ?> found</p>
        </div>

        <form method="GET" action="<?= SITE_URL ?>/index.php" style="display:flex;gap:.5rem;align-items:center;">
            <input type="hidden" name="page" value="recipes"> <!-- important ! -->

            <?php if ($selectedCat): ?>
                <input type="hidden" name="category" value="<?= htmlspecialchars($selectedCat) ?>">
            <?php endif; ?>

            <input type="search" name="search" class="form-control" placeholder="Search recipes…" value="<?= htmlspecialchars($search) ?>" style="max-width:260px;margin:0">
            <button type="submit" class="btn btn-primary" style="padding:.72rem 1.25rem;font-size:.9rem;">Search</button>

            <?php if ($search || $selectedCat): ?>
                <a href="<?= SITE_URL ?>/index.php?page=recipes" class="btn" style="padding:.72rem 1rem;font-size:.9rem;background:var(--border);color:var(--text-body)">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (!empty($categories)): ?>
        <div class="filter-bar">
            <a href="<?= SITE_URL ?>/index.php?page=recipes<?= $search ? '&search=' . urlencode($search) : '' ?>" class="filter-btn <?= !$selectedCat ? 'active' : '' ?>">All</a>
            <?php foreach ($categories as $cat): ?>
                <a href="<?= SITE_URL ?>/index.php?page=recipes&category=<?= urlencode($cat) ?><?= $search ? '&search=' . urlencode($search) : '' ?>" class="filter-btn <?= $selectedCat === $cat ? 'active' : '' ?>">
                    <?= ($categoryIcons[$cat] ?? '🍽️') . ' ' . htmlspecialchars($cat) ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($recipes)): ?>
        <div class="empty-state">
            <div class="empty-icon">🔍</div>
            <h3>No recipes found</h3>
            <p>
                Try a different search or category, or
                <a href="<?= SITE_URL ?>/index.php?page=add_recipe" style="color:var(--amber)">add the first one</a>!
            </p>
        </div>
    <?php else: ?>
        <div class="recipes-grid">
            <?php foreach ($recipes as $i => $recipe): 
                $icon = $categoryIcons[$recipe['category']] ?? '🍽️';
                $totalTime = ($recipe['prep_time'] ?? 0) + ($recipe['cook_time'] ?? 0);
            ?>
                <article class="recipe-card" style="animation-delay:<?= min($i * .08, .6) ?>s">
                    <div class="card-image">
                        <?= $icon ?>
                        <span class="card-badge"><?= htmlspecialchars($recipe['category']) ?></span>
                    </div>
                    <div class="card-body">
                        <div class="card-category"><?= htmlspecialchars($recipe['category']) ?></div>
                        <h3 class="card-title"><?= htmlspecialchars($recipe['title']) ?></h3>
                        <p class="card-desc"><?= htmlspecialchars($recipe['description']) ?></p>

                        <div class="card-meta">
                            <?php if ($totalTime > 0): ?>
                                <span class="meta-item"><span class="meta-icon">⏱</span> <?= $totalTime ?> min</span>
                            <?php endif; ?>
                            <?php if ($recipe['servings']): ?>
                                <span class="meta-item"><span class="meta-icon">🍽️</span> <?= $recipe['servings'] ?> servings</span>
                            <?php endif; ?>
                            <span class="meta-item difficulty-<?= $recipe['difficulty'] ?>"><?= ucfirst($recipe['difficulty']) ?></span>
                        </div>

                        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:1rem">
                            <span style="font-size:.8rem;color:var(--text-muted)">by <?= htmlspecialchars($recipe['username']) ?></span>
                            <a href="<?= SITE_URL ?>/index.php?page=recipe&id=<?= $recipe['id'] ?>" class="card-link">View →</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php require_once 'footer.php'; ?>