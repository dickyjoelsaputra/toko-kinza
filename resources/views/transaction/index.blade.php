@extends('layouts.app')

@section('title', 'Index Barang')

@push('style')
    <style>
        .form-inline {
            display: flex;
            flex-flow: row wrap;
            align-items: flex-start;
            margin-bottom: 10px;
        }
    </style>
@endpush

@section('main')
    @include('components._toast')

    <div class="main-content">
        <div class="card">
            <div class="card-header">
                <h4>Transaksi</h4>
            </div>
            <div class="card-body p-0">
                {{-- {{$transaction}} --}}

                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Waktu Transaksi</th>
                            <th>Total Transaksi</th>
                            <th>Pembayaran</th>
                            <th>Kembalian</th>
                            <th>Profit</th>
                            <th>User</th>
                            {{-- <th style="width: 20%;">Action</th> --}}
                        </tr>
                    </thead>

                    <div class="form-inline">
                        <div class="mx-2">
                            {{ $transaction->withQueryString()->links() }}
                        </div>
                        <form action="" method="GET" id="search-form">
                            <input class="form-control" value="{{ isset($date) ? $date : '' }}" type="date"
                                id="date" name="date">
                            <button type="submit" id="submit" class="btn btn-primary mx-2">Submit</button>
                        </form>
                    </div>

                    <tbody>
                        @foreach ($transaction as $transaksi)
                            <tr data-widget="expandable-table" aria-expanded="false">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $transaksi->created_at }}</td>
                                <td>{{ $transaksi->total }}</td>
                                <td>{{ $transaksi->pay }}</td>
                                <td>{{ $transaksi->change }}</td>
                                <td>{{ $transaksi->net_profit }}</td>
                                <td>{{ $transaksi->user->name }}</td>
                            </tr>
                            <tr class="expandable-body d-none">
                                <td colspan="7">
                                    <table class="table table-bordered able-striped table-info">
                                        <thead>
                                            <tr>
                                                <th>Nama Barang</th>
                                                <th>Harga Modal</th>
                                                <th>Harga Jual</th>
                                                <th>Jumlah / Qty</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($transaksi->carts as $keranjang)
                                                <tr>
                                                    <td>{{ $keranjang->item_name }}</td>
                                                    <td>{{ $keranjang->capital }}</td>
                                                    <td>{{ $keranjang->price }}</td>
                                                    <td>{{ $keranjang->quantity }}</td>
                                                    <td>{{ $keranjang->subtotal }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('[data-widget="expandable-table"]').click(function() {
                // Mengubah status aria-expanded saat diklik
                $(this).attr('aria-expanded', function(index, attr) {
                    return attr === 'true' ? 'false' : 'true';
                });

                // Menampilkan atau menyembunyikan elemen dengan kelas expandable-body yang berada di bawah elemen yang diklik
                $(this).closest('tr').next('.expandable-body').toggleClass('d-none');
            });
        });
    </script>
@endpush
