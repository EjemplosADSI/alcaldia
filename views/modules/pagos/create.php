<?php
require("../../partials/routes.php");
require_once("../../partials/check_login.php");

use App\Controllers\PagosController;
use App\Controllers\ContratosController;
use App\Models\GeneralFunctions;
use Carbon\Carbon;

$nameModel = "Pago";
$nameForm = 'frmCreate' . $nameModel;
$pluralModel = $nameModel . 's';
$frmSession = $_SESSION[$nameForm] ?? NULL;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Crear <?= $nameModel ?></title>
    <?php require("../../partials/head_imports.php"); ?>
    <!-- summernote -->
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/summernote/summernote-bs4.min.css">
</head>
<body class="hold-transition sidebar-mini">

<!-- Site wrapper -->
<div class="wrapper">
    <?php require("../../partials/navbar_customization.php"); ?>

    <?php require("../../partials/sliderbar_main_menu.php"); ?>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Crear un Nuevo <?= $nameModel ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a
                                        href="<?= $baseURL; ?>/views/"><?= $_ENV['ALIASE_SITE'] ?></a></li>
                            <li class="breadcrumb-item"><a href="index.php"><?= $pluralModel ?></a></li>
                            <li class="breadcrumb-item active">Crear</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Generar Mensaje de alerta -->
            <?= (!empty($_GET['respuesta'])) ? GeneralFunctions::getAlertDialog($_GET['respuesta'], $_GET['mensaje']) : ""; ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Horizontal Form -->
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-user"></i> &nbsp; Informaci??n
                                    del <?= $nameModel ?></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="card-refresh"
                                            data-source="create.php" data-source-selector="#card-refresh-content"
                                            data-load-on-init="false"><i class="fas fa-sync-alt"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                class="fas fa-expand"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                                class="fas fa-minus"></i></button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <!-- form start -->
                                <form class="form-horizontal" enctype="multipart/form-data" method="post"
                                      id="<?= $nameForm ?>"
                                      name="<?= $nameForm ?>"
                                      action="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=create">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <label for="numero" class="col-sm-2 col-form-label">Numero</label>
                                                <div class="col-sm-10">
                                                    <input required type="number" minlength="1" class="form-control"
                                                           id="numero" name="numero"
                                                           placeholder="Ingrese el numero del pago"
                                                           value="<?= $frmSession['numero'] ?? '' ?>">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="valor" class="col-sm-2 col-form-label">Valor</label>
                                                <div class="col-sm-10">
                                                    <input required type="text" minlength="4"
                                                           class="form-control inputmask text-left"
                                                           id="valor" name="valor"
                                                           placeholder="Ingrese el valor del contrato"
                                                           data-inputmask-prefix="$" data-inputmask-digits="2"
                                                           data-inputmask-greedy="false"
                                                           data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true"
                                                           value="<?= $frmSession['valor'] ?? '' ?>">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="tipo" class="col-sm-2 col-form-label">
                                                    Tipo</label>
                                                <div class="col-sm-10">
                                                    <select id="tipo" name="tipo" class="custom-select">
                                                        <option <?= (!empty($frmSession['tipo']) && $frmSession['tipo'] == "Parcial") ? "selected" : ""; ?>
                                                                value="Parcial">Parcial
                                                        </option>
                                                        <option <?= (!empty($frmSession['tipo']) && $frmSession['tipo'] == "Total") ? "selected" : ""; ?>
                                                                value="Total">Total
                                                        </option>
                                                        <option <?= (!empty($frmSession['tipo']) && $frmSession['tipo'] == "Avance") ? "selected" : ""; ?>
                                                                value="Avance">Avance
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="fecha" class="col-sm-2 col-form-label">Fecha</label>
                                                <div class="col-sm-10">
                                                    <input required type="date"
                                                           max="<?= Carbon::now()->format('Y-m-d') ?>"
                                                           class="form-control" id="fecha"
                                                           name="fecha" placeholder="Ingrese la fecha del pago"
                                                           value="<?= $frmSession['fecha'] ?? '' ?>">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="contratos_id"
                                                       class="col-sm-2 col-form-label">Contrato</label>
                                                <div class="col-sm-10">
                                                    <?= ContratosController::selectContratos(
                                                        array(
                                                            'id'    => 'contratos_id',
                                                            'name'  => 'contratos_id',
                                                            'defaultValue' => !empty($_GET['idContrato']) ? $_GET['idContrato'] : '',
                                                            'class' => 'form-control select2bs4 select2-info',
                                                            'where' => "estado = 'Activo'"
                                                        )
                                                    )
                                                    ?>
                                                    <a href="../contratos/create.php">Crear Contrato</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <button id="frmName" name="frmName" value="<?= $nameForm ?>" type="submit"
                                            class="btn btn-info">Enviar
                                    </button>
                                    <a href="index.php" role="button" class="btn btn-default float-right">Cancelar</a>
                                    <!-- /.card-footer -->
                                </form>
                            </div>
                            <!-- /.card-body -->

                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php require('../../partials/footer.php'); ?>
</div>
<!-- ./wrapper -->
<?php require('../../partials/scripts.php'); ?>
<!-- Summernote -->
<script src="<?= $adminlteURL ?>/plugins/summernote/summernote-bs4.min.js"></script>
<script src="<?= $baseURL ?>/node_modules/inputmask/dist/jquery.inputmask.min.js"></script>

<script>
    $(function () {
        $('.summernote').summernote();
        $('.inputmask').inputmask();
    })
</script>
</body>
</html>
