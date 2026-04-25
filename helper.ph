$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove base path dynamically
if ($basePath && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

$uri = trim($uri, '/');
$segments = $uri ? explode('/', $uri) : [];



adminOwn!@#2026
admin@gmail.com

rasheed@gmail.com
RasheeDev!@#22




  <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tags</label>
                                <div class="col-sm-12 col-md-7">
                                    <select name="tags[]" id="tag-select" class="form-control select2" multiple="" required <?php if (empty($tags)): ?> disabled <?php else: ?> required <?php endif?>>
                                        <option value="">Select tags</option>
                                        <?php if (! empty($tags)): ?>
                                            <?php foreach ($tags as $tag): ?>
                                                <option value="<?php echo e($tag['id']); ?>" <?php echo(isset($_POST['tags']) && in_array($tag['id'], $_POST['tags']) ? 'selected' : ''); ?>>
                                                    <?php echo e($tag['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="0" disabled>No tags available</option>
                                        <?php endif; ?>
                                    </select>
                                    <?php if (empty($tags)): ?>
                                        <small class="text-danger">No tags found. Please <a href="create-tag.php">create a tag</a> first.</small>
                                    <?php endif; ?>
                                </div>
                            </div>


























                            
                              <div class="table-responsive">
                                  <table class="table table-striped">
                                      <tr>
                                          <th class="pt-2">
                                              <div class="custom-checkbox custom-checkbox-table custom-control">
                                                  <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad"
                                                        class="custom-control-input" id="checkbox-all">
                                                  <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                              </div>
                                          </th>
                                          <th>Author</th>
                                          <th>Title</th>
                                          <th>Category</th>
                                          <th>Created At</th>
                                          <th>Published At</th>
                                          <th>Status</th>
                                          <th>Thumbnail</th>
                                      </tr>

                                      <?php foreach ($allPosts as $post): ?>
                                          <tr>
                                              <td>
                                                  <div class="custom-checkbox custom-control">
                                                      <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input"
                                                            id="checkbox-<?php echo $post['post_id']; ?>">
                                                      <label for="checkbox-<?php echo $post['post_id']; ?>" class="custom-control-label">&nbsp;</label>
                                                  </div>
                                              </td>
                                              <td>
                                                      <span class="d-inline-block ml-1"><?php echo $post['author_name']; ?></span>
                                              </td>

                                              <td><?php echo $post['title']; ?>
                                                <div class="table-links">
                                                    <a href="#">View</a>
                                                    <div class="bullet"></div>
                                                    <a href="index.php?<?php echo http_build_query(['route' => 'admin/pages', 'page' => 'edit', 'post_id' => $post['post_id']]); ?>">Edit</a>
                                                    <div class="bullet"></div>
                                                    <a href="#" class="text-danger">Trash</a>
                                                </div>

                                             </td>
                                              <td><a href="#"><?php echo $post['category_name']; ?></a></td>
                                              <td><?php echo $post['created_at']; ?></td>
                                              <td><?php echo $post['published_at']; ?></td>
                                              <td>
                                                  <div class="badge badge-<?php echo $post['status'] == 'published' ? 'success' : 'warning'; ?>">
                                                      <?php echo ucfirst($post['status']); ?>
                                                  </div>
                                              </td>
                                              <td><a href="#"><img alt="image" src="<?php echo $post['thumbnail']; ?>"
                                                                  class="rounded-circle" width="35" title=""></a></td>
                                          </tr>
                                      <?php endforeach; ?>
                                  </table>
                              </div>







header('Location: ' . base_url('admin/auth/login'));






$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove base path dynamically
if ($basePath && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

$uri = trim($uri, '/');
$segments = $uri ? explode('/', $uri) : [];










































<?php
require __DIR__ . '/inc/all.inc.php';

// Create the container instance
$container = new \BlogApp\Support\Container;

// ============================================  Containers ============================================ //

// Bind PDO (database connection)
$container->bind('pdo', function () {
    return require __DIR__ . '/inc/db-connect.inc.php';
});

// Bind Repositories
$container->bind('pagesRepository', function () use ($container) {
    $pdo = $container->get('pdo');
    return new \BlogApp\Repository\PagesRepository($pdo);
});

$container->bind('authPagesRepository', function () use ($container) {
    $pdo = $container->get('pdo');
    return new \BlogApp\Repository\Auth\AuthPagesRepository($pdo);
});

$container->bind('adminPagesRepository', function () use ($container) {
    $pdo = $container->get('pdo');
    return new \BlogApp\Repository\Admin\AdminPagesRepository($pdo);
});

// Controllers Bind with automatic dependency resolution
$container->bind('pagesController', function () use ($container) {
    return new \BlogApp\Frontend\Controller\PagesController(
        $container->get('pagesRepository'),
        $container->get('sessionController')

    );
});


whri kixu gghw ginh



$container->bind('notFoundFrontendController', function () use ($container) {
    return new \BlogApp\Frontend\Controller\NotFoundFrontendController(
        $container->get('pagesRepository'),
        $container->get('sessionController')

    );
});

$container->bind('authPagesController', function () use ($container) {
    return new \BlogApp\Admin\Controller\AuthPagesController(
        $container->get('authPagesRepository'),
        $container->get('sessionController')

    );
});

$container->bind('sessionController', function () {
    return new \BlogApp\Admin\Controller\SessionController();
});

$container->bind('dashboardController', function () use ($container) {
    return new \BlogApp\Admin\Controller\pagesController\DashboardController(
        $container->get('adminPagesRepository'),
        $container->get('sessionController')
    );
});

// ============================================  ROUTES ============================================ //



// $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// $uri = trim($uri, '/');
// $segments = explode('/', $uri);

// var_dump($uri);
// // var_dump($segments);
// die();

// // Default route
// if ($uri === '' || $segments[0] === 'pages') {
//     $page = $segments[1] ?? 'index';
//     $controller = $container->get('pagesController');
//     $controller->showPage($page);
// }

// // ADMIN ROUTES
// elseif ($segments[0] === 'admin') {

//     // /admin/dashboard
//     if (($segments[1] ?? '') === 'dashboard') {
//         $controller = $container->get('dashboardController');
//         $page = $segments[2] ?? 'index';
//         $controller->renderDashboardPages($page);
//     }

//     // /admin/auth/*
//     elseif (($segments[1] ?? '') === 'auth') {
//         $controller = $container->get('authPagesController');
//         $page = $segments[2] ?? 'login';
//         $controller->renderAuthScreens($page);
//     }

//     else {
//         $container->get('notFoundFrontendController')->error404();
//     }
// }

// else {
//     $container->get('notFoundFrontendController')->error404();
// }






// functions.php
define('BASE_PATH', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));

function url($path = '')
{
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    return ($base ? $base : '') . '/' . ltrim($path, '/');
}

$basePath = '/app/blog';

var_dump($_SERVER['SCRIPT_NAME']);
die();

// main index.php file
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

$uri = trim($uri, '/');
$segments = $uri ? explode('/', $uri) : [];

// DEFAULT (HOME)
if (empty($segments)) {
    $controller = $container->get('pagesController');
    $controller->showPage('index');
}

// FRONTEND PAGES
elseif ($segments[0] !== 'admin') {
    $page = $segments[0] ?? 'index';
    $controller = $container->get('pagesController');
    $controller->showPage($page);
}

// ADMIN ROUTES
elseif ($segments[0] === 'admin') {

    // /admin/auth/*
    if (($segments[1] ?? '') === 'auth') {
        $controller = $container->get('authPagesController');
        $page = $segments[2] ?? 'login';
        $controller->renderAuthScreens($page);
    }

    // /admin/dashboard
    elseif (($segments[1] ?? '') === 'dashboard') {
        $controller = $container->get('dashboardController');
        $page = $segments[2] ?? 'index';
        $controller->renderDashboardPages($page);
    }

    // /admin/posts/*
    elseif (($segments[1] ?? '') === 'posts') {
        $controller = $container->get('postsController');

        $action = $segments[2] ?? 'index';
        $id = $segments[3] ?? null;

        switch ($action) {
            case 'create':
                $controller->create();
                break;

            case 'edit':
                $controller->edit($id);
                break;

            case 'delete':
                $controller->delete($id);
                break;

            default:
                $controller->index();
        }
    }

    else {
        $container->get('notFoundAdminController')->error404();
    }
}

// FALLBACK 404
else {
    $container->get('notFoundFrontendController')->error404();
}




























// $route = @(string) ($_GET['route'] ?? 'pages');

// if ($route === 'pages') {
//     $page           = @(string) ($_GET['page'] ?? 'index');
//     $pageController = $container->get('pagesController');
//     $pageController->showPage($page);

// } else if ($route === 'admin/pages') {
//     $page                = @(string) ($_GET['page'] ?? 'dashboard');
//     $dashboardController = $container->get('dashboardController');
//     $dashboardController->renderDashboardPages($page);

// } else if ($route === 'admin/auth') {
//     $page                = @(string) ($_GET['page'] ?? 'login');
//     $authPagesController = $container->get('authPagesController');
//     $authPagesController->renderAuthScreens($page);

// } else {
//     $notFoundController = $container->get('notFoundFrontendController');
//     $notFoundController->error404();
// }












 <div class="collapse navbar-collapse" id="navbarResponsive">
              <ul class="navbar-nav ml-auto">
            <?php if (! empty($_GET['page'])):
                    $currentPage = $_GET['page'];
            endif; ?>
            <?php if (! empty($navigation)): ?>
                <!-- Loop through the navigation items -->
                <?php foreach ($navigation as $pageNav): ?>
                  <li class="nav-item <?php echo($pageNav->slug == $currentPage ? 'active' : ''); ?>">
                    <a class="nav-link" href="index.php?<?php echo http_build_query(['page' => $pageNav->slug]) ?>">
                      <?php echo $pageNav->title ?>
                      <?php if ($pageNav->slug == 'index'): ?>
                        <span class="sr-only">(current)</span>
                      <?php endif; ?>
                    </a>
                  </li>
                <?php endforeach; ?>
            <?php else: ?>
              <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                  <a class="nav-link" href="index.php?<?php echo http_build_query(['route' => 'pages', 'page' => $pageNav->slug]) ?>">Create menu</a>
                </li>
            <?php endif; ?>




              <?php if (! empty($isLoggedIn) && ! empty($isUserIdSession)): ?>
                 <li class="nav-item">
                      <a class="nav-link" href="index.php?<?php echo http_build_query(['route' => 'admin/pages', 'page' => 'dashbaord']) ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="index.php?<?php echo http_build_query(['route' => 'admin/auth', 'page' => 'logout']) ?>">Logout</a>
                </li>
                <?php else: ?>
                    <li class="nav-item">
                      <a class="nav-link" href="index.php?<?php echo http_build_query(['route' => 'admin/auth', 'page' => 'login']) ?>">Login</a>
                    </li>
                <?php endif?>
              </ul>
          </div>





































































          I needed to do some proper restructuring of my routing structure for best practice and scalability.


Looking at what i have now, i wants to refractor the to the following


frontend Pages routes
  / -  home
  /about -  About
  /contact -  Contact



Auth
   /admin/auth/login
    /admin/auth/signup


Admin pages

/admin/dashboard - to main dashboard page

  /admin/pages
/admin/pages/create
/admin/pages/edit
/admin/pages/delete


Admin posts

  /admin/posts
/admin/post/create
/admin/post/edit
/admin/post/delete


Now for better management of session handling and likes, i wants to group each phase to  a render function just as the sample used below. But provided a better approach to fit the exisiting strucuture