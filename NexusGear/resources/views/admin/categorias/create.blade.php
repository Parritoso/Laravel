@extends('layouts.admin')

@section('title', 'Nueva categoría')
@section('page-title', 'Nueva categoría')

@section('content')
<form method="POST" action="{{ route('admin.categorias.store') }}">
    @include('admin.categorias._form')
</form>
@endsection
