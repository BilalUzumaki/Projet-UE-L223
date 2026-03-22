<?php
require_once(__DIR__ . "/../../model/php/CommentModel.php");

class CommentController {

    private function filterBadWords($text) {
        $badWords = [
            'connard', 'pute', 'merde', 'idiot', 'stupide'
        ];

        foreach ($badWords as $word) {
            $text = preg_replace('/\b' . preg_quote($word, '/') . '\b/i', '****', $text);
        }

        return $text;
    }

    public function add() {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $recipe_id = $_POST['recipe_id'] ?? 0;
        $content   = trim($_POST['content'] ?? '');
        $parent_id = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;

        // ✅ BONUS 1 : anti spam
        if (strlen($content) < 3) {
            header("Location: index.php?page=recipe&id=" . $recipe_id);
            exit;
        }

        // ✅ BONUS 2 : limiter longueur
        $content = substr($content, 0, 500);

        // 🔥 filtre insultes
        $content = $this->filterBadWords($content);

        // save
        Comment::create($_SESSION['user']['id'], $recipe_id, $content, $parent_id);

        header("Location: index.php?page=recipe&id=" . $recipe_id);
        exit;
    }
}