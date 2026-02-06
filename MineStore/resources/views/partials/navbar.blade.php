<nav class="navbar">
    <div class="navbar-left">
        <a href="{{ route('home') }}" class="navbar-brand">
            {{ config('app.name') }}
        </a>
    </div>
    <ul class="navbar-tabs">
        @php
            $tabs = [
                ['route' => 'home', 'label' => 'Accueil'],
                ['route' => 'nos-produits', 'label' => 'Nos produits'],
                ['route' => 'blog', 'label' => 'Blog'],
            ];
        @endphp
        @foreach ($tabs as $tab)
            <li>
                <a href="{{ route($tab['route']) }}"{{ request()->routeIs($tab['route']) ? ' class="active"' : '' }}>
                    {{ $tab['label'] }}
                </a>
            </li>
        @endforeach
    </ul>
    <div class="navbar-right">
        <a href="{{ route('profile') }}" class="navbar-profile{{ request()->routeIs('profile') ? ' active' : '' }}">Profile</a>
    </div>
</nav>
