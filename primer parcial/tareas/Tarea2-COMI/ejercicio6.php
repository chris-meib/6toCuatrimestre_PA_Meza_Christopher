<?php

// crear número decimal a binario.
function decimalABinario($decimal) {
    // Caso base: si el número es 0, el resultado es "0"
    if ($decimal == 0) {
        return "0";
    }
    // Caso recursivo: dividir el número por 2 y concatenar el resto
    return decimalABinario(intval($decimal / 2)) . ($decimal % 2);
}
// Ejemplo de uso
echo ("El número 10 en binario es: ") . (decimalABinario(10));
?>