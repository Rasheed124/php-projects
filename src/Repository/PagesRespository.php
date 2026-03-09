<?php
namespace App\Repository;

use App\Model\PageModel;
use PDO;

class PagesRespository
{
    public function __construct(private PDO $pageModel)
    {}

    public function fetchNavigation(): array
    {
        $stmt = $this->pageModel->prepare('SELECT * from `pages`  ORDER BY `id` ASC');
        $stmt->execute();
        $entry = $stmt->fetchAll(PDO::FETCH_CLASS, PageModel::class);
        return $entry;

    }
    public function fetchBySlug(string $slug): ?PageModel
    {
        $stmt = $this->pageModel->prepare('SELECT * from `pages` WHERE  `slug` = :slug');
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

}
