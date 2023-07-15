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

        </div>
        <div class="card-body p-0">
            <button type="button" class="btn btn-success ml-4 mb-1"
                data-toggle="modal"
                data-target="#exampleModal">
                Tambah
            </button>

            <div class="table-responsive">
                <table class="table-striped table-md table" id="category-table">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($categorys as $category)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$category->name}}</td>
                        <td>{{$category->created_at->format('d-m-Y')}}</td>
                        {{-- make it center --}}
                        <td class="d-flex justify-content-around">
                            <button href="#" class="btn btn-primary edit-btn" data-id="{{$category->id}}">Edit</button>
                            <button href="#" class="btn btn-danger delete-btn" data-id="{{$category->id}}">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </table>

            </div>
        </div>
    </div>
</div>

<!-- Button trigger modal -->


{{-- START MODAL --}}
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
                <form id="category-create">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END MODAL --}}

{{-- START MODAL EDIT --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Satuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="category-edit">
                    <div class="form-group">
                        <label for="edit-name">Name</label>
                        <input type="text" class="form-control" id="edit-name" name="edit-name">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END MODAL EDIT --}}

@endsection

@push('scripts')
<!-- JS Libraies -->
<script src="{{ asset('library/prismjs/prism.js') }}"></script>

<!-- Page Specific JS File -->
<script src="{{ asset('js/page/bootstrap-modal.js') }}"></script>

<script>
    $(document).ready(function() {
        // CREATE START
        $('#category-create').on('submit', function(e) {
            e.preventDefault();
            var name = $('#name').val();
                $.ajax({
                    url: '{{ route('category.create') }}',
                    type: 'POST',
                    data: {
                        name: name,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log(response);
                                // showToast(response.message, 'success');
                        $('#name').val('');
                        $('#exampleModal').modal('hide');
                        $('#category-table').load(location.href + ' #category-table');
                        showToast(response.message, 'success');
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        Object.keys(errors).forEach(function(key) {
                            errorMessage += errors[key][0] + '<br>';
                        });
                    }
                    });
        });

        // CREATE END

        // DELETE START
        $("table").on('click','.delete-btn' ,function() {
        // e.preventDefault();
        var categoryId = $(this).data('id');
        $.ajax({
            url: "/category/" + categoryId,
            type: "DELETE",
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log(response);
                $('#category-table').load(location.href + ' #category-table');
                showToast(response.message, 'danger');
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                var errorMessage = '';

                Object.keys(errors).forEach(function(key) {
                    errorMessage += errors[key][0] + '<br>';
                });

                }
            });

        });
        // DELETE END

        // EDIT START
        $('table').on('click', '.edit-btn', function() {
            var categoryId = $(this).data('id');

            // Fetch category data via AJAX
            $.ajax({
                url: '/category/' + categoryId,
                type: 'GET',
                success: function(response) {
                    // console.log(response)
                    $('#edit-name').val(response.name);
                    $('#editModal #category-edit').data('id', categoryId);
                    // Show the edit modal
                    $('#editModal').modal('show');
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    console.log(errors)
                }
            });
        });

        // Submit form for edit
        $('#category-edit').on('submit', function(e) {
            e.preventDefault();

            var categoryId = $(this).data('id');
            var name = $('#edit-name').val();
            console.log(categoryId, name)

            $.ajax({
                url: '/category/' + categoryId,
                type: 'PUT',
                data: {
                    name: name,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response);
                    $('#editModal').modal('hide');
                    $('#category-table').load(location.href + ' #category-table');
                    showToast(response.message , 'primary');
                },
                error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                var errorMessage = '';

                Object.keys(errors).forEach(function(key) {
                    errorMessage += errors[key][0] + '<br>';
                });
                }
            });
        });

        // EDIT END

    });
</script>
@endpush
