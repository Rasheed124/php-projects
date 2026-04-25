<?php
namespace App\Repository\Admin;

use App\Model\CategoryModel;
use App\Model\TagModel;
use PDO;

class PostsRepository
{

    public function __construct(private PDO $pdo)
    {
    }

    public function getTotalCount(array $options = []): int
    {
        $sql = "SELECT COUNT(*) FROM posts p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'published' AND p.deleted_at IS NULL";

        $params = [];

        if (! empty($options['search'])) {
            $sql              .= " AND (p.title LIKE :search OR p.content LIKE :search)";
            $params['search']  = '%' . $options['search'] . '%';
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public function all(array $options = []): array
    {
        // 1. Setup Defaults
        $limit  = isset($options['limit']) ? (int) $options['limit'] : 10;
        $offset = isset($options['offset']) ? (int) $options['offset'] : 0;
        $status = $options['status'] ?? 'published';

        // 2. Base Query
        $sql = "SELECT
                p.*,
                c.name as category_name,
                c.slug as category_slug,
                u.username as author_name
            FROM posts p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.user_id = u.id";

        // 3. Dynamic Conditions
        $conditions = [
            "p.status = :status",
            "p.deleted_at IS NULL",
        ];

        // Filter by Category Slug
        if (! empty($options['category_slug'])) {
            $conditions[] = "c.slug = :category_slug";
        }

        // Filter by Tag Name/Slug
        if (! empty($options['tag_slug'])) {
            $sql .= " JOIN post_tags pt ON p.id = pt.post_id
                  JOIN tags t ON pt.tag_id = t.id";
            $conditions[]  = "t.name = :tag_slug";
        }

        // NEW: Search Logic (Title or Content)
        if (! empty($options['search'])) {
            $conditions[] = "(p.title LIKE :search OR p.content LIKE :search )";
        }

        // 4. Assemble SQL
        $sql .= " WHERE " . implode(" AND ", $conditions);
        $sql .= " ORDER BY p.created_at DESC";
        $sql .= " LIMIT :limit OFFSET :offset";

        // 5. Prepare and Bind
        $stmt = $this->pdo->prepare($sql);

        // Standard Binds
        $stmt->bindValue(':status', $status, \PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);

        // Dynamic Binds
        if (! empty($options['category_slug'])) {
            $stmt->bindValue(':category_slug', $options['category_slug'], \PDO::PARAM_STR);
        }

        if (! empty($options['tag_slug'])) {
            $stmt->bindValue(':tag_slug', $options['tag_slug'], \PDO::PARAM_STR);
        }

        if (! empty($options['search'])) {
            // We wrap the search term in % wildcards for partial matches
            $searchTerm = '%' . $options['search'] . '%';
            $stmt->bindValue(':search', $searchTerm, \PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function slugExists(string $slug): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM posts WHERE slug = :slug");
        $stmt->execute(['slug' => $slug]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function getCategories(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories ORDER BY name ASC");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, CategoryModel::class);
    }

    // Fetch available tags from the database
    public function getTags(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tags");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, TagModel::class);
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

    public function getPostById(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post) {
            // Fetch associated tag IDs for this post
            $stmt = $this->pdo->prepare("SELECT tag_id FROM post_tags WHERE post_id = :id");
            $stmt->execute(['id' => $id]);
            $post['tag_ids'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        return $post;
    }

    public function updatePost(int $id, array $data, array $tagIds): bool
    {
        try {
            $this->pdo->beginTransaction();

            // 1. Update Post Table
            $sql = "UPDATE posts SET
                title = :title,
                content = :content,
                category_id = :category_id,
                slug = :slug,
                status = :status";

            // Only update thumbnail if a new one was uploaded
            if ($data['thumbnail']) {
                $sql .= ", thumbnail = :thumbnail";
            }

            $sql .= " WHERE id = :id";

            $params = [
                'title'       => $data['title'],
                'content'     => $data['content'],
                'category_id' => $data['category_id'],
                'slug'        => $data['slug'],
                'status'      => $data['status'],
                'id'          => $id,
            ];

            if ($data['thumbnail']) {
                $params['thumbnail'] = $data['thumbnail'];
            }

            $this->pdo->prepare($sql)->execute($params);

            // 2. Sync Tags (Delete old, Insert new)
            $this->pdo->prepare("DELETE FROM post_tags WHERE post_id = :id")->execute(['id' => $id]);

            if (! empty($tagIds)) {
                $tagStmt = $this->pdo->prepare("INSERT INTO post_tags (post_id, tag_id) VALUES (:post_id, :tag_id)");
                foreach ($tagIds as $tagId) {
                    $tagStmt->execute(['post_id' => $id, 'tag_id' => $tagId]);
                }
            }

            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function slugExistsExcluding(string $slug, int $postId): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM posts WHERE slug = :slug AND id != :id");
        $stmt->execute(['slug' => $slug, 'id' => $postId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function deleteTagsFromPost($postId)
    {
        // Delete all tags associated with the post
        $stmt = $this->pdo->prepare("DELETE FROM post_tags WHERE post_id = :post_id");
        $stmt->bindValue(':post_id', $postId);
        $stmt->execute();
    }

    public function getPostsWithCategoryAndUser(string $status, int $limit, int $offset): array
    {
        // 1. Determine if we are looking at the Trash or Live posts
        if ($status === 'trash') {
            $whereClause = "p.deleted_at IS NOT NULL";
        } else {
            // Normal tabs (all, published, draft) should NEVER show trashed items
            $whereClause = "p.deleted_at IS NULL";

            if ($status !== 'all') {
                $whereClause .= " AND p.status = :status";
            }
        }

        $sql = "
        SELECT
            p.*,
            c.name AS category_name,
            u.username AS author_name
        FROM posts p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN users u ON p.user_id = u.id
        WHERE $whereClause
        ORDER BY p.created_at DESC
        LIMIT :limit OFFSET :offset
    ";

        $stmt  = $this->pdo->prepare($sql);

        if ($status !== 'all' && $status !== 'trash') {
            $stmt->bindValue(':status', $status);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function softDelete(int $id, int $userId, bool $isAdmin): bool
    {
        $sql = "UPDATE posts SET deleted_at = NOW() WHERE id = :id";

        // If not admin, verify ownership
        if (! $isAdmin) {
            $sql .= " AND user_id = :userId";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        if (! $isAdmin) {
            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        }

        return $stmt->execute();
    }

    public function restore(int $id, int $userId, bool $isAdmin): bool
    {
        $sql = "UPDATE posts SET deleted_at = NULL WHERE id = :id";

        if (! $isAdmin) {
            $sql .= " AND user_id = :userId";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        if (! $isAdmin) {
            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        }

        return $stmt->execute();
    }

    public function permanentDelete(int $id, int $userId, bool $isAdmin): bool
    {
        $sql = "DELETE FROM posts WHERE id = :id";

        if (! $isAdmin) {
            $sql .= " AND user_id = :userId";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        if (! $isAdmin) {
            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        }

        return $stmt->execute();
    }

    public function getTotalPostsByStatus(string $status): int
    {
        if ($status === 'trash') {
            $sql = "SELECT COUNT(*) FROM posts WHERE deleted_at IS NOT NULL";
        } else {
            $sql = "SELECT COUNT(*) FROM posts WHERE deleted_at IS NULL";
            if ($status !== 'all') {
                $sql .= " AND status = :status";
            }
        }

        $stmt  = $this->pdo->prepare($sql);
        if ($status !== 'all' && $status !== 'trash') {
            $stmt->bindValue(':status', $status);
        }
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getCategoryNameByID($id): CategoryModel
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', $id);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // =================================== CATEGORY ========================== \\
    //============================================================================== \\

    public function createCategory($name, $slug)
    {
        $stmt = $this->pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
        return $stmt->execute([$name, $slug]);
    }

    public function updateCategory($id, $name, $slug)
    {
        $stmt = $this->pdo->prepare("UPDATE categories SET name = ?, slug = ? WHERE id = ?");
        return $stmt->execute([$name, $slug, $id]);
    }

    public function deleteCategory($id)
    {
        // Basic delete
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // ===================================  TAGS ========================== \\
    //============================================================================== \\

    public function createTag($name)
    {
        $stmt = $this->pdo->prepare("INSERT INTO tags (name) VALUES (:name)");
        return $stmt->execute(['name' => $name]);
    }

    public function updateTag($id, $name)
    {
        $stmt = $this->pdo->prepare("UPDATE tags SET name = :name, WHERE id = :id");
        return $stmt->execute(['name' => $name, 'id' => $id]);
    }

    public function deleteTag($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM tags WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // ===================================  FORNTNEND POST PAGE RENDERING ========================== \\
    //============================================================================== \\

    public function getActiveCategories()
    {
        $sql = "SELECT c.name, c.slug, COUNT(p.id) as post_count
            FROM categories c
            JOIN posts p ON c.id = p.category_id
            WHERE p.status = 'published'
            AND p.deleted_at IS NULL
            GROUP BY c.id, c.name, c.slug
            ORDER BY post_count DESC";

        return $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getActiveTags()
    {
        $sql = "SELECT DISTINCT t.name, t.id
            FROM tags t
            JOIN post_tags pt ON t.id = pt.tag_id
            JOIN posts p ON pt.post_id = p.id
            WHERE p.status = 'published'
            AND p.deleted_at IS NULL
            ORDER BY t.name ASC";

        return $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchBySlug(string $slug): ?array
    {
        // 1. Fetch the Post, Author, and Category
        $sql = "SELECT
                p.*,
                c.name as category_name,
                c.slug as category_slug,
                u.username as author_name
            FROM posts p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.user_id = u.id
            WHERE p.slug = :slug
            AND p.deleted_at IS NULL
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        $post = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (! $post) {
            return null;
        }

        // 2. Fetch Tags specifically for this post
        $post['tags'] = $this->getTagsByPostId((int) $post['id']);

        return $post;
    }

/**
 * Helper to fetch tags for a specific post
 */
    private function getTagsByPostId(int $postId): array
    {
        $sql = "SELECT t.name, t.id
            FROM tags t
            JOIN post_tags pt ON t.id = pt.tag_id
            WHERE pt.post_id = :post_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['post_id' => $postId]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function allPostByTagAndCat(array $filters = []): array
    {
        $limit  = isset($filters['limit']) ? (int) $filters['limit'] : 10;
        $offset = isset($filters['offset']) ? (int) $filters['offset'] : 0;

        $sql = "SELECT p.*, c.name as category_name, u.username as author_name
            FROM posts p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.user_id = u.id";

        $conditions = ["p.status = 'published'", "p.deleted_at IS NULL"];
        $params     = [];

        if (isset($filters['category_slug'])) {
            $conditions[]     = "c.slug = :c_slug";
            $params['c_slug'] = $filters['category_slug'];
        }

        if (isset($filters['tag_slug'])) {
            $sql              .= " JOIN post_tags pt ON p.id = pt.post_id JOIN tags t ON pt.tag_id = t.id";
            $conditions[]      = "t.name = :t_slug";
            $params['t_slug']  = $filters['tag_slug'];
        }

        $sql .= " WHERE " . implode(" AND ", $conditions);
        $sql .= " ORDER BY p.created_at DESC";
        $sql .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);

        // Bind all params
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getCountByFilter(array $filters = []): int
    {
        $sql = "SELECT COUNT(DISTINCT p.id) FROM posts p
            LEFT JOIN categories c ON p.category_id = c.id";

        $conditions = ["p.status = 'published'", "p.deleted_at IS NULL"];
        $params     = [];

        if (isset($filters['category_slug'])) {
            $conditions[]     = "c.slug = :c_slug";
            $params['c_slug'] = $filters['category_slug'];
        }

        if (isset($filters['tag_slug'])) {
            $sql              .= " JOIN post_tags pt ON p.id = pt.post_id JOIN tags t ON pt.tag_id = t.id";
            $conditions[]      = "t.name = :t_slug";
            $params['t_slug']  = $filters['tag_slug'];
        }

        $sql  .= " WHERE " . implode(" AND ", $conditions);
        $stmt  = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

}
