<?php
$pageTitle = $title;
require_once 'header.php';
?>

<div class="container">

    <h2><?= $title ?></h2>

    <div class="recipes-list">
        <?php if (!empty($recipes)): ?>
            <?php foreach ($recipes as $r): 
                // Choisir une icône si pas d'image
                $categoryIcons = [
                    'Pasta'      => '🍝', 'Vegetarian' => '🥗', 'Dessert' => '🍰',
                    'Soup'       => '🍲', 'Seafood' => '🦞', 'Meat' => '🥩',
                    'Breakfast'  => '🥞', 'Salad' => '🥙',
                ];
                $icon = $categoryIcons[$r['category']] ?? '🍽️';

                // Nombre de likes (déjà récupéré si tu as utilisé Recipe::popular ou ajusté la requête)
                $likes = $r['likes'] ?? Recipe::countFavorites($r['id']);
            ?>
                <article class="recipe-card" style="display:flex;align-items:center;gap:15px;margin-bottom:12px;">

                    <!-- IMAGE -->
                    <div style="width:80px;height:80px;flex-shrink:0;">
                        <?php if (!empty($r['image'])): ?>
                            <img src="<?= SITE_URL . '/' . $r['image'] ?>" 
                                 style="width:100%;height:100%;object-fit:cover;border-radius:12px;">
                        <?php else: ?>
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#eee;border-radius:12px;font-size:30px;">
                                <?= $icon ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- CONTENT -->
                    <div style="flex:1;">
                        <div style="font-weight:600;font-size:15px;">
                            <?= htmlspecialchars($r['title']) ?>
                        </div>

                        <div style="font-size:12px;color:gray;margin-top:4px;">
                            by <?= htmlspecialchars($r['username']) ?>
                        </div>
                    </div>

                    <!-- LIKES -->
                    <div style="font-size:13px;margin-top:6px;color:#555;">
                        ❤️ <?= $likes ?>
                    </div>

                    <!-- ACTION -->
                    <div>
                        <a href="<?= SITE_URL ?>/index.php?page=recipe&id=<?= $r['id'] ?>" 
                           style="font-size:20px;text-decoration:none;">
                            →
                        </a>
                    </div>

                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center;color:gray;">No recipes found</p>
        <?php endif; ?>
    </div>

    <!-- BACK BUTTON -->
    <div style="margin-top:30px;">
        <a href="<?= SITE_URL ?>/index.php?page=profile" class="btn">
            ← Back to profile
        </a>
    </div>

</div>

<?php require_once 'footer.php'; ?>