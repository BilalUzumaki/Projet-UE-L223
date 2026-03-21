<?php
// sécurité
if (!isset($recipe)) return;

// nombre de likes
$likes = $recipe['likes'] ?? 0;?>

<article class="recipe-card" style="display:flex;align-items:center;gap:15px;">

    <!-- IMAGE -->
    <div style="width:80px;height:80px;flex-shrink:0;">
        <?php if (!empty($recipe['image'])): ?>
            <img src="<?= SITE_URL . '/' . $recipe['image'] ?>" 
                 style="width:100%;height:100%;object-fit:cover;border-radius:12px;">
        <?php else: ?>
            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#eee;border-radius:12px;">
                🍽️
            </div>
        <?php endif; ?>
    </div>

    <!-- CONTENT -->
    <div style="flex:1;">
        <div style="font-weight:600;">
            <?= htmlspecialchars($recipe['title']) ?>
        </div>

        <div style="font-size:12px;color:gray;">
            by <?= htmlspecialchars($recipe['username']) ?>
        </div>

        <div style="font-size:13px;margin-top:5px;">
            ❤️ <?= $likes ?>
        </div>
    </div>

    <!-- ACTION -->
    <div>
        <a href="<?= SITE_URL ?>/index.php?page=recipe&id=<?= $recipe['id'] ?>" 
           style="font-size:20px;text-decoration:none;">
            →
        </a>
    </div>

</article>