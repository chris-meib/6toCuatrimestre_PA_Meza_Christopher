<?php
//Crea una función recursiva que determine si una palabra o frase es un palíndromo
function Palindromo($cadena) {
    // Eliminar espacios y convertir a minúsculas
    $cadena = str_replace(' ', '', strtolower($cadena));
    
    // Caso base: si la cadena tiene 0 o 1 caracteres, es un palíndromo
    if (strlen($cadena) <= 1) {
        return true;
    }
    
    // Comparar el primer y último carácter
    if ($cadena[0] == $cadena[strlen($cadena) - 1]) {
        // Llamar a la función con la cadena sin el primer y último carácter
        return Palindromo(substr($cadena, 1, -1));
    }
    
    // Si los caracteres no coinciden, no es un palíndromo
    return false;
}
// Ejemplo de uso
echo ("'Ana lava lana' es un palíndromo: ") . (Palindromo("Ana lava lana") ? "Sí" : "No <br/>");
echo ("'la casa es grande' es un palíndromo: ") . (Palindromo("la casa es grande") ? "Sí" : "No <br/>");
echo ("'Anita lava la tina' es un palíndromo: ") . (Palindromo("Anita lava la tina") ? "Sí" : "No <br/>");
?>