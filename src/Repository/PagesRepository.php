<?php
namespace BlogApp\Repository;

use BlogApp\Model\PageModel;
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
        $stmt = $this->pdo->prepare('SELECT * from `pages`  ORDER BY `page_id` ASC');
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

}
