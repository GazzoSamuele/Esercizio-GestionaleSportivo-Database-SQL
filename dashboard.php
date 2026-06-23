<?php

//  ZONA 1 (in alto) — PRENDO i dati
require_once __DIR__ . '/DB/helpers/auth.php';
require_once __DIR__ . '/DB/classes/Calendar.php';

requireQuotaValida();

$user = currentUser();                  // utente loggato (id, name, role)
$partite = Calendar::findAllPartite();  // tutte le partite

?>

<?php include __DIR__ . '/admin/headerDashboard.php'; ?>

    <!-- ============ MAIN ============ -->
    <main class="dash-main">
        <header class="dash-head">
            <h1>Bentornato, <?= htmlspecialchars($user['name']) ?>!</h1>
        </header>

        <!-- ---- stat cards ---- -->
        <div class="dash-stats">
            <div class="dash-stat">
                <span class="dash-stat-icon"><i class="fas fa-calendar-check"></i></span>
                <div>
                    <b><?= count($partite) ?></b>
                    <small>Partite in calendario</small>
                </div>
            </div>
            <div class="dash-stat">
                <span class="dash-stat-icon"><i class="fas fa-id-badge"></i></span>
                <div>
                    <b><?= htmlspecialchars(ucfirst($user['role'])) ?></b>
                    <small>Tipo account</small>
                </div>
            </div>
            <div class="dash-stat">
                <span class="dash-stat-icon"><i class="fas fa-hashtag"></i></span>
                <div>
                    <b>#<?= (int) $user['id'] ?></b>
                    <small>User ID</small>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>   

<script src="/Gestionale-Hockey/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>

</body>
</html>
