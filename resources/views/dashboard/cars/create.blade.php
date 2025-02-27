@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 p-4 bg-white shadow rounded-lg">
    <h2 class="text-2xl font-bold mb-4">Add New Car</h2>
    <form action="{{ route('cars.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label for="merk" class="block text-gray-700 font-medium mb-2">Merk</label>
            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" id="merk" name="merk" required>
        </div>
        <div class="mb-4">
            <label for="model" class="block text-gray-700 font-medium mb-2">Model</label>
            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" id="model" name="model" required>
        </div>
        <div class="mb-4">
            <label for="deskripsi" class="block text-gray-700 font-medium mb-2">Deskripsi</label>
            <textarea class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" id="deskripsi" name="deskripsi" rows="3"></textarea>
        </div>
        <div class="mb-4">
            <label for="images" class="block text-gray-700 font-medium mb-2">Images</label>
            <input type="file" class="block w-full text-sm text-gray-500 border border-gray-300 rounded cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500" id="images" name="images[]" multiple>
            <small class="text-gray-500">You can upload multiple images.</small>
        </div>
        <div class="flex flex-wrap gap-4 mt-4" id="imagePreview"></div>
        
        <h3 class="text-xl font-bold mt-8 mb-4">Car Details</h3>
        <div id="carDetails">
            <div class="car-detail border border-gray-300 p-4 rounded mb-4">
                <div class="mb-2">
                    <label class="block text-gray-700">Bahan Bakar</label>
                    <input type="text" name="details[0][bahan_bakar]" class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700">Transmisi</label>
                    <input type="text" name="details[0][transmisi]" class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700">Fitur</label>
                    <input type="text" name="details[0][fitur]" class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700">Stok</label>
                    <input type="number" name="details[0][stok]" class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700">Warna</label>
                    <input type="text" name="details[0][warna]" class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700">Price</label>
                    <input type="number" name="details[0][price]" class="w-full px-
                    3 py-2 border border-gray-300 rounded">
                </div>
                <button type="button" class="btn btn-danger">-</button>
            </div>
        </div>
        <button type="button" id="addDetail" class="btn btn-success">+ Add Detail</button>
        
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('cars.index') }}" class="btn btn-dark">Back</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        var detailIndex = 1;
        $('#addDetail').on('click', function() {
            var newDetail = `
                <div class="car-detail border border-gray-300 p-4 rounded mb-4">
                    <div class="mb-2">
                        <label class="block text-gray-700">Bahan Bakar</label>
                        <input type="text" name="details[${detailIndex}][bahan_bakar]" class="w-full px-3 py-2 border border-gray-300 rounded">
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700">Transmisi</label>
                        <input type="text" name="details[${detailIndex}][transmisi]" class="w-full px-3 py-2 border border-gray-300 rounded">
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700">Fitur</label>
                        <input type="text" name="details[${detailIndex}][fitur]" class="w-full px-3 py-2 border border-gray-300 rounded">
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700">Stok</label>
                        <input type="number" name="details[${detailIndex}][stok]" class="w-full px-3 py-2 border border-gray-300 rounded">
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700">Warna</label>
                        <input type="text" name="details[${detailIndex}][warna]" class="w-full px-3 py-2 border border-gray-300 rounded">
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700">Price</label>
                        <input type="number" name="details[${detailIndex}][price]" class="w-full px-3 py-2 border border-gray-300 rounded">
                    </div>
                    <button type="button" class="remove-detail bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 mt-2">-</button>
                </div>`;
            $('#carDetails').append(newDetail);
            detailIndex++;
        });

        $('#carDetails').on('click', '.remove-detail', function() {
            $(this).closest('.car-detail').remove();
        });

        $('#images').on('change', function() {
            $('#imagePreview').html('');
            var files = $(this)[0].files;
            if (files.length > 0) {
                $.each(files, function(index, file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var imgElement = $('<img>').attr('src', e.target.result)
                                                   .addClass('w-40 h-40 object-cover rounded-lg shadow-md');
                        $('#imagePreview').append(imgElement);
                    }
                    reader.readAsDataURL(file);
                });
            }
        });
    });
</script>
@endsection
