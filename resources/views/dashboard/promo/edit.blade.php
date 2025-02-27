@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 p-4 bg-white shadow rounded-lg">
    <h2 class="text-2xl font-bold mb-4">Edit Promo</h2>
    <form action="{{ route('promos.update', $promo->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="car_detail_id" class="block text-gray-700 font-bold mb-2">Car Detail</label>
            <select id="car_detail_id" name="car_detail_id" class="block w-full bg-gray-100 border border-gray-300 rounded px-4 py-2">
                @foreach($carDetails as $detail)
                    <option value="{{ $detail->id }}" {{ $promo->car_detail_id == $detail->id ? 'selected' : '' }}>
                        {{ $detail->warna }} - {{ $detail->transmisi }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-4">
            <label for="harga_awal" class="block text-gray-700 font-bold mb-2">Harga Awal</label>
            <input type="text" id="harga_awal" name="harga_awal" value="{{ old('harga_awal', $hargaAwal) }}" class="block w-full bg-gray-100 border border-gray-300 rounded px-4 py-2" readonly>
        </div>

        <div class="mb-4">
            <label for="harga_promo" class="block text-gray-700 font-bold mb-2">Harga Promo</label>
            <input type="text" id="harga_promo" name="harga_promo" value="{{ $promo->harga_promo }}" class="block w-full bg-gray-100 border border-gray-300 rounded px-4 py-2" required>
        </div>

        <div class="mb-4">
            <label for="status" class="block text-gray-700 font-bold mb-2">Status</label>
            <select id="status" name="status" class="block w-full bg-gray-100 border border-gray-300 rounded px-4 py-2">
                <option value="aktif" {{ $promo->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="tidak" {{ $promo->status == 'tidak' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('promos.index') }}" class="btn btn-dark">Back</a>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#car_detail_id').on('change', function() {
            var carDetailId = $(this).val();
            if (carDetailId) {
                $.ajax({
                    url: '/get-car-detail-price/' + carDetailId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#harga_awal').val(data.price);
                    }
                });
            } else {
                $('#harga_awal').val('');
            }
        });
    });
</script>
@endsection
