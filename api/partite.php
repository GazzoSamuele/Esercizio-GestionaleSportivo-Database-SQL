<?php require __DIR__ . '/../DB/classes/Db.php';

header('Content-Type: application/json');

$categoria = trim($_GET['categoria'] ?? '');

if ($categoria === '') {
    $pdo = Db::connect();
    $stmt = $pdo->query('SELECT id, squadra_casa, squadra_ospite, data, categoria, tipo, gol_casa, gol_ospite FROM calendar ORDER BY data DESC');
} else {
    $pdo = Db::connect();
    $stmt = $pdo->prepare('SELECT id, squadra_casa, squadra_ospite, data, categoria, tipo, gol_casa, gol_ospite FROM calendar WHERE categoria = :categoria ORDER BY data DESC');
    $stmt->execute([
        ':categoria' => $categoria,
    ]);
}
$partite = $stmt->fetchAll();
echo json_encode($partite);
