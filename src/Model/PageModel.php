<?php
namespace BlogApp\Model;

class PageModel
{
    public ?int $id = null;
    public int $author_id;
    public string $title;
    public string $slug;
    public string $body;
    public string $status        = 'draft';
    public ?string $published_at = null;
    public ?string $created_at   = null;
    public ?string $updated_at   = null;

}
