<?php

 namespace Impuestos;

/**
 * @author Bismarck Sevilla <digital.nicaragua@gmail.com>
 *
 * Calcular Impuestos para el seguro social.
 *
 * Nota:
 * Es posible consultar el impuesto en base a una fecha
 * antigua, en este caso se debe recurrir a la definición
 * de porcentajes de impuestos almacenada en un historial
 * para que el calculo sea correcto.
 */
class Inss
{
    /**
     * @access protected
     * @var float
     */
    private $salario;

    /**
     * @access protected
     * @var object datetime
     */
    private $fecha;

    /**
     * @access protected
     * @var object
     */
    private $historial;

    /**
     * @access protected
     * @var object
     */
    private $impuestos;


    # # # # # # # # # #
    # # P U B L I C # #
    # # # # # # # # # #


    /**
     * @access public
     * @param DateTime $obj_DateTime
     * @param string $path_to_histoy
     */
    public function __construct($obj_DateTime= false, $json_historial= false)
    {
        $this->setHistorial($json_historial);

        $this->setFecha($obj_DateTime);
    }

    /**
     * @access public
     * @param float $salario
     * @return object
     */
    public function setSalario($salario)
    {
        $this->salario = (float) $salario;

        return $this;
    }

    /**
     * @access public
     * @return float
     */
    public function getSalario()
    {
        return $this->salario;
    }

    /**
     * @access public
     * @param object $DateTime
     * @return object
     */
    public function setFecha($DateTime = false)
    {
        if ($DateTime) {
            $this->fecha = $DateTime  ;
        } else {
            $this->fecha = new \DateTime('NOW');
        }

        return $this;
    }

    /**
     * @access public
     * @return mixto
     */
    public function getFecha($format = false)
    {
        if ($format) {
            return  $this->fecha->format($format);
        } else {
            return  $this->fecha;
        }
    }

    /**
     * @access public
     * @return mixto
     */
    public function getCiclo($format = false)
    {
        if ($format) {
            return  $this->getImpuestos()->InicioCiclo->format($format);
        } else {
            return  $this->getImpuestos()->InicioCiclo;
        }
    }

    /**
     * @access public
     * @return float
     */
    public function getInssPatronal()
    {
        if ($this->salario <= $this->getMinimo()) {
            return $this->getMinimo()* ($this->getImpuestoPatronal()/100);
        } elseif ($this->salario >= $this->getMaximo()) {
            return  $this->getMaximo() * ($this->getImpuestoPatronal()/100);
        } else {
            return $this->salario * ($this->getImpuestoPatronal()/100);
        }
    }

    /**
     * @access public
     * @return float
     */
    public function getInssEmpleado()
    {
        if ($this->salario <= $this->getMinimo()) {
            return $this->getMinimo()* ($this->getImpuestoEmpleado()/100);
        } elseif ($this->salario >= $this->getMaximo()) {
            return  $this->getMaximo() * ($this->getImpuestoEmpleado()/100);
        } else {
            return $this->salario * ($this->getImpuestoEmpleado()/100);
        }
    }


    /**
     * @access private
     * @param string $path
     * @return object
     */
    public function setHistorial($json= false)
    {
        if ($json) {
            $this->historial = json_decode($json);
        } else {
            $this->historial = json_decode(file_get_contents('src/data/inss_historial.json'));
        }

        return $this;
    }

    /**
     * @access private
     * @return array
     */
    public function getHistorial()
    {
        return  $this->historial;
    }

    # # # # # # # # # # #
    # # P R I V A T E # #
    # # # # # # # # # # #


    /**
     * @access private
     * @return float
     */
    private function getMinimo()
    {
        return  $this->getImpuestos()->minimo;
    }

    /**
     * @access private
     * @return float
     */
    private function getMaximo()
    {
        return  $this->getImpuestos()->maximo;
    }

    /**
     * Porcentaje de Impuesto Inss
     *
     * @access private
     * @return float
     */
    private function getImpuestoPatronal()
    {
        return  $this->getImpuestos()->patronal;
    }

    /**
     * Porcentaje de Impuesto Inss
     *
     * @access private
     * @return float
     */
    private function getImpuestoEmpleado()
    {
        return  $this->getImpuestos()->empleado;
    }

    /**
     * Recupera el impuesta según el reango de fechas
     * de historial de impuestos JSON
     *
     * @access private
     * @return object
     */
    private function getImpuestos()
    {
        if ($this->impuestos) {
            return $this->impuestos;
        } else {
            foreach ($this->getHistorial() as $fecha => $obj) {
                $date = new \DateTime($fecha);

                if ($this->getFecha() > $date) {
                    $obj->InicioCiclo = $date;
                    $this->impuestos = $obj;
                }
            }
        }
        return  $this->impuestos;
    }
}