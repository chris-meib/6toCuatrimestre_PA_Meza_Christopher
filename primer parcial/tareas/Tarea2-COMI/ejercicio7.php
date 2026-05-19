<?php
//función recursiva que sume todos los elementos de un arreglo.
function sumarArreglo($arreglo) {
    // Caso base: si el arreglo está vacío, el resultado es 0
    if (empty($arreglo)) {
        return 0;
    }
    // Caso recursivo: sumar el primer elemento del arreglo con la suma del resto del arreglo
    return $arreglo[0] + sumarArreglo(array_slice($arreglo, 1));
}   
// Ejemplo de uso
$arreglo = [1, 2, 3, 4, 5];
echo ("La suma de los elementos del arreglo es: ") . (sumarArreglo($arreglo));
?>