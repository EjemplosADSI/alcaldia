<?php
require("../../partials/routes.php");
require_once("../../partials/check_login.php");
require("../../../app/Controllers/ContratosController.php");

use App\Controllers\ContratosController;
use App\Controllers\UsuariosController;
use App\Models\GeneralFunctions;
use App\Models\Contratos;
use Carbon\Carbon;

$nameModel = "Contrato";
$nameForm = 'frmEdit'.$nameModel;
$pluralModel = $nameModel.'s';
$frmSession = $_SESSION[$nameForm] ?? NULL;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE']  ?> | Editar <?= $nameModel ?></title>
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
                        <h1>Editar <?= $nameModel ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= $baseURL; ?>/views/"><?= $_ENV['ALIASE_SITE'] ?></a></li>
                            <li class="breadcrumb-item"><a href="index.php"><?= $pluralModel ?></a></li>
                            <li class="breadcrumb-item active">Editar</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Generar Mensajes de alerta -->
            <?= (!empty($_GET['respuesta'])) ? GeneralFunctions::getAlertDialog($_GET['respuesta'], $_GET['mensaje']) : ""; ?>
            <?= (empty($_GET['id'])) ? GeneralFunctions::getAlertDialog('error', 'Faltan Criterios de B??squeda') : ""; ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Horizontal Form -->
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-user"></i>&nbsp; Informaci??n del <?= $nameModel ?></h3>
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
                            <?php if (!empty($_GET["id"]) && isset($_GET["id"])) { ?>
                                <p>
                                <?php

                                $DataContrato = ContratosController::searchForID(["id" => $_GET["id"]]);
                                /* @var $DataContrato Contratos */
                                if (!empty($DataContrato)) {
                                    ?>
                                    <!-- form start -->
                                    <div class="card-body">
                                        <form class="form-horizontal" method="post" id="<?= $nameForm ?>"
                                              name="<?= $nameForm ?>"
                                              action="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=edit">
                                            <input id="id" name="id" value="<?= $DataContrato->getId(); ?>" hidden
                                                   required="required" type="text">
                                            <div class="row">
                                                <div class="col-sm-12">

                                                    <div class="form-group row">
                                                        <label for="tipo" class="col-sm-2 col-form-label">
                                                            Tipo</label>
                                                        <div class="col-sm-10">
                                                            <select id="tipo" name="tipo" class="custom-select">
                                                                <option <?= ($DataContrato->getTipo() == "Contratacion Directa") ? "selected" : ""; ?> value="Contratacion Directa">Contratacion Directa</option>
                                                                <option <?= ($DataContrato->getTipo() == "Minima Cuantia") ? "selected" : ""; ?> value="Minima Cuantia">Minima Cuantia</option>
                                                                <option <?= ($DataContrato->getTipo() == "Seleccion Abreviada de Menor Cuantia") ? "selected" : ""; ?> value="Seleccion Abreviada de Menor Cuantia">Seleccion Abreviada de Menor Cuantia</option>
                                                                <option <?= ($DataContrato->getTipo() == "Seleccion Abreviada Subasta Inversa") ? "selected" : ""; ?> value="Seleccion Abreviada Subasta Inversa">Seleccion Abreviada Subasta Inversa</option>
                                                                <option <?= ($DataContrato->getTipo() == "Licitacion Publica") ? "selected" : ""; ?> value="Licitacion Publica">Licitacion Publica</option>
                                                                <option <?= ($DataContrato->getTipo() == "Convenios") ? "selected" : ""; ?> value="Convenios">Convenios</option>
                                                                <option <?= ($DataContrato->getTipo() == "R??gimen Especial") ? "selected" : ""; ?> value="R??gimen Especial">R??gimen Especial</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="siglas" class="col-sm-2 col-form-label">Siglas</label>
                                                        <div class="col-sm-10">
                                                            <input required type="text" class="form-control" id="siglas"
                                                                   name="siglas" placeholder="Ingrese lals siglas"
                                                                   value="<?= $DataContrato->getSiglas() ?? ''; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="objeto" class="col-sm-2 col-form-label">Objeto</label>
                                                        <div class="col-sm-10">
                                                            <textarea required id="objeto" name="objeto" class="summernote" placeholder="Ingrese el objeto del contrato">
                                                                <?= $DataContrato->getObjeto() ?? '' ?>
                                                            </textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="obligaciones" class="col-sm-2 col-form-label">Obligaciones</label>
                                                        <div class="col-sm-10">
                                                            <textarea required id="obligaciones" name="obligaciones" class="summernote" placeholder="Ingrese las obligaciones del contrato">
                                                                <?= $DataContrato->getObligaciones() ?? '' ?>
                                                            </textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="fechaInicio" class="col-sm-2 col-form-label">Fecha Inicio</label>
                                                        <div class="col-sm-10">
                                                            <input required type="date" class="form-control" id="fechaInicio"
                                                                   name="fechaInicio" placeholder="Ingrese la fecha de Inicio"
                                                                   value="<?= $DataContrato->getFechaInicio()->toDateString() ?? '' ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="fechaFinal" class="col-sm-2 col-form-label">Fecha Final</label>
                                                        <div class="col-sm-10">
                                                            <input required type="date" class="form-control" id="fechaFinal"
                                                                   name="fechaFinal" placeholder="Ingrese la fecha Final"
                                                                   value="<?= $DataContrato->getFechaFinal()->toDateString() ?? '' ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="valor" class="col-sm-2 col-form-label">Valor</label>
                                                        <div class="col-sm-10">
                                                            <input required type="text" minlength="4" class="form-control inputmask text-left"
                                                                   id="valor" name="valor" placeholder="Ingrese el valor del contrato"
                                                                   data-inputmask-prefix="$" data-inputmask-digits="2"
                                                                   data-inputmask-greedy="false" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true"
                                                                   value="<?= $DataContrato->getValor() ?? '' ?>">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="enlaceSecop" class="col-sm-2 col-form-label">Enlace Secop</label>
                                                        <div class="col-sm-10">
                                                            <input required type="text" class="form-control" id="enlaceSecop"
                                                                   name="enlaceSecop" placeholder="Ingrese el enlace del secop"
                                                                   value="<?= $DataContrato->getEnlaceSecop() ?? '' ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                                                        <div class="col-sm-10">
                                                            <select required id="estado" name="estado" class="custom-select">
                                                                <option <?= ( !empty($DataContrato->getEnlaceSecop()) && $DataContrato->getEnlaceSecop() == "Activo") ? "selected" : ""; ?> value="Activo">Activo</option>
                                                                <option <?= ( !empty($DataContrato->getEnlaceSecop()) && $DataContrato->getEnlaceSecop() == "Suspendido") ? "selected" : ""; ?> value="Suspendido">Suspendido</option>
                                                                <option <?= ( !empty($DataContrato->getEnlaceSecop()) && $DataContrato->getEnlaceSecop() == "Liquidado") ? "selected" : ""; ?> value="Liquidado">Liquidado</option>
                                                                <option <?= ( !empty($DataContrato->getEnlaceSecop()) && $DataContrato->getEnlaceSecop() == "Cancelado") ? "selected" : ""; ?> value="Cancelado">Cancelado</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="contratista_id" class="col-sm-2 col-form-label">Contratista</label>
                                                        <div class="col-sm-10">
                                                            <?= UsuariosController::selectUsuario(
                                                                array(
                                                                    'id' => 'contratista_id',
                                                                    'name' => 'contratista_id',
                                                                    'class' => 'form-control select2bs4 select2-info',
                                                                    'defaultValue' => (!empty($DataContrato->getContratistaId())) ? $DataContrato->getContratistaId() : '',
                                                                    'where' => "estado = 'Activo' and Rol = 'Contratista'"
                                                                )
                                                            )
                                                            ?>
                                                            <a href="../usuarios/create.php">Crear contratista</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <button id="frmName" name="frmName" value="<?= $nameForm ?>" type="submit" class="btn btn-info">Enviar</button>
                                            <a href="index.php" role="button" class="btn btn-default float-right">Cancelar</a>
                                        </form>
                                    </div>
                                    <!-- /.card-body -->

                                <?php } else { ?>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                                        No se encontro ningun registro con estos parametros de
                                        busqueda <?= ($_GET['mensaje']) ?? "" ?>
                                    </div>
                                <?php } ?>
                                </p>
                            <?php } ?>
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
