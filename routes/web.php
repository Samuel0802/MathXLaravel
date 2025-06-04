<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

route::get('/', [MainController::class, 'home'])->name('home');
route::post('/gerarExercicios', [MainController::class, 'gerarExercicios'])->name('gerarExercicios');
route::get('/printExercicios', [MainController::class, 'printExercicios'])->name('printExercicios');
route::get('/exportExercicios', [MainController::class, 'exportExercicios'])->name('exportExercicios');

