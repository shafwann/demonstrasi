<?php

use App\Http\Controllers\Mahasiswa;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// INDEX
Route::get('/', [Mahasiswa::class, 'index']);

// HOME
Route::get('/home', [Mahasiswa::class, 'home']);
Route::get('/pencarian', [Mahasiswa::class, 'pencarian']);

// ADMIN
Route::get('/admin', [Mahasiswa::class, 'admin']);
Route::post('/admin/tambah-mahasiswa', [Mahasiswa::class, 'tambahMahasiswa']);
Route::get('/admin/hapus-mahasiswa/{id}', [Mahasiswa::class, 'hapusMahasiswa']);
Route::put('/admin/edit-mahasiswa/{id}', [Mahasiswa::class, 'editMahasiswa']);