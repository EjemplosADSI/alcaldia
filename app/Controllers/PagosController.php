<?php


namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Pagos;

class PagosController
{
    private array $dataPagos;

    public function __construct(array $_FORM)
    {
        $this->dataPagos = array();
        $this->dataPagos['id'] = $_FORM['id'] ?? NULL;
        $this->dataPagos['numero'] = $_FORM['numero'] ?? NULL;
        $this->dataPagos['valor'] = $_FORM['valor'] ?? NULL;
        $this->dataPagos['tipo'] = $_FORM['tipo'] ?? NULL;
        $this->dataPagos['fecha'] = $_FORM['fecha'] ?? NULL;
        $this->dataPagos['contratos_id'] = $_FORM['contratos_id'] ?? NULL;
    }

    public function create() {
        try {
            if (!empty($this->dataPagos) && !Pagos::pagoRegistrado($this->dataPagos)) {
                $Pago = new Pagos($this->dataPagos);
                if ($Pago->insert()) {
                    unset($_SESSION['frmCreatePagos']);
                    header("Location: ../../views/modules/pagos/index.php?respuesta=success&mensaje=Pago Registrada");
                }
            } else {
                header("Location: ../../views/modules/pagos/create.php?respuesta=error&mensaje=Pago ya registrada");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function edit()
    {
        try {
            $pago = new Pagos($this->dataPagos);
            if($pago->update()){
                unset($_SESSION['frmEditPagos']);
            }
            header("Location: ../../views/modules/pagos/show.php?id=" . $pago->getId() . "&respuesta=success&mensaje=Pago Actualizado");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }


    static public function searchForID(array $data)
    {
        try {
            $result = Pagos::searchForId($data['id']);
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
            $result = Pagos::getAll();
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

    static public function selectPagos(array $params = []) {

        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "usuario_id";
        $params['name'] = $params['name'] ?? "usuario_id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrPagos = array();
        if ($params['where'] != "") {
            $base = "SELECT * FROM pagos WHERE ";
            $arrPagos = Pagos::search($base . ' ' . $params['where']);
        } else {
            $arrPagos = Pagos::getAll();
        }
        $htmlSelect = "<select " . (($params['isMultiple']) ? "multiple" : "") . " " . (($params['isRequired']) ? "required" : "") . " id= '" . $params['id'] . "' name='" . $params['name'] . "' class='" . $params['class'] . "' style='width: 100%;'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (is_array($arrPagos) && count($arrPagos) > 0) {
            /* @var $arrPagos Pagos[] */
            foreach ($arrPagos as $pago)
                if (!PagosController::pagoIsInArray($pago->getId(), $params['arrExcluir']))
                    $htmlSelect .= "<option " . (($pago != "") ? (($params['defaultValue'] == $pago->getId()) ? "selected" : "") : "") . " value='" . $pago->getId() . "'> " . $pago->getNumero() . " - " . $pago->getValor()
                        . " " . $pago->getTipo() ." " . $pago->getFecha() . "</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    private static function pagoIsInArray($idPago, $ArrPagos)
    {
        if (count($ArrPagos) > 0) {
            foreach ($ArrPagos as $Pagos) {
                if ($Pagos->getId() == $idPago) {
                    return true;
                }
            }
        }
        return false;
    }
}