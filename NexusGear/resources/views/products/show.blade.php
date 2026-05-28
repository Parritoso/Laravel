@extends('layouts.app')

@section('title', $product->nombre)

@section('content')
<nav aria-label="{{ __('common.breadcrumb') }}" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('products/show.breadcrumb') }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $product->nombre }}</li>
    </ol>
</nav>

@php($isFavorite = in_array($product->id, $favoriteProductIds ?? [], true))

<section class="product-detail mb-5">
    <div class="product-detail__visual">
        @if ($product->imagen)
            <img src="{{ asset('storage/' . $product->imagen) }}" alt="{{ $product->nombre }}" class="img-fluid w-100 rounded">
        @else
            <i class="bi {{ $product->icono }}"></i>
        @endif
    </div>

    <div class="product-detail__info">
        <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="badge text-bg-light">{{ $product->perfil_nombre }}</span>
            @if ($product->destacado)
                <span class="badge bg-primary">{{ __('products/show.badge_featured') }}</span>
            @endif
            @if($product->precio_final < $product->precio)
                <span class="badge bg-danger animate__animated animate__flash animate__infinite">
                    <i class="bi bi-patch-check-fill me-1"></i> {{ __('products/show.badge_offer') }}
                </span>
            @endif
        </div>

        <h1 class="display-5 fw-bold mb-3">{{ $product->nombre }}</h1>
        <p class="lead text-muted">{{ $product->descripcion }}</p>

        <div class="product-detail__purchase">
            <div>
                @if($product->precio_final < $product->precio)
                    <div class="d-flex align-items-baseline gap-2">
                        <div class="product-detail__price text-danger">
                            {{ number_format($product->precio_final, 2, ',', '.') }} €
                        </div>
                        <span class="text-muted text-decoration-line-through fs-5">
                            {{ $product->precio_formateado }}
                        </span>
                    </div>
                @else
                    <div class="product-detail__price">{{ $product->precio_formateado }}</div>
                @endif
                <div class="{{ $product->disponible ? 'text-success' : 'text-danger' }}">
                    {{ $product->disponible ? __('products/show.units_available', ['stock' => $product->stock]) : __('products/show.out_of_stock') }}
                </div>
            </div>

            <div class="product-detail__actions">
                <form method="POST" action="{{ route('cart.store', $product) }}" class="add-to-cart-form">
                    @csrf
                    <label for="cantidad" class="visually-hidden">{{ __('products/show.qty_label') }}</label>
                    <input
                        id="cantidad"
                        type="number"
                        name="cantidad"
                        value="1"
                        min="1"
                        max="{{ max($product->stock, 1) }}"
                        class="form-control form-control-lg"
                        @disabled(! $product->disponible)
                    >
                    <button class="btn btn-primary btn-lg" type="submit" @disabled(! $product->disponible)>
                        <i class="bi bi-cart-plus me-1"></i> {{ __('products/show.add_to_cart') }}
                    </button>
                </form>

                @auth
                    <form method="POST" action="{{ $isFavorite ? route('favorites.destroy', $product) : route('favorites.store', $product) }}" class="m-0">
                        @csrf
                        @if ($isFavorite)
                            @method('DELETE')
                        @endif
                        <button class="btn {{ $isFavorite ? 'btn-danger' : 'btn-outline-secondary' }} btn-lg" type="submit">
                            <i class="bi {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }} me-1"></i>
                            {{ $isFavorite ? __('products/show.remove_favorite') : __('products/show.add_favorite') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-heart me-1"></i> {{ __('products/show.login_to_favorite') }}
                    </a>
                @endauth
            </div>
        </div>

        <div class="product-detail__facts">
            <div>
                <span>{{ __('products/show.use_label') }}</span>
                <strong>{{ $product->perfil_nombre }}</strong>
            </div>
            <div>
                <span>{{ __('products/show.shipping_label') }}</span>
                <strong>{{ __('products/show.shipping_time') }}</strong>
            </div>
            <div>
                <span>{{ __('products/show.warranty_label') }}</span>
                <strong>{{ __('products/show.warranty_time') }}</strong>
            </div>
        </div>
    </div>
</section>

<section class="product-reviews mb-5">
    <hr class="my-5">
    <div class="row g-5">
        
        <div class="col-md-7">
            <h3 class="h4 fw-bold mb-4">
                {{ __('products/show.reviews_title', ['count' => $product->total_comentarios]) }} 
                ({{ $product->puntuacion_media }} <i class="bi bi-star-fill text-warning fs-5"></i>)
            </h3>

            @if($comentarios->isEmpty())
                <p class="text-muted">{{ __('products/show.no_reviews') }}</p>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($comentarios as $comentario)
                        <div class="card border-0 shadow-sm p-3 position-relative">
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong class="small text-dark">{{ $comentario->user->name }}</strong>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="text-muted x-small" style="font-size: 0.8rem;">{{ $comentario->created_at->diffForHumans() }}</span>
                                    
                                    @if(Auth::id() === $comentario->user_id)
                                        <form action="{{ route('comentarios.destroy', $comentario) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres borrar tu opinión?');" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0 border-0 lh-1" title="Borrar comentario">
                                                <i class="bi bi-trash3 fs-6"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <div class="text-warning mb-2 small">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($comentario->puntuacion >= $i)
                                        <i class="bi bi-star-fill"></i>
                                    @elseif($comentario->puntuacion >= ($i - 0.5))
                                        <i class="bi bi-star-half"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                            </div>

                            @if($comentario->contenido)
                                <p class="mb-0 text-secondary small">{{ $comentario->contenido }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $comentarios->links() }}
                </div>
            @endif
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-4 bg-light">
                <h4 class="h5 fw-bold mb-3">{{ __('products/show.write_review_title') }}</h4>
                
                @auth
                    <form action="{{ route('comentarios.store', $product) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label d-block fw-semibold small">{{ __('products/show.rating_label') }}</label>
                            
                            <div class="star-rating-picker d-inline-flex gap-1 text-warning display-6" style="cursor: pointer;">
                                <i class="bi bi-star" data-value="1"></i>
                                <i class="bi bi-star" data-value="2"></i>
                                <i class="bi bi-star" data-value="3"></i>
                                <i class="bi bi-star" data-value="4"></i>
                                <i class="bi bi-star" data-value="5"></i>
                            </div>

                            <input type="hidden" name="puntuacion" id="puntuacion-input" value="{{ old('puntuacion', $userReview->puntuacion ?? 5) }}">
                            @error('puntuacion') <span class="text-danger d-block small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contenido" class="form-label fw-semibold small">{{ __('products/show.comment_label') }}</label>
                            <textarea name="contenido" id="contenido" rows="3" class="form-control form-control-sm" 
                                placeholder="{{ __('products/show.comment_placeholder') }}">{{ old('contenido', $userReview->contenido ?? '') }}</textarea>
                            @error('contenido') <span class="text-danger d-block small">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            {{ $userReview ? __('products/show.update_review_btn') : __('products/show.submit_review_btn') }}
                        </button>
                    </form>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-chat-left-dots text-muted display-6 d-block mb-2"></i>
                        <p class="small text-muted mb-3">{{ __('products/show.login_to_review_desc') }}</p>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-4 fw-bold">
                            {{ __('products/show.login_btn') }}
                        </a>
                    </div>
                @endauth
            </div>
        </div>

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.querySelector('.star-rating-picker');
    if (!wrapper) return;

    const stars = wrapper.querySelectorAll('.bi');
    const input = document.getElementById('puntuacion-input');
    let currentRating = parseFloat(input.value) || 5;

    // Pintar estado inicial cargado
    updateStarsVisual(currentRating);

    stars.forEach(star => {
        star.addEventListener('mousemove', function (e) {
            const rect = star.getBoundingClientRect();
            const starValue = parseFloat(star.getAttribute('data-value'));
            // Si el cursor está en la mitad izquierda de la estrella, resta 0.5
            const isHalf = (e.clientX - rect.left) < (rect.width / 2);
            const hoverValue = isHalf ? starValue - 0.5 : starValue;
            
            updateStarsVisual(hoverValue);
        });

        star.addEventListener('click', function (e) {
            const rect = star.getBoundingClientRect();
            const starValue = parseFloat(star.getAttribute('data-value'));
            const isHalf = (e.clientX - rect.left) < (rect.width / 2);
            
            currentRating = isHalf ? starValue - 0.5 : starValue;
            input.value = currentRating;
        });
    });

    wrapper.addEventListener('mouseleave', function () {
        // Al quitar el ratón, vuelve a fijar la puntuación guardada/seleccionada
        updateStarsVisual(currentRating);
    });

    function updateStarsVisual(value) {
        stars.forEach(star => {
            const starValue = parseFloat(star.getAttribute('data-value'));
            // Reseteamos las clases base manteniendo Bootstrap Icons
            star.className = 'bi'; 
            
            if (value >= starValue) {
                star.classList.add('bi-star-fill');
            } else if (value === starValue - 0.5) {
                star.classList.add('bi-star-half');
            } else {
                star.classList.add('bi-star');
            }
        });
    }
});
</script>

