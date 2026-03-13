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

    public function edit()
    {

        $pages = $this->pagesRespository->get();
        // $id    = @(int) ($_GET['id'] ?? 0);
        $slug = @(string) ($_GET['slug'] ?? '');

        $slugExists = $this->pagesRespository->getSlugExists($slug);

        if (! empty($slugExists)) {
            $editPage = $this->pagesRespository->fetchBySlug($slug);
            // header("Location: index.php?route=admin/pages");
            // return;

            // var_dump($editPage);

            $this->render('pages/edit', [
                'editpage' => $editPage,
            ]);

        }

        // $editedtitle = @(string) ($_POST['title'] ?? '');
        // $editedSlug    = @(string) ($_POST['slug'] ?? '');
        // $editedContent = @(string) ($_POST['content'] ?? '');

        // if (empty($slugExists)) {
        //     $this->pagesRespository->create($title, $slug, $content);
        //     header("Location: index.php?route=admin/pages");
        //     return;
        // }

        //  header("Location : index.php?route=admin/pages");

        // var_dump($id);

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
