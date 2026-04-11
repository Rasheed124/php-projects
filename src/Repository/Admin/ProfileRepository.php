<?php
namespace App\Repository\Admin;

use App\Model\UserModel;
use PDO;

class ProfileRepository
{
    public function __construct(private PDO $pdo)
    {}

    public function fetchById(int $id): ?UserModel
    {
        $stmt = $this->pdo->prepare('SELECT * FROM `users` WHERE `id` = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, \App\Model\UserModel::class);
        return $stmt->fetch() ?: null;
    }

    public function getUser(): ?array
    {
        // 1. Get the ID from the session
        $userId = $_SESSION['user_id'] ?? null;

        if (! $userId) {
            return null;
        }

        // 2. Query the database for fresh data
        // Assuming you have access to PDO within this class or via a repository
        $sql = "SELECT id, username, email, profile_image, role
            FROM users
            WHERE id = :id
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $userId]);

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function getAll(string $status = 'active', int $offset = 0, int $limit = 10): array
    {
        $sql  = "SELECT * FROM users WHERE status = :status LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCount(string $status = 'active'): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE status = :status");
        $stmt->execute(['status' => $status]);
        return (int) $stmt->fetchColumn();
    }

    public function trash(int $id): bool
    {
        // Generate a random generic password so they can't login while trashed
        $tempPass = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
        $stmt     = $this->pdo->prepare("UPDATE users SET status = 'trash', password = :pass WHERE id = :id");
        return $stmt->execute(['pass' => $tempPass, 'id' => $id]);
    }

    public function restore(int $id, string $newGenericPassword): bool
    {
        $hashed = password_hash($newGenericPassword, PASSWORD_BCRYPT);
        $stmt   = $this->pdo->prepare("UPDATE users SET status = 'active', password = :pass WHERE id = :id");
        return $stmt->execute(['pass' => $hashed, 'id' => $id]);
    }

    public function deletePermanently(int $id): bool
    {
        return $this->pdo->prepare("DELETE FROM users WHERE id = :id")->execute(['id' => $id]);
    }

    public function updateProfile(int $id, array $data): bool
    {
        // 1. Base SQL structure (Changed updated_at and column name)
        $sql = "UPDATE `users` SET
            username = :username,
            location = :location,
            bio = :bio,
            social_links = :social_links,
            created_at = NOW()"; // Use updated_at here, not created_at

        // 2. Fix: Check against 'profile_image' column
        if (! empty($data['image'])) {
            $sql .= ", profile_image = :profile_image";
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        // 3. Bind standard values
        $stmt->bindValue(':username', $data['username'], PDO::PARAM_STR);
        $stmt->bindValue(':location', $data['location'], PDO::PARAM_STR);
        $stmt->bindValue(':bio', $data['bio'], PDO::PARAM_STR);
        $stmt->bindValue(':social_links', json_encode($data['social_links']), PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        // 4. Bind image only if it exists
        if (! empty($data['image'])) {
            $stmt->bindValue(':profile_image', $data['image'], PDO::PARAM_STR);
        }

        return $stmt->execute();
    }
}
