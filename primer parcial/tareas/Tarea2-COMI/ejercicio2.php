<?php
//Realiza una función recursiva que multiplique dos números enteros utilizando únicamente sumas.
function multiplicar($a, $b) {
    // Caso base: si uno de los números es 0, el resultado es 0
    if ($a == 0 || $b == 0) {
        return 0;
    }
    // Caso recursivo: sumar $a consigo mismo $b veces
    return $a + multiplicar($a, $b - 1);
}
// Ejemplo de uso
echo ("El resultado de multiplicar 5 y 3 es: ") . (multiplicar(5, 3));
?>