@extends('layouts.admin')

@section('title', 'Nuevo producto')
@section('page-title', 'Nuevo producto')

@section('content')
<form method="POST" action="{{ route('admin.products.store') }}">
    @include('admin.products._form')
</form>
@endsection
