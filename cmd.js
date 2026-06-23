// TABELLA NELL'INDEX PER MOSTRARE I RISULTATI E I GIOCATORI ISCIRTTI

document.querySelectorAll('.tabs .tab').forEach(tab => {
// per ogni tab della tabella mi cancelli il contenuto precedente
// e mi aggiungi la parte della sezione che voglio visualizzare
  tab.addEventListener('click', () => {
    const target = tab.dataset.tab

    // attiva solo il tab cliccato
    document.querySelectorAll('.tabs .tab')
        .forEach(t => t.classList.remove('active'));
            tab.classList.add('active')

    // mostra solo il pannello corrispondente
    document.querySelectorAll('.tab-panel')
        .forEach(p => {
            // l'attributo hidden nasconde senza CSS extra
            p.hidden = (p.dataset.panel !== target)
    })
  })
})

// TABELLA NELL'INDEX PER ATTIVARE GLI ACCORDION DELLA SEZIONE SHOP

document.querySelectorAll('.accordion .acc__head').forEach(head => {
  head.addEventListener('click', () => {
    const item = head.parentElement;                 // .acc
    const body = item.querySelector('.acc__body');
    const giaAperto = item.classList.contains('open');

    // chiudo tutti (così ne resta aperto uno solo)
    document.querySelectorAll('.accordion .acc').forEach(a => {
      a.classList.remove('open');
      a.querySelector('.acc__body').style.maxHeight = null;
    });

    // se non era già aperto, apro questo
    if (!giaAperto) {
      item.classList.add('open');
      body.style.maxHeight = body.scrollHeight + 'px';
    }
  });
});

// TABELLA NEL FILE CALENDARIO.PHP PER MOSTRARE I RISULTATI DELLe SCQUADRe NEL CAMPIONATO

document.querySelectorAll('.categories .category').forEach(cat => {
  cat.addEventListener('click', () => {

    const results = cat.dataset.tab;

    document.querySelectorAll('.categories .category')
      .forEach(c => c.classList.remove('active'));

    cat.classList.add('active');

    document.querySelectorAll('.tab-panel')
      .forEach(tp => {
        tp.hidden = (tp.dataset.panel !== results);
      });
  });
});


// TABELLA NEL FILE CALENDARIO.PHP PER MOSTRARE la Classifica Del Campionato

document.querySelectorAll('.results .result').forEach(res => {
  res.addEventListener('click', () => {

    const position = res.dataset.tab;

    document.querySelectorAll('.results .result')
      .forEach(r => r.classList.remove('active'));

    res.classList.add('active');

    document.querySelectorAll('.results-panel')
      .forEach(tp => {
        tp.hidden = (tp.dataset.panel !== position);
      });
  });
});

