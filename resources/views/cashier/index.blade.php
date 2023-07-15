@extends('layouts.app')

@section('title', 'Kasir')

@push('style')
    <style>
        .search-results {
            position: absolute;
            z-index: 9999;
            top: 95%;
            left: 0;
            right: 0;
        }


        .search-results li {
            cursor: pointer;
        }

        .search-results li:hover {
            background-color: #99d8f5;
        }
    </style>
@endpush

@section('main')
    @include('components._toast')

    <div class="main-content">
        {{-- TOAST --}}
        {{-- END TOAST --}}
        <div class="card">
            <div class="card-header">
                <h4>Kasir</h4>
            </div>
            <div class="mx-3">
                <div class="card-body p-0">
                    <div class="row my-3">
                        <div class="col-6">
                            <div class="px-2 py-2">
                                <div class="form-group">
                                    <label>Scan Barang : </label>
                                    {{-- FORM START --}}
                                    <form id="form-scanner">
                                        <input id="scanner" name="scan" type="text"
                                            placeholder="Gunakan mesin scanner" class="form-control">
                                    </form>
                                    {{-- FORM END --}}
                                </div>
                                <div class="form-group position-relative">
                                    <label>Cari Nama atau Kode Manual : </label>
                                    <div class="position-relative">
                                        <input id="searchinput" name="searchinput" type="text" class="form-control">
                                    </div>
                                    <ul class="search-results list-group mt-1">
                                    </ul>
                                </div>
                                <div class="px-2 py-2">
                                    <button type="button" id="print" class="btn btn-primary btn-block">Cetak
                                        Resi</button>
                                </div>
                                <div class="px-2 py-2">
                                    <button id="proses" type="button" class="btn btn-success btn-block">Proses</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 px-2">

                            <div class="bg-light px-2 py-2">
                                <div class="form-group">
                                    <label>
                                        <label>Uang Pembeli : </label>
                                    </label>
                                    <input id="costumermoneyinput" name="costumermoneyinput" type="text"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <label>Total : </label>
                                    </label>
                                    <div class="w-100 p-2 form-control font-weight-bold total"
                                        style="background-color: rgb(203, 253, 183); ">
                                        0
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <label>Kembalian : </label>
                                    </label>
                                    <div id="changemoney" class="w-100 p-2 form-control font-weight-bold"
                                        style="background-color: rgb(231, 241, 137); ">
                                        0
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <table class="table table-bordered table-respnonsive">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Gambar</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Satuan</th>
                                <th style="width: 10%;">Qty</th>
                                <th>Harga</th>
                                <th>Sub Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            var dataArray = [];
            var formatter = new Intl.NumberFormat('id-ID');

            $(document).on('input', '#costumermoneyinput', function() {
                var harga = $(this).val();
                harga = harga.replace(/[^0-9]/g, '');
                var formattedHarga = new Intl.NumberFormat('id-ID').format(harga);
                $(this).val(formattedHarga);
            });

            function newRow(data) {
                var maxPrice = getMaxPrice(data.prices);

                var tr = `<tr>
                <td>${data.id}</td>
                <td><img class="img-thumbnail" src="storage/${data.photo}" alt="" style="width: 100px;"></td>
                <td>${data.code}</td>
                <td>[${data.category.name}] ${data.name}</td>
                <td>
                    <select class="custom-select">
                        ${data.prices.map(price => `<option value="${price.price}" ${price.price===maxPrice ? 'selected' : '' }>${price.unit.alias} || ${price.minimal}</option>`).join('')}
                    </select>
                </td>
                <td>
                    <input class="form-control" value="1">
                </td>
                <td>${formatter.format(maxPrice)}</td>
                <td>
                    <div class="subtotal">${formatter.format(maxPrice)}</div>
                </td>
                <td>
                    <div class="delete-item bg-danger d-flex align-items-center justify-content-center border rounded"
                        style="width: 100%; height: 75%; cursor: pointer;">
                        <i class="fas fa-trash text-white"></i>
                    </div>
                </td>
            </tr>`;
                return tr;
            }

            function getMaxPrice(prices) {
                let maxPrice = 0;
                for (let i = 0; i < prices.length; i++) {
                    if (prices[i].price > maxPrice) {
                        maxPrice = prices[i].price;
                    }
                }
                return maxPrice;
            }

            function updatePrice(element) {
                var price = element.val();
                var row = element.closest('tr');

                row.find('td:eq(6)').text(formatter.format(price));

                var quantity = row.find('td:eq(5) input').val();
                var subtotal = price * quantity;
                row.find('td:eq(7) .subtotal').text(formatter.format(subtotal));

                calculateTotal();
            }

            $('table').on('change', 'select', function() {
                updatePrice($(this));
            });

            function updateSubtotal(element) {
                var quantity = element.val();
                var row = element.closest('tr');

                var price = row.find('td:eq(6)').text().replace(/\D/g, '');

                var subtotal = price * quantity;
                row.find('td:eq(7) .subtotal').text(formatter.format(subtotal));

                calculateTotal();
            }

            // Event handler for quantity change
            $('table').on('input', 'input', function() {
                updateSubtotal($(this));
            });

            // SCANNER START
            $("#scanner").focus();

            $("#form-scanner").on("submit", function(e) {
                e.preventDefault();
                let scanValue = $("#scanner").val();
                $.ajax({
                    url: "{{ route('cashier.scan') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        scan: scanValue,
                        dataarray: dataArray,
                    },
                    success: function(response) {
                        $("#scanner").val("");
                        $("#scanner").focus();
                        dataArray.push(response.results.id);
                        var newRowData = newRow(response.results);
                        $("table tbody").prepend(newRowData);

                        // Trigger perubahan harga dan subtotal
                        updatePrice($("table tbody tr:first").find("select"));
                        updateSubtotal($("table tbody tr:first").find("input"));

                        showToast("Product added successfully", "success");
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON;
                        showToast(errors.message, "danger");
                        $("#scanner").val("");
                        $("#scanner").focus();
                    }
                });
            });
            // SCANNER END

            // FUNCTION TO REMOVE ITEM
            $("table").on("click", ".delete-item", function() {
                var row = $(this).closest("tr");
                var id = parseInt(row.find("td:eq(0)").text());

                var index = dataArray.indexOf(id);
                if (index > -1) {
                    dataArray.splice(index, 1);
                }

                console.log(dataArray)

                $("#scanner").focus();
                row.remove();

                calculateTotal();
            });

            // SEARCH START
            $('#searchinput').on('input', function() {
                var searchValue = $(this).val();

                if (searchValue === '') {
                    $('.search-results').empty();
                    return;
                }

                $.ajax({
                    url: "{{ route('cashier.search') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        dataarray: dataArray,
                        searchinput: searchValue
                    },
                    success: function(response) {
                        var results = response.results;

                        $('.search-results').empty();

                        if (results.length > 0) {
                            results.forEach(function(result) {
                                var li = $('<li class="list-group-item"></li>')
                                    .text('[' + result.category.name +'] ' + result.name + ' - ' + result.code)
                                    .data('itemId', result.id)
                                    .appendTo('.search-results');

                                li.on('click', function() {
                                    var itemId = $(this).data('itemId');
                                    addProductToTable(itemId);
                                    $('.search-results').empty();
                                    $('#searchinput').val('');
                                });
                            });
                        } else {
                            var li = $('<li class="list-group-item text-muted"></li>')
                                .text('No results found')
                                .appendTo('.search-results');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr);
                    }
                });
            });

            function addProductToTable(itemId) {
                // console.log(itemId)
                $.ajax({
                    url: "{{ route('cashier.getItem') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        itemId: itemId
                    },
                    success: function(response) {
                        var product = response.results;
                        var newRowData = newRow(product);
                        $('table tbody').prepend(newRowData);
                        updatePrice($('table tbody tr:first').find('select'));
                        updateSubtotal($('table tbody tr:first').find('input'));
                        showToast('Product added successfully', 'success');
                        dataArray.push(itemId);
                        $("#scanner").focus();
                    },
                    error: function(xhr) {
                        console.log(xhr);
                    }
                });
            }

            function calculateTotal() {
                var total = 0;
                $('table tbody tr').each(function() {
                    var subtotal = $(this).find('.subtotal').text().replace(/\D/g, '');
                    total += parseInt(subtotal);
                });

                $('.total').text(formatter.format(total));

                var costumerMoney = parseInt($('#costumermoneyinput').val().replace(/\D/g, ''));

                if (!isNaN(costumerMoney)) {
                    var changeMoney = costumerMoney - total;
                    $('#changemoney').text(formatter.format(changeMoney));
                } else {
                    costumerMoney = 0;
                    var changeMoney = costumerMoney - total;
                    $('#changemoney').text(formatter.format(changeMoney));
                }
            }

            $('#costumermoneyinput').on('input', function() {
                calculateTotal();
            });

            // START PRINTER
            $('#print').on('click', function() {
                printReceipt();
            });

            function printReceipt() {
                // var costumerMoney = $('#costumermoneyinput').val();
                var costumerMoney = parseInt($('#costumermoneyinput').val().replace(/\D/g, ''));
                var changeMoney = $('#changemoney').text();

                if (costumerMoney === '') {
                    showToast('Please input costumer money', 'danger');
                    return;
                }

                if (parseInt(changeMoney.replace(/\D/g, '')) < 0) {
                    showToast('Costumer money is not enough', 'danger');
                    return;
                }

                var receipt = '';
                var struct = '';
                var maxNameLength = 20;
                var lineLength = 58;
                $('table tbody tr').each(function(index) {
                    var name = $(this).find('td:eq(3)').text();
                    var quantity = $(this).find('td:eq(5) input').val();
                    var subtotal = $(this).find('td:eq(7) .subtotal').text();

                    if (name.length > maxNameLength) {
                        var slicedName = name.slice(0, maxNameLength); // Memotong nama barang
                        var remainingName = name.slice(maxNameLength); // Bagian sisanya

                        receipt += '• ' + slicedName + '\n';
                        while (remainingName.length > 0) {
                            var line = remainingName.slice(0, lineLength);
                            receipt += line + '\n';
                            remainingName = remainingName.slice(lineLength);
                        }
                    } else {
                        receipt += '• ' + name + '\n';
                    }

                    receipt += '(' + quantity + ') ' + subtotal + '\n';
                });

                var total = $('.total').text();
                var costumerMoney = formatter.format($('#costumermoneyinput').val());
                var changeMoney = $('#changemoney').text();

                struct += '\nTotal: \n';
                struct += total;
                struct += '\nUang Pembeli: \n'
                struct += costumerMoney;
                struct += '\nKembalian: \n'
                struct += changeMoney;

                var printWindow = window.open('', '_blank');
                printWindow.document.open();
                printWindow.document.write('<html><head><title>Struk</title>');
                printWindow.document.write('<style>');
                printWindow.document.write('@media print {');
                printWindow.document.write('body { font-size: 10pt; }');
                printWindow.document.write('hr { border-top: 1px dashed #000; }');
                printWindow.document.write('}');
                printWindow.document.write('</style>');
                printWindow.document.write('</head><body>');
                printWindow.document.write('<h5 style="text-align: center; margin:2;">TOKO AN_NISA</h5>');
                printWindow.document.write('<h5 style="text-align: center; margin:2;">Jl.Telesonik No.37</h5>');
                printWindow.document.write('<hr>');
                printWindow.document.write('<pre>' + receipt + '</pre>');
                printWindow.document.write('<hr>');
                printWindow.document.write('<pre>' + struct + '</pre>');
                printWindow.document.write('<hr>');
                printWindow.document.write('</body></html>');

                printWindow.document.close();
                printWindow.print();
                printWindow.close();
            }

            $('#proses').on('click', function() {
                var total = $('.total').text();
                var costumerMoney = parseInt($('#costumermoneyinput').val().replace(/\D/g, ''));
                var changeMoney = $('#changemoney').text();

                if (costumerMoney === '') {
                    showToast('Please input costumer money', 'danger');
                    return;
                }

                if (parseInt(changeMoney.replace(/\D/g, '')) < 0) {
                    showToast('Costumer money is not enough', 'danger');
                    return;
                }

                var allCarts = [];

                $('table tbody tr').each(function(index) {
                    var itemid = $(this).find('td:eq(0)').text();
                    var quantity = $(this).find('td:eq(5) input').val();
                    var price = $(this).find('td:eq(6)').text();
                    var subtotal = $(this).find('td:eq(7) .subtotal').text();

                    var carts = {
                        itemId: itemid,
                        quantity: quantity,
                        price: price,
                        subtotal: subtotal
                    };

                    allCarts.push(carts);
                });

                // ajax request
                $.ajax({
                    url: "{{ route('transaction.ajaxStore') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        total: total,
                        costumerMoney: costumerMoney,
                        changeMoney: changeMoney,
                        allCarts: allCarts
                    },
                    success: function(response) {
                        showToast('Transaction success', 'success');
                        $('table tbody tr').remove();
                        $('.total').text(formatter.format(0));
                        dataArray = [];
                        $('#costumermoneyinput').val('');
                        $('#changemoney').text(formatter.format(0));
                        $("#scanner").focus();
                    },
                    error: function(xhr) {
                        console.log(xhr);
                    }
                });

                console.log(total, costumerMoney, changeMoney, allCarts);

            });
            // END PRINTER
        });
    </script>
@endpush
