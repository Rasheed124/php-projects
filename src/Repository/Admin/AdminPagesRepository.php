<?php
namespace BlogApp\Repository\Admin;

use BlogApp\Model\CategoryModel;
use PDO;

class AdminPagesRepository
{

    public function __construct(private PDO $pdo)
    {
    }

    public function getCategories(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createPost($title, $content, $userId, $categoryId, $slug, $status, $thumbnail)
    {
        $publishedAt = ($status === 'published') ? date('Y-m-d H:i:s') : null;

        $stmt = $this->pdo->prepare('INSERT INTO posts (title, body, user_id, category_id, slug, status, thumbnail, published_at)
        VALUES (:title, :body, :user_id, :category_id, :slug, :status, :thumbnail, :published_at)');

        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':body', $content);
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':category_id', $categoryId);
        $stmt->bindValue(':slug', $slug);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':thumbnail', $thumbnail);

        if ($publishedAt === null) {
            $stmt->bindValue(':published_at', null, \PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':published_at', $publishedAt);
        }

        return $stmt->execute();
    }

    // public function getPostsByStatus($status, $limit, int $offset)
    // {
    //     $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE status = :status ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
    //     $stmt->bindValue(':status', $status);
    //     $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    //     $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    //     $stmt->execute();
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

    public function getPostsWithCategoryAndUser($status, int $limit, int $offset): array
    {
        $stmt = $this->pdo->prepare('
            SELECT p.*, c.name AS category_name, u.username AS author_name
            FROM posts p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.user_id = u.id
            WHERE p.status = :status
            ORDER BY p.created_at DESC
            LIMIT :limit OFFSET :offset
        ');

        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryNameByID($id): CategoryModel
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', $id);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get total number of posts for a specific status (for pagination)
    public function getTotalPostsByStatus($status)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM posts WHERE status = :status");
        $stmt->bindValue(':status', $status);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

}
