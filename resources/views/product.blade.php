@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Products</h2>
            <a href="#" class="btn btn-primary mb-3" id="openModal">Add Product</a>
            <a href="{{ url('home') }}" class="btn btn-secondary mb-3">Back to category</a>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr id="row-<?php echo $product->id; ?>">
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->price }}</td>
                        <td><img src="{{ asset('product-images/' . $product->image ) }}" alt="No-image" width="100"></td>
                        <td>
                            <a href="#" class="btn btn-info viewBtn" data-productid="{{  $product->id }}">View</a>
                            <!-- Add delete button with a confirmation dialog -->
                            <button type="submit" class="btn btn-danger" onclick="deleteProduct(<?php echo $product->id; ?>)">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Modal title</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="" method="POST" id="storeProduct" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="category_id" value="{{ $categoryId }}">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="inputName" name="name">
                    <p id="viewName"></p>
                    <span class="text-danger" id="errorName"></span>
                    <label for="price">Price</label>
                    <input type="number" class="form-control" id="inputPrice" name="price">
                    <p id="viewPrice"></p>
                    <span class="text-danger" id="errorPrice"></span>
                    <label for="image">Image</label>
                    <input type="file" class="form-control" id="inputImage" name="image">
                    <p id="viewImage"></p>
                    <span class="text-danger" id="errorImage"></span>
                    <br>
                    <label for="specifications">Specification</label>
                    <p id="viewSpecification"></p>
                    <textarea class="form-control" id="inputDesc" name="specifications" rows="3"></textarea>
                    <span class="text-danger" id="errorSpecification"></span>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="button">Save changes</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    $(document).ready(function() {
        $('#openModal').on('click', function() {
            $('#modal').modal('show');
        })


    })

    $(document).on('click', '.viewBtn', function() {
        $('#modal').modal('show');
        let catId = $(this).data('categoryid');
        $.ajax({
            url: "{{ url('/categories') }}/" + catId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('[id^="error"]').text('');
                $('#viewName').text(data.name);
                $('#viewDescription').text(data.description);
                $('#inputName').css('display', 'none');
                $('#inputName').val(data.name);
                $('#inputDesc').css('display', 'none');
                $('#inputDesc').val(data.description);
                $('#button').text('Update Values');
                $('#button').attr('data-catid', catId);
            }
        })
    });

    document.getElementById('storeProduct').addEventListener('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: "{{ url('products') }}",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {}
        })
    })


    function appendHtml(data) {
        return `<td>${data.id}</td>
                        <td>${data.name}</td>
                        <td>${data.price}</td>
                        <td>
                            <a href="#" class="btn btn-info viewBtn" data-productid="${data.id}">View</a>
                            <a href="{{ url('categories') }}/${data.id}" class="btn btn-warning">Edit</a>
                                <button type="submit" class="btn btn-danger" onclick="deleteCategory(${data.id})">Delete</button>
                        </td>`;
    }
</script>

@endsection