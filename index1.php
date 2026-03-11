<?php
header('Content-Type: text/plain');

class PostRepository
{
    public function __construct(
        private string $a,
        private string $b,
    ) {}
}

class PostController
{
    public function __construct(private PostRepository $postRepository)
    {}
}

class Container
{

    private array $instances = [];
    private array $recipes   = [];

    public function bind(string $what, \Closure $recipe)
    {
        $this->recipes[$what] = $recipe;
    }

    public function get($what)
    {
        if (empty($this->instances[$what])) {
            if (empty($this->recipes[$what])) {
                echo "Could not build {$what}.";
                die();
            }
            $this->instances[$what] = $this->recipes[$what]();
        }
        return $this->instances[$what];

    }

}

$container = new Container();
$container->bind('postRepository', function () {
    return new PostRepository('A', 'B');
});

$container->bind('postController', function () use ($container) {
    $postRepository = $container->get('postRepository');
    return new PostController($postRepository);
});

$postRepository  = $container->get('postRepository');
$postRepository2 = $container->get('postRepository');
var_dump($postRepository);
var_dump($postRepository2);

$postController = $container->get('postController');
var_dump($postController);
$postController2 = $container->get('postController');
var_dump($postController2);
