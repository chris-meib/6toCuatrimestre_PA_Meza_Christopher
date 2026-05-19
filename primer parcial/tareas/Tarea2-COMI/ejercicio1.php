<?php
//crea una funcion recursiva que calcule la potencia de un número.

function potencia($base, $exponente) {
    // Caso base: cualquier número elevado a la potencia de 0 es 1
    if ($exponente == 0) {
        return 1;
    }
    // Caso recursivo: multiplicar la base por la potencia de la base con el exponente reducido en 1
    return $base * potencia($base, $exponente - 1);
}
// Ejemplo de uso
echo ("2 elevado a la potencia de 3 es: ") . (potencia(2, 3)); // Imprime 8
?>