<?php

namespace App\Models;

use App\Core\Database;
use PDO;

/**
 * Consultas sobre la tabla `city`.
 * Cada ciudad pertenece a un país a través de CountryCode.
 */
class City
{
    /**
     * Ciudades de un país ordenadas por población.
     *
     * @param string   $countryCode Código del país, ej. "COL"
     * @param string   $order       'DESC' más pobladas primero, 'ASC' menos pobladas primero
     * @param int|null $limit       Cuántas traer (null = todas)
     */
    public static function byCountry(string $countryCode, string $order = 'DESC', ?int $limit = null): array
    {
        // ORDER BY no acepta parámetros, así que solo dejamos pasar ASC o DESC
        $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

        $sql = "SELECT ID, Name, District, Population
                FROM city
                WHERE CountryCode = :code
                ORDER BY Population {$order}, Name";

        if ($limit !== null) {
            $sql .= ' LIMIT :limit';
        }

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':code', $countryCode);
        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        }
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
