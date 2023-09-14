<?php

use Src\Model\DB;

?>

<?php require_once(PARTIALS . "/head.view.php"); ?>

<!-- Navbar -->
<?php require_once(PARTIALS . "/navbar.view.php"); ?>

<!-- Main Sidebar Container -->
<?php require_once(PARTIALS . "/sidebar.view.php"); ?>

<?php
$db = new DB();
$sql = "SELECT * FROM books";
$statement = $db->prepare($sql);
$statement->execute();
$results = $statement->fetchAll();
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col">
                    <h1 class="m-0">Index</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Main row -->
            <div class="row">
                <table class="table table-dark table-striped table-hover" id="booksDatatable">
                    <thead>
                        <td>id</td>
                        <td>book name</td>
                        <td>book author</td>
                        <td>book year</td>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $book) : ?>
                            <tr>
                                <td>
                                    <?= $book['id']; ?>
                                </td>
                                <td>
                                    <?= $book['book_name']; ?>
                                </td>
                                <td>
                                    <?= $book['book_author']; ?>
                                </td>
                                <td>
                                    <?= $book['book_year']; ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <!-- /.row -->
        </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once(PARTIALS . "/footer.view.php"); ?>

<script>
new DataTable('#booksDatatable');
</script>

<?php require_once(PARTIALS . "/pageend.view.php"); ?>
