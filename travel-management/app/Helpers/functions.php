<?php

if (!function_exists('generateMathQuestion')) {
    function generateMathQuestion(): array
    {
        $a = rand(1, 10);
        $b = rand(1, 10);
        $operators = ['+', '-', '*'];
        $op = $operators[array_rand($operators)];

        switch ($op) {
            case '+':
                $result = $a + $b;
                $question = "$a + $b";
                break;
            case '-':
                if ($a < $b) { $temp = $a; $a = $b; $b = $temp; }
                $result = $a - $b;
                $question = "$a - $b";
                break;
            case '*':
                $result = $a * $b;
                $question = "$a × $b";
                break;
            default:
                $result = $a + $b;
                $question = "$a + $b";
        }

        return [
            'question' => "What is $question?",
            'answer' => $result
        ];
    }
}