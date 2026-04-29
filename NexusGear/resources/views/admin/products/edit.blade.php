@extends('layouts.admin')

@section('title', __('admin/products/edit.title'))
@section('page-title', __('admin/products/edit.title'))

@section('content')
<form method="POST" action="{{ route('admin.products.update', $product) }}">
    @method('PUT')
    @include('admin.products._form')
</form>
@endsection