@if ($relatedProducts->isNotEmpty())
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-end gap-3 mb-3">
            <div>
                <span class="catalog-kicker text-primary">{{ __('products/show.related_kicker') }}</span>
                <h2 class="h4 fw-bold mb-0">{{ __('products/show.related_title') }}</h2>
            </div>
            <a href="{{ route('products.index', ['profile' => $product->categoria->slug ?? '']) }}" class="text-primary fw-bold text-decoration-none">
                {{ __('products/show.view_profile') }} <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach ($relatedProducts as $relatedProduct)
                <div class="col-md-4">
                    <article class="product-card h-100">
                        <a href="{{ route('products.show', $relatedProduct) }}" class="product-card__media product-card__media--compact">
                            @if($relatedProduct->precio_final < $relatedProduct->precio)
                                <span class="badge bg-danger position-absolute top-0 start-0 m-2">{{ __('products/show.badge_offer') }}</span>
                            @endif
                            @if ($relatedProduct->imagen)
                                <img src="{{ asset('storage/' . $relatedProduct->imagen) }}" alt="{{ $relatedProduct->nombre }}">
                            @else
                                <i class="bi {{ $relatedProduct->icono }}"></i>
                            @endif
                        </a>
                        <div class="product-card__body">
                            <h3 class="h6 fw-bold mb-2">
                                <a href="{{ route('products.show', $relatedProduct) }}" class="text-reset text-decoration-none">
                                    {{ $relatedProduct->nombre }}
                                </a>
                            </h3>
                            @if($relatedProduct->precio_final < $relatedProduct->precio)
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold text-danger">{{ number_format($relatedProduct->precio_final, 2, ',', '.') }} €</span>
                                    <span class="text-muted text-decoration-line-through x-small" style="font-size: 0.75rem;">{{ $relatedProduct->precio_formateado }}</span>
                                </div>
                            @else
                                <div class="product-price">{{ $relatedProduct->precio_formateado }}</div>
                            @endif
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </section>
@endif
@endsection
