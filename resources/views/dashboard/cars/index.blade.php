@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4">Car List</h2>
    {{-- <a href="{{ route('cars.create') }}" class="inline-block mb-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Add Car</a> --}}
    <a href="{{ route('cars.create') }}" class="btn btn-primary mb-4">Add Car</a>

    @if (session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 border-b">No</th>
                    <th class="px-4 py-2 border-b">Merk</th>
                    <th class="px-4 py-2 border-b">Model</th>
                    <th class="px-4 py-2 border-b">Deskripsi</th>
                    <th class="px-4 py-2 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cars as $car)
                <tr class="hover:bg-gray-100">
                    <td class="px-4 py-2 border-b text-center">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2 border-b">{{ $car->merk }}</td>
                    <td class="px-4 py-2 border-b">{{ $car->model }}</td>
                    <td class="px-4 py-2 border-b">{{ $car->deskripsi }}</td>
                    <td class="px-4 py-2 border-b">
                        <a href="{{ route('cars.edit', $car->id) }}" 
                           class="btn btn-primary">Edit</a>
                        <form action="{{ route('cars.destroy', $car->id) }}" method="POST" class="inline-block" 
                              onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn btn-danger">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
