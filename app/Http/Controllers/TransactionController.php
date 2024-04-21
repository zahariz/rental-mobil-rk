<?php

namespace App\Http\Controllers;

use App\Http\Requests\RentalCreateRequest;
use App\Http\Requests\ReturnCreateRequest;
use App\Models\Car;
use App\Models\Contact;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class TransactionController extends Controller
{
    public function index(): Response | RedirectResponse
    {
        $user = Auth::user();
        $contact = Contact::where('user_id', $user->id)->first();
        if(!$contact->sim || !$contact->alamat || !$contact->no_hp)
        {
            return Redirect::route('users.edit', $user->id)->with('error', 'Perbarui profile anda!');
        }
        $filter = Request::all('search');
        $rent = Transaksi::with('cars')->orderBy('tanggal_selesai', 'desc')
        ->filter(Request::only('search'))
        ->paginate(8)
        ->withQueryString()
        ->through(fn ($cars) => [
            'id' => $cars->id,
            'tanggal_mulai' => $cars->tanggal_mulai,
            'tanggal_selesai' => $cars->tanggal_selesai,
            'harga' => $cars->harga,
            'status' => $cars->status,
            'cars' => [
                'merek' => $cars->cars->merek,
                'model' => $cars->cars->model,
                'no_plat' => $cars->cars->no_plat,
                'gambar' => $cars->cars->gambar,
            ] 
        ]);
        return Inertia::render('Rent/Index', [
            'rents' => $rent,
            'filters' => $filter
        ]);
    }

    public function rental(int $id): Response | RedirectResponse
    {
        $user = Auth::user();
        $contact = Contact::where('user_id', $user->id)->first();
        if(!$contact->sim || !$contact->alamat || !$contact->no_hp)
        {
            return Redirect::route('users.edit', $user->id)->with('error', 'Perbarui profile anda!');
        }
        $data = Car::where('id', $id)->first();
        return Inertia::render('Rent/Rental',[
            'cars' => $data
        ]);
    }

    public function storeRental(RentalCreateRequest $request, int $id): RedirectResponse
    {
        $data = $request->validated();
        // dd($data);
        $car = Car::where('id', $id)->first();
        $transaksi = new Transaksi($data);
        $car->decrement('qty',1);
        $transaksi->car_id = $id;
        $transaksi->user_id = Auth::user()->id;
        $transaksi->save();

        return Redirect::route('transaction.index')->with('success', 'Mobil berhasil disewa!');
    }

    public function return(): Response | RedirectResponse
    {
        $user = Auth::user();
        $contact = Contact::where('user_id', $user->id)->first();
        if(!$contact->sim || !$contact->alamat || !$contact->no_hp)
        {
            return Redirect::route('users.edit', $user->id)->with('error', 'Perbarui profile anda!');
        }

        $filter = Request::all('search');
        $user = Auth::user();
        $rent = Transaksi::with('cars')
        ->where('status', 'Rented')
        ->where('user_id', $user->id)
        ->orderBy('tanggal_selesai', 'desc')
        ->filter(Request::only('search'))
        ->paginate(8)
        ->withQueryString()
        ->through(fn ($cars) => [
            'id' => $cars->id,
            'tanggal_mulai' => $cars->tanggal_mulai,
            'tanggal_selesai' => $cars->tanggal_selesai,
            'harga' => $cars->harga,
            'status' => $cars->status,
            'cars' => [
                'merek' => $cars->cars->merek,
                'model' => $cars->cars->model,
                'no_plat' => $cars->cars->no_plat,
                'tarif_sewa' => $cars->cars->tarif_sewa,
                'gambar' => $cars->cars->gambar,
            ] 
        ]);
        return Inertia::render('Rent/Return', [
            'rents' => $rent,
            'filters' => $filter
        ]);
    }

    public function createReturn(int $id): Response | RedirectResponse
    {
        $user = Auth::user();
        $contact = Contact::where('user_id', $user->id)->first();
        if(!$contact->sim || !$contact->alamat || !$contact->no_hp)
        {
            return Redirect::route('users.edit', $user->id)->with('error', 'Perbarui profile anda!');
        }

        $filter = Request::all('search');
        $user = Auth::user();
        $rent = Transaksi::with('cars')
        ->where('status', 'Rented')
        ->where('user_id', $user->id)
        ->where('id', $id)
        ->first();
        return Inertia::render('Rent/Returned', [
            'rents' => $rent,
            'filters' => $filter
        ]);
    }

    public function storeReturn(ReturnCreateRequest $request, int $id)
    {
        $data = $request->validated();
        // dd($data);
        $user = Auth::user();
        $transaksi = Transaksi::with('cars')
        ->where('status', 'Rented')
        ->where('user_id', $user->id)
        ->where('id', $id)
        ->first();
        $cars = Car::where('id', $transaksi->cars->id)->first();
        if($data['penalty'] > 0)
        {
            $transaksi->update([
                'status' => 'Returned',
                'harga' => $data['harga'] + $data['penalty'],
                'tanggal_pengembalian' => Carbon::now()->timestamp
            ]);
        } else {
            $transaksi->update([
                'status' => 'Returned'
            ]);
        }
        
        $cars->increment('qty', 1);

        return Redirect::route('transaction.return')->with('success', 'Mobil berhasil dikembalikan!');
    }
}
