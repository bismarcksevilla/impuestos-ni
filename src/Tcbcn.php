<?php

 namespace Impuestos;

 use SoapClient;
/**
 * @author Bismarck Sevilla <digital.nicaragua@gmail.com>
 *
 * Tipo de cambio según el:
 * BANCO CENTRAL DE NICARAGUA
 * BCN
 */
class Tcbcn
{
    /**
     * @access protected
     * @var object
     */
    private $fecha;

    /**
     * @access protected
     * @var float
     */
    private $dia=false;

    /**
     * @access protected
     * @var array
     */
    private $data;

    /**
     * @access protected
     * @var array
     */
    private $path = 'http://servicios.bcn.gob.ni/';

    # # # # # # # # # #
    # # P U B L I C # #
    # # # # # # # # # #

    /**
     * @access public
     * @param DateTime $obj_DateTime
     */
    public function __construct($obj_DateTime= false)
    {
        $this->setFecha($obj_DateTime);
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
     * @return mixto: onbject fecha | string fecha
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
     * @return object
     */
    public function setDia()
    {
        // try {
        //     $arrContextOptions=
        //     [
        //         "ssl"=>[
        //             "verify_peer"=>false,
        //             "verify_peer_name"=>false,
        //             'crypto_method' => STREAM_CRYPTO_METHOD_TLS_CLIENT]
        //     ];

        //     $options = [
        //             'soap_version'=>SOAP_1_2,
        //             'exceptions'=>true,
        //             'trace'=>1,
        //             'cache_wsdl'=>WSDL_CACHE_NONE,
        //             'stream_context' => stream_context_create($arrContextOptions)
        //     ];
        // return new SoapClient("http://servicios.bcn.gob.ni/", $options);

        // } catch (Exception $e) {
        //     echo "<h2>Exception Error!</h2>";
        //     echo $e->getMessage();
        // }

        // $parametros= []; //parametros de la llamada
        // $parametros['Ano']="2018";
        // $parametros['Mes']="08";

        return $client = new SoapClient($this->path);

        return $this->dia = $client->RecuperaTC_Dia(2018, 05, 6);

        // return $this;
    }

    /**
     * @access public
     * @return float
     */
    public function getDia()
    {
        if ($this->dia) {
            return  $this->dia;
        } else {
            $this->setDia();
            return  $this->dia;
        }
    }

    # # # # # # # # # # #
    # # P R I V A T E # #
    # # # # # # # # # # #

}