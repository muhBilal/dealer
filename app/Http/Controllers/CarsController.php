<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CarsController extends Controller
{
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
                'price' => $value['price'],
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

    public function update(Request $request, Cars $car)
    {
        $request->validate([
            'merk' => 'required',
            'model' => 'required',
            'deskripsi' => 'nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $car->update([
            'merk' => $request->merk,
            'model' => $request->model,
            'deskripsi' => $request->deskripsi
        ]);

        if ($request->hasFile('images')) {
            $oldImages = DB::table('car_gallerys')->where('car_id', $car->id)->get();
            foreach ($oldImages as $oldImage) {
                Storage::disk('public')->delete($oldImage->image);
            }
            DB::table('car_gallerys')->where('car_id', $car->id)->delete();

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

        $existingDetails = DB::table('car_details')->where('car_id', $car->id)->pluck('id')->toArray();
        $updatedDetailIds = [];

        if ($request->has('details')) {
            foreach ($request->details as $detail) {
                if (isset($detail['id']) && in_array($detail['id'], $existingDetails)) {
                    DB::table('car_details')->where('id', $detail['id'])->update([
                        'bahan_bakar' => $detail['bahan_bakar'],
                        'transmisi' => $detail['transmisi'],
                        'fitur' => $detail['fitur'],
                        'stok' => $detail['stok'],
                        'warna' => $detail['warna'],
                        'price' => $detail['price'],
                        'updated_at' => now()
                    ]);
                    $updatedDetailIds[] = $detail['id'];
                } else {
                    DB::table('car_details')->insert([
                        'car_id' => $car->id,
                        'bahan_bakar' => $detail['bahan_bakar'],
                        'transmisi' => $detail['transmisi'],
                        'fitur' => $detail['fitur'],
                        'stok' => $detail['stok'],
                        'warna' => $detail['warna'],
                        'price' => $detail['price'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }

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
