<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function gerarExercicios(Request $request): View
    {


        $regra = [
            'check_sum' => 'required_without_all::check_subtraction,check_multiplication,check_division',
            'check_subtraction' => 'required_without_all::check_sum,check_multiplication,check_division',
            'check_multiplication' => 'required_without_all::check_sum,check_subtraction,check_division',
            'check_division' => 'required_without_all::check_sum,check_subtraction,check_multiplication',
            'number_one' => 'required|integer|min:0|max:999|lt:number_two',
            'number_two' => 'required|integer|min:0|max:999',
            'number_exercises' => 'required|integer|min:5|max:50',

        ];


        $request->validate($regra);

        //identificar quais operações matemáticas o usuário escolheu em um formulário (via checkbox)
        $operations = [];

        if ($request->check_sum) {
            $operations[] = 'sum';
        }

        if ($request->check_subtraction) {
            $operations[] = 'subtraction';
        }
        if ($request->check_multiplication) {
            $operations[] = 'multiplication';
        }

        if ($request->check_division) {
            $operations[] = 'division';
        }

        //Caso tenha operação vazio, volta para tela home
        if (empty($operations)) {
            return view('pages.home')->with('exercises', []);
        }


        //numeros (min e max)
        $min = $request->number_one;
        $max = $request->number_two;

        //quantidade de exercícios
        $numberExercises = $request->number_exercises;

        //gerar exercicios
        $exercises = [];

        for ($i = 1; $i <= $numberExercises; $i++) {
            $exercises[] = $this->generateExercises($i, $operations, $min, $max);
        }

        //Guardar os exercícios na sessão
        session(['exercises' => $exercises]);



        return view('pages.operations', ['exercises' => $exercises]);
    }

    public function printExercicios()
    {
        //verificar se existe exercicios na sessão
        if(!session()->has('exercises')){
            return redirect()->route('home');
        }

        // recuperando da sessão uma variável
        $exercises = session('exercises');

        //imprindo na tela
        echo '<pre>';
        echo '<h1>Exercicios de matemática (' . env('APP_NAME') .') </h1>';
         echo '<hr>';

         foreach($exercises as $exercise){
          echo '<h2><small>' . str_pad($exercise['exercise_number'], 2, "0", STR_PAD_LEFT) . ' >> </small> ' . $exercise['exercise'] .'</h2>';
         }

         //solução
         echo '<hr>';
         echo '<small>Soluções</small><br>';

            foreach($exercises as $exercise){
          echo '<small>' . str_pad($exercise['exercise_number'], 2, "0", STR_PAD_LEFT) . ' >>  ' . $exercise['sollution'] .'</small><br>';
         }




    }

    public function exportExercicios()
    {
        echo 'exportar exercicios para um arquivo de texto';
    }


    private function generateExercises($i, $operations, $min, $max): array
    {

        //array_rand: Sortear todas as operações matemáticas
        $operation = $operations[array_rand($operations)];

        //rand: sortear valor min e max
        $number1 = rand($min, $max);
        $number2 = rand($min, $max);

        $exercise = '';
        $sollution = '';

        switch ($operation) {
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
                if ($number2 == 0) {
                    $number2 = 1;
                }

                $exercise = "$number1 : $number2 = ";
                $sollution = $number1 / $number2;
                break;
        }

        //Adicionando 2 casa decimal na solução
        if (is_float($sollution)) {
            $sollution = round($sollution, 2);
        }

        return [
            'operation' => $operation,
            'exercise_number' => $i,
            'exercise' => $exercise,
            'sollution' => "$exercise $sollution"
        ];
    }
}
