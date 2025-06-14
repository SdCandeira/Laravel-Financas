@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Despesa</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @include('dividas.form', [
        'route' => route('dividas.update', $divida->id_divida),
        'method' => 'PUT',
        'divida' => $divida
    ])
</div>
@endsection
