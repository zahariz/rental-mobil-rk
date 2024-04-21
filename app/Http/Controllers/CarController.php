<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarCreateRequest;
use App\Models\Car;
use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class CarController extends Controller
{
    public function index(): Response
    {
        $filter = Request::all('search');
        $car = Car::orderBy('qty', 'desc')
        ->filter(Request::only('search'))
        ->paginate(8)
        ->withQueryString()
        ->through(fn ($cars) => [
            'id' => $cars->id,
            'merek' => $cars->merek,
            'model' => $cars->model,
            'no_plat' => $cars->no_plat,
            'tarif_sewa' => $cars->tarif_sewa,
            'qty' => $cars->qty,
            'gambar' => $cars->gambar
        ]);
        return Inertia::render('Cars/Index', [
            'cars' => $car,
            'filters' => $filter
        ]);
    }

    public function list(): Response
    {
        $filter = Request::all('search');
        $car = Car::orderBy('qty', 'desc')
        ->filter(Request::only('search'))
        ->paginate(8)
        ->withQueryString()
        ->through(fn ($cars) => [
            'id' => $cars->id,
            'merek' => $cars->merek,
            'model' => $cars->model,
            'no_plat' => $cars->no_plat,
            'tarif_sewa' => $cars->tarif_sewa,
            'qty' => $cars->qty,
            'gambar' => $cars->gambar
        ]);
        return Inertia::render('Cars/List', [
            'cars' => $car,
            'filters' => $filter
        ]);
    }

    public function create(): Response 
    {
        return Inertia::render('Cars/Create');
    }

    public function store(CarCreateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        // dd($data);
        if ($request->hasFile('gambar')) {
            // Mendapatkan nama unik untuk file gambar
            $imageName = time() . '.' . $request->gambar->getClientOriginalExtension();
            
            // Menyimpan file gambar ke dalam direktori yang diinginkan
            $request->gambar->storeAs('public/images', $imageName);
            
            // Menyimpan path atau URL gambar ke dalam data yang akan disimpan
            $data['gambar'] = 'storage/images/' . $imageName;
        }

        $car = new Car($data);
        $car->save();

        return Redirect::route('cars.index')->with('success', 'Data berhasil disimpan');
    }
}
