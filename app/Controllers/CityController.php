<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\City;
use App\Models\Country;

/**
 * Controla la pantalla principal: selector de país y sus ciudades.
 */
class CityController extends Controller
{
    /** Tamaño de los rankings (escala de 10 solicitada). */
    private const RANKING_SIZE = 10;

    /**
     * Sin país muestra solo el selector; con ?country=XXX muestra también sus ciudades.
     */
    public function index(): void
    {
        $countries = Country::all();
        $code = $this->readCountryCode();

        $data = [
            'countries' => $countries,
            'selected'  => $code,
            'country'   => null,
            'error'     => null,
        ];

        if ($code !== null) {
            $country = Country::find($code);

            if ($country === null) {
                $data['error'] = 'El país seleccionado no existe. Elige uno de la lista.';
            } else {
                $data['country']   = $country;
                $data['cities']    = City::byCountry($code);
                $data['mostPopulated']  = City::byCountry($code, 'DESC', self::RANKING_SIZE);
                $data['leastPopulated'] = City::byCountry($code, 'ASC', self::RANKING_SIZE);
            }
        } elseif (isset($_GET['country'])) {
            // El país es obligatorio: llegó vacío o con un formato inválido
            $data['error'] = 'Debes seleccionar un país válido.';
        }

        $this->view('home', $data);
    }

    /**
     * Los mismos datos en JSON: /?route=api/cities&country=COL (para probar con Postman).
     */
    public function apiCities(): void
    {
        $code = $this->readCountryCode();

        if ($code === null) {
            $this->json(['error' => 'El parámetro "country" es obligatorio (código de 3 letras, ej. COL).'], 400);
            return;
        }

        $country = Country::find($code);
        if ($country === null) {
            $this->json(['error' => "No existe un país con código {$code}."], 404);
            return;
        }

        $this->json([
            'country'         => $country,
            'most_populated'  => City::byCountry($code, 'DESC', self::RANKING_SIZE),
            'least_populated' => City::byCountry($code, 'ASC', self::RANKING_SIZE),
            'cities'          => City::byCountry($code),
        ]);
    }

    /**
     * Lee ?country= y lo valida: debe ser un código de exactamente 3 letras.
     * No confiamos en lo que manda el navegador, aunque venga del selector.
     * Devuelve el código en mayúsculas, o null si no es válido.
     */
    private function readCountryCode(): ?string
    {
        $code = strtoupper(trim($_GET['country'] ?? ''));

        return preg_match('/^[A-Z]{3}$/', $code) ? $code : null;
    }
}
