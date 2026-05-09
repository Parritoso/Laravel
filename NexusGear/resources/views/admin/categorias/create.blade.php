@extends('layouts.admin')

@section('title', __('admin/categorias/create.title'))
@section('page-title', __('admin/categorias/create.title'))

@section('content')
<form method="POST" action="{{ route('admin.categorias.store') }}">
    @include('admin.categorias._form')
</form>
@endsection
