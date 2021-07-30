<?php

namespace App\Models;
use Carbon\Carbon;
use Carbon\Traits\Creator;
use Exception;
use JsonSerializable;

class Pagos extends AbstractDBConnection implements \App\Interfaces\Model
{

    private ?int $id;
    private int $numero;
    private float $valor;
    private string $tipo;
    private Carbon $fecha;
    private int $contratos_id;

    /* Objeto de la relacion */
    private ?Contratos $contrato;

    /**
     * Usuarios constructor. Recibe un array asociativo
     * @param array $contrato
     */
    public function __construct(array $contrato = [])
    {
        parent::__construct();
        $this->setId($contrato['id'] ?? null);
        $this->setNumero($contrato['numero'] ?? 0);
        $this->setValor($contrato['valor'] ?? 0);
        $this->setTipo($contrato['tipo'] ?? '');
        $this->setFecha(!empty($contrato['fecha']) ?
            Carbon::parse($contrato['fecha']) : new Carbon() ?? 0);
        $this->setContratosId($contrato['contratos_id'] ?? 0);
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
    public function getNumero(): int
    {
        return $this->numero;
    }

    /**
     * @param int $numero
     */
    public function setNumero(int $numero): void
    {
        $this->numero = $numero;
    }

    /**
     * @return float
     */
    public function getValor(): float
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
     * @return Carbon
     */
    public function getFecha(): Carbon
    {
        return $this->fecha;
    }

    /**
     * @param Carbon $fecha
     */
    public function setFecha(Carbon $fecha): void
    {
        $this->fecha = $fecha;
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
            ':numero' =>   $this->getNumero(),
            ':valor' =>   $this->getValor(),
            ':tipo' =>  $this->getTipo(),
            ':fecha' =>   $this->getFecha()->toDateString(),
            ':contratos_id' =>   $this->getContratosId(), //YYYY-MM-DD
        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO pagos VALUES (
            :id, :numero,:valor,:tipo,:fecha, :contratos_id
        )";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE pagos SET 
            numero = :numero, valor = :valor, tipo = :tipo, 
            fecha = :fecha, contratos_id = :contratos_id WHERE id = :id";
        return $this->save($query);
    }

    static function search($query): ?array
    {
        try {
            $arrPagos = array();
            $tmp = new Pagos();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Pagos = new Pagos($valor);
                    array_push($arrPagos, $Pagos);
                    unset($Pagos);
                }
                return $arrPagos;
            }
            return null;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    static function searchForId(int $id): ?Pagos
    {
        try {
            if ($id > 0) {
                $tmpPago = new Pagos();
                $tmpPago->Connect();
                $getrow = $tmpPago->getRow("SELECT * FROM pagos WHERE id =?", array($id));
                $tmpPago->Disconnect();
                return ($getrow) ? new Pagos($getrow) : null;
            } else {
                throw new Exception('Id del pago Invalido');
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
    public static function pagoRegistrado($arrData): bool
    {
        $result = Pagos::search("SELECT * FROM pagos where numero = '" . $arrData['numero']."' and valor = '" . $arrData['valor']."' and tipo = '" . $arrData['tipo']."' and fecha = '" . $arrData['fecha']."' and contratos_id = '" . $arrData['contratos_id']."'");
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }

    static function getAll(): ?array
    {
        return Pagos::search("SELECT * FROM pagos");
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "tipo: $this->tipo, 
                numero: $this->numero, 
                valor: $this->valor, 
                tipo: $this->tipo, 
                fecha: $this->fecha, 
                contratos_id: $this->contratos_id";
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'numero' => $this->getTipo(),
            'valor' => $this->getValor(),
            'tipo' => $this->getTipo(),
            'fecha' => $this->getFecha(),
            'contratos_id' => $this->getContrato()->jsonSerialize(),
        ];
    }

    function deleted(): ?bool
    {
        return false;
    }
}