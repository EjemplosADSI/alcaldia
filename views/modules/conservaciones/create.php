<?php
require("../../partials/routes.php");
require_once("../../partials/check_login.php");

use App\Controllers\ConservacionesController;
use App\Controllers\ContratosController;
use App\Controllers\UsuariosController;
use App\Models\GeneralFunctions;

$nameModel = "Conservacion";
$nameForm = 'frmCreate' . $nameModel;
$pluralModel = $nameModel . 'es';
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
                                <h3 class="card-title"><i class="fas fa-user"></i> &nbsp; Información
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
                                                <label for="numeroCaja" class="col-sm-2 col-form-label">Numero de
                                                    Caja</label>
                                                <div class="col-sm-10">
                                                    <input required type="number" minlength="1" class="form-control"
                                                           id="numeroCaja" name="numeroCaja"
                                                           placeholder="Ingrese el numero de la caja"
                                                           value="<?= $frmSession['numeroCaja'] ?? '' ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="carpeta" class="col-sm-2 col-form-label">Carpeta</label>
                                                <div class="col-sm-10">
                                                    <input required type="text" class="form-control" id="carpeta"
                                                           name="carpeta" placeholder="Ingrese el nombre de la carpeta"
                                                           value="<?= $frmSession['carpeta'] ?? '' ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="folio" class="col-sm-2 col-form-label">Numero de
                                                    Folio</label>
                                                <div class="col-sm-10">
                                                    <input required type="number" minlength="1" class="form-control"
                                                           id="folio" name="folio"
                                                           placeholder="Ingrese el numero del folio"
                                                           value="<?= $frmSession['folio'] ?? '' ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="tomo" class="col-sm-2 col-form-label">Tomo</label>
                                                <div class="col-sm-10">
                                                    <input required type="text" class="form-control" id="tomo"
                                                           name="tomo" placeholder="Ingrese el nombre del Tomo"
                                                           value="<?= $frmSession['tomo'] ?? '' ?>">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="medioEntrega" class="col-sm-2 col-form-label">
                                                    Medio de Entrega</label>
                                                <div class="col-sm-10">
                                                    <select id="medioEntrega" name="medioEntrega" class="custom-select">
                                                        <option <?= (!empty($frmSession['medioEntrega']) && $frmSession['medioEntrega'] == "Fisico") ? "selected" : ""; ?>
                                                                value="Fisico">Fisico
                                                        </option>
                                                        <option <?= (!empty($frmSession['medioEntrega']) && $frmSession['medioEntrega'] == "Digital") ? "selected" : ""; ?>
                                                                value="Digital">Digital
                                                        </option>
                                                        <option <?= (!empty($frmSession['medioEntrega']) && $frmSession['medioEntrega'] == "Magnetico") ? "selected" : ""; ?>
                                                                value="Magnetico">Magnetico
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="notas" class="col-sm-2 col-form-label">Notas</label>
                                                <div class="col-sm-10">
                                                    <textarea required id="notas" name="notas" class="summernote"
                                                              placeholder="Ingrese las notas de la conservacion">
                                                        <?= $frmSession['notas'] ?? '' ?>
                                                    </textarea>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="tipoVisibilidad" class="col-sm-2 col-form-label">
                                                    Tipo Visibilidad</label>
                                                <div class="col-sm-10">
                                                    <select id="tipoVisibilidad" name="tipoVisibilidad"
                                                            class="custom-select">
                                                        <option <?= (!empty($frmSession['tipoVisibilidad']) && $frmSession['tipoVisibilidad'] == "Privado") ? "selected" : ""; ?>
                                                                value="Privado">Privado
                                                        </option>
                                                        <option <?= (!empty($frmSession['tipoVisibilidad']) && $frmSession['tipoVisibilidad'] == "Publico") ? "selected" : ""; ?>
                                                                value="Publico">Publico
                                                        </option>
                                                    </select>
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
