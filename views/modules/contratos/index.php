<?php
require_once("../../../app/Controllers/ContratosController.php");
require_once("../../partials/routes.php");
require_once("../../partials/check_login.php");

use App\Controllers\ContratosController;
use App\Models\GeneralFunctions;
use App\Models\Contratos;

$nameModel = "Contrato";
$pluralModel = $nameModel.'s';
$frmSession = $_SESSION['frm'.$pluralModel] ?? NULL;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Gestión de <?= $pluralModel ?></title>
    <?php require("../../partials/head_imports.php"); ?>
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/datatables-responsive/css/responsive.bootstrap4.css">
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/datatables-buttons/css/buttons.bootstrap4.css">
</head>
<body class="hold-transition sidebar-mini">

<!-- Site wrapper -->
<div class="wrapper">
    <?php require_once("../../partials/navbar_customization.php"); ?>

    <?php require_once("../../partials/sliderbar_main_menu.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Pagina Principal</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= $baseURL; ?>/views/"><?= $_ENV['ALIASE_SITE'] ?></a></li>
                            <li class="breadcrumb-item active"><?= $pluralModel ?></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Generar Mensajes de alerta -->
            <?= (!empty($_GET['respuesta'])) ? GeneralFunctions::getAlertDialog($_GET['respuesta'], $_GET['mensaje']) : ""; ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Default box -->
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-user"></i> &nbsp; Gestionar <?= $pluralModel ?></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="card-refresh"
                                            data-source="index.php" data-source-selector="#card-refresh-content"
                                            data-load-on-init="false"><i class="fas fa-sync-alt"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                class="fas fa-expand"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            data-toggle="tooltip" title="Collapse">
                                        <i class="fas fa-minus"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove"
                                            data-toggle="tooltip" title="Remove">
                                        <i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto mr-auto"></div>
                                    <div class="col-auto">
                                        <a role="button" href="create.php" class="btn btn-primary float-right"
                                           style="margin-right: 5px;">
                                            <i class="fas fa-plus"></i> Crear <?= $nameModel ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <table id="tbl<?= $pluralModel ?>" class="datatable table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Contratista</th>
                                                <th>Tipo</th>
                                                <th>Siglas</th>
                                                <th>Objeto</th>
                                                <th>Obligaciones</th>
                                                <th>Fecha Inicio</th>
                                                <th>Fecha Final</th>
                                                <th>Valor</th>
                                                <th>Enlace</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $arrContratos = ContratosController::getAll();
                                            /* @var $arrContratos Contratos[] */
                                            if(is_array($arrContratos) && count($arrContratos)>0)
                                            foreach ($arrContratos as $contrato) {
                                                ?>
                                                <tr>
                                                    <td><?= $contrato->getId(); ?></td>
                                                    <td><?= $contrato->getContratista()->nombresCompletos(); ?></td>
                                                    <td><?= $contrato->getTipo(); ?></td>
                                                    <td><?= $contrato->getSiglas(); ?></td>
                                                    <td><?= $contrato->getObjeto(); ?></td>
                                                    <td><?= $contrato->getObligaciones(); ?></td>
                                                    <td><?= $contrato->getFechaInicio(); ?></td>
                                                    <td><?= $contrato->getFechaFinal(); ?></td>
                                                    <td><?= GeneralFunctions::formatCurrency($contrato->getValor()); ?></td>
                                                    <td><a href="<?= $contrato->getEnlaceSecop(); ?>">Enlace Secop</a></td>
                                                    <td><?= $contrato->getEstado(); ?></td>
                                                    <td>
                                                        <a href="show.php?id=<?php echo $contrato->getId(); ?>"
                                                           type="button" data-toggle="tooltip" title="Ver"
                                                           class="btn docs-tooltip btn-warning btn-xs"><i
                                                                    class="fa fa-eye"></i></a>
                                                        <a href="edit.php?id=<?php echo $contrato->getId(); ?>"
                                                           type="button" data-toggle="tooltip" title="Actualizar"
                                                           class="btn docs-tooltip btn-primary btn-xs"><i
                                                                    class="fa fa-edit"></i></a>
                                                        <?php if ($contrato->getEstado() != "Activo") { ?>
                                                            <a href="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=activate&id=<?= $contrato->getId(); ?>"
                                                               type="button" data-toggle="tooltip" title="Activar"
                                                               class="btn docs-tooltip btn-success btn-xs"><i
                                                                        class="fa fa-check-square"></i></a>
                                                        <?php } else { ?>
                                                            <a type="button"
                                                               href="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=cancelar&id=<?= $contrato->getId(); ?>"
                                                               data-toggle="tooltip" title="Inactivar"
                                                               class="btn docs-tooltip btn-danger btn-xs"><i
                                                                        class="fa fa-times-circle"></i></a>
                                                        <?php } ?>
                                                        <br/>
                                                        <a href="../conservaciones/create.php?idContrato=<?php echo $contrato->getId(); ?>"
                                                           type="button" data-toggle="tooltip" title="Registrar Documento"
                                                           class="btn docs-tooltip btn-github btn-xs"><i
                                                                    class="fa fa-boxes"></i></a>
                                                        <a href="../pagos/create.php?idContrato=<?php echo $contrato->getId(); ?>"
                                                           type="button" data-toggle="tooltip" title="Registrar Pago"
                                                           class="btn docs-tooltip btn-instagram btn-xs"><i
                                                                    class="far fa-money-bill-alt"></i></a>
                                                    </td>
                                                </tr>
                                            <?php } ?>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Contratista</th>
                                                <th>Tipo</th>
                                                <th>Siglas</th>
                                                <th>Objeto</th>
                                                <th>Obligaciones</th>
                                                <th>Fecha Inicio</th>
                                                <th>Fecha Final</th>
                                                <th>Valor</th>
                                                <th>Enlace</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                Pie de Página.
                            </div>
                            <!-- /.card-footer-->
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
<!-- Scripts requeridos para las datatables -->
<?php require('../../partials/datatables_scripts.php'); ?>

</body>
</html>
