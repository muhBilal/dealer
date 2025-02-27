<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CarsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {

    // }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     // 
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //     //
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(Cars $cars)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(Cars $cars)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, Cars $cars)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(Cars $cars)
    // {
    //     //
    // }

    public function index()
    {
        $cars = Cars::all();
        return view('dashboard.cars.index', compact('cars'));
    }

    public function create()
    {
        return view('dashboard.cars.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'merk' => 'required',
            'model' => 'required',
            'deskripsi' => 'nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
    
        $car = Cars::create([
            'merk' => $request->merk,
            'model' => $request->model,
            'deskripsi' => $request->deskripsi
        ]);
    
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('uploads', 'public');
                DB::table('car_gallerys')->insert([
                    'car_id' => $car->id,
                    'image' => $path,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    
        $detail = $request->details;
        foreach ($detail as $key => $value) {
            DB::table('car_details')->insert([
                'car_id' => $car->id,
                'bahan_bakar' => $value['bahan_bakar'],
                'transmisi' => $value['transmisi'],
                'fitur' => $value['fitur'],
                'stok' => $value['stok'],   
                'warna' => $value['warna'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        return redirect()->route('cars.index')->with('success', 'Car added successfully.');
    }
    

    public function edit(Cars $car)
    {
        $car = DB::table('cars')
            ->where('cars.id', $car->id)
            ->first();
        $gallery = DB::table('car_gallerys')
            ->where('car_id', $car->id)
            ->get();
        $detail = DB::table('car_details')
            ->where('car_id', $car->id)
            ->get();

        $car->gallery = $gallery;
        $car->details = $detail;
        return view('dashboard.cars.edit', compact('car'));
    }

    // public function update(Request $request, Cars $car)
    // {
    //     $request->validate([
    //         'merk' => 'required',
    //         'model' => 'required',
    //         'deskripsi' => 'nullable',
    //     ]);

    //     $car->update($request->all());

    //     return redirect()->route('cars.index')->with('success', 'Car updated successfully.');
    // }

//     public function update(Request $request, Cars $car)
// {
//     $request->validate([
//         'merk' => 'required',
//         'model' => 'required',
//         'deskripsi' => 'nullable',
//         'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
//     ]);

//     // Update data utama mobil
//     $car->update([
//         'merk' => $request->merk,
//         'model' => $request->model,
//         'deskripsi' => $request->deskripsi
//     ]);

//     // Update gambar
//     if ($request->hasFile('images')) {
//         // Hapus gambar lama (opsional)
//         $oldImages = DB::table('car_gallerys')->where('car_id', $car->id)->get();
//         foreach ($oldImages as $oldImage) {
//             Storage::disk('public')->delete($oldImage->image);
//         }
//         DB::table('car_gallerys')->where('car_id', $car->id)->delete();

//         // Upload gambar baru
//         foreach ($request->file('images') as $image) {
//             $path = $image->store('uploads', 'public');
//             DB::table('car_gallerys')->insert([
//                 'car_id' => $car->id,
//                 'image' => $path,
//                 'created_at' => now(),
//                 'updated_at' => now()
//             ]);
//         }
//     }

//     // Update detail
//     DB::table('car_details')->where('car_id', $car->id)->delete();
//     if ($request->has('details')) {
//         $details = $request->details;
//         foreach ($details as $detail) {
//             DB::table('car_details')->insert([
//                 'car_id' => $car->id,
//                 'bahan_bakar' => $detail['bahan_bakar'],
//                 'transmisi' => $detail['transmisi'],
//                 'fitur' => $detail['fitur'],
//                 'stok' => $detail['stok'],
//                 'warna' => $detail['warna'],
//                 'created_at' => now(),
//                 'updated_at' => now()
//             ]);
//         }
//     }

//     return redirect()->route('cars.index')->with('success', 'Car updated successfully.');
// }
public function update(Request $request, Cars $car)
{
    $request->validate([
        'merk' => 'required',
        'model' => 'required',
        'deskripsi' => 'nullable',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    // Update data utama mobil
    $car->update([
        'merk' => $request->merk,
        'model' => $request->model,
        'deskripsi' => $request->deskripsi
    ]);

    // Update gambar
    if ($request->hasFile('images')) {
        // Hapus gambar lama (opsional)
        $oldImages = DB::table('car_gallerys')->where('car_id', $car->id)->get();
        foreach ($oldImages as $oldImage) {
            Storage::disk('public')->delete($oldImage->image);
        }
        DB::table('car_gallerys')->where('car_id', $car->id)->delete();

        // Upload gambar baru
        foreach ($request->file('images') as $image) {
            $path = $image->store('uploads', 'public');
            DB::table('car_gallerys')->insert([
                'car_id' => $car->id,
                'image' => $path,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    // Update detail mobil
    $existingDetails = DB::table('car_details')->where('car_id', $car->id)->pluck('id')->toArray();
    $updatedDetailIds = [];

    if ($request->has('details')) {
        foreach ($request->details as $detail) {
            if (isset($detail['id']) && in_array($detail['id'], $existingDetails)) {
                // Update detail yang ada
                DB::table('car_details')->where('id', $detail['id'])->update([
                    'bahan_bakar' => $detail['bahan_bakar'],
                    'transmisi' => $detail['transmisi'],
                    'fitur' => $detail['fitur'],
                    'stok' => $detail['stok'],
                    'warna' => $detail['warna'],
                    'updated_at' => now()
                ]);
                $updatedDetailIds[] = $detail['id'];
            } else {
                // Tambahkan detail baru
                DB::table('car_details')->insert([
                    'car_id' => $car->id,
                    'bahan_bakar' => $detail['bahan_bakar'],
                    'transmisi' => $detail['transmisi'],
                    'fitur' => $detail['fitur'],
                    'stok' => $detail['stok'],
                    'warna' => $detail['warna'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    // Hapus detail yang tidak ada di form
    $toDelete = array_diff($existingDetails, $updatedDetailIds);
    if (!empty($toDelete)) {
        DB::table('car_details')->whereIn('id', $toDelete)->delete();
    }

    return redirect()->route('cars.index')->with('success', 'Car updated successfully.');
}



    public function destroy(Cars $car)
    {
        $car->delete();

        return redirect()->route('cars.index')->with('success', 'Car deleted successfully.');
    }
}
