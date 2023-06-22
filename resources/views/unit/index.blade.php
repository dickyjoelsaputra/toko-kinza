@extends('layouts.app')

@section('title', 'Satuan')

@push('style')
@endpush

@section('main')
@include('components._toast')

<div class="main-content">
    {{-- TOAST --}}
    {{-- END TOAST --}}
    <div class="card">
        <div class="card-header">
            <h4>Satuan</h4>
            <div class="card-header-action">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
                    Tambah
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table-striped table-md table">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Alias</th>
                        <th>Quantity</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Pieces</td>
                        <td>Pcs</td>
                        <td>1</td>
                        <td>2017-01-09</td>
                        {{-- make it center --}}
                        <td class="d-flex justify-content-around">
                            <a href="#" class="btn btn-primary">Edit</a>
                            <a href="#" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah satuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="unit-create">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="alias">Alias</label>
                        <input type="text" class="form-control" id="alias" name="alias">
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="text" class="form-control" id="quantity" name="quantity">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END MODAL --}}

@endsection

@push('scripts')
<!-- JS Libraies -->
<script src="{{ asset('library/prismjs/prism.js') }}"></script>

<!-- Page Specific JS File -->
<script src="{{ asset('js/page/bootstrap-modal.js') }}"></script>

<script>
    $(document).ready(function() {
                $('#unit-create').on('submit', function(e) {
                    e.preventDefault();
                    var name = $('#name').val();
                    var alias = $('#alias').val();
                    var quantity = $('#quantity').val();
                    $.ajax({
                        url: '{{ route('unit.create') }}',
                        type: 'POST',
                        data: {
                            name: name,
                            alias: alias,
                            quantity: quantity,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.status === 'success') {
                                showToast(response.message, 'success');
                                $('#name').val('');
                                $('#alias').val('');
                                $('#quantity').val('');
                                $('#exampleModal').modal('hide');
                            } else {
                                showToast('Terjadi kesalahan. Coba lagi.', 'error');
                            }
                        },
                        error: function(xhr) {
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                var errors = xhr.responseJSON.errors;
                                var errorMessage = '';

                                    Object.keys(errors).forEach(function(key) {
                                        errorMessage += errors[key][0] + '<br>';
                                    });

                                showToast(errorMessage, 'error');
                            } else {
                                showToast('Terjadi kesalahan. Coba lagi.', 'error');
                            }
                        }
                    });

                    // Menutup modal setelah mengambil data
                });
            });
</script>
@endpush
