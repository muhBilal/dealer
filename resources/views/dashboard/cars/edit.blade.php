@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4">Edit Car</h2>
    <form action="{{ route('cars.update', $car->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="merk" class="block text-sm font-medium text-gray-700">Merk</label>
            <input type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                id="merk" name="merk" value="{{ $car->merk }}" required>
        </div>
        
        <div class="mb-4">
            <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
            <input type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                id="model" name="model" value="{{ $car->model }}" required>
        </div>

        <div class="mb-4">
            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                id="deskripsi" name="deskripsi" rows="3">{{ $car->deskripsi }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Current Images</label>
            <div class="flex space-x-2 mt-2">
                @foreach ($car->gallery as $image)
                    <img src="{{ asset('storage/' . $image->image) }}" alt="Car Image" class="w-24 h-24 object-cover rounded-md">
                @endforeach
            </div>
        </div>

        <div class="mb-4">
            <label for="images" class="block text-sm font-medium text-gray-700">Update Images</label>
            <input type="file" name="images[]" multiple class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <h3 class="text-xl font-semibold">Details</h3>
            <div id="details-container">
                @foreach ($car->details as $key => $detail)
                <div class="border p-4 rounded-md mb-4 detail-item">
                    <input type="hidden" name="details[{{ $key }}][id]" value="{{ $detail->id }}">
                    
                    <input type="text" name="details[{{ $key }}][bahan_bakar]" 
                        value="{{ $detail->bahan_bakar }}" placeholder="Bahan Bakar"
                        class="block w-full border rounded-md p-2 mb-2">

                    <input type="text" name="details[{{ $key }}][transmisi]" 
                        value="{{ $detail->transmisi }}" placeholder="Transmisi"
                        class="block w-full border rounded-md p-2 mb-2">

                    <input type="text" name="details[{{ $key }}][fitur]" 
                        value="{{ $detail->fitur }}" placeholder="Fitur"
                        class="block w-full border rounded-md p-2 mb-2">

                    <input type="number" name="details[{{ $key }}][stok]" 
                        value="{{ $detail->stok }}" placeholder="Stok"
                        class="block w-full border rounded-md p-2 mb-2">

                    <input type="text" name="details[{{ $key }}][warna]" 
                        value="{{ $detail->warna }}" placeholder="Warna"
                        class="block w-full border rounded-md p-2 mb-2">

                    <button type="button" class="remove-detail btn btn-danger">Remove</button>
                </div>
                @endforeach
            </div>
            <button type="button" id="add-detail" class="btn btn-success">Add Detail</button>
        </div>


        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('cars.index') }}" class="btn btn-dark">Back</a>
    </form>
</div>

<script>
    $(document).ready(function() {
        var index = {{ count($car->details) }};
        
        $('#add-detail').on('click', function() {
            var newDetail = `
                <div class="border p-4 rounded-md mb-4 detail-item">
                    <input type="hidden" name="details[${index}][id]">
                    <input type="text" name="details[${index}][bahan_bakar]" placeholder="Bahan Bakar" class="block w-full border rounded-md p-2 mb-2">
                    <input type="text" name="details[${index}][transmisi]" placeholder="Transmisi" class="block w-full border rounded-md p-2 mb-2">
                    <input type="text" name="details[${index}][fitur]" placeholder="Fitur" class="block w-full border rounded-md p-2 mb-2">
                    <input type="number" name="details[${index}][stok]" placeholder="Stok" class="block w-full border rounded-md p-2 mb-2">
                    <input type="text" name="details[${index}][warna]" placeholder="Warna" class="block w-full border rounded-md p-2 mb-2">
                    <button type="button" class="remove-detail bg-red-500 text-white rounded-md px-2 py-1 mt-2">Remove</button>
                </div>`;
            $('#details-container').append(newDetail);
            index++;
        });

        $(document).on('click', '.remove-detail', function() {
            $(this).closest('.detail-item').remove();
        });
    });
</script>
@endsection
