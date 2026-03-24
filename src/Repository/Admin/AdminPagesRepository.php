<?php
namespace BlogApp\Repository\Admin;

use PDO;

class AdminPagesRepository
{

    public function __construct(private PDO $pdo)
    {
    }

    public function createPost($title, $content, $authorId, $category, $status, $thumbnail): bool
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO posts (title, content, author_id, category_id, status, thumbnail)
            VALUES (:title, :content, :author_id, :category_id, :status, :thumbnail)
        ');

        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':author_id', $authorId);
        $stmt->bindValue(':category_id', $category);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':thumbnail', $thumbnail);

        return $stmt->execute();
    }

    // Other repository methods...
}
