<?php
namespace App\Admin\Controller;

use App\Repository\PagesRespository;

class PagesAdminController extends AbstractAdminController
{

    public function __construct(private PagesRespository $pagesRespository)
    {}

    public function index()
    {

        $pages = $this->pagesRespository->get();

        $this->render('pages/index', [
            'pages' => $pages,
        ]);
    }

    public function create()
    {
        $errors = [];

        if (! empty($_POST)) {
            $title   = @(string) ($_POST['title'] ?? '');
            $slug    = @(string) ($_POST['slug'] ?? '');
            $content = @(string) ($_POST['content'] ?? '');

            $slug = strtolower($slug);
            $slug = str_replace(['/', ' ', '.'], ['-', '-', '-'], $slug);
            $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);

            if (! empty($title) && ! empty($slug) && ! empty($content)) {
                $slugExists = $this->pagesRespository->getSlugExists($slug);
                if (empty($slugExists)) {
                    $this->pagesRespository->create($title, $slug, $content);
                    header("Location: index.php?route=admin/pages");
                    return;
                } else {
                    $errors[] = 'Slug already exists!';
                }
            } else {
                $errors[] = 'Are all fields filled out?';
            }
        }
        $this->render('pages/create', [
            'errors' => $errors,
        ]);
    }

    public function delete()
    {
        $id = @(int) ($_POST['id'] ?? 0);
        if (! empty($id)) {
            $this->pagesRespository->delete($id);
        }
        header("Location: index.php?route=admin/pages");
    }
}
