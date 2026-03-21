<?php
$pageTitle = "Cook n' Share";

$popularRecipes = Recipe::popular(3);
$latestRecipes  = Recipe::latest(10);

include 'header.php';
?>

<!-- HERO -->
<section class="hero" style="text-align:center;padding:40px 20px;">
    <h1>🍳 Cook n' Share</h1>

    <h2>Discover & Share<br><em>Delicious Recipes</em></h2>

    <p style="max-width:500px;margin:15px auto;">
        Join our community of passionate home cooks. Find inspiration,
        share your creations, and explore dishes from around the world.
    </p>

    <div style="margin-top:20px;display:flex;gap:10px;justify-content:center;">
        <a href="<?= SITE_URL ?>/index.php?page=recipes" class="btn btn-primary">
            Browse Recipes →
        </a>

        <a href="<?= SITE_URL ?>/index.php?page=add_recipe" class="btn">
            Share a Recipe
        </a>
    </div>
</section>

<!-- 🔥 POPULAR -->
<div class="section-heading">
    <h2>🔥 Most Popular</h2>
</div>

<?php if (!empty($popularRecipes)): ?>
    <div class="recipes-list">
        <?php foreach ($popularRecipes as $recipe): ?>
            <?php include 'components/recipe_card.php'; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- 🕒 LATEST -->
<div class="section-heading">
    <h2>🕒 Latest Recipes</h2>
</div>

<?php if (!empty($latestRecipes)): ?>
    <div class="recipes-list">
        <?php foreach ($latestRecipes as $recipe): ?>
            <?php include 'components/recipe_card.php'; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>