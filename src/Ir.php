<?php
namespace Impuestos;

/**
 * Calcular IR Anual.
 */
class Ir
{

    # Sueldo
    private $pagos = [];



    public function calcular( $data, $Obj = false ){

        # Salario Ir
        $Salario = $data->TotalIngresos - $data->InssEmpleado;

        if( $Salario >0 ):

            # Tabla de Calculos IR Vigente
            $IR[5] = [ "Desde" =>0,          "Porcentaje"=>0,  "Base"=>0,     "Exceso"=>0 ];
            $IR[4] = [ "Desde" =>100000.001, "Porcentaje"=>15, "Base"=>0,     "Exceso"=>100000 ];
            $IR[3] = [ "Desde" =>200000.001, "Porcentaje"=>20, "Base"=>15000, "Exceso"=>200000 ];
            $IR[2] = [ "Desde" =>350000.001, "Porcentaje"=>25, "Base"=>45000, "Exceso"=>350000 ];
            $IR[1] = [ "Desde" =>500000.001, "Porcentaje"=>30, "Base"=>82500, "Exceso"=>500000 ];

            # Ir Anual Exacto
            if( @$Obj ):

                # Calcula discriminando los pagos recibidos.



            # Ir Proyectado Anual
            else:

                # Proyectar
                if( $data->Ciclo == "Quincenal"):
                    $SalarioAnio  = $Salario * 24;

                elseif( $data->Ciclo == "Semanal"):
                    $SalarioAnio  = $Salario * 52;

                elseif( $data->Ciclo == "Mensual"):
                    $SalarioAnio  = $Salario * 12;

                else: # Mensual Default

                    $SalarioAnio  = $Salario * 12;
                endif;

            endif;


            # Identificar el rango de la matriz
            switch ( $SalarioAnio ):

                case ( $SalarioAnio  >= $IR[1]["Desde"] ):
                    $rango = 1;
                    break;

                case ( $SalarioAnio  >= $IR[2]["Desde"] ):
                    $rango = 2;
                    break;

                case ( $SalarioAnio  >= $IR[3]["Desde"] ):
                    $rango = 3;
                    break;

                case ( $SalarioAnio  >= $IR[4]["Desde"] ):
                    $rango = 4;
                    break;

                case ( $SalarioAnio  >= $IR[5]["Desde"] ):
                    $rango = 5;
                    break;

                default:
                    $rango = 5;
                    break;

            endswitch;


            $valor = (  ( $SalarioAnio - $IR[$rango]["Exceso"] ) *
                     ( $IR[$rango]["Porcentaje"]/100) ) + $IR[$rango]["Base"] ;


            # Calcula discriminando los pagos recibidos.
            if( @$Obj ):

                //

            else:

                # Proyectar Anio
                if( $data->Ciclo == "Quincenal"):

                    return $valor/24;

                elseif( $data->Ciclo == "Semanal"):

                    return $valor/52;

                else: # Mensual

                    return $valor/12;
                endif;

            endif;

        else:

            return 0;

        endif;

    } # END


}
