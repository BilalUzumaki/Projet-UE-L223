<?php
require_once(__DIR__ . "/../../model/php/RecipeModel.php");
class RecipeController {

    public function list() {
        $selectedCat = $_GET['category'] ?? '';
        $search      = trim($_GET['search'] ?? '');

        $filters = [
            'category' => $selectedCat,
            'search'   => $search,
        ];

        // Récupérer les recettes filtrées
        $recipes = Recipe::all($filters);

        // Récupérer les catégories existantes
        $categories = Recipe::categories();

        // Préparer les icônes pour chaque recette
        $categoryIcons = [
            'Pasta'       => '🍝',
            'Vegetarian'  => '🥗',
            'Dessert'     => '🍰',
            'Soup'        => '🍲',
            'Seafood'     => '🦞',
            'Meat'        => '🥩',
            'Breakfast'   => '🥞',
            'Salad'       => '🥙',
        ];

        foreach ($recipes as &$r) {
            $r['icon'] = $categoryIcons[$r['category']] ?? '🍽️';
            $r['totalTime'] = ($r['prep_time'] ?? 0) + ($r['cook_time'] ?? 0);
        }

        include(__DIR__ . "/../../view/php/recipes.php");
    }

    public function view() {
        $id = $_GET['id'] ?? 0;
        $recipe = Recipe::find($id);

        // Rediriger si la recette n'existe pas
        if (!$recipe) {
            header("Location: index.php?page=recipes");
            exit;
        }

        // Enregistrer la vue dans l'historique si l'utilisateur est connecté
        if (isset($_SESSION['user'])) {
            $stmt = Database::getInstance()->prepare("
                INSERT INTO recipe_views (user_id, recipe_id) VALUES (?, ?)
            ");
            $stmt->execute([$_SESSION['user']['id'], $id]);
        }

        // Préparer les variables pour la vue
        $categoryIcons = [
            'Pasta' => '🍝', 'Vegetarian' => '🥗', 'Dessert' => '🍰',
            'Soup'  => '🍲', 'Seafood' => '🦞', 'Meat' => '🥩',
            'Breakfast' => '🥞', 'Salad' => '🥙',
        ];

        $icon = $categoryIcons[$recipe['category']] ?? '🍽️';
        $totalTime = ($recipe['prep_time'] ?? 0) + ($recipe['cook_time'] ?? 0);
        $ingredients  = array_filter(array_map('trim', explode("\n", $recipe['ingredients'] ?? '')));
        $instructions = array_filter(array_map('trim', explode("\n", $recipe['instructions'] ?? '')));

        include(__DIR__ . "/../../view/php/recipe.php");
    }

    public function add() {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $error = '';
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title        = trim($_POST['title'] ?? '');
            $description  = trim($_POST['description'] ?? '');
            $categories   = $_POST['category'] ?? [];
            $difficulty   = $_POST['difficulty'] ?? 'easy';
            $ingredients  = trim($_POST['ingredients'] ?? '');
            $instructions = trim($_POST['instructions'] ?? '');
            $prep_time    = (int)($_POST['prep_time'] ?? 0);
            $cook_time    = (int)($_POST['cook_time'] ?? 0);
            $servings     = (int)($_POST['servings'] ?? 0);

            // Validation des champs obligatoires
            if (empty($title) || empty($ingredients) || empty($instructions)) {
                $error = "Please fill in the required fields (title, ingredients, instructions).";
            }

            // Validation des catégories
            $allowed_cats = [
                'Breakfast','Brunch','Appetizer','Soup','Salad','Pasta','Rice & Grains',
                'Vegetarian','Vegan','Meat','Poultry','Seafood','Dessert',
                'Snack','Side dish','Sauce','Beverage','Other'
            ];

            foreach ($categories as $cat) {
                if ($cat !== '' && !in_array($cat, $allowed_cats)) {
                    $error = "One of the selected categories is invalid.";
                    break;
                }
            }

            if (!$error) {
                // Crée la recette via le modèle
                Recipe::create([
                    'title' => $title,
                    'description' => $description,
                    'category' => $categories,
                    'difficulty' => $difficulty,
                    'ingredients' => $ingredients,
                    'instructions' => $instructions,
                    'prep_time' => $prep_time,
                    'cook_time' => $cook_time,
                    'servings' => $servings
                ], $_SESSION['user']['id']);

                // Redirection immédiate pour éviter le double POST
                header("Location: index.php?page=recipes");
                exit;
            }
        }

        // Préparer les catégories pour le formulaire
        $cats = [
            'Breakfast','Brunch','Appetizer','Soup','Salad','Pasta','Rice & Grains',
            'Vegetarian','Vegan','Meat','Poultry','Seafood','Dessert',
            'Snack','Side dish','Sauce','Beverage','Other'
        ];

        include(__DIR__ . "/../../view/php/add_recipe.php");
    }

    public function edit() {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $id = $_GET['id'] ?? 0;
        $recipe = Recipe::find($id);

        // Vérifier que la recette existe et appartient à l'utilisateur
        if (!$recipe || $recipe['user_id'] != $_SESSION['user']['id']) {
            header("Location: index.php?page=profile&view=my_recipes");
            exit;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title        = trim($_POST['title'] ?? '');
            $description  = trim($_POST['description'] ?? '');
            $categories   = $_POST['category'] ?? [];
            $difficulty   = $_POST['difficulty'] ?? 'easy';
            $ingredients  = trim($_POST['ingredients'] ?? '');
            $instructions = trim($_POST['instructions'] ?? '');
            $prep_time    = (int)($_POST['prep_time'] ?? 0);
            $cook_time    = (int)($_POST['cook_time'] ?? 0);
            $servings     = (int)($_POST['servings'] ?? 0);

            if (empty($title) || empty($ingredients) || empty($instructions)) {
                $error = "Please fill in the required fields.";
            }

            if (!$error) {
                Recipe::update($id, [
                    'title' => $title,
                    'description' => $description,
                    'category' => $categories,
                    'difficulty' => $difficulty,
                    'ingredients' => $ingredients,
                    'instructions' => $instructions,
                    'prep_time' => $prep_time,
                    'cook_time' => $cook_time,
                    'servings' => $servings
                ]);

                header("Location: index.php?page=profile&view=my_recipes");
                exit;
            }
        }

        $cats = [
            'Breakfast','Brunch','Appetizer','Soup','Salad','Pasta','Rice & Grains',
            'Vegetarian','Vegan','Meat','Poultry','Seafood','Dessert',
            'Snack','Side dish','Sauce','Beverage','Other'
        ];

        include(__DIR__ . "/../../view/php/edit_recipe.php");
    }

    public function delete() {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $id = $_GET['id'] ?? 0;
        $recipe = Recipe::find($id);

        if ($recipe && $recipe['user_id'] == $_SESSION['user']['id']) {
            Recipe::delete($id);
        }

        header("Location: index.php?page=profile&view=my_recipes");
        exit;
    }

    public function toggleFavorite() {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $recipe_id = $_GET['id'] ?? 0;
        $user_id = $_SESSION['user']['id'];

        // Vérifier si l'utilisateur a déjà mis la recette en favori
        $stmt = Database::getInstance()->prepare("
            SELECT * FROM recipe_favorites WHERE user_id = ? AND recipe_id = ?
        ");
        $stmt->execute([$user_id, $recipe_id]);
        $exists = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($exists) {
            Recipe::removeFavorite($user_id, $recipe_id);
        } else {
            Recipe::addFavorite($user_id, $recipe_id);
        }

        header("Location: index.php?page=recipe&id=$recipe_id");
        exit;
    }
}