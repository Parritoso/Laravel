@extends('layouts.admin')

@section('title', __('admin/discounts/edit.title'))
@section('page-title', __('admin/discounts/edit.title'))

@section('content')
<form method="POST" action="{{ route('admin.discounts.update', $discount) }}">
    @method('PUT')
    @include('admin.discounts._form')
</form>
@endsection
