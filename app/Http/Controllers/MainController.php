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
        'number_one' => 'required|integer|min:0|max:999|lt:number_two',
        'number_two' => 'required|integer|min:0|max:999',
        'number_exercises' => 'required|integer|min:5|max:50',

        ];

        $feedback = [

        ];

        $request->validate($regra, $feedback);

        //identificar quais operações matemáticas o usuário escolheu em um formulário (via checkbox)
        $operations = [];

        if($request->check_sum){
            $operations[] = 'sum';
        }

        if($request->check_subtraction){
            $operations[] = 'subtraction';
        }
        if($request->check_multiplication){
            $operations[] = 'multiplication';
        }

        if($request->check_division){
            $operations[] = 'division';
        }



        //numeros (min e max)
        $min = $request->number_one;
        $max = $request->number_two;

        //quantidade de exercícios
        $numberExercises = $request->number_exercises;

        //gerar exercicios
        $exercises = [];

        for($i = 1; $i <= $numberExercises; $i++){

            //array_rand: Sortear todas as operações matemáticas
            $operation = $operations[array_rand($operations)];

            //rand: sortear valor min e max
            $number1 = rand($min, $max);
            $number2 = rand($min, $max);

            $exercise = '';
            $sollution = '';

            switch($operation){
               case 'sum':
                 $exercise = "$number1 + $number2 = ";
                 $sollution = $number1 + $number2;
                 break;

                 case 'subtraction':
                 $exercise = "$number1 - $number2 = ";
                 $sollution = $number1 - $number2;
                 break;


                 case 'multiplication':
                 $exercise = "$number1 x $number2 = ";
                 $sollution = $number1 * $number2;
                 break;

                 case 'division':

                     //evitar que divisão por zero pode acontecer
                     if($number2 == 0){
                        $number2 = 1;
                     }

                 $exercise = "$number1 : $number2 = ";
                 $sollution = $number1 / $number2;
                 break;
            }

            //Adicionando 2 casa decimal na solução
            if(is_float($sollution)){
                $sollution = round($sollution, 2);
            }

             $exercises[] = [
                'operation' => $operation,
                'exercise_number' => $i,
                'exercise' => $exercise,
                'sollution' => "$exercise $sollution"
             ];

        }

        dd($exercises);

    }

    public function printExercicios(){
        echo 'Imprimir exercicios';
    }

    public function exportExercicios(){
        echo 'exportar exercicios para um arquivo de texto';
    }
}
