<?php
//Crea una función recursiva que determine cuántos caracteres tiene una cadena de texto.
function contarCaracteres($cadena) {
    // Caso base: si la cadena está vacía, el resultado es 0
    if ($cadena == "") {
        return 0;
    }
    // Caso recursivo: sumar 1 y llamar a la función con la cadena sin el primer carácter
    return 1 + contarCaracteres(substr($cadena, 1));
}
// Ejemplo de uso
echo ("La frase 'Hola, mundo!' tiene ") . (contarCaracteres("Hola, mundo!")) . (" caracteres."); 
?>