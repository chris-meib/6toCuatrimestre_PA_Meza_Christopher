<?php
//algoritmo de Euclides utilizando recursividad para calcular el MCD entredos números.
function mcd($a, $b) {
    // Caso base: si b es 0, el MCD es a
    if ($b == 0) {
        return $a;
    }
    // Caso recursivo: llamar a la función con b y el resto de a dividido por b
    return mcd($b, $a % $b);
}
// Ejemplo de uso
echo ("El MCD de 48 y 18 es: ") . (mcd(48, 18));
?>