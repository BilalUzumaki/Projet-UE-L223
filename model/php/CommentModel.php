<?php
require_once 'Database.php';

class Comment {

    public static function create($user_id, $recipe_id, $content, $parent_id = null) {
        $stmt = Database::getInstance()->prepare("
            INSERT INTO comments (user_id, recipe_id, content, parent_id)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$user_id, $recipe_id, $content, $parent_id]);
    }

    public static function byRecipe($recipe_id) {
        $stmt = Database::getInstance()->prepare("
            SELECT c.*, u.username
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.recipe_id = ?
            ORDER BY c.created_at ASC
        ");
        $stmt->execute([$recipe_id]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 🔥 transformer en arbre
        $tree = [];
        $refs = [];

        foreach ($comments as $c) {
            $c['replies'] = [];
            $refs[$c['id']] = $c;
        }

        foreach ($refs as $id => &$comment) {
            if ($comment['parent_id']) {
                $refs[$comment['parent_id']]['replies'][] = &$comment;
            } else {
                $tree[] = &$comment;
            }
        }

        return $tree;
    }
}