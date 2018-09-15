<?php
namespace Impuestos;

use Impuestos\Inss;

/**
 *
 * @author Bismarck Sevilla <digital.nicaragua@gmail.com>
 *
 * Calcular IR Anual.
 * Procesa ultimos 12 Pagos.
 * Debe exluir el impuesto del Inss.
 */
class Ir extends Inss
{
    /**
     * Historial de Pagos
     *
     * @access protected
     * @var array
     */
    private $pagos = [];

    /**
     * Pago sin Inss
     *
     * @access protected
     * @var array
     */
    private $pagosInss = [];

    /**
     * Calcula Inss de los pagos
     *
     * @access protected
     * @var boolean
     */
    private $calcularInss;

    /**
     * Salario Promedio según los pagos.
     *
     * @access protected
     * @var float
     */
    private $promedio;

    /**
     * Forma de Pago | Quincenal, Semanal, Mensual.
     *
     * @access protected
     * @var string
     */
    private $modo="Mensual";

    /**
     * Impuestos según tabla establecida
     * en el Arto.52 de la Ley 822
     *
     * @access protected
     * @var object
     */
    private $tabla;

    /**
     * Valor Ir
     * @access protected
     * @var object
     */
    private $impuesto;


    # # # # # # # # # #
    # # P U B L I C # #
    # # # # # # # # # #


    /**
     * @access public
     * @param array $pagos  // Historial de Pagos
     * @param string $modo  // Forma de Pago
     * @param boolean $inss // Calcula Inss
     * @param object $json_tabla_impuestos // Establecida en el Arto.52 de la Ley 822
     */
    public function __construct($pagos=false, $calcularInss=true, $json_tabla_impuestos=false)
    {
        $this->setPagos($pagos);

        $this->setTabla($json_tabla_impuestos);

        $this->setCalcularInss($calcularInss);

        $this->setHistorial();
        $this->setFecha();
    }

    /**
     * @access public
     * @param array $pagos
     * @return object
     */
    public function setPagos($pagos)
    {
        foreach ($pagos as $fecha => $pago) {
            if ($pago>0) {
                $this->addPago($pago, $fecha);

                if ($this->getCalcularInss()) {
                    $this->addPagoInss($pago, $fecha);
                }
                $this->setSalario($pago);
            }
        }
        return $this;
    }


    /**
     * @access public
     * @return array
     */
    public function getPagos()
    {
        return $this->pagos;
    }

    /**
     * @access public
     * @param string $modo
     * @return object
     */
    public function setModo($modo)
    {
        if ($modo=='Mensual' || $modo=='Semanal' || $modo=='Quincenal') {
            $this->modo = $modo;
        }
        return $this;
    }

    /**
     * @access public
     * @return string
     */
    public function getModo()
    {
        return $this->modo;
    }

    /**
     * @access public
     * @return float
     */
    public function getPromedio()
    {
        if ($this->promedio) {
            return $this->promedio;
        } else {
            $this->promedio = $this->getTotalPagado() / $this->getCantidadPagos();
            return $this->promedio;
        }
    }

    /**
     * @access private
     * @param string $path
     * @return object
     */
    public function setTabla($json= false)
    {
        if ($json) {
            $this->tabla = json_decode($json);
        } else {
            $this->tabla = json_decode(file_get_contents('src/data/ir_tabla_impuestos.json'));
        }
        return $this;
    }

    public function getTabla()
    {
        return $this->tabla;
    }


    public function getImpuesto()
    {
        if ($this->impuesto) {
            return $this->impuesto;
        } else {
            foreach ($this->getTabla() as $key => $object) {
                if ($this->getProyeccion() >= $object->Desde) {
                    $this->impuesto = $object;
                }
            }
            return $this->impuesto;
        }
    }


    public function getIr()
    {
        return
            ((($this->getProyeccion()-$this->getImpuesto()->Exceso)
            *
            ($this->getImpuesto()->Porcentaje/100)) + $this->getImpuesto()->Base) /12;
    }


    # # # # # # # # # # #
    # # P R I V A T E # #
    # # # # # # # # # # #

    /**
     * @access private
     * @param float $pago
     * @return object
     */
    private function addPago($pago, $fecha="")
    {
        $this->pagos[$fecha] = $pago;

        return $this;
    }

    /**
     * @access private
     * @param float $pago
     * @return object
     */
    private function addPagoInss($pago, $fecha="")
    {
        $this->setSalario($pago);
        $this->setFecha($fecha);

        $this->pagosInss[$fecha] = $pago - $this->getImpuestoEmpleado();

        return $this;
    }


    /**
     * Proyectar Salario
     *
     * @access public
     * @return float
     */
    public function getProyeccion()
    {
        if ($this->getModo() == "Quincenal") {
            return $this->getPromedio() * 24;
        } elseif ($this->getModo() == "Semanal") {
            return $this->getPromedio() * 52;
        } else {
            return $this->getPromedio() * 12;
        }
    }

    /**
     * @access public
     * @return int
     */
    public function getCantidadPagos()
    {
        return count($this->pagos);
    }

    /**
     * @access private
     * @return float
     */
    public function getTotalPagado()
    {
        $total = 0;

        foreach ($this->pagos as $valor) {
            $total += $valor;
        }
        return $total;
    }

    /**
    * @access private
    * @param boolean
    * @return object
    */
    private function setCalcularInss($bool)
    {
        $this->calcularInss = $bool;

        return $this;
    }

    /**
     * @access private
     * @return boolean
     */
    private function getCalcularInss()
    {
        return $this->calcularInss;
    }
}
