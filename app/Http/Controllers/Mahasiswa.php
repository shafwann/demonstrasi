<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa as ModelsMahasiswa;
use Illuminate\Http\Request;

class Mahasiswa extends Controller
{
    public function index()
    {
        return redirect('/home');
    }

    public function home()
    {
        // Mengambil semua data mahasiswa dan diurutkan berdasarkan NIM secara descending
        $mahasiswa = ModelsMahasiswa::all()->sortByDesc('nim');

        // Menghitung jumlah semua mahasiswa
        $totalMahasiswa = ModelsMahasiswa::count();

        // Menghitung jumlah mahasiswa laki-laki
        $totalMahasiswaLaki = ModelsMahasiswa::where('gender', 'L')->count();

        // Menghitung jumlah mahasiswa perempuan
        $totalMahasiswaPerempuan = ModelsMahasiswa::where('gender', 'P')->count();

        return view('home', ['mahasiswa' => $mahasiswa, 'totalMahasiswa' => $totalMahasiswa, 'totalMahasiswaLaki' => $totalMahasiswaLaki, 'totalMahasiswaPerempuan' => $totalMahasiswaPerempuan]);
    }

    public function pencarian(Request $request)
    {
        // Validasi inputan
        $request->validate([
            'nama' => 'required|max:50',
        ]);

        // Mencari mahasiswa berdasarkan nama sama dengan inputan
        $mahasiswa = ModelsMahasiswa::where('nama', 'like', '%' . $request->nama . '%')->get();

        return view('pencarian', ['mahasiswa' => $mahasiswa]);
    }

    public function admin()
    {
        // Mengambil semua data mahasiswa dan diurutkan berdasarkan NIM secara descending
        $mahasiswa = ModelsMahasiswa::all()->sortByDesc('nim');

        return view('admin', ['mahasiswa' => $mahasiswa]);
    }

    public function tambahMahasiswa(Request $request)
    {
        try {
            // Validasi inputan
            $request->validate([
                'nim' => 'required|numeric',
                'nama' => 'required|max:50',
                'alamat' => 'required',
                'tgl_lahir' => 'required|date',
                'gender' => 'required|in:L,P',
                'usia' => 'required|numeric',
            ]);

            // Menambahkan mahasiswa baru
            $mahasiswa = ModelsMahasiswa::create([
                'nim' => $request->nim,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'tgl_lahir' => $request->tgl_lahir,
                'gender' => $request->gender,
                'usia' => $request->usia,
            ]);

            if ($mahasiswa) {
                $pop = [
                    'head' => 'Berhasil',
                    'body' => 'Mahasiswa Telah Ditambahkan',
                    'status' => 'success'
                ];

                return redirect('/admin')->with('pop-up', $pop);
            } else {
                throw new \Exception('Silakan Cek Kembali Mahasiswa Anda');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $pop = [
                'head' => 'Gagal Menambah Mahasiswa',
                'body' => '<ul class="text-justify"><li>' . implode('</li><li>', $e->validator->errors()->all()) . '</li></ul>',
                'status' => 'error'
            ];
            return redirect()->back()->with('pop-up', $pop);
        } catch (\Exception $e) {
            $pop = [
                'head' => 'Gagal Menambah Mahasiswa',
                'body' => $e->getMessage(),
                'status' => 'error'
            ];
            return redirect()->back()->with('pop-up', $pop);
        }
    }

    public function hapusMahasiswa($id)
    {
        try {
            // Mencari mahasiswa berdasarkan ID
            $mahasiswa = ModelsMahasiswa::find($id);

            if ($mahasiswa) {
                // Menghapus mahasiswa
                $mahasiswa->delete();

                $pop = [
                    'head' => 'Berhasil',
                    'body' => 'Mahasiswa Telah Dihapus',
                    'status' => 'success'
                ];

                return redirect('/admin')->with('pop-up', $pop);
            } else {
                throw new \Exception('Mahasiswa Tidak Ditemukan');
            }
        } catch (\Exception $e) {
            $pop = [
                'head' => 'Gagal Menghapus Mahasiswa',
                'body' => $e->getMessage(),
                'status' => 'error'
            ];
            return redirect()->back()->with('pop-up', $pop);
        }
    }

    public function editMahasiswa($id, Request $request)
    {
        try {
            // Mencari mahasiswa berdasarkan ID
            $mahasiswa = ModelsMahasiswa::find($id);

            // Validasi inputan
            $request->validate([
                'nama' => 'required|max:50',
                'alamat' => 'required',
                'tgl_lahir' => 'required|date',
                'gender' => 'required|in:L,P',
                'usia' => 'required|numeric',
            ]);

            if ($mahasiswa) {
                // Mengedit mahasiswa
                $mahasiswa->update([
                    'nama' => $request->nama,
                    'alamat' => $request->alamat,
                    'tgl_lahir' => $request->tgl_lahir,
                    'gender' => $request->gender,
                    'usia' => $request->usia,
                ]);

                $pop = [
                    'head' => 'Berhasil',
                    'body' => 'Mahasiswa Telah Diedit',
                    'status' => 'success'
                ];

                return redirect('/admin')->with('pop-up', $pop);
            } else {
                throw new \Exception('Mahasiswa Tidak Ditemukan');
            }
        } catch (\Exception $e) {
            $pop = [
                'head' => 'Gagal Mengedit Mahasiswa',
                'body' => $e->getMessage(),
                'status' => 'error'
            ];
            return redirect('/admin')->with('pop-up', $pop);
        }
    }
}
