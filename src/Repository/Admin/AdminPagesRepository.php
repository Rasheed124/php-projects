<?php
namespace BlogApp\Repository\Admin;

use PDO;

class AdminPagesRepository
{

    public function __construct(private PDO $pdo)
    {
    }

    // Check if category exists
    public function categoryExists($categoryId)
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM categories WHERE id = :category_id');
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

    public function createPost($title, $content, $authorId, $categoryId, $slug, $status, $thumbnail)
    {
        $stmt = $this->pdo->prepare('
        INSERT INTO posts (title, content, author_id, category_id, slug, status, thumbnail)
        VALUES (:title, :content, :author_id, :category_id, :slug, :status, :thumbnail)
    ');

        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':author_id', $authorId);
        $stmt->bindValue(':category_id', $categoryId);
        $stmt->bindValue(':slug', $slug);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':thumbnail', $thumbnail);

        return $stmt->execute();
    }

    // Other repository methods...
}
