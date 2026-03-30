<?php
namespace BlogApp\Model;

class CategoryModel
{
    public int $id;
    public string $name;
    public ?string $slug = null;
    public ?string $description = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;

}
