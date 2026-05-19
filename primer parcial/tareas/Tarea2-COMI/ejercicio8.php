<?php
//función recursiva que determine si un elemento existe dentro de un arreglo.
function elementoEnArreglo($arreglo, $elemento) {
    // Caso base: si el arreglo está vacío, el elemento no existe
    if (empty($arreglo)) {
        return false;
    }
    // Caso recursivo: verificar si el primer elemento del arreglo es el elemento buscado
    if ($arreglo[0] == $elemento) {
        return true;
    }
    // Llamar a la función con el resto del arreglo
    return elementoEnArreglo(array_slice($arreglo, 1), $elemento);
}   
// Ejemplo de uso
$arreglo = [1, 2, 3, 4, 5];
echo ("el arreglo es [1, 2, 3, 4, 5]") . "<br>";
echo ("El número 3 ") . (elementoEnArreglo($arreglo, 3) ? "fue encontrado" : "no fue encontrado") . "<br>";
echo ("El número 6 ") . (elementoEnArreglo($arreglo, 6) ? "fue encontrado" : "no fue encontrado") . "<br>";
?>