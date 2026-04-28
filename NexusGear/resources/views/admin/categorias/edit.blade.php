@extends('layouts.admin')

@section('title', 'Editar categoría')
@section('page-title', 'Editar categoría')

@section('content')
<form method="POST" action="{{ route('admin.categorias.update', $categoria) }}">
    @method('PUT')
    @include('admin.categorias._form')
</form>
@endsection
