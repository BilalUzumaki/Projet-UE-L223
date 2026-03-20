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

        <?php if (!empty($recipe['image'])): ?>
            <div style="margin:10px 0;">
                <img src="<?= SITE_URL . $recipe['image'] ?>" style="width:100%;border-radius:12px;">
            </div>
        <?php endif; ?>
        
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

    <!-- 💬 COMMENTS -->
    <div class="recipe-card">
        <h3>💬 Comments</h3>

        <!-- COMMENT FORM -->
        <?php if(isset($_SESSION['user'])): ?>
            <form method="POST" action="index.php?page=add_comment">
                <input type="hidden" name="recipe_id" value="<?= $recipe['id'] ?>">

                <textarea name="content" placeholder="Write a comment..." required></textarea>

                <button type="submit" class="btn btn-primary" style="margin-top:10px;">
                    Post Comment
                </button>
            </form>
        <?php else: ?>
            <p style="color:gray;">
                You must <a href="index.php?page=login">login</a> to comment.
            </p>
        <?php endif; ?>

        <hr style="margin:20px 0;">

        <!-- 🔁 FUNCTION -->
        <?php
        function displayComments($comments, $level = 0) {
            foreach ($comments as $c): ?>
                
                <div style="margin-left: <?= $level * 20 ?>px; margin-bottom:15px;">

                    <strong><?= htmlspecialchars($c['username']) ?></strong>
                    <div style="font-size:12px;color:gray;">
                        <?= $c['created_at'] ?>
                    </div>

                    <p><?= htmlspecialchars($c['content']) ?></p>

                    <!-- BOUTON REPLY -->
                    <?php if(isset($_SESSION['user'])): ?>
                        <button type="button"
                                onclick="toggleReplyForm(<?= $c['id'] ?>)"
                                style="font-size:12px;color:gray;background:none;border:none;cursor:pointer;">
                            ↩️ Reply
                        </button>

                        <!-- FORMULAIRE CACHE -->
                        <form method="POST"
                              action="index.php?page=add_comment"
                              id="reply-form-<?= $c['id'] ?>"
                              style="margin-top:5px; display:none;">

                            <input type="hidden" name="recipe_id" value="<?= $c['recipe_id'] ?>">
                            <input type="hidden" name="parent_id" value="<?= $c['id'] ?>">

                            <input type="text" name="content" placeholder="Reply..." required>
                            <button type="submit">Send</button>
                        </form>
                    <?php endif; ?>

                    <!-- REPLIES -->
                    <?php if (!empty($c['replies'])): ?>
                        <?php displayComments($c['replies'], $level + 1); ?>
                    <?php endif; ?>

                </div>

            <?php endforeach;
        }
        ?>

        <!-- DISPLAY -->
        <?php if(!empty($comments)): ?>
            <?php displayComments($comments); ?>
        <?php else: ?>
            <p style="color:gray;">No comments yet.</p>
        <?php endif; ?>

    </div>

    <!-- BACK -->
    <div style="margin-top:20px;">
        <a href="<?= SITE_URL ?>/index.php?page=recipes" class="btn">
            ← Back
        </a>
    </div>

</div>

<!-- 🔥 SCRIPT -->
<script>
function toggleReplyForm(id) {

    // fermer tous les autres
    document.querySelectorAll("[id^='reply-form-']").forEach(f => {
        if (f.id !== "reply-form-" + id) {
            f.style.display = "none";
        }
    });

    const form = document.getElementById("reply-form-" + id);

    form.style.display = (form.style.display === "none") ? "block" : "none";
}
</script>

<?php require_once 'footer.php'; ?>