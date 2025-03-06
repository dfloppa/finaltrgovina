@extends('layouts.shop')

@section('title', 'Checkout')

@section('content')
<div class="bg-white py-8">
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-semibold mb-6">Checkout</h1>
        
        <livewire:checkout-form />
    </div>
</div>
@endsection 