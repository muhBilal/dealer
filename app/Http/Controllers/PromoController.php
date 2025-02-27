<?php

namespace App\Http\Controllers;

use App\Models\CarDetail;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::with(['carDetail.car'])->get();
        return view('dashboard.promo.index', compact('promos'));
    }

    public function create()
    {
        $carDetails = CarDetail::all();
        return view('dashboard.promo.create', compact('carDetails'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_detail_id' => 'required|exists:car_details,id',
            'harga_promo' => 'required|numeric|lt:harga_awal',
            'status' => 'required|in:aktif,tidak',
            'end_date' => 'required|date|after:today',
        ]);

        DB::table('promos')->insert([
            'car_detail_id' => $request->car_detail_id,
            'harga_promo' => $request->harga_promo,
            'status' => $request->status,
            'end_date' => $request->end_date,
        ]);
        return redirect()->route('promos.index')->with('success', 'Promo added successfully.');
    }

    public function edit(Promo $promo)
    {
        $carDetails = CarDetail::all();
        $hargaAwal = $promo->carDetail->price;
        return view('dashboard.promo.edit', compact('promo', 'carDetails', 'hargaAwal'));
    }

    public function update(Request $request, Promo $promo)
    {
        $request->validate([
            'car_detail_id' => 'required|exists:car_details,id',
            'harga_awal' => 'required|numeric',
            'harga_promo' => 'required|numeric|lt:harga_awal',
            'status' => 'required|in:aktif,tidak',
        ]);

        $promo->update($request->all());
        return redirect()->route('promos.index')->with('success', 'Promo updated successfully.');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();
        return redirect()->route('promos.index')->with('success', 'Promo deleted successfully.');
    }

    public function getDetailPrice($id)
    {
        $carDetail = CarDetail::findOrFail($id);
        return response()->json(['price' => $carDetail->price]);
    }
}
