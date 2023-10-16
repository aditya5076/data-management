@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Categories</h2>
            <a href="#" class="btn btn-primary mb-3" id="openModal">Add Category</a>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description }}</td>
                        <td>
                            <a href="#" class="btn btn-info" id="viewBtn" data-categoryid="{{  $category->id }}">View</a>
                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning">Edit</a>
                            <!-- Add delete button with a confirmation dialog -->
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">Delete</button>
                            </form>
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
            <form action="{{ route('categories.store') }}" method="POST" id="storeCategory">
                @csrf
                <div class="modal-body">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="inputName" name="name">
                    <p id="viewName"></p>
                    <span class="text-danger" id="errorName"></span>
                    <br>
                    <label for="description">Description</label>
                    <p id="viewDescription"></p>
                    <textarea class="form-control" id="inputDesc" name="description" rows="3"></textarea>
                    <span class="text-danger" id="errorDesc"></span>
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

        $('#viewBtn').on('click', function() {
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
                    $('#modal').modal('show');
                    $('#button').text('Update Values');
                    $('#button').data('categoryId', data.id);
                }
            })
        });


    })

    $('#modal').on('hidden.bs.modal', function() {
        $('#viewName').text('');
        $('#viewDescription').text('');
        $('#inputName').css('display', 'block');
        $('#inputDesc').css('display', 'block');
        $('#inputName').val('');
        $('#inputDesc').val('');
        $('#button').text('Save changes');
        $('#button').removeData('categoryId');

    })

    $('#button').on('click', function() {
        if ($(this).data('categoryId')) {
            $('#viewName').text('');
            $('#viewDescription').text('');
            $('#inputName').css('display', 'block');
            $('#inputDesc').css('display', 'block');
            $('#button').text('Save changes');

            // TODO : TO MAKE A SINGLE FUNCTION
            let inputVal = $('#inputName').val();
            let descVal = $('#inputDesc').val();
            $('[id^="error"]').text('');
            let hasError = false;
            e.preventDefault();
            if (e.target.name.value.trim() === '') {
                $('#errorName').text('Name is required');
                hasError = true;
            }

            if (e.target.description.value.trim() === '') {
                $('#errorDesc').text('Description is required');
                hasError = true;
            }
            if (hasError) {
                return false;
            }
            $.ajax({
                url: "{{ url('/categories') }}",
                data: {
                    id: $(this).data('categoryId'),
                    name: inputVal,
                    description: descVal,
                },
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {

                }
            })
        }
    })


    $('#storeCategory').submit(function(e) {
        let inputVal = $('#inputName').val();
        let descVal = $('#inputDesc').val();
        $('[id^="error"]').text('');
        let hasError = false;
        e.preventDefault();
        if (e.target.name.value.trim() === '') {
            $('#errorName').text('Name is required');
            hasError = true;
        }

        if (e.target.description.value.trim() === '') {
            $('#errorDesc').text('Description is required');
            hasError = true;
        }
        if (hasError) {
            return false;
        }

        $.ajax({
            url: "{{ url('/categories') }}",
            data: {
                name: inputVal,
                description: descVal,
            },
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {

            },
            error: function(data) {

                console.log(data.responseJSON.errors['name']['0']);

                if (data.responseJSON.errors['name'] !== 'undefined') {
                    $('#errorName').text(data.responseJSON.errors['name']['0']);
                } else if (data.responseJSON.errors['description'] !== 'undefined') {
                    $('#errorDesc').text(data.responseJSON.errors['name']['1']);

                }

            }

        })
    })
</script>
@endsection