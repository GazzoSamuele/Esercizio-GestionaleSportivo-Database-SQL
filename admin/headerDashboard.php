<?php
require_once __DIR__ . "/helpersDashboard.php";

$pagina        = basename($_SERVER['PHP_SELF']);
$titoloPagina  = $titoloPagina ?? 'Dashboard';
$iniziale      = strtoupper(substr($user['name'] ?? '', 0, 1));

// voci della sidebar in base al ruolo: file => [percorso, icona, etichetta]
if ($user['role'] === 'admin') {
    $gruppoMenu = 'Gestione';
    $vociMenu = [
        'users.php'                        => ['/admin/users.php', 'fa-users', 'Utenti registrati'],
        'ordiniProdottiRicevuti.php'       => ['/admin/ordiniProdottiRicevuti.php', 'fa-receipt', 'Prodotti e ordini'],
        'gestioneNewsAndComunicazioni.php' => ['/admin/gestioneNewsAndComunicazioni.php', 'fa-newspaper', 'News e comunicazioni'],
        'gestioneImpegni.php'              => ['/admin/gestioneImpegni.php', 'fa-calendar-days', 'Gestione impegni'],
    ];
} else {
    $gruppoMenu = 'La mia area';
    $vociMenu = [
        'calendarioPartite.php'          => ['/client/calendarioPartite.php', 'fa-calendar-days', 'Calendario partite'],
        'ordiniEffettuati.php'           => ['/client/ordiniEffettuati.php', 'fa-receipt', 'I miei ordini'],
        'mostraNewsAndComunicazioni.php' => ['/client/mostraNewsAndComunicazioni.php', 'fa-newspaper', 'News e comunicazioni'],
        'mostraImpegni.php'              => ['/client/mostraImpegni.php', 'fa-calendar-check', 'I miei impegni'],
    ];
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard-style.css">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_URL ?>/favicons/apple-touch-icon-72x72.png">
    <title><?= htmlspecialchars($titoloPagina) ?> | Gestionale Società Sportiva</title>
</head>

<body>
    <a class="sr-only" href="#contenuto">Vai al contenuto</a>

    <!-- barra visibile solo su mobile -->
    <div class="dash-topbar">
        <a class="logo" href="<?= BASE_URL ?>/index.php" aria-label="Torna al sito">LOGO</a>
        <button class="dash-toggle" type="button" aria-controls="dash-sidebar" aria-expanded="false" aria-label="Menu area riservata">
            <i class="fa-solid fa-bars" aria-hidden="true"></i>
        </button>
    </div>

    <div class="dash-shell">
        <aside class="dash-sidebar" id="dash-sidebar">
            <div class="dash-brand">
                <a class="logo" href="<?= BASE_URL ?>/index.php" aria-label="Torna al sito">LOGO</a>
                <span class="dash-brand__label">Gestionale</span>
            </div>

            <nav class="dash-nav" aria-label="Menu area riservata">
                <span class="dash-nav__group">Generale</span>
                <a href="<?= BASE_URL ?>/dashboard.php" class="dash-link <?= $pagina === 'dashboard.php' ? 'is-active' : '' ?>"
                    <?= $pagina === 'dashboard.php' ? 'aria-current="page"' : '' ?>>
                    <i class="fa-solid fa-table-cells-large" aria-hidden="true"></i><span>Dashboard</span>
                </a>

                <span class="dash-nav__group"><?= $gruppoMenu ?></span>
                <?php foreach ($vociMenu as $file => [$percorso, $icona, $etichetta]): ?>
                    <a href="<?= BASE_URL . $percorso ?>" class="dash-link <?= $pagina === $file ? 'is-active' : '' ?>"
                        <?= $pagina === $file ? 'aria-current="page"' : '' ?>>
                        <i class="fa-solid <?= $icona ?>" aria-hidden="true"></i><span><?= $etichetta ?></span>
                    </a>
                <?php endforeach; ?>

                <span class="dash-nav__group">Sito</span>
                <a href="<?= BASE_URL ?>/index.php" class="dash-link">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i><span>Torna al sito</span>
                </a>
            </nav>

            <div class="dash-user">
                <span class="dash-avatar" aria-hidden="true"><?= htmlspecialchars($iniziale) ?></span>
                <span class="dash-user__info">
                    <strong><?= htmlspecialchars($user['name']) ?></strong>
                    <small><?= $user['role'] === 'admin' ? 'Amministratore' : 'Socio' ?></small>
                </span>
                <a class="dash-user__action" href="<?= BASE_URL ?>/logout.php" aria-label="Esci" title="Esci">
                    <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
                </a>
            </div>
        </aside>

        <main class="dash-main" id="contenuto">