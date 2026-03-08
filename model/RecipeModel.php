<?php
require_once 'Database.php';

class Recipe {

    // Crée une nouvelle recette
    public static function create($data, $user_id) {
        $stmt = Database::getInstance()->prepare(
            "INSERT INTO recipes 
            (title, description, category, difficulty, ingredients, instructions, prep_time, cook_time, servings, user_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        // s'assurer que category est un tableau
        $categories = is_array($data['category']) ? $data['category'] : [$data['category']];

        return $stmt->execute([
            $data['title'],
            $data['description'],
            implode(',', $categories),
            $data['difficulty'],
            $data['ingredients'],
            $data['instructions'],
            $data['prep_time'] ?? null,
            $data['cook_time'] ?? null,
            $data['servings'] ?? null,
            $user_id
        ]);
    }

    // Retourne toutes les recettes avec filtres optionnels
    public static function all($filters = []) {
        $sql = "SELECT r.*, u.username FROM recipes r JOIN users u ON r.user_id = u.id WHERE 1";
        $params = [];

        // Filtre par catégorie
        if (!empty($filters['category'])) {
            $sql .= " AND FIND_IN_SET(?, category)";
            $params[] = $filters['category'];
        }

        // Filtre par recherche dans le titre ou la description
        if (!empty($filters['search'])) {
            $sql .= " AND (title LIKE ? OR description LIKE ?)";
            $params[] = "%" . $filters['search'] . "%";
            $params[] = "%" . $filters['search'] . "%";
        }

        $sql .= " ORDER BY r.created_at DESC";

        $stmt = Database::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retourne une recette par son ID
    public static function find($id) {
        $stmt = Database::getInstance()->prepare(
            "SELECT r.*, u.username FROM recipes r 
             JOIN users u ON r.user_id = u.id 
             WHERE r.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Retourne toutes les catégories uniques (même stockées en CSV)
    public static function categories() {
        $stmt = Database::getInstance()->query("SELECT DISTINCT category FROM recipes");
        $cats = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $parts = explode(',', $row['category']);
            foreach ($parts as $p) {
                $cats[trim($p)] = trim($p);
            }
        }
        return array_values($cats);
    }

    public static function latest($limit = 5) {
        $db = Database::getInstance();

        // On s'assure que $limit est bien un entier pour éviter toute injection
        $limit = (int)$limit;

        $stmt = $db->prepare("
            SELECT r.*, u.username
            FROM recipes r
            JOIN users u ON r.user_id = u.id
            ORDER BY r.created_at DESC
            LIMIT $limit
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}