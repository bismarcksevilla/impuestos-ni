<?php
namespace Impuestos;

/**
 *
 * @author Bismarck Sevilla <digital.nicaragua@gmail.com>
 *
 * Calcular IR Anual.
 * Procesa ultimos 12 Pagos.
 * Debe exluir el impuesto del Inss.
 */
class Ir
{
    /**
     *
     * @access protected
     * @var array
     */
    private $pagos = [];

    /**
     *
     * @access protected
     * @var float
     */
    private $promedio;

    /**
     *
     * @access protected
     * @var string
     */
    private $modo;

    /**
     *
     * @access protected
     * @var object
     */
    private $impuestos;

    /**
     *
     * @access protected
     * @var object
     */
    private $impuesto;


    # # # # # # # # # #
    # # P U B L I C # #
    # # # # # # # # # #


    /**
     * @access public
     * @param array $pagos
     * @param string $modo
     * @param object $json_tabla_impuestos
     */
    public function __construct($pagos=false, $modo='Mensual', $json_tabla_impuestos=false)
    {
        $this->setPagos($pagos);

        $this->setModo($modo);

        $this->setImpuestos($json_tabla_impuestos);
    }

    /**
     * @access public
     * @param array $pagos
     * @return object
     */
    public function setPagos($pagos)
    {
        foreach ($pagos as $pago) {
            if ($pago) {
                $this->addPago($pago);
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
        } else {
            $this->modo = "Mensual";
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
            return $this->calcularPromedio();
        }
    }

    /**
     * @access private
     * @param string $path
     * @return object
     */
    public function setImpuestos($json= false)
    {
        if ($json) {
            $this->impuestos = json_decode($json);
        } else {
            $this->impuestos = json_decode(file_get_contents('src/data/ir_tabla_impuestos.json'));
        }
        return $this;
    }

    public function getImpuestos(){
        return $this->impuestos;
    }


    public function getImpuesto()
    {
        if ($this->impuesto) {
            return $this->impuesto;
        } else {
            foreach ($this->getImpuestos() as $key => $object) {
                if ($this->getSalarioAnual() >= $object->Desde) {
                    $this->impuesto = $object;
                }
            }
            return $this->impuesto;
        }
    }


    public function get(){
        return ((($this->getSalarioAnual()-$this->getImpuesto()->Exceso) * ($this->getImpuesto()->Porcentaje/100)) + $this->getImpuesto()->Base) /12;
    }


    # # # # # # # # # # #
    # # P R I V A T E # #
    # # # # # # # # # # #

    /**
     * @access private
     * @param float $pago
     * @return object
     */
    private function addPago($pago)
    {
        $this->pagos[] = $pago;

        return $this;
    }

    /**
     * @access private
     * @return object
     */
    private function calcularPromedio()
    {
        return $this->getTotalPagado() / $this->getTotalPagos();
    }

    /**
     * Proyectar Salario
     *
     * @access private
     * @return float
     */
    private function getSalarioAnual()
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
     * @access private
     * @return int
     */
    private function getTotalPagos()
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
}
