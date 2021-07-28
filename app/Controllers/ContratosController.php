<?php


namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Contratos;
use Carbon\Carbon;
use Carbon\Traits\Creator;

class ContratosController
{
    private array $dataContrato;

    public function __construct(array $_FORM)
    {
        $this->dataContrato = array();
        $this->dataContrato['id'] = $_FORM['id'] ?? NULL;
        $this->dataContrato['tipo'] = $_FORM['tipo'] ?? NULL;
        $this->dataContrato['siglas'] = $_FORM['siglas'] ?? null;
        $this->dataContrato['objeto'] = $_FORM['objeto'] ?? NULL;
        $this->dataContrato['obligaciones'] = $_FORM['obligaciones'] ?? NULL;
        $this->dataContrato['fechaInicio'] = $_FORM['fechaInicio'] ?? NULL;
        $this->dataContrato['fechaFinal'] = $_FORM['fechaFinal'] ?? NULL;
        $this->dataContrato['valor'] = $_FORM['valor'] ?? NULL;
        $this->dataContrato['enlaceSecop'] = $_FORM['enlaceSecop'] ?? NULL;
        $this->dataContrato['estado'] = $_FORM['estado'] ?? NULL;
        $this->dataContrato['contratista_id'] = $_FORM['contratista_id'] ?? NULL;
    }

    public function create() {
        try {
            if (!empty($this->dataContrato['enlaceSecop']) && !Contratos::contratoRegistrado($this->dataContrato['enlaceSecop'])) {
                $Contrato = new Contratos ($this->dataContrato);
                if ($Contrato->insert()) {
                    unset($_SESSION['frmContratos']);
                    header("Location: ../../views/modules/contratos/index.php?respuesta=success&mensaje=Contrato Registrado");
                }
            } else {
                header("Location: ../../views/modules/contratos/create.php?respuesta=error&mensaje=Contrato ya registrado");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function edit()
    {
        try {
            $contrato = new Contratos($this->dataContrato);
            if($contrato->update()){
                unset($_SESSION['frmContratos']);
            }
            header("Location: ../../views/modules/contratos/show.php?id=" . $contrato->getId() . "&respuesta=success&mensaje=Contrato Actualizado");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }


    static public function searchForID(array $data)
    {
        try {
            $result = Contratos::searchForId($data['id']);
            if (!empty($data['request']) and $data['request'] === 'ajax' and !empty($result)) {
                header('Content-type: application/json; charset=utf-8');
                $result = json_encode($result->jsonSerialize());
            }
            return $result;
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    static public function getAll(array $data = null)
    {
        try {
            $result = Contratos::getAll();
            if (!empty($data['request']) and $data['request'] === 'ajax') {
                header('Content-type: application/json; charset=utf-8');
                $result = json_encode($result);
            }
            return $result;
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    static public function activate(int $id)
    {
        try {
            $ObjContrato = Contratos::searchForId($id);
            $ObjContrato->setEstado("Activo");
            if ($ObjContrato->update()) {
                header("Location: ../../views/modules/contratos/index.php");
            } else {
                header("Location: ../../views/modules/contratos/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function cancelar(int $id)
    {
        try {
            $ObjContrato = Contratos::searchForId($id);
            $ObjContrato->setEstado("Inactivo");
            if ($ObjContrato->update()) {
                header("Location: ../../views/modules/contratos/index.php");
            } else {
                header("Location: ../../views/modules/contratos/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function selectContratos(array $params = []) {

        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "usuario_id";
        $params['name'] = $params['name'] ?? "usuario_id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrContratos = array();
        if ($params['where'] != "") {
            $base = "SELECT * FROM contratos WHERE ";
            $arrContratos = Contratos::search($base . ' ' . $params['where']);
        } else {
            $arrContratos = Contratos::getAll();
        }
        $htmlSelect = "<select " . (($params['isMultiple']) ? "multiple" : "") . " " . (($params['isRequired']) ? "required" : "") . " id= '" . $params['id'] . "' name='" . $params['name'] . "' class='" . $params['class'] . "' style='width: 100%;'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (is_array($arrContratos) && count($arrContratos) > 0) {
            /* @var $arrContratos Contratos[] */
            foreach ($arrContratos as $contrato)
                if (!ContratosController::contratoIsInArray($contrato->getId(), $params['arrExcluir']))
                    $htmlSelect .= "<option " . (($contrato != "") ? (($params['defaultValue'] == $contrato->getId()) ? "selected" : "") : "") . " value='" . $contrato->getId() . "'>" . $contrato->getSiglas() . " - " . $contrato->getId() . " " . $contrato->getTipo() . "</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    private static function contratoIsInArray($idContrato, $ArrContratos)
    {
        if (count($ArrContratos) > 0) {
            foreach ($ArrContratos as $Contrato) {
                if ($Contrato->getId() == $idContrato) {
                    return true;
                }
            }
        }
        return false;
    }
}