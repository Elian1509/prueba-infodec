<?php
/**
 * Pantalla principal: selector de país, datos del país, rankings y tabla de ciudades.
 * Recibe del controlador: $countries, $selected, $country, $error
 * y, si se eligió un país: $cities, $mostPopulated, $leastPopulated
 */

// Agrupamos los países por continente para mostrarlos en grupos dentro del selector
$byContinent = [];
foreach ($countries as $c) {
    $byContinent[$c['Continent']][] = $c;
}
?>

<form class="card search" method="get" action="">
    <label for="country">País</label>
    <div class="search__row">
        <select id="country" name="country" required>
            <option value="">-- Selecciona un país --</option>
            <?php foreach ($byContinent as $continent => $list): ?>
                <optgroup label="<?= e($continent) ?>">
                    <?php foreach ($list as $c): ?>
                        <option value="<?= e($c['Code']) ?>" <?= $c['Code'] === $selected ? 'selected' : '' ?>>
                            <?= e($c['Name']) ?>
                        </option>
                    <?php endforeach; ?>
                </optgroup>
            <?php endforeach; ?>
        </select>
        <button type="submit">Consultar</button>
    </div>
</form>

<?php if ($error): ?>
    <div class="alert"><?= e($error) ?></div>
<?php endif; ?>

<?php if ($country): ?>
    <section class="card country">
        <h2><?= e($country['Name']) ?> <small>(<?= e($country['Code']) ?>)</small></h2>
        <dl class="country__facts">
            <div><dt>Continente</dt><dd><?= e($country['Continent']) ?> · <?= e($country['Region']) ?></dd></div>
            <div><dt>Capital</dt><dd><?= e($country['CapitalName'] ?? 'Sin registro') ?></dd></div>
            <div><dt>Población del país</dt><dd><?= num($country['Population']) ?></dd></div>
            <div><dt>Idiomas oficiales</dt><dd><?= e(implode(', ', $country['OfficialLanguages']) ?: 'Sin registro') ?></dd></div>
            <div><dt>Ciudades registradas</dt><dd><?= count($cities) ?></dd></div>
        </dl>
    </section>

    <?php if (count($cities) === 0): ?>
        <div class="alert">La base de datos no tiene ciudades registradas para este país.</div>
    <?php else: ?>
        <?php $max = $cities[0]['Population'] ?: 1; // La ciudad más poblada es el 100 % de las barras ?>

        <div class="rankings">
            <section class="card">
                <h3>Top 10 · más pobladas</h3>
                <ol class="ranking">
                    <?php foreach ($mostPopulated as $city): ?>
                        <li>
                            <span class="ranking__name"><?= e($city['Name']) ?></span>
                            <span class="ranking__pop"><?= num($city['Population']) ?></span>
                            <span class="bar"><span style="width: <?= round($city['Population'] / $max * 100, 1) ?>%"></span></span>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </section>

            <section class="card">
                <h3>Top 10 · menos pobladas</h3>
                <ol class="ranking">
                    <?php foreach ($leastPopulated as $city): ?>
                        <li>
                            <span class="ranking__name"><?= e($city['Name']) ?></span>
                            <span class="ranking__pop"><?= num($city['Population']) ?></span>
                            <span class="bar bar--low"><span style="width: <?= round($city['Population'] / $max * 100, 1) ?>%"></span></span>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </section>
        </div>

        <?php if (count($cities) < 20): ?>
            <p class="note">Este país tiene menos de 20 ciudades registradas, por eso algunas aparecen en ambos rankings.</p>
        <?php endif; ?>

        <section class="card">
            <div class="table-header">
                <h3>Todas las ciudades (<?= count($cities) ?>)</h3>
                <input type="search" id="filter" placeholder="Filtrar por ciudad o distrito…" aria-label="Filtrar ciudades">
            </div>
            <div class="table-wrap">
                <table id="cities-table">
                    <thead>
                        <tr><th>#</th><th>Ciudad</th><th>Distrito</th><th class="num">Población</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cities as $i => $city): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= e($city['Name']) ?></td>
                                <td><?= e($city['District']) ?></td>
                                <td class="num"><?= num($city['Population']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>
