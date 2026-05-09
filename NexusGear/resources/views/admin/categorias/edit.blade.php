@extends('layouts.admin')

@section('title', __('admin/categorias/edit.title'))
@section('page-title', __('admin/categorias/edit.title'))

@section('content')
<form method="POST" action="{{ route('admin.categorias.update', $categoria) }}">
    @method('PUT')
    @include('admin.categorias._form')
</form>
@endsection
