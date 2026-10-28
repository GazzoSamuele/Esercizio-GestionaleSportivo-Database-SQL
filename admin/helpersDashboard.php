<?php
// ============================================================
//  PICCOLI HELPER PER LE PAGINE DELL'AREA RISERVATA
//  (solo presentazione: nessuna query qui dentro)
// ============================================================

// box con gli errori e il messaggio di successo dei form
function mostraEsito(array $errors, string $success = ''): void
{
    if ($success !== '') {
        echo '<div class="notice notice--ok" role="status"><i class="fa-solid fa-check" aria-hidden="true"></i> '
            . htmlspecialchars($success) . '</div>';
    }
    if (!empty($errors)) {
        echo '<div class="notice notice--error" role="alert"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i><ul>';
        foreach ($errors as $e) {
            echo '<li>' . htmlspecialchars($e) . '</li>';
        }
        echo '</ul></div>';
    }
}

// etichetta colorata per lo stato di un ordine
function pillStato(string $status): string
{
    $mappa = [
        'pending'    => ['In attesa', 'pill--wait'],
        'In attesa'  => ['In attesa', 'pill--wait'],
        'pagato'     => ['Pagato', 'pill--ok'],
        'paid'       => ['Pagato', 'pill--ok'],
        'completed'  => ['Completato', 'pill--ok'],
        'Rimborsato' => ['Rimborsato', 'pill--neutral'],
        'refunded'   => ['Rimborsato', 'pill--neutral'],
        'cancelled'  => ['Annullato', 'pill--neutral'],
    ];
    [$testo, $classe] = $mappa[$status] ?? [ucfirst($status), 'pill--neutral'];
    return '<span class="pill ' . $classe . '">' . htmlspecialchars($testo) . '</span>';
}

// etichetta colorata per il tipo di impegno
function pillImpegno(string $tipo): string
{
    $classi = [
        'allenamento' => 'pill--ok',
        'partita'     => 'pill--dark',
        'coppa'       => 'pill--wait',
    ];
    return '<span class="pill ' . ($classi[$tipo] ?? 'pill--neutral') . '">' . htmlspecialchars(ucfirst($tipo)) . '</span>';
}

// etichetta per news / comunicazione
function pillNews(string $tipo): string
{
    return $tipo === 'notizia'
        ? '<span class="pill pill--wait">Notizia</span>'
        : '<span class="pill pill--ok">Comunicazione</span>';
}

// miniatura (immagine o segnaposto con icona)
function miniatura(?string $src, string $icona = 'fa-image'): string
{
    if (!empty($src)) {
        return '<img class="thumb" src="' . htmlspecialchars($src) . '" alt="">';
    }
    return '<span class="thumb thumb--ph" aria-hidden="true"><i class="fa-solid ' . $icona . '"></i></span>';
}

// data in formato breve italiano (es. 04 ott 2026)
function dataBreve(?string $data, bool $anno = true): string
{
    if (empty($data)) {
        return '—';
    }
    $mesi = ['gen', 'feb', 'mar', 'apr', 'mag', 'giu', 'lug', 'ago', 'set', 'ott', 'nov', 'dic'];
    $ts = strtotime($data);
    return date('d', $ts) . ' ' . $mesi[(int) date('n', $ts) - 1] . ($anno ? ' ' . date('Y', $ts) : '');
}
