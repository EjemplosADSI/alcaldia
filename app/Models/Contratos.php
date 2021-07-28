<?php

namespace App\Models;
use Carbon\Carbon;
use Carbon\Traits\Creator;
use Exception;
use JsonSerializable;

class Contratos extends AbstractDBConnection implements \App\Interfaces\Model
{

    private ?int $id;
    private string $tipo;
    private string $siglas;
    private string $objeto;
    private string $obligaciones;
    private Carbon $fechaInicio;
    private Carbon $fechaFinal;
    private string $valor;
    private string $enlaceSecop;
    private string $estado;
    private string $contratista_id;

    /* Objeto de la relacion */
    private ?Usuarios $contratista;

    /**
     * Usuarios constructor. Recibe un array asociativo
     * @param array $contrato
     */
    public function __construct(array $contrato = [])
    {
        parent::__construct();
        $this->setId($contrato['id'] ?? null);
        $this->setTipo($contrato['tipo'] ?? '');
        $this->setSiglas($contrato['siglas'] ?? '');
        $this->setObjeto($contrato['objeto'] ?? '');
        $this->setObligaciones($contrato['obligaciones'] ?? '');
        $this->setFechaInicio(!empty($contrato['fechaInicio']) ?
            Carbon::parse($contrato['fechaInicio']) : new Carbon() ?? 0);
        $this->setFechaFinal(!empty($contrato['fechaFinal']) ?
            Carbon::parse($contrato['fechaFinal']) : new Carbon() ?? 0);
        $this->setValor($contrato['valor'] ?? 0);
        $this->setEnlaceSecop($contrato['enlaceSecop'] ?? '');
        $this->setEstado($contrato['estado'] ?? '');
        $this->setContratistaId($contrato['contratista_id'] ?? '');
    }

