<?php


namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Conservaciones;

class ConservacionesController
{
    private array $dataConservacion;

    public function __construct(array $_FORM)
    {
        $this->dataConservacion = array();
        $this->dataConservacion['id'] = $_FORM['id'] ?? NULL;
        $this->dataConservacion['numeroCaja'] = $_FORM['numeroCaja'] ?? NULL;
        $this->dataConservacion['carpeta'] = $_FORM['carpeta'] ?? null;
        $this->dataConservacion['folio'] = $_FORM['folio'] ?? NULL;
        $this->dataConservacion['tomo'] = $_FORM['tomo'] ?? NULL;
        $this->dataConservacion['medioEntrega'] = $_FORM['medioEntrega'] ?? NULL;
        $this->dataConservacion['notas'] = $_FORM['notas'] ?? NULL;
        $this->dataConservacion['tipoVisibilidad'] = $_FORM['tipoVisibilidad'] ?? NULL;
        $this->dataConservacion['contratos_id'] = $_FORM['contratos_id'] ?? NULL;
    }

    public function create() {
        try {
            if (!empty($this->dataConservacion) && !Conservaciones::conservacionRegistrada($this->dataConservacion)) {
                $Conservacion = new Conservaciones($this->dataConservacion);
                if ($Conservacion->insert()) {
                    unset($_SESSION['frmConservaciones']);
                    header("Location: ../../views/modules/conservaciones/index.php?respuesta=success&mensaje=Conservacion Registrada");
                }
            } else {
                header("Location: ../../views/modules/conservaciones/create.php?respuesta=error&mensaje=Conservacion ya registrada");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function edit()
    {
        try {
            $conservacion = new Conservaciones($this->dataConservacion);
            if($conservacion->update()){
                unset($_SESSION['frmConservaciones']);
            }
            header("Location: ../../views/modules/conservaciones/show.php?id=" . $conservacion->getId() . "&respuesta=success&mensaje=Conservacion Actualizada");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }


    static public function searchForID(array $data)
    {
        try {
            $result = Conservaciones::searchForId($data['id']);
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
            $result = Conservaciones::getAll();
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

    static public function selectConservaciones(array $params = []) {

        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "usuario_id";
        $params['name'] = $params['name'] ?? "usuario_id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrConservaciones = array();
        if ($params['where'] != "") {
            $base = "SELECT * FROM conservaciones WHERE ";
            $arrConservaciones = Conservaciones::search($base . ' ' . $params['where']);
        } else {
            $arrConservaciones = Conservaciones::getAll();
        }
        $htmlSelect = "<select " . (($params['isMultiple']) ? "multiple" : "") . " " . (($params['isRequired']) ? "required" : "") . " id= '" . $params['id'] . "' name='" . $params['name'] . "' class='" . $params['class'] . "' style='width: 100%;'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (is_array($arrConservaciones) && count($arrConservaciones) > 0) {
            /* @var $arrConservaciones Conservaciones[] */
            foreach ($arrConservaciones as $conservacion)
                if (!ConservacionesController::conservacionIsInArray($conservacion->getId(), $params['arrExcluir']))
                    $htmlSelect .= "<option " . (($conservacion != "") ? (($params['defaultValue'] == $conservacion->getId()) ? "selected" : "") : "") . " value='" . $conservacion->getId() . "'> Contrato: " . $conservacion->getContrato()->getId() . " - " . $conservacion->getNumeroCaja()
                        . " " . $conservacion->getNotas() . "</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    private static function conservacionIsInArray($idConservacion, $ArrConservaciones)
    {
        if (count($ArrConservaciones) > 0) {
            foreach ($ArrConservaciones as $Conservacion) {
                if ($Conservacion->getId() == $idConservacion) {
                    return true;
                }
            }
        }
        return false;
    }
}