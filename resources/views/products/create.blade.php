@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Product</div>
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="title" class="font-weight-bold">Title</label>
                            <input type="text" name="title" class="form-control" id="title" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description" class="font-weight-bold">Description</label>
                            <textarea name="description" class="form-control" id="description" required></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="main_image" class="font-weight-bold">Main Image</label>
                            <input type="file" name="main_image" class="form-control-file" id="main_image" required>
                            <input type="hidden" name="main_image_path" id="main_image_path">
                        </div>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Variants</label>
                            <div id="variants">
                                <div class="form-row" id="variant_row_0">
                                    <div class="col">
                                        <label for="variant_size_0" class="font-weight-bold">Size</label>
                                        <input type="text" name="variants[0][size]" class="form-control" id="variant_size_0">
                                    </div>
                                    <div class="col">
                                        <label for="variant_color_0" class="font-weight-bold">Color</label>
                                        <input type="text" name="variants[0][color]" class="form-control" id="variant_color_0">
                                    </div>
                                    <div class="col">
                                        <button type="button" class="btn btn-danger mt-4" onclick="removeVariant(0)">Remove</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary mt-3" onclick="addVariant()">Add Variant</button>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function addVariant() {
        var variants = document.getElementById('variants');
        var index = variants.children.length;
        var div = document.createElement('div');
        div.classList.add('form-row', 'mt-3');
        div.id = 'variant_row_' + index;
        div.innerHTML = `
            <div class="col">
                <label for="variant_size_${index}" class="font-weight-bold">Size</label>
                <input type="text" name="variants[${index}][size]" class="form-control" id="variant_size_${index}">
            </div>
            <div class="col">
                <label for="variant_color_${index}" class="font-weight-bold">Color</label>
                <input type="text" name="variants[${index}][color]" class="form-control" id="variant_color_${index}">
            </div>
            <div class="col">
                <button type="button" class="btn btn-danger mt-4" onclick="removeVariant(${index})">Remove</button>
            </div>
        `;
        variants.appendChild(div);
    }

    function removeVariant(index) {
        var variantRow = document.getElementById('variant_row_' + index);
        if (variantRow) {
            variantRow.remove();
        }
    }

    document.getElementById('main_image').addEventListener('change', function() {
        var formData = new FormData();
        formData.append('file', this.files[0]);

        fetch('{{ route('products.upload_image') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.path) {
                document.getElementById('main_image_path').value = data.path;
            }
        });
    });

    document.getElementById('productForm').addEventListener('submit', function(event) {
        if (!document.getElementById('main_image_path').value) {
            event.preventDefault();
            alert('Please upload the main image first.');
        }
    });
</script>
@endsection
