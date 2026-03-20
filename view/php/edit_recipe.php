<?php
$pageTitle = "Edit Recipe";
require_once 'header.php';
?>

<div class="container" style="padding:20px;">

    <h2>Edit Recipe: <?= htmlspecialchars($recipe['title']) ?></h2>

    <?php if(!empty($error)): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <label>Recipe Image</label>

        <?php if (!empty($recipe['image'])): ?>
            <div style="margin-bottom:10px;">
                <img src="<?= SITE_URL . $recipe['image'] ?>" style="width:150px;border-radius:10px;">
            </div>
        <?php endif; ?>

        <input type="file" name="image" accept="image/*">

        <label>
            <input type="checkbox" name="delete_image"> Remove current image
        </label>
        
        <label>Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($recipe['title']) ?>" required>

        <label>Description</label>
        <textarea name="description"><?= htmlspecialchars($recipe['description']) ?></textarea>

        <label>Category</label>
        <select name="category[]" multiple>
            <?php foreach($cats as $cat): ?>
                <option value="<?= $cat ?>" <?= in_array($cat, explode(',', $recipe['category'])) ? 'selected' : '' ?>>
                    <?= $cat ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Difficulty</label>
        <select name="difficulty">
            <?php foreach(['easy','medium','hard'] as $diff): ?>
                <option value="<?= $diff ?>" <?= $recipe['difficulty']==$diff ? 'selected' : '' ?>>
                    <?= ucfirst($diff) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Ingredients</label>
        <textarea name="ingredients" required><?= htmlspecialchars($recipe['ingredients']) ?></textarea>

        <label>Instructions</label>
        <textarea name="instructions" required><?= htmlspecialchars($recipe['instructions']) ?></textarea>

        <label>Prep Time (min)</label>
        <input type="number" name="prep_time" value="<?= $recipe['prep_time'] ?? 0 ?>">

        <label>Cook Time (min)</label>
        <input type="number" name="cook_time" value="<?= $recipe['cook_time'] ?? 0 ?>">

        <label>Servings</label>
        <input type="number" name="servings" value="<?= $recipe['servings'] ?? 0 ?>">

        <button type="submit" class="btn btn-primary" style="margin-top:10px;">Save Changes</button>
    </form>

</div>

<?php require_once 'footer.php'; ?>