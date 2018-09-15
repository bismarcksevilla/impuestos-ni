# **IMPUESTOS-NI**

Librería php para estandarizar los cálculos de impuestos en nicaragua.
# INSS
- Los porcentajes para calcular el Inss sufren ajustes cada seis meses, por lo tanto, se incluye un archivo JSON actualizado en el repositorio para realizar este cálculo correctamente. Opcionalmente puede indicar estos valores.  
`$Inss = new Impuestos\Inss(new DateTime( $obj_datetime ));`  

- Historial de valores  
`$Inss->setHistorial( $json );`  

- Parametros del $json:
```
"2017-01-01 00:00:00":{ //Marca de tiempo
    "patronal": 19,
    "empleado": 6.25,
    "maximo": 80000,
    "minimo": 4200
}
```

- Establecer Salario.   
`$Inss->setSalario($salario);`  

- Recuperar valores.  
`$Ir->getInssEmpleado()`  
`$Ir->getInssPatronal()`  
`$Ir->getCiclo()->format('d/m/Y')`  

# IR
- El constructor de la clase 'Ir' recibe por parámetro un arreglo con los pagos recibidos en el año (máximo 12), ordenados del más antiguo al más reciente, con el fin de minimizar la cantidad proyectada y el cálculo anual resulte lo más preciso posible.  
`$Ir = new Impuestos\Ir( $array_pagos_mensuales);`

- El segundo parámetro es opcional y recibe un booleano, por defecto 'true': Deduce el INSS del salario mensual en este caso los cálculos de la clase inss están disponibles para el último salario del arreglo (último salario pagado).  
`$Ir->getInssEmpleado()`  
`$Ir->getInssPatronal()`  
`$Ir->getCiclo()->format('d/m/Y')`

- Recuperar total de pagos (máximo 12).  
`$Ir->getCantidadPagos();`

- Recuperar pago actual (último del arreglo).  
`$Ir->getSalario();`



**Ejemplo ver archivo "index.php"**