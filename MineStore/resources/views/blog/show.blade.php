@extends('layouts.app')

@section('title', $article->nom)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modal-form.css') }}">
@endpush

@section('content')
    <div class="container mx-auto px-4 py-8" style="padding-top: 200px; max-width: 1200px;">
        <a href="{{ route('blog.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem; color: #5baa47; font-family: 'Minecrafter Alt', sans-serif; text-decoration: none;">
            ← Retour au blog
        </a>

        {{-- Article : image, titre, description, bouton --}}
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 3rem;">
            {{-- Image --}}
            <div style="width: 100%; min-height: 300px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; padding: 2rem;">
                @if($article->produit && $article->produit->image)
                    <img src="{{ asset($article->produit->image) }}" alt="{{ $article->produit->nom }}" style="max-width: 100%; max-height: 400px; object-fit: contain;">
                @else
                    <img src="{{ asset('images/placeholder-product.png') }}" alt="" style="max-width: 100%; max-height: 400px; object-fit: contain;">
                @endif
            </div>

            {{-- Titre, description, bouton --}}
            <div style="padding: 2rem 2.5rem;">
                <h1 style="font-family: 'Minecrafter Alt', sans-serif; font-size: 2rem; font-weight: bold; color: #1b1b18; margin-bottom: 0.75rem;">
                    {{ $article->nom }}
                </h1>
                <p style="font-size: 0.875rem; color: #706f6c; margin-bottom: 1.5rem;">
                    {{ $article->created_at->format('d/m/Y à H:i') }}
                    @if($article->produit)
                        · <a href="{{ route('produits.show', $article->produit->id_produit) }}" style="color: #5baa47; text-decoration: none;">{{ $article->produit->nom }}</a>
                    @endif
                </p>

                {{-- Description du blog --}}
                <div style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18; font-size: 1rem; line-height: 1.6; margin-bottom: 2rem; white-space: pre-wrap;">{!! nl2br(e($article->description)) !!}</div>

                {{-- Bouton Aller sur le produit --}}
                @if($article->produit)
                    <a href="{{ route('produits.show', $article->produit->id_produit) }}" style="display: inline-block; text-decoration: none;">
                        <div style="position: relative; display: inline-block; width: 280px;">
                            <img src="{{ asset('images/btn.png') }}" alt="" style="width: 100%; height: auto; display: block;">
                            <span style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1rem; font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                                Aller sur le produit
                            </span>
                        </div>
                    </a>
                @endif
            </div>
        </div>

        {{-- Séparateur --}}
        <div style="border-top: 2px solid #5baa47; width: 60%; margin: 0 auto 3rem; padding-top: 2rem;"></div>

        {{-- Section commentaires --}}
        <div style="margin-top: 2rem; padding-top: 2rem; padding-bottom: 3rem;">
            <h2 style="font-family: 'Minecrafter Alt', sans-serif; font-size: 1.875rem; font-weight: bold; color: white; margin-bottom: 1.5rem; text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000, 0 0 10px rgba(0, 150, 0, 0.8);">
                Commentaires ({{ $article->commentaires->count() }})
            </h2>

            @if(session('success'))
                <div style="background: #d1fae5; border: 1px solid #34d399; color: #065f46; padding: 0.75rem 1rem; border-radius: 4px; margin-bottom: 1rem;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Formulaire commentaire --}}
            <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; margin-bottom: 2rem;">
                @auth
                    <h3 style="font-family: 'Minecrafter Alt', sans-serif; font-size: 1.25rem; font-weight: bold; color: #1b1b18; margin-bottom: 1rem;">
                        Laisser un commentaire
                    </h3>
                    <form action="{{ route('blog.commentaires.store', $article) }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 1rem;">
                            <textarea name="contenu" rows="4" required
                                      style="width: 100%; padding: 1rem; background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; min-height: 100px; font-family: 'Minecrafter Alt', sans-serif; box-sizing: border-box;"
                                      placeholder="Écrivez votre commentaire...">{{ old('contenu') }}</textarea>
                            @error('contenu')
                                <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div style="position: relative; display: inline-block; width: 200px;">
                            <img src="{{ asset('images/btn.png') }}" alt="" style="width: 100%; height: auto; display: block;">
                            <button type="submit" style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: transparent; border: none; cursor: pointer; padding: 0;">
                                <span style="color: white; font-weight: bold; font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                                    Envoyer
                                </span>
                            </button>
                        </div>
                    </form>
                @else
                    <p style="color: #374151; margin-bottom: 0; font-family: 'Minecrafter Alt', sans-serif;">
                        <a href="{{ route('login') }}" style="color: #5baa47; text-decoration: none;">Connectez-vous</a> pour laisser un commentaire.
                    </p>
                @endauth
            </div>

            {{-- Liste des commentaires --}}
            @if($article->commentaires->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @foreach($article->commentaires as $commentaire)
                        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem;">
                            <div style="display: flex; align-items: flex-start; gap: 1rem;">
                                @php
                                    $userAvatar = $commentaire->user && $commentaire->user->avatar
                                        ? asset('images/avatar/' . $commentaire->user->avatar)
                                        : asset('images/avatar/base.png');
                                @endphp
                                <div style="width: 48px; height: 48px; border-radius: 50%; border: 2px solid #5baa47; overflow: hidden; flex-shrink: 0;">
                                    <img src="{{ $userAvatar }}" alt="" style="width: 100%; height: 100%; object-fit: contain;">
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
                                        <h4 style="font-weight: bold; color: #1b1b18; font-family: 'Minecrafter Alt', sans-serif; margin: 0;">
                                            {{ trim(($commentaire->user->prenom ?? '').' '.($commentaire->user->nom ?? '')) ?: $commentaire->user->name }}
                                        </h4>
                                        @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->id() === $commentaire->user_id))
                                            <form action="{{ route('blog.commentaires.destroy', $commentaire) }}" method="POST" onsubmit="return confirm('Supprimer ce commentaire ?');" style="margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="color: #ef4444; font-size: 0.875rem; background: none; border: none; cursor: pointer; padding: 0;">Supprimer</button>
                                            </form>
                                        @endif
                                    </div>
                                    <p style="font-size: 0.875rem; color: #6b7280; margin: 0.25rem 0 0.5rem;">{{ $commentaire->created_at->format('d/m/Y à H:i') }}</p>
                                    <p style="color: #1b1b18; margin: 0; white-space: pre-wrap;">{{ $commentaire->contenu }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem; text-align: center;">
                    <p style="color: #706f6c; font-family: 'Minecrafter Alt', sans-serif; margin: 0;">
                        Aucun commentaire. Soyez le premier à commenter !
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection
