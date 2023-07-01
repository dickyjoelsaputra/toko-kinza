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
            <div class="form-group mt-3 mr-3">
                <div class="row mx-2">
                    <input class="form-control col-12 col-md-6" type="text" class="form-control" id="search"
                        placeholder="Cari barang... (Nama / Kode)">
                    <div class="col-md-6 col-12">
                        <label class="custom-switch p-0 mt-md-2 mt-3">
                            <input type="checkbox" id="manualSwitch"
                                class="custom-switch-input">
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">Manual only</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="table-responsive">

                <table class="table-bordered table-md table" id="item-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Nama</th>
                            <th>Kode</th>
                            <th>Manual</th>
                            <th>Harga / Minimal / Satuan</th>
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
    var manualOnly = false;

    function fetchItems(page = 1, search = '', manual = false) {
    // AJAX REQUEST START
    $.ajax({
    url: "/item-ajax",
    type: "GET",
    data: {
    page: page,
    search: search,
    manual: manual,
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
            // row.append($("<td>").text(item.id));
            // row.append($("<td>").html('<img width="100" src="{{ asset("' + item.photo + '") }}" alt="..." class="img-thumbnail">'));
            var flagsUrl = '{{ asset("") }}' + "storage/" + item.photo;
            row.append($("<td>").html('<img width="100" src="' + flagsUrl + '" alt="..." class="img-thumbnail">'));
            row.append($("<td>").text(item.name));
            row.append($("<td>").text(item.code));
            row.append($("<td>").text(item.manual));

            var prices = item.prices;
            var priceTable = $("<table>").css("min-width", "auto");;

            $.each(prices, function(index, price) {

                var priceRow = $("<tr>");
                    var formattedHarga = new Intl.NumberFormat('id-ID').format(price.price);
                    priceRow.append($("<td>").text(formattedHarga));
                    priceRow.append($("<td>").text(price.minimal));
                    priceRow.append($("<td>").text(price.unit.name));
                    priceTable.append(priceRow);
                });
            priceTable.append("</table>");
            row.append($("<td>").html(priceTable));
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

    function updateData() {
        fetchItems(currentPage, currentSearch, manualOnly);
    }

    fetchItems();

    $(document).on('click', '#pagination .page-link' , function(e) {
        e.preventDefault();
        var page=$(this).data('page');
        currentPage = page;
        updateData();
        // fetchItems(page, currentSearch);
    });

    // Search input keyup event
    $(document).on('keyup', '#search' , function() {
        var searchValue=$(this).val();
        currentSearch=searchValue;
        currentPage = 1;
        updateData();
    });

    $(document).on('change', '#manualSwitch', function() {
        manualOnly = $(this).is(':checked');
        currentPage = 1;
        // console.log(manualOnly)
        updateData();
    });

    // DELETE

    $(document).on('click', '.delete-btn', function () {
        var itemId = $(this).attr('data-id');

        $.ajax({
            url: "/item/" + itemId,
            type: "DELETE",
            data: {
            _token: '{{ csrf_token() }}'
            },
        success: function (response) {
            console.log(response);
            showToast(response.message, 'danger');
            updateData();
        },
        error: function (xhr) {
            console.log(xhr);
            showToast('error', 'Terjadi kesalahan saat menghapus item.');
        }
        });
    });

});
</script>
@endpush
