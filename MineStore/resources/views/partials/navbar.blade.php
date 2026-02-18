<nav id="main-navbar" class="fixed z-50 backdrop-blur-sm border-b border-[#e3e3e0] navbar-bg navbar-floating navbar-scroll" style="background-image: url('{{ asset('images/navbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; height: 100px;">
    <div class="w-full px-8 sm:px-10 lg:px-14 h-full relative z-10 flex items-center">
        <div class="flex items-center justify-between w-full h-full">
            {{-- Logo à gauche --}}
            <div class="flex-shrink-0 flex items-center h-full">
                <a href="{{ route('home') }}" class="flex items-center font-medium transition-colors">
                    @if(file_exists(public_path('images/logo.png')))
                        <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name', 'MineStore') }}" class="h-16 w-auto">
                    @elseif(file_exists(public_path('images/logo.svg')))
                        <img src="{{ asset('images/logo.svg') }}" alt="{{ config('app.name', 'MineStore') }}" class="h-16 w-auto">
                    @else
                        <span class="flex items-center justify-center w-16 h-16 rounded-lg bg-[#1b1b18] text-white shrink-0" aria-hidden="true">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </span>
                    @endif
                </a>
            </div>

            {{-- Menu central : Accueil, Nos produits, Blog, Administration --}}
            <div class="hidden md:flex items-center justify-center flex-1 gap-10 h-full">
                <a href="{{ route('home') }}" class="navbar-link text-[1.2rem] font-medium text-white transition-all duration-300 {{ request()->routeIs('home') ? 'navbar-link-active' : '' }}" style="font-family: 'Minecrafter Alt', sans-serif;">
                    Accueil
                </a>
                <a href="{{ route('produits.index') }}" class="navbar-link text-[1.2rem] font-medium text-white transition-all duration-300 {{ request()->routeIs('produits.*') ? 'navbar-link-active' : '' }}" style="font-family: 'Minecrafter Alt', sans-serif;">
                    Nos produits
                </a>
                <a href="{{ route('blog.index') }}" class="navbar-link text-[1.2rem] font-medium text-white transition-all duration-300 {{ request()->routeIs('blog.*') ? 'navbar-link-active' : '' }}" style="font-family: 'Minecrafter Alt', sans-serif;">
                    Blog
                </a>
                @if(auth()->check() && auth()->user()->entreprise_id && auth()->user()->role !== 'admin' && auth()->user()->role !== 'user')
                    <a href="{{ route('entreprise.index') }}" class="navbar-link text-[1.2rem] font-medium text-white transition-all duration-300 {{ request()->routeIs('entreprise.*') ? 'navbar-link-active' : '' }}" style="font-family: 'Minecrafter Alt', sans-serif;">
                        Entreprise
                    </a>
                @endif
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('admin.index') }}" class="navbar-link text-[1.2rem] font-medium text-white transition-all duration-300 {{ request()->routeIs('admin.*') ? 'navbar-link-active' : '' }}" style="font-family: 'Minecrafter Alt', sans-serif;">
                        Administration
                    </a>
                @endif
            </div>

            {{-- Profil à droite --}}
            <div class="flex items-center justify-end gap-3 h-full">
                @if (auth()->check())
                    <div class="relative" id="profile-dropdown">
                        @php
                            $avatarFile = auth()->user()->avatar ?: 'base.png';
                            $userAvatar = asset('images/avatar/' . $avatarFile);
                            $userAvatar .= (auth()->user()->updated_at ? '?v=' . auth()->user()->updated_at->timestamp : '');
                        @endphp
                        <button id="profile-toggle" class="navbar-link transition-all duration-300 px-2 py-1 flex items-center gap-2">
                            <div class="w-10 h-10 rounded border-2 border-white overflow-hidden flex-shrink-0" style="aspect-ratio: 1/1;">
                                <img src="{{ $userAvatar }}" 
                                     alt="Avatar" 
                                     id="navbar-avatar"
                                     class="w-full h-full"
                                     style="object-fit: contain;">
                            </div>
                            <span class="hidden sm:inline text-white text-[1.2rem] font-medium" style="font-family: 'Minecrafter Alt', sans-serif;">{{ auth()->user()->name ?? 'Profil' }}</span>
                        </button>
                        <div id="profile-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-[#e3e3e0] py-1 z-50">
                            <a href="{{ route('profil.index') }}" class="block px-4 py-2 text-[1.2rem] text-[#1b1b18] hover:bg-[#e3e3e0] transition-colors" style="font-family: 'Minecrafter Alt', sans-serif;">Mon profil</a>
                            @if (Route::has('dashboard'))
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-[1.2rem] text-[#1b1b18] hover:bg-[#e3e3e0] transition-colors" style="font-family: 'Minecrafter Alt', sans-serif;">Tableau de bord</a>
                            @endif
                            @if (Route::has('logout'))
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-[1.2rem] text-[#706f6c] hover:bg-[#e3e3e0] transition-colors" style="font-family: 'Minecrafter Alt', sans-serif;">Déconnexion</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @else
                    <a href="{{ Route::has('login') ? route('login') : route('profil.index') }}" class="navbar-link text-[1.2rem] font-medium text-white transition-all duration-300 px-2 py-1" style="font-family: 'Minecrafter Alt', sans-serif;">
                        Profil
                    </a>
                @endif

                <button type="button" id="nav-toggle" class="md:hidden p-1.5 rounded-lg transition-all duration-300 hover:transform hover:-translate-y-1" aria-label="Menu">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Menu mobile déroulant --}}
        <div id="nav-mobile" class="hidden md:hidden pb-2 border-t border-[#e3e3e0]">
            <div class="flex flex-col gap-1 pt-2">
                <a href="{{ route('home') }}" class="navbar-link px-3 py-2 rounded-lg font-medium text-white transition-all duration-300 text-[1.2rem]" style="font-family: 'Minecrafter Alt', sans-serif;">Accueil</a>
                <a href="{{ route('produits.index') }}" class="navbar-link px-3 py-2 rounded-lg text-white transition-all duration-300 text-[1.2rem]" style="font-family: 'Minecrafter Alt', sans-serif;">Nos produits</a>
                <a href="{{ route('blog.index') }}" class="navbar-link px-3 py-2 rounded-lg text-white transition-all duration-300 text-[1.2rem]" style="font-family: 'Minecrafter Alt', sans-serif;">Blog</a>
                @if (auth()->check() && auth()->user()->entreprise_id && auth()->user()->role !== 'admin' && auth()->user()->role !== 'user')
                    <a href="{{ route('entreprise.index') }}" class="navbar-link px-3 py-2 rounded-lg text-white transition-all duration-300 text-[1.2rem]" style="font-family: 'Minecrafter Alt', sans-serif;">Entreprise</a>
                @endif
                @if (auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('admin.index') }}" class="navbar-link px-3 py-2 rounded-lg text-white transition-all duration-300 text-[1.2rem]" style="font-family: 'Minecrafter Alt', sans-serif;">Administration</a>
                @endif
                @if (auth()->check())
                    <a href="{{ route('profil.index') }}" class="navbar-link px-3 py-2 rounded-lg text-white transition-all duration-300 text-[1.2rem]" style="font-family: 'Minecrafter Alt', sans-serif;">Mon profil</a>
                @endif
            </div>
        </div>
    </div>
</nav>

<script>
    // Menu mobile
    document.getElementById('nav-toggle')?.addEventListener('click', function() {
        document.getElementById('nav-mobile')?.classList.toggle('hidden');
    });
    
    // Dropdown profil
    const profileToggle = document.getElementById('profile-toggle');
    const profileMenu = document.getElementById('profile-menu');
    if (profileToggle && profileMenu) {
        profileToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
        });
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#profile-dropdown')) profileMenu.classList.add('hidden');
        });
    }

    // Navbar hide/show on scroll
    let lastScrollTop = 0;
    const navbar = document.getElementById('main-navbar');
    let scrollThreshold = 10; // Minimum scroll distance before hiding
    
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > lastScrollTop && scrollTop > scrollThreshold) {
            // Scrolling down - hide navbar
            navbar.classList.add('navbar-hidden');
        } else if (scrollTop < lastScrollTop) {
            // Scrolling up - show navbar
            navbar.classList.remove('navbar-hidden');
        }
        
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    });
</script>
