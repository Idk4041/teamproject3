<?php
$_DBNAME = 'planten';
$conn = require_once __DIR__ . '/partials/dbconnection.php';

$naam  = $_GET['naam']   ?? '';
$kleur = $_GET['kleur']  ?? '';
$pagina = $_GET['pagina'] ?? 1;

$perPagina = 24;
$offset = ($pagina - 1) * $perPagina;

$sql = "SELECT naam, verkoopprijs_eur, kleur, overview_image, standplaats_id FROM planten_met_afbeeldingen";

if ($naam != '' && $kleur != '') {
    $sql .= " WHERE naam LIKE '%" . $conn->real_escape_string($naam) . "%' AND kleur = '" . $conn->real_escape_string($kleur) . "'";
} elseif ($naam != '') {
    $sql .= " WHERE naam LIKE '%" . $conn->real_escape_string($naam) . "%'";
} elseif ($kleur != '') {
    $sql .= " WHERE kleur = '" . $conn->real_escape_string($kleur) . "'";
}

$sql .= " ORDER BY naam LIMIT $perPagina OFFSET $offset";

$result  = $conn->query($sql);
$planten = $result->fetch_all(MYSQLI_ASSOC);

$totaalResult = $conn->query("SELECT COUNT(*) FROM planten_met_afbeeldingen WHERE voorraad > 0");
$totaal = $totaalResult->fetch_row()[0];
$totaalPaginas = ceil($totaal / $perPagina);

$kleuren = $conn->query("SELECT DISTINCT kleur FROM planten_met_afbeeldingen ORDER BY kleur");
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Nora's Flora – Assortiment</title>
</head>
<body>
  <header>
    <div id="divLogo">
      <img id="imgLogo" src="images/Nora'sFloraLogo.png" alt="Logo">
    </div>
    <div id="divNavigatie">
      <a href="index.html">| Home |</a>
      <a href="assortiment.php">| Assortiment |</a>
      <a href="contact.html">| Contact |</a>
    </div>
  </header>

  <form method="GET" action="assortiment.php" id="filterBar">
    <input type="text" name="naam" value="<?= $naam ?>" placeholder="Zoek op naam…">
    <select name="kleur">
      <option value="">Alle kleuren</option>
      <?php while ($r = $kleuren->fetch_assoc()): ?>
        <option value="<?= $r['kleur'] ?>" <?= $kleur == $r['kleur'] ? 'selected' : '' ?>>
          <?= $r['kleur'] ?>
        </option>
      <?php endwhile; ?>
    </select>
    <button type="submit">Zoeken</button>
    <a href="assortiment.php">Reset</a>
  </form>

  <p id="resultaatTeller"><?= $totaal ?> planten gevonden</p>

  <main>
    <div id="assortimentContainer">
      <div id="producten">
        <?php foreach ($planten as $p): ?>
          <div class="plant-card">
            <img loading="lazy" src="plant_images/<?= $p['overview_image'] ?>" alt="<?= $p['naam'] ?>" onerror="this.style.display='none'">
            <h3><?= $p['naam'] ?></h3>
            <p><?= $p['kleur'] ?></p>
            <p><?= $p['standplaats_id'] ?></p>
            <p><strong>€<?= number_format($p['verkoopprijs_eur'], 2, ',', '.') ?></strong></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </main>

  <nav id="paginering">
    <?php if ($pagina > 1): ?>
      <a href="assortiment.php?pagina=<?= $pagina - 1 ?>&naam=<?= $naam ?>&kleur=<?= $kleur ?>">‹ Vorige</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totaalPaginas; $i++): ?>
      <?php if ($i == $pagina): ?>
        <span class="actief"><?= $i ?></span>
      <?php else: ?>
        <a href="assortiment.php?pagina=<?= $i ?>&naam=<?= $naam ?>&kleur=<?= $kleur ?>"><?= $i ?></a>
      <?php endif; ?>
    <?php endfor; ?>

    <?php if ($pagina < $totaalPaginas): ?>
      <a href="assortiment.php?pagina=<?= $pagina + 1 ?>&naam=<?= $naam ?>&kleur=<?= $kleur ?>">Volgende ›</a>
    <?php endif; ?>
  </nav>

  <footer id="footerContainer">
    <div id="footerLinks">
      <p>Email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: contact@noraflora.com</p>
      <p>Telefoon&nbsp;&nbsp;: 06 12345678</p>
      <p>Adres&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Zwolle, pannenkoekendijk 420 B</p>
    </div>
    <div id="footerRechts">
      <h2>Openingstijden</h2>
      <p>Ma - Vr : 12:00 - 17:00</p>
      <p>Zaterdag : 10:00 - 17:00</p>
    </div>
  </footer>
</body>
</html>