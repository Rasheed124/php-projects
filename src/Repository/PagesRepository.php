<?php
namespace App\Repository;

use App\Model\PageModel;
use PDO;

class PagesRepository
{

    public function __construct(private PDO $pdo)
    {}

    public function fetchNavigation(): array
    {
        return $this->getPages();
    }

    public function getPages(): array
    {
        $stmt = $this->pdo->prepare('SELECT * from `pages`  ORDER BY `id` ASC');
        $stmt->execute();
        $entry = $stmt->fetchAll(PDO::FETCH_CLASS, PageModel::class);
        return $entry;

    }

    public function fetchBySlug(string $page_slug): ?PageModel
    {
        $stmt = $this->pdo->prepare('SELECT * from `pages` WHERE  `slug` = :slug');
        $stmt->bindValue(':slug', $page_slug);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, PageModel::class);
        $entry = $stmt->fetch();
        if (! empty($entry)) {
            return $entry;
        } else {
            return null;
        }

    }

    public function createPage(string $title, string $slug, string $content, string $status, ?string $thumbnail, int $userId): bool
    {
        // Added the missing colon before :status
        $sql = "INSERT INTO `pages` (title, slug, content, thumbnail, status, user_id, created_at)
            VALUES (:title, :slug, :content, :thumbnail, :status, :user_id, NOW())";

        $stmt = $this->pdo->prepare($sql);

        // Using bindValue for explicit type safety
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':thumbnail', $thumbnail, $thumbnail === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Fetch pages with author names and status filtering
     */
    public function getPagesWithUser(string $status = 'all', int $limit = 10, int $offset = 0): array
    {
        $sql = "SELECT p.*, u.username as author_name
            FROM `pages` p
            LEFT JOIN `users` u ON p.user_id = u.id";

        if ($status !== 'all') {
            $sql .= " WHERE p.status = :status";
        }

        $sql .= " ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        if ($status !== 'all') {
            $stmt->bindValue(':status', $status);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

/**
 * Count pages based on status
 */
    public function getTotalPagesByStatus(string $status = 'all'): int
    {
        $sql = "SELECT COUNT(*) FROM `pages`";
        if ($status !== 'all') {
            $sql .= " WHERE status = :status";
        }

        $stmt = $this->pdo->prepare($sql);
        if ($status !== 'all') {
            $stmt->bindValue(':status', $status);
        }
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

/**
 * Permanent Delete
 */
    public function deletePage(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM `pages` WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function fetchById(int $id): ?PageModel
    {
        $stmt = $this->pdo->prepare('SELECT * FROM `pages` WHERE `id` = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, PageModel::class);
        $entry = $stmt->fetch();
        return $entry ?: null;
    }

    public function updatePage(int $id, string $title, string $slug, string $content, string $status, ?string $thumbnail): bool
    {
        $sql = "UPDATE `pages` SET
            title = :title,
            slug = :slug,
            content = :content,
            status = :status,
            updated_at = NOW()";

        // Only update thumbnail if a new one was uploaded
        if ($thumbnail !== null) {
            $sql .= ", thumbnail = :thumbnail";
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        if ($thumbnail !== null) {
            $stmt->bindValue(':thumbnail', $thumbnail, PDO::PARAM_STR);
        }

        return $stmt->execute();
    }

    

}
