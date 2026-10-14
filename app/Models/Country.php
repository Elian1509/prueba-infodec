<?php

namespace App\Models;

use App\Core\Database;

/**
 * Consultas sobre la tabla `country`.
 * Cada país se identifica por su código de 3 letras (Code), por ejemplo "COL".
 */
class Country
{
    /**
     * Todos los países para el selector, ordenados por continente y nombre.
     */
    public static function all(): array
    {
        $sql = 'SELECT Code, Name, Continent
                FROM country
                ORDER BY Continent, Name';

        return Database::getConnection()->query($sql)->fetchAll();
    }

    /**
     * Datos de un país con su capital e idiomas oficiales.
     * Devuelve null si el código no existe.
     */
    public static function find(string $code): ?array
    {
        $db = Database::getConnection();

        // LEFT JOIN para no perder países sin capital (ej. la Antártida)
        $stmt = $db->prepare(
            'SELECT co.Code, co.Name, co.Continent, co.Region, co.Population,
                    ci.Name AS CapitalName
             FROM country co
             LEFT JOIN city ci ON ci.ID = co.Capital
             WHERE co.Code = :code'
        );
        $stmt->execute(['code' => $code]);
        $country = $stmt->fetch();

        if ($country === false) {
            return null;
        }

        $stmt = $db->prepare(
            "SELECT Language
             FROM countrylanguage
             WHERE CountryCode = :code AND IsOfficial = 'T'
             ORDER BY Percentage DESC"
        );
        $stmt->execute(['code' => $code]);
        $country['OfficialLanguages'] = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        return $country;
    }
}
