<?php
//función recursiva que calcule la suma de todos los números pares desde 0 hasta n.
function sumaPares($n) {
    // Caso base: si n es menor que 0, la suma es 0
    if ($n < 0) {
        return 0;
    }
    // Caso recursivo: si n es par, sumar n con la suma de los pares anteriores; si n es impar, simplemente llamar a la función con n-1
    if ($n % 2 == 0) {
        return $n + sumaPares($n - 2);
    } else {
        return sumaPares($n - 1);
    }
}   
// Ejemplo de uso
echo ("La suma de los números pares del 0 al 10 es: ") . (sumaPares(10));
?>