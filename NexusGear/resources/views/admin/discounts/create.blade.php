@extends('layouts.admin')

@section('title', __('admin/discounts/create.title'))
@section('page-title', __('admin/discounts/create.title'))

@section('content')
<form method="POST" action="{{ route('admin.discounts.store') }}">
    @include('admin.discounts._form')
</form>
@endsection