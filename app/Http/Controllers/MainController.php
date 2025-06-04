<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function home(){
       return view('pages.home');
    }

    public function gerarExercicios(Request $request){


        $regra = [
        'check_sum' => 'required_without_all::check_subtraction,check_multiplication,check_division',
        'check_subtraction' => 'required_without_all::check_sum,check_multiplication,check_division',
        'check_multiplication' => 'required_without_all::check_sum,check_subtraction,check_division',
        'check_division' => 'required_without_all::check_sum,check_subtraction,check_multiplication',
        'number_one' => 'required|integer|min:0|max:999',
        'number_two' => 'required|integer|min:0|max:999',
        'number_exercises' => 'required|integer|min:5|max:50',

        ];

        $feedback = [

        ];



        $request->validate($regra, $feedback);

          dd($request->all());
    }

    public function printExercicios(){
        echo 'Imprimir exercicios';
    }

    public function exportExercicios(){
        echo 'exportar exercicios para um arquivo de texto';
    }
}
