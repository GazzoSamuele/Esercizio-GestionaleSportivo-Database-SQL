    </main>

    <footer class="site-footer">
        <div class="wrap site-footer__grid">
            <div class="site-footer__col">
                <a class="logo" href="<?= BASE_URL ?>/index.php" aria-label="Olimpia Sport Club — Home">LOGO</a>
                <p>Società sportiva di hockey dal 1985. Risultati, calendario, shop e area soci.</p>
            </div>

            <nav class="site-footer__col" aria-label="Navigazione secondaria">
                <span class="site-footer__title">Navigazione</span>
                <a href="<?= BASE_URL ?>/index.php">Home</a>
                <a href="<?= BASE_URL ?>/calendario.php">Calendario</a>
                <a href="<?= BASE_URL ?>/prodotti.php">Prodotti</a>
                <a href="<?= BASE_URL ?>/contatti.php">Contatti</a>
            </nav>

            <div class="site-footer__col">
                <span class="site-footer__title">Area soci</span>
                <a href="<?= BASE_URL ?>/login.php">Accedi</a>
                <a href="<?= BASE_URL ?>/register.php">Registrati</a>
            </div>

            <div class="site-footer__col">
                <span class="site-footer__title">Contatti</span>
                <span>Via dello Sport 12, 38100 Trento</span>
                <a href="mailto:info@hockeyclub.it">info@hockeyclub.it</a>
                <a href="tel:+390461123456">+39 0461 123456</a>
            </div>
        </div>

        <div class="site-footer__bottom">
            <div class="wrap">
                <span>© <?= date('Y') ?> Olimpia Sport Club</span>
                <span>Gestionale società sportiva</span>
            </div>
        </div>
    </footer>

    <script src="<?= BASE_URL ?>/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/cmd.js"></script>
</body>

</html>
