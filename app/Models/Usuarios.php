<?php


namespace App\Models;
use Exception;
use JsonSerializable;

class Usuarios extends AbstractDBConnection implements \App\Interfaces\Model
{
    private ?int $id;
    private string $nombres;
    private string $apellidos;
    private string $tipoDocumento;
    private int $documento;
    private int $telefono;
    private string $direccion;
    private int $municipios_id;
    private string $correo;
    private ?string $user;
    private ?string $password;
    private string $rol;
    private string $estado;

    /* Objeto de la relacion */
    private ?Municipios $municipio;

    /* Seguridad de Contraseña */
    const HASH = PASSWORD_DEFAULT;
    const COST = 10;

    /**
     * Usuarios constructor. Recibe un array asociativo
     * @param array $usuario
     */
    public function __construct(array $usuario = [])
    {
        parent::__construct();
        $this->setId($usuario['id'] ?? null);
        $this->setNombres($usuario['nombres'] ?? '');
        $this->setApellidos($usuario['apellidos'] ?? '');
        $this->setTipoDocumento($usuario['tipoDocumento'] ?? '');
        $this->setDocumento($usuario['documento'] ?? 0);
        $this->setTelefono($usuario['telefono'] ?? 0);
        $this->setDireccion($usuario['direccion'] ?? '');
        $this->setMunicipiosId($usuario['municipios_id'] ?? 0);
        $this->setCorreo($usuario['correo'] ?? '');
        $this->setUser($usuario['user'] ?? null);
        $this->setPassword($usuario['password'] ?? null);
        $this->setRol($usuario['rol'] ?? '');
        $this->setEstado($usuario['estado'] ?? '');
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
    public function getNombres(): string
    {
        return $this->nombres;
    }

    /**
     * @param string $nombres
     */
    public function setNombres(string $nombres): void
    {
        $this->nombres = $nombres;
    }

    /**
     * @return string
     */
    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    /**
     * @param string $apellidos
     */
    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    /**
     * @return string
     */
    public function getTipoDocumento(): string
    {
        return $this->tipoDocumento;
    }

    /**
     * @param string $tipoDocumento
     */
    public function setTipoDocumento(string $tipoDocumento): void
    {
        $this->tipoDocumento = $tipoDocumento;
    }

    /**
     * @return int
     */
    public function getDocumento(): int
    {
        return $this->documento;
    }

    /**
     * @param int $documento
     */
    public function setDocumento(int $documento): void
    {
        $this->documento = $documento;
    }

    /**
     * @return int
     */
    public function getTelefono(): int
    {
        return $this->telefono;
    }

    /**
     * @param int $telefono
     */
    public function setTelefono(int $telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * @return string
     */
    public function getDireccion(): string
    {
        return $this->direccion;
    }

    /**
     * @param string $direccion
     */
    public function setDireccion(string $direccion): void
    {
        $this->direccion = $direccion;
    }

    /**
     * @return int
     */
    public function getMunicipiosId(): int
    {
        return $this->municipios_id;
    }

    /**
     * @param int $municipios_id
     */
    public function setMunicipiosId(int $municipios_id): void
    {
        $this->municipios_id = $municipios_id;
    }

    /**
     * @return string
     */
    public function getCorreo(): string
    {
        return $this->correo;
    }

    /**
     * @param string $correo
     */
    public function setCorreo(string $correo): void
    {
        $this->correo = $correo;
    }

    /**
     * @return string|null
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     * @param string|null $user
     */
    public function setUser(?string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getRol(): string
    {
        return $this->rol;
    }

    /**
     * @param string $rol
     */
    public function setRol(string $rol): void
    {
        $this->rol = $rol;
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
     * @return Municipios|null
     */
    public function getMunicipio(): Municipios|null
    {
        if (!empty($this->municipios_id)) {
            return Municipios::searchForId($this->municipios_id) ?? new Municipios();
        }
        return null;
    }

    protected function save(string $query): ?bool
    {
        $hashPassword = password_hash($this->password, self::HASH, ['cost' => self::COST]);

        $arrData = [
            ':id' =>    $this->getId(),
            ':nombres' =>   $this->getNombres(),
            ':apellidos' =>   $this->getApellidos(),
            ':tipoDocumento' =>  $this->getTipoDocumento(),
            ':documento' =>   $this->getDocumento(),
            ':telefono' =>   $this->getTelefono(),
            ':direccion' =>   $this->getDireccion(),
            ':municipios_id' =>   $this->getMunicipiosId(),
            ':correo' =>  $this->getCorreo(), //YYYY-MM-DD
            ':user' =>  $this->getUser(),
            ':password' =>   $hashPassword,
            ':rol' =>   $this->getRol(),
            ':estado' =>   $this->getEstado(),
        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO usuarios VALUES (
            :id,:nombres,:apellidos,:tipoDocumento,:documento,
            :telefono,:direccion,:municipios_id,:correo,:user,
            :password,:rol,:estado
        )";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE usuarios SET 
            nombres = :nombres, apellidos = :apellidos, tipoDocumento = :tipoDocumento, 
            documento = :documento, telefono = :telefono, direccion = :direccion, 
            municipios_id = :municipios_id, correo = :correo, user = :user,  
            password = :password, rol = :rol, estado = :estado WHERE id = :id";
        return $this->save($query);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function deleted(): bool
    {
        $this->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $this->update();                    //Guarda los cambios..
    }

    static function search($query): ?array
    {
        try {
            $arrUsuarios = array();
            $tmp = new Usuarios();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Usuario = new Usuarios($valor);
                    array_push($arrUsuarios, $Usuario);
                    unset($Usuario);
                }
                return $arrUsuarios;
            }
            return null;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    static function searchForId(int $id): ?Usuarios
    {
        try {
            if ($id > 0) {
                $tmpUsuario = new Usuarios();
                $tmpUsuario->Connect();
                $getrow = $tmpUsuario->getRow("SELECT * FROM usuarios WHERE id =?", array($id));
                $tmpUsuario->Disconnect();
                return ($getrow) ? new Usuarios($getrow) : null;
            } else {
                throw new Exception('Id de usuario Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    /**
     * @param $documento
     * @return bool
     * @throws Exception
     */
    public static function usuarioRegistrado($documento): bool
    {
        $result = Usuarios::search("SELECT * FROM usuarios where documento = " . $documento);
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }

    static function getAll(): ?array
    {
        return Usuarios::search("SELECT * FROM usuarios");
    }

    /**
     * @return string
     */
    public function nombresCompletos(): string
    {
        return $this->nombres . " " . $this->apellidos;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "Nombres: $this->nombres, 
                Apellidos: $this->nombres, 
                Tipo Documento: $this->tipoDocumento, 
                Documento: $this->documento, 
                Telefono: $this->telefono, 
                Direccion: $this->direccion , $this->municipios_id, 
                Correo: $this->correo, 
                Rol: $this->rol";
    }

    public function login($user, $password): Usuarios|String|null
    {

        try {
            $resultUsuarios = Usuarios::search("SELECT * FROM usuarios WHERE user = '$user'");
            /* @var $resultUsuarios Usuarios[] */
            if (!empty($resultUsuarios) && count($resultUsuarios) >= 1) {
                if (password_verify($password, $resultUsuarios[0]->getPassword())) {
                    if ($resultUsuarios[0]->getEstado() == 'Activo') {
                        return $resultUsuarios[0];
                    } else {
                        return "Usuario Inactivo";
                    }
                } else {
                    return "Contraseña Incorrecta";
                }
            } else {
                return "Usuario Incorrecto";
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
            return "Error en Servidor";
        }
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'nombres' => $this->getNombres(),
            'apellidos' => $this->getApellidos(),
            'tipoDocumento' => $this->getTipoDocumento(),
            'documento' => $this->getDocumento(),
            'telefono' => $this->getTelefono(),
            'direccion' => $this->getDireccion(),
            'municipios_id' => $this->getMunicipiosId(),
            'correo' => $this->getCorreo(),
            'user' => $this->getUser(),
            'password' => $this->getPassword(),
            'rol' => $this->getRol(),
            'estado' => $this->getEstado()
        ];
    }
}