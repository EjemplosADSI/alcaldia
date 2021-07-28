<?php

namespace App\Models;

use Exception;
use JsonSerializable;

class Conservaciones extends AbstractDBConnection implements \App\Interfaces\Model
{
    private ?int $id;
    private int $numeroCaja;
    private string $carpeta;
    private int $folio;
    private string $tomo;
    private string $medioEntrega;
    private string $notas;
    private string $tipoVisibilidad;
    private int $contratos_id;

    /* Objeto de la relacion */
    private ?Contratos $contrato;

    /**
     * Conservaciones constructor. Recibe un array asociativo
     * @param array $conservacion
     */
    public function __construct(array $conservacion = [])
    {
        parent::__construct();
        $this->setId($conservacion['id'] ?? null);
        $this->setNumeroCaja($conservacion['numeroCaja'] ?? 0);
        $this->setCarpeta($conservacion['carpeta'] ?? '');
        $this->setFolio($conservacion['folio'] ?? 0);
        $this->setTomo($conservacion['tomo'] ?? '');
        $this->setMedioEntrega($conservacion['medioEntrega'] ?? '');
        $this->setNotas($conservacion['notas'] ?? '');
        $this->setTipoVisibilidad($conservacion['tipoVisibilidad'] ?? '');
        $this->setContratosId($conservacion['contratos_id'] ?? 0);
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
     * @return int
     */
    public function getNumeroCaja(): int
    {
        return $this->numeroCaja;
    }

    /**
     * @param int $numeroCaja
     */
    public function setNumeroCaja(int $numeroCaja): void
    {
        $this->numeroCaja = $numeroCaja;
    }

    /**
     * @return string
     */
    public function getCarpeta(): string
    {
        return $this->carpeta;
    }

    /**
     * @param string $carpeta
     */
    public function setCarpeta(string $carpeta): void
    {
        $this->carpeta = $carpeta;
    }

    /**
     * @return int
     */
    public function getFolio(): int
    {
        return $this->folio;
    }

    /**
     * @param int $folio
     */
    public function setFolio(int $folio): void
    {
        $this->folio = $folio;
    }

    /**
     * @return string
     */
    public function getTomo(): string
    {
        return $this->tomo;
    }

    /**
     * @param string $tomo
     */
    public function setTomo(string $tomo): void
    {
        $this->tomo = $tomo;
    }

    /**
     * @return string
     */
    public function getMedioEntrega(): string
    {
        return $this->medioEntrega;
    }

    /**
     * @param string $medioEntrega
     */
    public function setMedioEntrega(string $medioEntrega): void
    {
        $this->medioEntrega = $medioEntrega;
    }

    /**
     * @return string
     */
    public function getNotas(): string
    {
        return $this->notas;
    }

    /**
     * @param string $notas
     */
    public function setNotas(string $notas): void
    {
        $this->notas = $notas;
    }

    /**
     * @return string
     */
    public function getTipoVisibilidad(): string
    {
        return $this->tipoVisibilidad;
    }

    /**
     * @param string $tipoVisibilidad
     */
    public function setTipoVisibilidad(string $tipoVisibilidad): void
    {
        $this->tipoVisibilidad = $tipoVisibilidad;
    }

    /**
     * @return int
     */
    public function getContratosId(): int
    {
        return $this->contratos_id;
    }

    /**
     * @param int $contratos_id
     */
    public function setContratosId(int $contratos_id): void
    {
        $this->contratos_id = $contratos_id;
    }

    /**
     * @return Contratos|null
     */
    public function getContrato(): ?Contratos
    {
        if (!empty($this->contratos_id)) {
            return Contratos::searchForId($this->contratos_id) ?? new Contratos();
        }
        return null;
    }

    protected function save(string $query): ?bool
    {
        $arrData = [
            ':id' =>    $this->getId(),
            ':numeroCaja' =>   $this->getNumeroCaja(),
            ':carpeta' =>   $this->getCarpeta(),
            ':folio' =>  $this->getFolio(),
            ':tomo' =>   $this->getTomo(),
            ':medioEntrega' =>   $this->getMedioEntrega(),
            ':notas' =>  $this->getNotas(),
            ':tipoVisibilidad' =>  $this->getTipoVisibilidad(),
            ':contratos_id' =>  $this->getContratosId(),
        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO conservaciones VALUES (
            :id, :numeroCaja,:carpeta,:folio,:tomo,
            :medioEntrega,:notas,:tipoVisibilidad,:contratos_id
        )";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE conservaciones SET 
            numeroCaja = :numeroCaja, carpeta = :carpeta, folio = :folio, 
            tomo = :tomo, medioEntrega = :medioEntrega, notas = :notas, 
            tipoVisibilidad = :tipoVisibilidad, contratos_id = :contratos_id WHERE id = :id";
        return $this->save($query);
    }

    static function search($query): ?array
    {
        try {
            $arrConservaciones = array();
            $tmp = new Conservaciones();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Conservacion = new Conservaciones($valor);
                    array_push($arrConservaciones, $Conservacion);
                    unset($Conservacion);
                }
                return $arrConservaciones;
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
                $tmpConservacion = new Conservaciones();
                $tmpConservacion->Connect();
                $getrow = $tmpConservacion->getRow("SELECT * FROM conservaciones WHERE id =?", array($id));
                $tmpConservacion->Disconnect();
                return ($getrow) ? new Conservaciones($getrow) : null;
            } else {
                throw new Exception('Id de conservacion Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    /**
     * @param $arrData
     * @return bool
     * @throws Exception
     */
    public static function conservacionRegistrada($arrData): bool
    {
        $result = Conservaciones::search("SELECT * FROM conservaciones where contratos_id = '" . $arrData['contratos_id']."' and numeroCaja = '" . $arrData['numeroCaja']."' and carpeta = '" . $arrData['carpeta']."' and folio = '" . $arrData['folio']."' and tomo = '" . $arrData['tomo']."' and medioEntrega = '" . $arrData['medioEntrega']."' and tipoVisibilidad = '" . $arrData['tipoVisibilidad']."'");
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }

    static function getAll(): ?array
    {
        return Conservaciones::search("SELECT * FROM conservaciones");
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "numeroCaja: $this->numeroCaja, 
                carpeta: $this->carpeta, 
                folio: $this->folio, 
                tomo: $this->tomo, 
                medioEntrega: $this->medioEntrega, 
                notas: $this->notas,
                tipoVisibilidad: $this->tipoVisibilidad,
                contratos_id: $this->contratos_id";
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'numeroCaja' => $this->getNumeroCaja(),
            'carpeta' => $this->getCarpeta(),
            'folio' => $this->getFolio(),
            'tomo' => $this->getTomo(),
            'medioEntrega' => $this->getMedioEntrega(),
            'notas' => $this->getNotas(),
            'tipoVisibilidad' => $this->getTipoVisibilidad(),
            'contratos_id' => $this->getContrato()->jsonSerialize()
        ];
    }

    function deleted(): ?bool
    {
        return false;
    }
}