// ============================================================
//  SCRIPT CONDIVISI — sito pubblico e area riservata
// ============================================================

// ---------- TAB A SEGMENTI ----------
// Markup:
//   <div class="segmented" role="tablist" data-tabs="nome-gruppo">
//     <button class="segmented__btn is-active" data-tab="a" role="tab" aria-selected="true">A</button>
//     <button class="segmented__btn" data-tab="b" role="tab" aria-selected="false">B</button>
//   </div>
//   <div data-panel-group="nome-gruppo" data-panel="a">…</div>
//   <div data-panel-group="nome-gruppo" data-panel="b" hidden>…</div>
// Più gruppi possono stare nella stessa pagina perché ognuno ha il suo nome.

document.querySelectorAll("[data-tabs]").forEach((gruppo) => {
  const nome = gruppo.dataset.tabs;
  const bottoni = gruppo.querySelectorAll("[data-tab]");

  bottoni.forEach((btn) => {
    btn.addEventListener("click", () => {
      const target = btn.dataset.tab;

      // attivo solo il bottone cliccato
      bottoni.forEach((b) => {
        const attivo = b === btn;
        b.classList.toggle("is-active", attivo);
        b.setAttribute("aria-selected", attivo ? "true" : "false");
      });

      // mostro solo i pannelli del gruppo che corrispondono
      document
        .querySelectorAll(`[data-panel-group="${nome}"]`)
        .forEach((p) => {
          p.hidden = p.dataset.panel !== target;
        });

      // eventuali testi che riportano l'etichetta della tab (es. "Classifica · Under 19")
      document
        .querySelectorAll(`[data-tab-label="${nome}"]`)
        .forEach((el) => {
          el.textContent = btn.textContent.trim();
        });
    });
  });
});

// ---------- MENU MOBILE (sito pubblico) ----------
const navToggle = document.querySelector(".nav-toggle");
const siteNav = document.getElementById("site-nav");

if (navToggle && siteNav) {
  navToggle.addEventListener("click", () => {
    const aperto = siteNav.classList.toggle("is-open");
    navToggle.setAttribute("aria-expanded", aperto ? "true" : "false");
    navToggle.setAttribute("aria-label", aperto ? "Chiudi il menu" : "Apri il menu");
  });
}

// ---------- SIDEBAR MOBILE (area riservata) ----------
const sideToggle = document.querySelector(".dash-toggle");
const sidebar = document.getElementById("dash-sidebar");

if (sideToggle && sidebar) {
  sideToggle.addEventListener("click", () => {
    const aperta = sidebar.classList.toggle("is-open");
    sideToggle.setAttribute("aria-expanded", aperta ? "true" : "false");
  });
}
