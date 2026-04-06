<?php
namespace App\Repository\Admin;

use App\Model\CategoryModel;
use App\Model\PostModel;
use PDO;

class PostsRepository
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

    // Fetch available tags from the database
    public function getTags()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tags");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createPost($title, $content, $userId, $categoryId, $slug, $status, $thumbnail, $tags)
    {
        try {
            $this->pdo->beginTransaction();

            $publishedAt = ($status === 'published') ? date('Y-m-d H:i:s') : null;

            $sql = 'INSERT INTO posts (title, content, user_id, category_id, slug, status, thumbnail, published_at)
                VALUES (:title, :content, :user_id, :category_id, :slug, :status, :thumbnail, :published_at)';

            $stmt = $this->pdo->prepare($sql);

            // Explicitly binding with types
            $stmt->bindValue(':title', $title, \PDO::PARAM_STR);
            $stmt->bindValue(':content', $content, \PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
            $stmt->bindValue(':category_id', $categoryId, \PDO::PARAM_INT);
            $stmt->bindValue(':slug', $slug, \PDO::PARAM_STR);
            $stmt->bindValue(':status', $status, \PDO::PARAM_STR);

            // Handling potential NULL for thumbnail
            if ($thumbnail === null) {
                $stmt->bindValue(':thumbnail', null, \PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':thumbnail', $thumbnail, \PDO::PARAM_STR);
            }

            // Handling potential NULL for published_at
            if ($publishedAt === null) {
                $stmt->bindValue(':published_at', null, \PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':published_at', $publishedAt, \PDO::PARAM_STR);
            }

            $stmt->execute();
            $postId = $this->pdo->lastInsertId();

            // Handle Tags (Many-to-Many)
            if (! empty($tags) && is_array($tags)) {
                $tagSql  = "INSERT INTO post_tags (post_id, tag_id) VALUES (:post_id, :tag_id)";
                $tagStmt = $this->pdo->prepare($tagSql);

                foreach ($tags as $tagId) {
                    $tagStmt->bindValue(':post_id', $postId, \PDO::PARAM_INT);
                    $tagStmt->bindValue(':tag_id', $tagId, \PDO::PARAM_INT);
                    $tagStmt->execute();
                }
            }

            $this->pdo->commit();
            return true;

        } catch (\Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            // Log error here in a real app: error_log($e->getMessage());
            return false;
        }
    }

    public function updatePost($postId, $title, $content, $userId, $categoryId, $slug, $status, $thumbnail, $tags)
    {
        $publishedAt = ($status === 'published') ? date('Y-m-d H:i:s') : null;

        // Update the post in the posts table
        $stmt = $this->pdo->prepare('
        UPDATE posts SET title = :title, content = :content, user_id = :user_id,
        category_id = :category_id, slug = :slug, status = :status,
        thumbnail = :thumbnail, published_at = :published_at, updated_at = CURRENT_TIMESTAMP
        WHERE post_id = :post_id
    ');

        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':category_id', $categoryId);
        $stmt->bindValue(':slug', $slug);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':thumbnail', $thumbnail);
        $stmt->bindValue(':post_id', $postId);

        if ($publishedAt === null) {
            $stmt->bindValue(':published_at', null, \PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':published_at', $publishedAt);
        }

        if ($stmt->execute()) {
            // Update tags in the post_tags table (many-to-many relationship)
            // Delete current tags
            $this->deleteTagsFromPost($postId);

            // Insert new tags
            if (! empty($tags)) {
                $stmt = $this->pdo->prepare("INSERT INTO post_tags (post_id, tag_id) VALUES (:post_id, :tag_id)");

                foreach ($tags as $tagId) {
                    $stmt->bindValue(':post_id', $postId);
                    $stmt->bindValue(':tag_id', $tagId);
                    $stmt->execute();
                }
            }

            return true;
        }

        return false;
    }

    public function deleteTagsFromPost($postId)
    {
        // Delete all tags associated with the post
        $stmt = $this->pdo->prepare("DELETE FROM post_tags WHERE post_id = :post_id");
        $stmt->bindValue(':post_id', $postId);
        $stmt->execute();
    }

public function getPostsWithCategoryAndUser($status, int $limit, int $offset): array
{
    $whereClause = ($status === 'all') ? "1=1" : "p.status = :status";
    
    $stmt = $this->pdo->prepare("
        SELECT p.*, c.name AS category_name, u.username AS author_name
        FROM posts p
        LEFT JOIN categories c ON p.id = c.id
        LEFT JOIN users u ON p.id = u.id
        WHERE $whereClause
        ORDER BY p.created_at DESC
        LIMIT :limit OFFSET :offset
    ");

    if ($status !== 'all') {
        $stmt->bindValue(':status', $status);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getTotalPostsByStatus($status)
    {
        // If 'all', we select everything. Otherwise, filter by the specific status string.
        $sql = ($status === 'all')
            ? "SELECT COUNT(*) FROM posts"
            : "SELECT COUNT(*) FROM posts WHERE status = :status";

        $stmt = $this->pdo->prepare($sql);

        if ($status !== 'all') {
            $stmt->bindValue(':status', $status);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function getCategoryNameByID($id): CategoryModel
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', $id);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPostById($post_id): ?PostModel
    {
        $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE post_id = :post_id");
        $stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            return null; // Post not found
        }

        // Map the result to a PostModel
        $post               = new PostModel();
        $post->post_id      = $result['post_id'];
        $post->user_id      = $result['user_id'];
        $post->category_id  = $result['category_id'];
        $post->title        = $result['title'];
        $post->slug         = $result['slug'];
        $post->content      = $result['content'];
        $post->status       = $result['status'];
        $post->published_at = $result['published_at'];
        $post->created_at   = $result['created_at'];
        $post->updated_at   = $result['updated_at'];
        $post->thumbnail    = $result['thumbnail'];

        return $post;
    }

}
