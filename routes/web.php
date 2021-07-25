<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Unit;
use App\Http\Livewire\Tenant;
use App\Http\Livewire\Visitor;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/dashboard');

});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return redirect(route('unit.list'));
    })->name('dashboard');

    Route::get('unit', Unit::class)->name('unit.list');
    Route::get('tenant/list/', Tenant::class)->name('tenant.list');
    // Route::get('visitor', Visitor::class)->name('visitor.list');
    
    Route::match(['get', 'post'], 'visitor', Visitor::class)->name('visitor.list');

});


