<?php
$pageTitle = "Add Recipe — Cook n' Share";
require_once 'header.php';
?>
<div class="add-recipe-page">

    <div class="page-header">
        <h1>Share a Recipe</h1>
        <p>Fill in the details below and share your creation with the community.</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= SITE_URL ?>/index.php?page=add_recipe">

        <!-- Basic info -->
        <div class="form-section">
            <div class="form-section-title">📋 Basic Information</div>

            <div class="form-group">
                <label for="title">Recipe Title *</label>
                <input type="text" id="title" name="title" required
                       value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="description">Short Description</label>
                <textarea id="description" name="description" rows="3"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <?php for ($i = 0; $i < 3; $i++): ?>
                    <label>Category <?= $i + 1 ?> <?= $i === 0 ? '*' : '' ?></label>
                    <select name="category[]" class="category-select" <?= $i === 0 ? 'required' : '' ?>>
                        <option value="">Select category…</option>
                        <?php foreach ($cats as $cat): ?>
                            <?php $sel = (isset($_POST['category'][$i]) && $_POST['category'][$i] === $cat) ? 'selected' : ''; ?>
                            <option value="<?= htmlspecialchars($cat) ?>" <?= $sel ?>><?= htmlspecialchars($cat) ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endfor; ?>
            </div>

            <div class="form-group">
                <label for="difficulty">Difficulty</label>
                <select id="difficulty" name="difficulty">
                    <?php foreach (['easy' => 'Easy','medium' => 'Medium','hard' => 'Hard'] as $val => $label): ?>
                        <?php $sel = (($_POST['difficulty'] ?? 'easy') === $val) ? 'selected' : ''; ?>
                        <option value="<?= $val ?>" <?= $sel ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Times & Servings -->
        <div class="form-section">
            <label for="prep_time">Prep Time (minutes)</label>
            <input type="number" id="prep_time" name="prep_time" min="0" value="<?= htmlspecialchars($_POST['prep_time'] ?? '') ?>">

            <label for="cook_time">Cook Time (minutes)</label>
            <input type="number" id="cook_time" name="cook_time" min="0" value="<?= htmlspecialchars($_POST['cook_time'] ?? '') ?>">

            <label for="servings">Servings</label>
            <input type="number" id="servings" name="servings" min="1" value="<?= htmlspecialchars($_POST['servings'] ?? '') ?>">
        </div>

        <!-- Ingredients -->
        <div class="form-section">
            <label for="ingredients">Ingredients *</label>
            <textarea id="ingredients" name="ingredients" rows="8"><?= htmlspecialchars($_POST['ingredients'] ?? '') ?></textarea>
        </div>

        <!-- Instructions -->
        <div class="form-section">
            <label for="instructions">Instructions *</label>
            <textarea id="instructions" name="instructions" rows="10"><?= htmlspecialchars($_POST['instructions'] ?? '') ?></textarea>
        </div>

        <div class="form-actions">
            <a href="<?= SITE_URL ?>/index.php?page=recipes" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary">Publish Recipe →</button>
        </div>
    </form>
</div>
<?php require_once 'footer.php'; ?>

<script>
    // Désactivation des options en double
    document.addEventListener('DOMContentLoaded', () => {
        const selects = document.querySelectorAll('.category-select');

        function updateOptions() {
            const selected = Array.from(selects).map(s => s.value).filter(v => v);
            selects.forEach(s => {
                Array.from(s.options).forEach(o => {
                    o.disabled = o.value && selected.includes(o.value) && o.value !== s.value;
                });
            });
        }

        selects.forEach(s => s.addEventListener('change', updateOptions));
        updateOptions();
    });
</script>