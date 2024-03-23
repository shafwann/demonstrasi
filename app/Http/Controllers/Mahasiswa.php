<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa as ModelsMahasiswa;
use Illuminate\Http\Request;

class Mahasiswa extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function admin()
    {
        $mahasiswa = ModelsMahasiswa::all()->sortByDesc('nim');

        return view('admin', ['mahasiswa' => $mahasiswa]);
    }

    public function tambahMahasiswa(Request $request)
    {
        try {
            $request->validate([
                'nim' => 'required|numeric',
                'nama' => 'required|max:50',
                'alamat' => 'required',
                'tgl_lahir' => 'required|date',
                'gender' => 'required|in:L,P',
                'usia' => 'required|numeric',
            ]);

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
            $mahasiswa = ModelsMahasiswa::find($id);

            if ($mahasiswa) {
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

    public function editMahasiswa($id)
    {
        try {
            $mahasiswa = ModelsMahasiswa::find($id);

            if ($mahasiswa) {
                return view('edit', ['mahasiswa' => $mahasiswa]);
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