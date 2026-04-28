@extends('layouts.admin')

@section('title', 'Editar producto')
@section('page-title', 'Editar producto')

@section('content')
<form method="POST" action="{{ route('admin.products.update', $product) }}">
    @method('PUT')
    @include('admin.products._form')
</form>
@endsection
