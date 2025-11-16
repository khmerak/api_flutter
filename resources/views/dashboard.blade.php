@extends('layouts.index')

@section('main')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-5 bg-white shadow rounded">
            <p class="text-gray-500">Total Products</p>
            <h3 class="text-2xl font-bold">120</h3>
        </div>

        <div class="p-5 bg-white shadow rounded">
            <p class="text-gray-500">Categories</p>
            <h3 class="text-2xl font-bold">15</h3>
        </div>

        <div class="p-5 bg-white shadow rounded">
            <p class="text-gray-500">Users</p>
            <h3 class="text-2xl font-bold">50</h3>
        </div>
    </div>
@endsection
