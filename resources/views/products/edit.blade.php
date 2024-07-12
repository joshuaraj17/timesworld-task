@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Product</div>
                <div class="card-body">
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="title" class="font-weight-bold">Title</label>
                            <input type="text" name="title" class="form-control" id="title" value="{{ $product->title }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description" class="font-weight-bold">Description</label>
                            <textarea name="description" class="form-control" id="description" required>{{ $product->description }}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="main_image" class="font-weight-bold">Main Image</label>
                            <input type="file" name="main_image" class="form-control-file" id="main_image">
                            <input type="hidden" name="main_image_path" id="main_image_path" value="{{ $product->main_image }}">
                            @if ($product->main_image)
                                <img src="{{ Storage::url($product->main_image) }}" alt="Main Image" style="max-width: 200px; margin-top: 10px;">
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Variants</label>
                            <div id="variants">
                                @foreach ($product->variants as $index => $variant)
                                    <div class="form-row" id="variant_row_{{ $index }}">
                                        <div class="col">
                                            <label for="variant_size_{{ $index }}" class="font-weight-bold">Size</label>
                                            <input type="text" name="variants[{{ $index }}][size]" class="form-control" id="variant_size_{{ $index }}" value="{{ $variant->size }}">
                                        </div>
                                        <div class="col">
                                            <label for="variant_color_{{ $index }}" class="font-weight-bold">Color</label>
                                            <input type="text" name="variants[{{ $index }}][color]" class="form-control" id="variant_color_{{ $index }}" value="{{ $variant->color }}">
                                        </div>
                                        <div class="col">
                                            <button type="button" class="btn btn-danger mt-4" onclick="removeVariant({{ $index }})">Remove</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-secondary mt-3" onclick="addVariant()">Add Variant</button>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
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
        if (!document.getElementById('main_image_path').value && !document.getElementById('main_image').value) {
            event.preventDefault();
            alert('Please upload the main image first.');
        }
    });
</script>
@endsection
