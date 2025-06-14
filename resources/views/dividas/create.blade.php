@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Nova Despesa</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @include('dividas.form', [
        'route' => route('dividas.store'),
        'method' => 'POST',
        'divida' => null
    ])
</div>
@endsection
