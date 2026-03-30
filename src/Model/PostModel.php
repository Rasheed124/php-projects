<?php
namespace BlogApp\Model;

class PostModel
{
    public int $id;
    public int $user_id;
    public ?int $category_id = null;
    public string $title;
    public string $slug;
    public string $body;
    public string $status        = 'draft';
    public ?string $published_at = null;
    public ?string $created_at   = null;
    public ?string $updated_at   = null;
    public ?string $thumbnail    = null;

}
