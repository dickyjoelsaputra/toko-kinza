@extends('layouts.app')

@section('title', 'Index Barang')

@push('style')
<style>
</style>
@endpush

@section('main')
@include('components._toast')

<div class="main-content">
    <div class="card">
        <div class="card-header">
            <h4>Barang</h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <div class="form-group mt-3 ml-3 mr-3">
                    <input type="text" class="form-control" id="search" placeholder="Cari barang... (Nama / Kode)">
                </div>
                <table class="table-bordered table-md table" id="item-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Nama</th>
                            <th>Kode</th>
                            <th>Manual</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be dynamically inserted here -->
                    </tbody>
                </table>
                <div id="pagination" class="d-flex justify-content-center"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('library/prismjs/prism.js') }}"></script>
<script>
    $(document).ready(function() {
    var currentPage = 1;
    var totalPages = 1;
    var currentSearch = '';

    function fetchItems(page = 1, search = '') {
    // AJAX REQUEST START
    $.ajax({
    url: "/item-ajax",
    type: "GET",
    data: {
    page: page,
    search: search,
    _token: '{{ csrf_token() }}'
    },
    success: function(response) {
    console.log(response);
    var items = response.data.data;
    currentPage = response.data.current_page;
    totalPages = response.data.last_page;

    var tableBody = $("#item-table tbody");
    tableBody.empty();

    $.each(items, function(index, item) {
        var row = $("<tr>");

        row.append($("<td>").text((currentPage - 1) * 10 + index + 1));
            row.append($("<td>").html('<img width="100" src="' + item.photo + '" alt="..." class="img-thumbnail">'));
            row.append($("<td>").text(item.name));
            row.append($("<td>").text(item.code));
            row.append($("<td>").text(item.manual));

            var prices = item.prices;
            var priceTable = $("<table>");
                $.each(prices, function(index, price) {
                var priceRow = $("<tr>");
                    priceRow.append($("<td>").text(price.price.toLocaleString('id-ID')));priceRow.append($("<td>").text(price.unit.name));
                    priceTable.append(priceRow);
                });
            row.append($("<td>").html(priceTable));

        // var prices = item.prices;
        // var priceTable = $("<table>").addClass("table table-bordered table-sm table-primary");
        //     var priceTableBody = $("<tbody>");

        //     $.each(prices, function(index, price) {
        //         var priceRow = $("<tr>");
        //         priceRow.append($("<td>").text(price.price.toLocaleString('id-ID')));
        //         priceRow.append($("<td>").text(price.unit.name));
        //         priceTableBody.append(priceRow);
        //     });

        //     priceTable.append(priceTableBody);
        //     var priceTableWrapper = $("<div>").addClass("table-responsive").css("width", "200px").append(priceTable);
        //     var priceTableContainer = $("<div>").append(priceTableWrapper);
        //     row.append($("<td>").append(priceTableContainer));

            // Menambahkan tombol Edit dan Delete
            var actionButtons = $("<td>");
            actionButtons.append($("<button>").addClass("btn btn-primary edit-btn mr-1").attr("data-id",item.id).text("Edit"));
            actionButtons.append($("<button>").addClass("btn btn-warning print-btn mr-1").attr("data-id",item.id).text("Print"));
            actionButtons.append($("<button>").addClass("btn btn-danger delete-btn").attr("data-id",item.id).text("Delete"));

            row.append(actionButtons);

            tableBody.append(row);
            });

                                    // Update pagination
            var pagination = $("#pagination");
            pagination.empty();
            var paginationHtml = '';
            if (totalPages > 1) {
                paginationHtml += '<ul class="pagination">';
                    if (currentPage > 1) {
                        paginationHtml += '<li class="page-item"><a class="page-link" href="#"data-page="' + (currentPage - 1) + '">Prev</a></li>';
                    } else {
                        paginationHtml += '<li class="page-item disabled"><span class="page-link">Prev</span></li>';
                    }
                    for (var i = 1; i <= totalPages; i++) {
                        if (i===currentPage) {
                            paginationHtml +='<li class="page-item active"><span class="page-link">' + i + '</span></li>' ;
                        } else {
                            paginationHtml +='<li class="page-item"><a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>' ;
                        }
                    }

                    if (currentPage < totalPages) {
                        paginationHtml +='<li class="page-item"><a class="page-link" href="#" data-page="' + (currentPage + 1) + '">Next</a></li>' ;
                    } else {
                        paginationHtml +='<li class="page-item disabled"><span class="page-link">Next</span></li>' ;
                    }

                    paginationHtml +='</ul>' ;
                }

                pagination.html(paginationHtml);
            },
            error: function(xhr) {
                console.log(xhr);
            }
        });
    }

    fetchItems();

    $(document).on('click', '#pagination .page-link' , function(e) {
        e.preventDefault();
        var page=$(this).data('page');
        fetchItems(page, currentSearch);
    });

    // Search input keyup event
    $(document).on('keyup', '#search' , function() {
        var searchValue=$(this).val();
        currentSearch=searchValue;
        fetchItems(1, searchValue);
    });
});
</script>
@endpush
