@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4">Promo List</h2>
    <a href="{{ route('promos.create') }}" class="btn btn-primary">Add Promo</a>

    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr class="bg-gray-100">
                <th class="py-2 px-4 border-b">No</th>
                <th class="py-2 px-4 border-b">Merk</th>
                <th class="py-2 px-4 border-b">Model</th>
                <th class="py-2 px-4 border-b">Transmisi</th>
                <th class="py-2 px-4 border-b">Bahan Bakar</th>
                <th class="py-2 px-4 border-b">Harga</th>
                <th class="py-2 px-4 border-b">Harga Promo</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($promos as $index => $promo)
            <tr>
                <td class="py-2 px-4 border-b text-center">{{ $index + 1 }}</td>
                <td class="py-2 px-4 border-b">{{ $promo->carDetail->car->merk }}</td>
                <td class="py-2 px-4 border-b">{{ $promo->carDetail->car->model }}</td>
                <td class="py-2 px-4 border-b">{{ $promo->carDetail->transmisi }}</td>
                <td class="py-2 px-4 border-b">{{ $promo->carDetail->bahan_bakar }}</td>
                <td class="py-2 px-4 border-b">Rp {{ number_format($promo->carDetail->price, 0, ',', '.') }}</td>
                <td class="py-2 px-4 border-b">Rp {{ number_format($promo->harga_promo, 0, ',', '.') }}</td>
                <td class="py-2 px-4 border-b text-center">
                    <a href="{{ route('promos.edit', $promo->id) }}" class="btn btn-primary">Edit</a>
                    <form action="{{ route('promos.destroy', $promo->id) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
