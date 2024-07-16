<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\json;

class MobilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mobils = Mobil::all();
        return view('dashboard', compact('mobils'));
    }

    public function mobil()
    {
        //
    }
    public function inputMobil()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('inputMobil');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'tipeMobil' => 'required|min:3',
            'jenisMobil' => 'required|min:3',
            'transmisi' => 'required|min:3',
            'harga' => 'required|numeric',
            'fotoMobil' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $messages = [
            'required' => 'Kolom :attribute harus diisi.',
            'min' => 'Kolom :attribute minimal :min karakter.',
            'image' => 'File harus berupa gambar.',
            'mimes' => 'Format file :attribute harus jpeg, png, jpg, gif, atau svg.',
            'max' => 'Ukuran file :attribute tidak boleh lebih dari :max kilobytes.',
            'numeric' => 'Kolom :attribute harus berupa angka.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['messages' => 'Gagal Input', 'errors' => $validator->errors()], 400);
        }

        $mobil = new Mobil();
        $mobil->jenisMobil = $request->jenisMobil;
        $mobil->tipeMobil = $request->tipeMobil;
        $mobil->transmisi = $request->transmisi;
        $mobil->harga = $request->harga;

        if ($request->hasFile('fotoMobil')) {
            $file = $request->file('fotoMobil');
            $filePath = $file->store('foto_mobil', 'public');
            $mobil->fotoMobil = $filePath;
        }

        $mobil->save();
        // return response()->json(['message' => 'Mobil ditambah!', 'mobil' => $mobil], 200);
        // return Redirect::route('dashboard')->with('success', 'Mobil berhasil ditambahkan!');
        return redirect()->back()->with('success', 'Mobil berhasil ditambahkan!');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $mobil = Mobil::findOrFail($id);
        return view('edit', compact('mobil'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'tipeMobil' => 'required|min:3',
            'jenisMobil' => 'required|min:3',
            'transmisi' => 'required|min:3',
            'harga' => 'required|numeric',
            'fotoMobil' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $messages = [
            'required' => 'Kolom :attribute harus diisi.',
            'min' => 'Kolom :attribute minimal :min karakter.',
            'image' => 'File harus berupa gambar.',
            'mimes' => 'Format file :attribute harus jpeg, png, jpg, gif, atau svg.',
            'max' => 'Ukuran file :attribute tidak boleh lebih dari :max kilobytes.',
            'numeric' => 'Kolom :attribute harus berupa angka.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['messages' => 'Gagal Input', 'errors' => $validator->errors()], 400);
        }

        $mobil = Mobil::findOrFail($id);
        $mobil->jenisMobil = $request->jenisMobil;
        $mobil->tipeMobil = $request->tipeMobil;
        $mobil->transmisi = $request->transmisi;
        $mobil->harga = $request->harga;

        if ($request->hasFile('fotoMobil')) {
            $file = $request->file('fotoMobil');
            $filePath = $file->store('foto_mobil', 'public');
            $mobil->fotoMobil = $filePath;
        }

        $mobil->save();

        return redirect()->route('dashboard')->with('status', 'Data mobil berhasil diperbarui!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mobil = Mobil::findOrFail($id);
        $mobil->delete();

        return redirect()->route('dashboard')->with('status', 'Data mobil berhasil dihapus!');
    }
}
