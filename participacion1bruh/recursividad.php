<?php


function factorial($n) {
    if ($n == 0) {
        return 1;
    } else {
        return $n * factorial($n - 1);
    }
}
function fibonacci($n) {
    if ($n == 0) {
        return 0;
    } elseif ($n == 1) {
        return 1;
    } else {
        return fibonacci($n - 1) + fibonacci($n - 2); 
    }

    }

function invertirCadena($cadena) {
    if (strlen($cadena) == 0) {
        return "";
    } else {
        return invertirCadena(substr($cadena, 1)) . $cadena[0];
    }
}

echo "Factorial de 5: " . factorial(5) . "<br>";
echo "Fibonacci de 10: " . fibonacci(10) . "<br>";
echo "Invertir 'Hola Mundo': " . invertirCadena("Hola Mundo") . "<br>";
?>