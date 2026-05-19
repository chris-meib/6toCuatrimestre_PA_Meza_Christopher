<?php
//función recursiva que cuente cuántas vocales contiene una cadena de texto
    function vocales($cadena) {
        // Eliminar espacios y convertir a minúsculas
        $cadena = str_replace(' ', '', strtolower($cadena));
        
        // Caso base: si la cadena está vacía, el resultado es 0
        if ($cadena == "") {
            return 0;
        }
        
        // Verificar si el primer carácter es una vocal
        $vocales = ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'];
        $contador = in_array($cadena[0], $vocales) ? 1 : 0;
        
        // Llamar a la función con la cadena sin el primer carácter
        return $contador + vocales(substr($cadena, 1));
    }
    // Ejemplo de uso
    echo ("La cadena 'Hola, mundo!' contiene ") . (vocales("Hola, mundo!")) . (" vocales.");
?>