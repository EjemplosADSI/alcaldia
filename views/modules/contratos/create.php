<?php
require("../../partials/routes.php");
require_once("../../partials/check_login.php");

use App\Controllers\ContratosController;
use App\Controllers\UsuariosController;
use App\Models\GeneralFunctions;
use Carbon\Carbon;

$nameModel = "Contrato";
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
                                                <label for="tipo" class="col-sm-2 col-form-label">
                                                    Tipo</label>
                                                <div class="col-sm-10">
                                                    <select id="tipo" name="tipo" class="custom-select">
                                                        <option <?= (!empty($frmSession['tipo']) && $frmSession['tipo'] == "Contratacion Directa") ? "selected" : ""; ?>
                                                                value="Contratacion Directa">Contratacion Directa
                                                        </option>
                                                        <option <?= (!empty($frmSession['tipo']) && $frmSession['tipo'] == "Minima Cuantia") ? "selected" : ""; ?>
                                                                value="Minima Cuantia">Minima Cuantia
                                                        </option>
                                                        <option <?= (!empty($frmSession['tipo']) && $frmSession['tipo'] == "Seleccion Abreviada de Menor Cuantia") ? "selected" : ""; ?>
                                                                value="Seleccion Abreviada de Menor Cuantia">Seleccion
                                                            Abreviada de Menor Cuantia
                                                        </option>
                                                        <option <?= (!empty($frmSession['tipo']) && $frmSession['tipo'] == "Seleccion Abreviada Subasta Inversa") ? "selected" : ""; ?>
                                                                value="Seleccion Abreviada Subasta Inversa">Seleccion
                                                            Abreviada Subasta Inversa
                                                        </option>
                                                        <option <?= (!empty($frmSession['tipo']) && $frmSession['tipo'] == "Licitacion Publica") ? "selected" : ""; ?>
                                                                value="Licitacion Publica">Licitacion Publica
                                                        </option>
                                                        <option <?= (!empty($frmSession['tipo']) && $frmSession['tipo'] == "Convenios") ? "selected" : ""; ?>
                                                                value="Convenios">Convenios
                                                        </option>
                                                        <option <?= (!empty($frmSession['tipo']) && $frmSession['tipo'] == "Régimen Especial") ? "selected" : ""; ?>
                                                                value="Régimen Especial">Régimen Especial
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="siglas" class="col-sm-2 col-form-label">Siglas</label>
                                                <div class="col-sm-10">
                                                    <input required type="text" class="form-control" id="siglas"
                                                           name="siglas" placeholder="Ingrese lals siglas"
                                                           value="<?= $frmSession['siglas'] ?? '' ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="objeto" class="col-sm-2 col-form-label">Objeto</label>
                                                <div class="col-sm-10">
                                                    <textarea required id="objeto" name="objeto" class="summernote"
                                                              placeholder="Ingrese el objeto del contrato">
                                                        <?= $frmSession['objeto'] ?? '' ?>
                                                    </textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="obligaciones"
                                                       class="col-sm-2 col-form-label">Obligaciones</label>
                                                <div class="col-sm-10">
                                                    <textarea required id="obligaciones" name="obligaciones"
                                                              class="summernote"
                                                              placeholder="Ingrese las obligaciones del contrato">
                                                        <?= $frmSession['obligaciones'] ?? '' ?>
                                                    </textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="fechaInicio" class="col-sm-2 col-form-label">Fecha
                                                    Inicio</label>
                                                <div class="col-sm-10">
                                                    <input required type="date"
                                                           class="form-control" id="fechaInicio"
                                                           name="fechaInicio" placeholder="Ingrese la fecha de Inicio"
                                                           value="<?= $frmSession['fechaInicio'] ?? '' ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="fechaFinal" class="col-sm-2 col-form-label">Fecha
                                                    Final</label>
                                                <div class="col-sm-10">
                                                    <input required type="date"
                                                           class="form-control" id="fechaFinal"
                                                           name="fechaFinal" placeholder="Ingrese la fecha Final"
                                                           value="<?= $frmSession['fechaFinal'] ?? '' ?>">
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
                                                <label for="enlaceSecop" class="col-sm-2 col-form-label">Enlace
                                                    Secop</label>
                                                <div class="col-sm-10">
                                                    <input required type="text" class="form-control" id="enlaceSecop"
                                                           name="enlaceSecop" placeholder="Ingrese el enlace del secop"
                                                           value="<?= $frmSession['enlaceSecop'] ?? '' ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                                                <div class="col-sm-10">
                                                    <select required id="estado" name="estado" class="custom-select">
                                                        <option <?= (!empty($frmSession['estado']) && $frmSession['estado'] == "Activo") ? "selected" : ""; ?>
                                                                value="Activo">Activo
                                                        </option>
                                                        <option <?= (!empty($frmSession['estado']) && $frmSession['estado'] == "Suspendido") ? "selected" : ""; ?>
                                                                value="Suspendido">Suspendido
                                                        </option>
                                                        <option <?= (!empty($frmSession['estado']) && $frmSession['estado'] == "Liquidado") ? "selected" : ""; ?>
                                                                value="Liquidado">Liquidado
                                                        </option>
                                                        <option <?= (!empty($frmSession['estado']) && $frmSession['estado'] == "Cancelado") ? "selected" : ""; ?>
                                                                value="Cancelado">Cancelado
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="contratista_id"
                                                       class="col-sm-2 col-form-label">Contratista</label>
                                                <div class="col-sm-10">
                                                    <?= UsuariosController::selectUsuario(
                                                        array(
                                                            'id'    => 'contratista_id',
                                                            'name'  => 'contratista_id',
                                                            'class' => 'form-control select2bs4 select2-info',
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
