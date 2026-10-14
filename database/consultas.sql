-- =====================================================================
-- PREGUNTA 2 - Consultas sobre la base de datos World
-- Ejecutar en HeidiSQL / DBeaver / phpMyAdmin con la BD `world` seleccionada.
-- =====================================================================
USE world;

-- ---------------------------------------------------------------------
-- 1. Todas las ciudades con una población menor o igual a 100.000 habitantes.
--    Se ordena de mayor a menor población para que sea más fácil de leer.
-- ---------------------------------------------------------------------
SELECT ID, Name, CountryCode, District, Population
FROM city
WHERE Population <= 100000
ORDER BY Population DESC;


-- ---------------------------------------------------------------------
-- 2. Conteo de las ciudades cuyo nombre comienza con la letra "R".
--    LIKE 'R%' = empieza por R y luego cualquier cosa.
-- ---------------------------------------------------------------------
SELECT COUNT(*) AS total_ciudades_con_R
FROM city
WHERE Name LIKE 'R%';


-- ---------------------------------------------------------------------
-- 3. Top 10 de las ciudades más pobladas donde se habla español.
--    city.CountryCode -> countrylanguage.CountryCode indica qué idiomas
--    se hablan en el país de cada ciudad.
--
--    Versión A : países donde se habla español, sea oficial o no.
--    OJO: incluye ciudades de Estados Unidos, porque el español
--    aparece como idioma no oficial.
-- ---------------------------------------------------------------------
SELECT ci.Name        AS ciudad,
       co.Name        AS pais,
       ci.Population  AS poblacion,
       cl.IsOfficial  AS espanol_oficial,
       cl.Percentage  AS porcentaje_hablantes
FROM city ci
JOIN countrylanguage cl ON cl.CountryCode = ci.CountryCode
JOIN country co         ON co.Code        = ci.CountryCode
WHERE cl.Language = 'Spanish'
ORDER BY ci.Population DESC
LIMIT 10;

--    Versión B: solo países donde el español es idioma oficial.
SELECT ci.Name        AS ciudad,
       co.Name        AS pais,
       ci.Population  AS poblacion
FROM city ci
JOIN countrylanguage cl ON cl.CountryCode = ci.CountryCode
JOIN country co         ON co.Code        = ci.CountryCode
WHERE cl.Language = 'Spanish'
  AND cl.IsOfficial = 'T'
ORDER BY ci.Population DESC
LIMIT 10;
