<?php
require_once "pelikula.php";
require_once "PelikulaKudeatzailea.php";

// 1. Pelikulen zerrenda berreskuratu hidden input-etik (explode erabiliz)
$hasierakoPelikulak = [];
if (!empty($_POST['pelikulak_data'])) {
    $lineak = explode(';', $_POST['pelikulak_data']);
    foreach ($lineak as $linea) {
        if (!empty($linea)) {
            $hasierakoPelikulak[] = Pelikula::fromLine($linea);
        }
    }
}

$kudeatzailea = new PelikulaKudeatzailea($hasierakoPelikulak);

// Formularioan sartutako balioak gorde (formularioan berriro erakusteko)
$inputIzena = $_POST['izena'] ?? '';
$inputIsan = $_POST['isan'] ?? '';
$inputUrtea = $_POST['urtea'] ?? '';
$inputPuntuazioa = $_POST['puntuazioa'] ?? '';

// Formularioa bidali bada, logika prozesatu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kudeatzailea->prozesatuFormularioa($inputIzena, $inputIsan, $inputUrtea, $inputPuntuazioa);
}

// Pelikula guztiak testu bakarrean bihurtu hidden input-erako (implode erabiliz)
$lineasTexto = array_map(fn($p) => $p->toLine(), $kudeatzailea->getPelikulak());
$pelikulakString = implode(';', $lineasTexto);
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filmen Kudeatzailea</title>
</head>
<body>

    <h1>Ikusi dituzun filmen zerrenda</h1>

    <!-- GOIALDEA: Zerrenda -->
    <table border="1">
        <thead>
            <tr>
                <th>Filma (Izena)</th>
                <th>ISAN</th>
                <th>Urtea</th>
                <th>Puntuazioa</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($kudeatzailea->getPelikulak())): ?>
                <tr><td colspan="4">Ez dago pelikulatik erregistratuta.</td></tr>
            <?php else: ?>
                <?php foreach ($kudeatzailea->getPelikulak() as $peli): ?>
                    <tr>
                        <td><?= htmlspecialchars($peli->getIzena()) ?></td>
                        <td><?= htmlspecialchars($peli->getIsan()) ?></td>
                        <td><?= $peli->getUrtea() ?></td>
                        <td><?= $peli->getPuntuazioa() ?> / 5</td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Warning / Abisuak -->
    <?php if ($kudeatzailea->getAbisua()): ?>
        <p><strong><?= htmlspecialchars($kudeatzailea->getAbisua()) ?></strong></p>
    <?php endif; ?>

    <!-- Bilaketaren emaitzak (ISAN hutsik badago) -->
    <?php if (!empty($kudeatzailea->getBilaketaEmaitzak())): ?>
        <h3>Bilaketaren emaitzak:</h3>
        <ul>
            <?php foreach ($kudeatzailea->getBilaketaEmaitzak() as $res): ?>
                <li>"<?= htmlspecialchars($res->getIzena()) ?>" <?= $res->getUrtea() ?> urtekoa.</li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <hr>

    <!-- BEHEALDEA: Zure Formularioa -->
    <h2>Film baten datuak sartu / kudeatu</h2>
    <form action="main.php" method="post">  
        <!-- CAMPO OCULTO OBLIGATORIO PARA PERSISTIR LA LISTA -->
        <input type="hidden" name="pelikulak_data" value="<?= htmlspecialchars($pelikulakString) ?>">

        <label for="izena">Sartu izena:</label><br>
        <input type="text" name="izena" id="izena" value="<?= htmlspecialchars($inputIzena) ?>"><br><br>

        <label for="isan">Sartu Isan zenbakia:</label><br>
        <input type="text" name="isan" id="isan" value="<?= htmlspecialchars($inputIsan) ?>"><br><br>

        <label for="urtea">Sartu urtea:</label><br>
        <input type="number" name="urtea" id="urtea" max="2026" min="0" value="<?= htmlspecialchars($inputUrtea) ?>"><br><br>

        <label for="puntuazioa">Sartu puntuazioa:</label><br>
        <select name="puntuazioa" id="puntuazioa">
            <option value="">-- Hautatu --</option>
            <?php for ($i = 0; $i <= 5; $i++): ?>
                <option value="<?= $i ?>" <?= ($inputPuntuazioa !== '' && (int)$inputPuntuazioa === $i) ? 'selected' : '' ?>>
                    <?= $i ?>
                </option>
            <?php endfor; ?>
        </select><br><br>

        <button type="submit">Bidali</button>
    </form>

</body>
</html>