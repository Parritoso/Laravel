@extends('layouts.app')

@vite(['resources/css/checkout.scss','resources/js/checkout.js'])

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('cart.index') }}" class="btn btn-link text-decoration-none p-0 me-3">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="h3 fw-bold mb-0">{{ __('auth/checkout/index.heading') }}</h1>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="row g-5">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                                <i class="bi bi-truck text-primary fs-4"></i>
                            </div>
                            <h2 class="h5 fw-bold mb-0">{{ __('auth/checkout/index.shipping_title') }}</h2>
                        </div>

                        @error('direccion_id')
                            <div class="alert alert-danger py-2 small">{{ $message }}</div>
                        @enderror

                        <div class="row g-3 mb-4" id="address-selector">
                            @foreach($user->direcciones as $dir)
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check address-radio" name="direccion_id" id="dir-{{ $dir->id }}" value="{{ $dir->id }}" {{ $loop->first ? 'checked' : '' }}>
                                    <label class="btn btn-outline-light text-dark border p-3 w-100 text-start h-100 address-label shadow-sm-hover" for="dir-{{ $dir->id }}">
                                        <i class="bi bi-check-circle-fill text-primary selected-icon"></i>
                                        <span class="d-block fw-bold text-primary">{{ $dir->etiqueta ?? __('auth/checkout/index.address_default') }}</span>
                                        <span class="small d-block text-muted">{{ $dir->calle }}</span>
                                        <span class="small d-block text-muted">{{ $dir->ciudad }}, {{ $dir->codigo_postal }}</span>
                                    </label>
                                </div>
                            @endforeach

                            <div class="col-md-6">
                                <input type="radio" class="btn-check address-radio" name="direccion_id" id="new-address" value="new" {{ $user->direcciones->isEmpty() ? 'checked' : '' }}>
                                <label class="btn btn-outline-light text-dark border p-3 w-100 text-start h-100 address-label    d-flex align-items-center" for="new-address">
                                    <div class="text-center w-100">
                                        <i class="bi bi-plus-circle fs-4 text-primary"></i>
                                        <span class="d-block small fw-bold">{{ __('auth/checkout/index.new_address') }}</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div id="new-address-fields" class="{{ old('direccion_id') === 'new' || $user->direcciones->isEmpty() ? '' : 'd-none' }}">
                            <div class="row g-3">
                                <div class="col-9">
                                    <label class="form-label small fw-bold">{{ __('auth/checkout/index.street_label') }}</label>
                                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}">
                                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-3">
                                    <label class="form-label small fw-bold">{{ __('auth/checkout/index.number_label') }}</label>
                                    <input type="number" name="number" class="form-control @error('number') is-invalid @enderror" value="{{ old('number') }}">
                                    @error('number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label small fw-bold">{{ __('auth/checkout/index.city_label') }}</label>
                                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}">
                                    @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">{{ __('auth/checkout/index.zip_label') }}</label>
                                    <input type="text" name="zip_code" class="form-control @error('zip_code') is-invalid @enderror" value="{{ old('zip_code') }}">
                                    @error('zip_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="save_address" value="1" id="saveAddress" {{ old('save_address') ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="saveAddress">{{ __('auth/checkout/index.save_address') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-4">{{ __('auth/checkout/index.payment_title') }}</h2>
                        <div class="form-check border rounded p-3 mb-2">
                            <input class="form-check-input" type="radio" name="payment" id="card" checked>
                            <label class="form-check-label d-flex justify-content-between w-100" for="card">
                                <span>{{ __('auth/checkout/index.payment_card') }}</span>
                                <i class="bi bi-credit-card"></i>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm sticky-top" style="top: 2rem;">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-4">{{ __('auth/checkout/index.summary_title') }}</h2>

                        @foreach($cart->items as $item)
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">{{ $item->cantidad }}x {{ $item->producto->nombre }}</span>
                            <span>{{ number_format($item->cantidad * $item->producto->precio_final, 2, ',', '.') }} €</span>
                        </div>
                        @endforeach

                        <hr class="my-3">

                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('auth/checkout/index.subtotal') }}</span>
                            <span>{{ $cart->total_formateado }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-success fw-medium">
                            <span>{{ __('auth/checkout/index.shipping_label') }}</span>
                            <span>{{ __('auth/checkout/index.free') }}</span>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <span class="h5 fw-bold">{{ __('auth/checkout/index.total_label') }}</span>
                            <span class="h5 fw-bold text-primary">{{ $cart->total_formateado }}</span>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mt-4 fw-bold shadow-sm">
                            {{ __('auth/checkout/index.submit') }}
                        </button>

                        <p class="text-muted small text-center mt-3 mb-0">
                            <i class="bi bi-shield-check me-1"></i> {{ __('auth/checkout/index.secure_payment') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
