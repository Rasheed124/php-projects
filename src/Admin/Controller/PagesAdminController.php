<?php
namespace App\Admin\Controller;

use App\Admin\Support\AuthService;
use App\Repository\PagesRespository;

class PagesAdminController extends AbstractAdminController
{

    public function __construct(
        AuthService $authService,
        private PagesRespository $pagesRespository
    ) {
        parent::__construct($authService);
    }

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

    public function edit()
    {
        $id     = @(int) ($_GET['id'] ?? 0);
        $errors = [];

        if (! empty($_POST)) {
            $title   = @(string) ($_POST['title'] ?? '');
            $content = @(string) ($_POST['content'] ?? '');

            if (! empty($title) && ! empty($content)) {
                $this->pagesRespository->editPageTitleAndContent($id, $title, $content);
                header("Location: index.php?route=admin/pages");
                return;

            } else {
                $errors[] = 'Please make sure you feel all fields';
            }
        }
        $page = $this->pagesRespository->fetchByID($id);

        $this->render('pages/edit', [
            'editpage' => $page,
            'errors'   => $errors,
        ]);

    }
}