    public function __destruct()
    {
        if ($this->isConnected()) {
            $this->Disconnect();
        }
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTipo(): string
    {
        return $this->tipo;
    }

    /**
     * @param string $tipo
     */
    public function setTipo(string $tipo): void
    {
        $this->tipo = $tipo;
    }

    /**
     * @return string
     */
    public function getSiglas(): string
    {
        return $this->siglas;
    }

    /**
     * @param string $siglas
     */
    public function setSiglas(string $siglas): void
    {
        $this->siglas = $siglas;
    }

    /**
     * @return string
     */
    public function getObjeto(): string
    {
        return $this->objeto;
    }

    /**
     * @param string $objeto
     */
    public function setObjeto(string $objeto): void
    {
        $this->objeto = $objeto;
    }

    /**
     * @return string
     */
    public function getObligaciones(): string
    {
        return $this->obligaciones;
    }

    /**
     * @param string $obligaciones
     */
    public function setObligaciones(string $obligaciones): void
    {
        $this->obligaciones = $obligaciones;
    }

    /**
     * @return Carbon
     */
    public function getFechaInicio(): Carbon
    {
        return $this->fechaInicio->locale('es');;
    }

    /**
     * @param Carbon $fechaInicio
     */
    public function setFechaInicio(Carbon $fechaInicio): void
    {
        $this->fechaInicio = $fechaInicio;
    }

    /**
     * @return Carbon
     */
    public function getFechaFinal(): Carbon
    {
        return $this->fechaFinal->locale('es');;
    }

    /**
     * @param Carbon $fechaFinal
     */
    public function setFechaFinal(Carbon $fechaFinal): void
    {
        $this->fechaFinal = $fechaFinal;
    }

    /**
     * @return string
     */
    public function getValor(): string
    {
        return $this->valor;
    }

    /**
     * @param string $valor
     */
    public function setValor(string $valor): void
    {
        $this->valor = str_replace(",", "", str_replace("$", "", $valor));
    }

    /**
     * @return string
     */
    public function getEnlaceSecop(): string
    {
        return $this->enlaceSecop;
    }

    /**
     * @param string $enlaceSecop
     */
    public function setEnlaceSecop(string $enlaceSecop): void
    {
        $this->enlaceSecop = $enlaceSecop;
    }

    /**
     * @return string
     */
    public function getEstado(): string
    {
        return $this->estado;
    }

    /**
     * @param string $estado
     */
    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    /**
     * @return string
     */
    public function getContratistaId(): string
    {
        return $this->contratista_id;
    }

    /**
     * @param string $contratista_id
     */
    public function setContratistaId(string $contratista_id): void
    {
        $this->contratista_id = $contratista_id;
    }

    /**
     * @return Usuarios|null
     */
    public function getContratista(): ?Usuarios
    {
        if (!empty($this->contratista_id)) {
            return Usuarios::searchForId($this->contratista_id) ?? new Usuarios();
        }
        return null;
    }

    protected function save(string $query): ?bool
    {
        $arrData = [
            ':id' =>    $this->getId(),
            ':tipo' =>   $this->getTipo(),
            ':siglas' =>   $this->getSiglas(),
            ':objeto' =>  $this->getObjeto(),
            ':obligaciones' =>   $this->getObligaciones(),
            ':fechaInicio' =>   $this->getFechaInicio()->toDateString(), //YYYY-MM-DD
            ':fechaFinal' =>   $this->getFechaFinal()->toDateString(), //YYYY-MM-DD
            ':valor' =>   $this->getValor(),
            ':enlaceSecop' =>  $this->getEnlaceSecop(),
            ':estado' =>  $this->getEstado(),
            ':contratista_id' =>  $this->getContratistaId(),
        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO contratos VALUES (
            :id, :tipo,:siglas,:objeto,:obligaciones,
            :fechaInicio,:fechaFinal,:valor,:enlaceSecop,:estado,:contratista_id
        )";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE contratos SET 
            tipo = :tipo, siglas = :siglas, objeto = :objeto, 
            obligaciones = :obligaciones, fechaInicio = :fechaInicio, fechaFinal = :fechaFinal, 
            valor = :valor, enlaceSecop = :enlaceSecop, estado = :estado, contratista_id = :contratista_id
            WHERE id = :id";
        return $this->save($query);
    }

    function deleted(): ?bool
    {
        $this->setEstado("Cancelado"); //Cambia el estado del Usuario
        return $this->update();                    //Guarda los cambios..
    }

    static function search($query): ?array
    {
        try {
            $arrContratos = array();
            $tmp = new Contratos();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Contrato = new Contratos($valor);
                    array_push($arrContratos, $Contrato);
                    unset($Contrato);
                }
                return $arrContratos;
            }
            return null;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    static function searchForId(int $id): ?object
    {
        try {
            if ($id > 0) {
                $tmpContrato = new Contratos();
                $tmpContrato->Connect();
                $getrow = $tmpContrato->getRow("SELECT * FROM contratos WHERE id =?", array($id));
                $tmpContrato->Disconnect();
                return ($getrow) ? new Contratos($getrow) : null;
            } else {
                throw new Exception('Id de contrato Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    /**
     * @param $enlaceSecop
     * @return bool
     * @throws Exception
     */
    public static function contratoRegistrado($enlaceSecop): bool
    {
        $result = Contratos::search("SELECT * FROM contratos where enlaceSecop = '" . $enlaceSecop."'");
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }

    static function getAll(): ?array
    {
        return Contratos::search("SELECT * FROM contratos");
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "tipo: $this->tipo, 
                siglas: $this->siglas, 
                objeto: $this->objeto, 
                obligaciones: $this->obligaciones, 
                fechaInicio: $this->fechaInicio, 
                fechaFinal: $this->fechaFinal 
                valor: $this->valor, 
                enlaceSecop: $this->enlaceSecop,
                contratista_id: $this->contratista_id,
                estado: $this->estado";
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'tipo' => $this->getTipo(),
            'siglas' => $this->getSiglas(),
            'objeto' => $this->getObjeto(),
            'obligaciones' => $this->getObligaciones(),
            'fechaInicio' => $this->getFechaInicio(),
            'fechaFinal' => $this->getFechaFinal(),
            'valor' => $this->getValor(),
            'enlaceSecop' => $this->getEnlaceSecop(),
            'estado' => $this->getEstado(),
            'contratista_id' => $this->getContratista()->jsonSerialize()
        ];
    }
}