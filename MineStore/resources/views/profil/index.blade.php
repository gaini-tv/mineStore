@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
    <div class="container mx-auto px-4 py-8" style="padding-top: 200px;">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 max-w-4xl mx-auto">
                {{ session('success') }}
            </div>
        @endif
        
        @auth
            {{-- Contenu pour utilisateur connecté --}}
            <h1 class="text-3xl font-bold text-[#1b1b18] mb-4" style="font-family: 'Minecrafter Alt', sans-serif;">Mon profil</h1>
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-[#706f6c] mb-4" style="font-family: 'Minecrafter Alt', sans-serif;">Bienvenue, {{ auth()->user()->name }}!</p>
                <p class="text-[#706f6c]" style="font-family: 'Minecrafter Alt', sans-serif;">Informations du profil à venir...</p>
            </div>
        @else
            {{-- Formulaires pour utilisateur non connecté --}}
            <div class="flex items-center justify-center min-h-[60vh]">
                <div class="w-[500px]">
                    {{-- Formulaire de connexion --}}
                    <div id="login-form" class="bg-white rounded-lg p-[100px]">
                        <h2 class="text-3xl font-bold text-[#1b1b18] mb-6 text-center" style="margin-bottom: 20px; font-family: 'Minecrafter Alt', sans-serif;">Connexion</h2>
                        
                        @if ($errors->has('email') && !$errors->has('name'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('login.post') }}" style="display: flex; flex-direction: column; flex-wrap: nowrap; align-content: center; justify-content: center; row-gap: 20px;">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="login-email" class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Email</label>
                                <div class="p-2" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                                    <input type="email" 
                                           id="login-email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           required 
                                           class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none"
                                           style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none;"
                                           placeholder="Votre email">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="login-password" class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Mot de passe</label>
                                <div class="p-2" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                                    <input type="password" 
                                           id="login-password" 
                                           name="password" 
                                           required 
                                           class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none"
                                           style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none;"
                                           placeholder="Votre mot de passe">
                                </div>
                            </div>
                            
                            <div class="mb-6">
                                <label class="flex items-center">
                                    <input type="checkbox" name="remember" class="mr-2">
                                    <span class="text-sm text-[#706f6c]" style="font-family: 'Minecrafter Alt', sans-serif;">Se souvenir de moi</span>
                                </label>
                            </div>
                            
                            <div class="relative mx-auto" style="display: inline-block; width: 200px;">
                                <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block">
                                <button type="submit"
                                        class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200"
                                        style="background: transparent; border: none; cursor: pointer; padding: 0;">
                                    <span class="text-white font-bold text-base md:text-lg" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                                        Se connecter
                                    </span>
                                </button>
                            </div>
                        </form>
                        
                        <p class="text-center mt-[20px] text-[#706f6c] text-sm" style="margin-top: 20px; font-family: 'Minecrafter Alt', sans-serif;">
                            Pas encore de compte ? 
                            <a href="#" id="show-register" class="text-[#5baa47] hover:underline font-bold">S'inscrire</a>
                        </p>
                    </div>
                    
                    {{-- Formulaire d'inscription --}}
                    <div id="register-form" class="bg-white rounded-lg p-[100px] hidden">
                        <h2 class="text-3xl font-bold text-[#1b1b18] mb-6 text-center" style="margin-bottom: 20px; font-family: 'Minecrafter Alt', sans-serif;">Inscription</h2>
                        
                        @if ($errors->has('name') || ($errors->has('email') && $errors->has('name')) || $errors->has('password'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('register.post') }}" style="display: flex; flex-direction: column; flex-wrap: nowrap; align-content: center; justify-content: center; row-gap: 20px;">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="register-name" class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Nom</label>
                                <div class="p-2" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                                    <input type="text" 
                                           id="register-name" 
                                           name="name" 
                                           value="{{ old('name') }}"
                                           required 
                                           class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none"
                                           style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none;"
                                           placeholder="Votre nom">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="register-email" class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Email</label>
                                <div class="p-2" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                                    <input type="email" 
                                           id="register-email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           required 
                                           class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none"
                                           style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none;"
                                           placeholder="Votre email">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="register-password" class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Mot de passe</label>
                                <div class="p-2" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                                    <input type="password" 
                                           id="register-password" 
                                           name="password" 
                                           required 
                                           minlength="8"
                                           class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none"
                                           style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none;"
                                           placeholder="Votre mot de passe">
                                </div>
                                <p class="text-xs text-[#706f6c] mt-1" style="font-family: 'Minecrafter Alt', sans-serif;">Minimum 8 caractères</p>
                            </div>
                            
                            <div class="mb-6">
                                <label for="register-password-confirm" class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Confirmer le mot de passe</label>
                                <div class="p-2" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                                    <input type="password" 
                                           id="register-password-confirm" 
                                           name="password_confirmation" 
                                           required 
                                           class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none"
                                           style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none;"
                                           placeholder="Confirmez votre mot de passe">
                                </div>
                            </div>
                            
                            <div class="relative mx-auto" style="display: inline-block; width: 200px;">
                                <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block">
                                <button type="submit"
                                        class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200"
                                        style="background: transparent; border: none; cursor: pointer; padding: 0;">
                                    <span class="text-white font-bold text-base md:text-lg" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                                        S'inscrire
                                    </span>
                                </button>
                            </div>
                        </form>
                        
                        <p class="text-center mt-[20px] text-[#706f6c] text-sm" style="margin-top: 20px; font-family: 'Minecrafter Alt', sans-serif;">
                            Déjà un compte ? 
                            <a href="#" id="show-login" class="text-[#5baa47] hover:underline font-bold">Se connecter</a>
                        </p>
                    </div>
                </div>
            </div>
        @endauth
    </div>
    
    @push('scripts')
    <script>
        // Basculer entre connexion et inscription
        document.getElementById('show-register')?.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('login-form').classList.add('hidden');
            document.getElementById('register-form').classList.remove('hidden');
        });
        
        document.getElementById('show-login')?.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('register-form').classList.add('hidden');
            document.getElementById('login-form').classList.remove('hidden');
        });
        
        // Afficher le formulaire d'inscription si des erreurs d'inscription sont présentes
        @if ($errors->has('name') || ($errors->has('email') && $errors->has('name')) || $errors->has('password'))
            document.getElementById('login-form').classList.add('hidden');
            document.getElementById('register-form').classList.remove('hidden');
        @endif
    </script>
    @endpush
@endsection
