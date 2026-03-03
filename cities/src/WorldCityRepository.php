<?php

class WorldCityRepository
{

    public function __construct(private PDO $pdo)
    {
    }

    public function getCityModel(array $entry): WorldCityModel
    {
        return new WorldCityModel(
            $entry['id'],
            $entry['city'],
            $entry['city_ascii'],
            (float) $entry['lat'],
            (float) $entry['lng'],
            $entry['country'],
            $entry['iso2'],
            $entry['iso3'],
            $entry['admin_name'],
            $entry['capital'],
            $entry['population']
        );

    }

    public function fetchByID(int $id): ?WorldCityModel
    {
        $stmt = $this->pdo->prepare('SELECT * FROM `worldcities` WHERE `id` = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $entry = $stmt->fetch(PDO::FETCH_ASSOC);
        if (! empty($entry)) {
            return $this->getCityModel($entry);
        } else {
            return null;
        }

    }

    public function fetch(): array
    {
        $stmt = $this->pdo->prepare('SELECT *
            FROM `worldcities`
            ORDER BY `population`
            DESC LIMIT 10');

        $stmt->execute();

        $models  = [];
        $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($entries as $entry) {
            $models[] = $this->getCityModel($entry);
        }

        return $models;

    }

    public function count(): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) AS `count`
            FROM `worldcities`');
        $stmt->execute();
        $entriesCount = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $entriesCount[0]['count'];

    }

    public function pagination(int $page, int $perPage = 15): array
    {
        // Page 0 or negative pages don't exist => showing page 1 then
        $page = max(1, $page);
        $stmt = $this->pdo->prepare('SELECT *
            FROM `worldcities`
            ORDER BY `population`
            DESC LIMIT :perpage OFFSET :offset');
        $stmt->bindValue(':perpage', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', ($page - 1) * $perPage, PDO::PARAM_INT);
        $stmt->execute();

        $models  = [];
        $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($entries as $entry) {
            $models[] = $this->getCityModel($entry);
        }

        return $models;

    }


    public function update(int $id, array $properties): WorldCityModel {
        $stmt = $this->pdo->prepare('UPDATE `worldcities` 
            SET 
                `city` = :city,
                `city_ascii` = :cityAscii,
                `country` = :country,
                `iso2` = :iso2, 
                `population` = :population
            WHERE `id` = :id');

        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':city', $properties['city']);
        $stmt->bindValue(':cityAscii', $properties['cityAscii']);
        $stmt->bindValue(':country', $properties['country']);
        $stmt->bindValue(':iso2', $properties['iso2']);
        $stmt->bindValue(':population', $properties['population']);
        $stmt->execute();

        return $this->fetchById($id);
    }


}
