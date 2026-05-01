<?php
namespace App\Model;

class PageModel
{
    public int $id;
    public string $title;
    public string $slug;
    public string $content;
    public ?string $thumbnail;
    public int $user_id;
    public string $created_at;
    public ?string $updated_at;

    public string $status;
    public int $show_in_menu;

}
