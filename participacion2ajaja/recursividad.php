<?php 
//crea una funcion recursiva que cuente cuantas veces aparece un numero especifico detro de un arreglo
function contarnumero($arreglo, $numero, $indice = 0) {
    // caso base: si el índice supera la longitud del arreglo, retornar 0
    if ($indice >= count($arreglo)) {
        return 0;
    }
    
    // Si el elemento en el índice actual es igual al número  buscado, sumar 1 y continuar con el siguiente índice
    if ($arreglo[$indice] == $numero) {
        return 1 + contarnumero($arreglo, $numero, $indice + 1);
    } else {
        //si no es igual, simplemente continuar con el numero siguiente, sin hacer nada
      return contarnumero($arreglo, $numero, $indice +1);
}
}
//esto es practicamente el ejermplo donde se usa con echo para mostrar el resultado de esta funcion
echo ("1. el numero 4 aparece " . contarnumero([1, 2, 3, 4, 5, 3, 6], 4) . " veces en el arreglo."."<br>");

//Realiza una función recursiva que encuentre el número menor dentro de un arreglo de enteros.
function numeromenor($arreglo, $indice = 0){
    // caso base: si el indice supera la longitud del arreglo, retornar un valor muy alto para que no afecte el resultado
    if ($indice >= count ($arreglo ))
    {
        return PHP_INT_MAX;
    }
    //obtener el numero menor del resto del arreglo
    $menorresto = numeromenor($arreglo, $indice + 1);
    //comparar el numero actual con el numero menor del resto del arreglo y retornar el menor       
    return min($arreglo[$indice], $menorresto);
}
//esto es practicamente el ejermplo donde se usa con echo para mostrar el resultado de la funcion
echo ("2. el numero menor en el arreglo es: " .numeromenor([5,3,6,7,4]) . "<br>");

//crea una funcion recursiva que repita una cadena de texto un numero especifico de veces 
function repetirtexto($texto, $repetir, $contador = 0) {
    // caso base: si el contador es igual al numero de repeticiones, retornar una cadena vacia
    if ($contador >= $repetir) {
        return "";
    }
    // concatenar el texto con el resultado de la funcion recursiva incrementando el contador
    return $texto . repetirtexto($texto, $repetir, $contador + 1);
}
// esto es...ya para que lo repito si ya lo dije antes
echo ("3. el texto que se repite es: en la grasa hay un papu <br> " . repetirtexto("en la grasa hay un papu <br>", 1) . "<br>");

?>