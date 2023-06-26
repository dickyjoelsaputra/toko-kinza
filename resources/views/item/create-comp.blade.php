@extends('layouts.app')

@section('title', 'Tambah Barang Baru - Komputer')

@push('style')
@endpush

@section('main')
@include('components._toast')

<div class="main-content">
    <div class="card">
        <div class="card-header">
            <h4>Tambah Barang - Komputer</h4>
        </div>
        <div class="card-body p-0">

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('library/prismjs/prism.js') }}"></script>
<script>
    $(document).ready(function() {

    });
</script>
@endpush
