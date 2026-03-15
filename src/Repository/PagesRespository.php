<?php
namespace App\Repository;

use App\Model\PageModel;
use PDO;

class PagesRespository
{
    public function __construct(private PDO $pdo)
    {}

    public function fetchNavigation(): array
    {
        return $this->get();
    }
    public function get(): array
    {
        $stmt = $this->pdo->prepare('SELECT * from `pages`  ORDER BY `id` ASC');
        $stmt->execute();
        $entry = $stmt->fetchAll(PDO::FETCH_CLASS, PageModel::class);
        return $entry;

    }
    public function fetchBySlug(string $slug): ?PageModel
    {
        $stmt = $this->pdo->prepare('SELECT * from `pages` WHERE  `slug` = :slug');
        $stmt->bindValue(':slug', $slug);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, PageModel::class);
        $entry = $stmt->fetch();
        if (! empty($entry)) {
            return $entry;
        } else {
            return null;
        }

    }

    public function getSlugExists(string $slug): bool
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) AS `count` FROM `pages` WHERE `slug` = :slug');
        $stmt->bindValue(':slug', $slug);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result['count'] >= 1);
    }

    public function create(string $title, string $slug, string $content)
    {
        $stmt = $this->pdo->prepare('INSERT INTO `pages` (`title`, `slug`, `content`)
            VALUES(:title, :slug, :content)');
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':slug', $slug);
        $stmt->bindValue(':content', $content);
        $stmt->execute();
    }

    public function delete(int $id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM `pages` WHERE `id` = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function fetchByID(int $id): ?PageModel
    {
        $stmt = $this->pdo->prepare('SELECT * from `pages` WHERE  `id` = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, PageModel::class);
        $entry = $stmt->fetch();
        if (! empty($entry)) {
            return $entry;
        } else {
            return null;
        }

    }

    public function editPageTitleAndContent(int $id, string $title, string $content)
    {
        $stmt = $this->pdo->prepare("UPDATE `pages` SET `title` = :title,  `content` = :content WHERE `id` = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':content', $content);
        $stmt->execute();

    }

}
