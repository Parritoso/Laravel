@extends('layouts.admin')

@section('title', __('admin/products/create.title'))
@section('page-title', __('admin/products/create.title'))

@section('content')
<form method="POST" action="{{ route('admin.products.store') }}">
    @include('admin.products._form')
</form>
@endsection
