@extends('layouts.app')

@section('title', __('home.title'))

@section('content')
<section class="catalog-hero mb-5">
    <div class="catalog-hero__content">
        <span class="catalog-kicker">NexusGear</span>
        <h1 class="display-5 fw-bold mb-3">{{ __('home.hero_title') }}</h1>
        <p class="lead mb-4">{{ __('home.hero_subtitle') }}</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg px-4 fw-bold">
            {{ __('home.view_catalog') }}
        </a>
    </div>
</section>
@endsection
