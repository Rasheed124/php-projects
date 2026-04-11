<?php
namespace App\Model;

class UserModel
{
    public int $id;
    public string $username;
    public string $email;
    public string $password;
    public ?string $location;
    public ?string $role;
    public ?string $profile_image;
    public ?string $bio;
    
    /**
     * Stored as a JSON string in DB
     * Example: [{"platform": "Instagram", "url": "https://..."}]
     */
    public ?string $social_links; 

    public string $created_at;
    public ?string $updated_at;

    /**
     * Helper to get social links as a PHP array
     */
    public function getSocialLinksArray(): array
    {
        return $this->social_links ? json_decode($this->social_links, true) : [];
    }
}