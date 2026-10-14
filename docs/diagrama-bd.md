# Diagrama de la base de datos `world`

GitHub dibuja este diagrama automáticamente (Mermaid).

```mermaid
erDiagram
    COUNTRY ||--o{ CITY : "tiene"
    COUNTRY ||--o{ COUNTRYLANGUAGE : "habla"
    COUNTRY |o--o| CITY : "capital"

    COUNTRY {
        char(3) Code PK "ISO 3166-1 alfa-3, ej. COL"
        char(52) Name
        enum Continent
        char(26) Region
        decimal SurfaceArea
        smallint IndepYear
        int Population
        decimal LifeExpectancy
        decimal GNP
        decimal GNPOld
        char(45) LocalName
        char(45) GovernmentForm
        char(60) HeadOfState
        int Capital FK "-> city.ID"
        char(2) Code2
    }

    CITY {
        int ID PK "AUTO_INCREMENT"
        char(35) Name
        char(3) CountryCode FK "-> country.Code"
        char(20) District
        int Population
    }

    COUNTRYLANGUAGE {
        char(3) CountryCode PK, FK "-> country.Code"
        char(30) Language PK
        enum IsOfficial "T / F"
        decimal Percentage
    }
```

## Relaciones

| Relación | Tipo | Columnas | Explicación |
|---|---|---|---|
| country → city | 1 : N | `city.CountryCode` → `country.Code` | Un país tiene muchas ciudades; cada ciudad pertenece a un solo país. |
| country → countrylanguage | 1 : N | `countrylanguage.CountryCode` → `country.Code` | Un país habla varios idiomas, con su porcentaje y si es oficial. |
| country → city (capital) | 1 : 0..1 | `country.Capital` → `city.ID` | Referencia a la ciudad capital. Puede ser `NULL` (p. ej. Antártida). |

`countrylanguage` usa una **clave primaria compuesta** (`CountryCode`, `Language`): el mismo
idioma no puede repetirse para un mismo país.

> En el script oficial `world.sql` la columna `country.Capital` **no** tiene una restricción
> FOREIGN KEY declarada; la relación es lógica (se usa con un JOIN). Las de `city` y
> `countrylanguage` sí están declaradas como FOREIGN KEY.
