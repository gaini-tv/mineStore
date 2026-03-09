<footer>
    <div class="footer-container">
        <div class="footer-grid">
            {{-- À propos --}}
            <div class="footer-section">
                <h3>a propos</h3>
                <ul>
                    <li><a href="{{ route('pages.about') }}">Qui sommes-nous</a></li>
                    <li><a href="{{ route('pages.history') }}">Notre histoire</a></li>
                    <li><a href="{{ route('pages.contact') }}">Contact</a></li>
                </ul>
            </div>

            {{-- Informations --}}
            <div class="footer-section">
                <h3>Informations</h3>
                <ul>
                    <li><a href="{{ route('pages.shipping') }}">Livraison</a></li>
                    <li><a href="{{ route('pages.returns') }}">Retours</a></li>
                    <li><a href="{{ route('pages.terms') }}">CGV</a></li>
                    <li><a href="{{ route('pages.legal') }}">Mentions légales</a></li>
                </ul>
            </div>

            {{-- Mon compte --}}
            <div class="footer-section">
                <h3>Mon compte</h3>
                <ul>
                    <li><a href="{{ route('login') }}">Connexion</a></li>
                    <li><a href="{{ route('register') }}">Inscription</a></li>
                    <li><a href="{{ route('profil.index') }}">Mes commandes</a></li>
                    <li><a href="{{ route('panier.index') }}">Mon panier</a></li>
                </ul>
            </div>

            {{-- Suivez-nous --}}
            <div class="footer-section">
                <h3>Suivez-nous</h3>
                <ul>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">YouTube</a></li>
                </ul>
            </div>
        </div>

        {{-- Copyright --}}
        <div class="footer-copyright">
            <p>
                &copy; {{ date('Y') }} minstore. Tous droits reserves.
            </p>
        </div>

        {{-- Site fictif --}}
        <div style="text-align: center; padding-top: 0.5rem; padding-bottom: 0.5rem; font-size: 0.75rem; color: #5baa47; font-family: 'Minecrafter Alt', sans-serif;">
            Site fictif
        </div>
    </div>
</footer>
