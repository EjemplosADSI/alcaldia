<?php
namespace App\Models;

use Carbon\Carbon;
use Carbon\Traits\Creator;
use Exception;
use JsonSerializable;

class Municipios extends AbstractDBConnection implements \App\Interfaces\Model
{
    private ?int $id;
    private string $nombre;
    private int $departamento_id;
    private string $acortado;
    private string $estado;
    private Carbon $created_at;
    private Carbon $updated_at;
    private Carbon $deleted_at;
    /* Objeto de la relacion */
    private Departamentos $departamento;

    /**
     * Municipios constructor. Recibe un array asociativo
     * @param array $municipio
     * @throws Exception
     */
    public function __construct(array $municipio = [])
    {
        parent::__construct();
        $this->setId($municipio['id'] ?? null);
        $this->setNombre($municipio['nombre'] ?? '');
        $this->setDepartamentoId($municipio['departamento_id'] ?? 0);
        $this->setAcortado($municipio['acortado'] ?? '');
        $this->setEstado($municipio['estado'] ?? '');
        $this->setCreatedAt(!empty($municipio['created_at']) ? Carbon::parse($municipio['created_at']) : new Carbon());
        $this->setUpdatedAt(!empty($municipio['updated_at']) ? Carbon::parse($municipio['updated_at']) : new Carbon());
        $this->setDeletedAt(!empty($municipio['deleted_at']) ? Carbon::parse($municipio['deleted_at']) : new Carbon());
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
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return int
     */
    public function getDepartamentoId(): int
    {
        return $this->departamento_id;
    }

    /**
     * @param int $departamento_id
     */
    public function setDepartamentoId(int $departamento_id): void
    {
        $this->departamento_id = $departamento_id;
    }

    /**
     * @return string
     */
    public function getAcortado(): string
    {
        return $this->acortado;
    }

    /**
     * @param string $acortado
     */
    public function setAcortado(string $acortado): void
    {
        $this->acortado = $acortado;
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
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->created_at->locale('es');
    }

    /**
     * @param Carbon $created_at
     */
    public function setCreatedAt(Carbon $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at->locale('es');
    }

    /**
     * @param Carbon $updated_at
     */
    public function setUpdatedAt(Carbon $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return Carbon
     */
    public function getDeletedAt(): Carbon
    {
        return $this->deleted_at->locale('es');
    }

    /**
     * @param Carbon $deleted_at
     */
    public function setDeletedAt(Carbon $deleted_at): void
    {
        $this->deleted_at = $deleted_at;
    }

    /**
     * Relacion con departamento
     *
     * @return null|Departamentos
     */
    public function getDepartamento(): ?Departamentos
    {
        if (!empty($this->departamento_id)) {
            $this->departamento = Departamentos::searchForId($this->departamento_id) ?? new Departamentos();
        }
        return $this->departamento;
    }

    /**
     * @param Departamentos $departamento
     */
    public function setDepartamento(Departamentos $departamento): void
    {
        $this->departamento = $departamento;
    }

    protected function save(string $query): ?bool
    {
        return null;
    }

    function insert(): ?bool
    {
        return null;
    }

    function update(): ?bool
    {
        return null;
    }

    function deleted(): ?bool
    {
        return null;
    }

    static function search($query): ?array
    {
        try {
            $arrMunicipios = array();
            $tmp = new Municipios();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Municipio = new Municipios($valor);
                array_push($arrMunicipios, $Municipio);
                unset($Municipio);
            }
            return $arrMunicipios;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    static function searchForId(int $id): ?Municipios
    {
        try {
            if ($id > 0) {
                $tmpMun = new Municipios();
                $tmpMun->Connect();
                $getrow = $tmpMun->getRow("SELECT * FROM municipios WHERE id =?", array($id));
                $tmpMun->Disconnect();
                return ($getrow) ? new Municipios($getrow) : null;
            } else {
                throw new Exception('Id de municipio Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    static function getAll(): ?array
    {
        return Municipios::search("SELECT * FROM municipios");
    }

    public function __toString() : string
    {
        return "Nombre: $this->nombre, Estado: $this->estado";
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'departamento_id' => $this->getDepartamento()->jsonSerialize(),
            'acortado' => $this->getAcortado(),
            'estado' => $this->getEstado(),
            'created_at' => $this->getCreatedAt()->toDateTimeString(),
            'updated_at' => $this->getUpdatedAt()->toDateTimeString(),
            'deleted_at' => $this->getDeletedAt()->toDateTimeString(),
        ];
    }
}