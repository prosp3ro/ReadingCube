<?php require_once(PARTIALS . "/head.view.php"); ?>
<?php require_once(PARTIALS . "/header.view.php"); ?>

<?php
?>

<div class="container">
    <!-- <?php if (isset($_SESSION['user_id'])) : ?> -->
    <!--     <p class="text-success">You are logged in.</p> -->
    <!-- <?php endif; ?> -->

    <div class="mt-4 mb-5">
        <table class="table table-light table-hover table-bordered" id="booksDatatable">
            <thead class="table-success">
                <td>ID</td>
                <td>Book</td>
                <td>Author</td>
                <td>Year</td>
            </thead>
            <tbody>
                <?php foreach ($books as $book) : ?>
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
</div>

<?php require_once(PARTIALS . "/scripts.view.php"); ?>
<?php require_once(PARTIALS . "/footer.view.php"); ?>

<script>
    new DataTable('#booksDatatable', {
        "lengthMenu": [
            [10, 25, 50],
            [10, 25, 50]
        ],
        "pageLength": 10,
        responsive: {
            breakpoints: [{
                    name: 'bigdesktop',
                    width: Infinity
                },
                {
                    name: 'meddesktop',
                    width: 1480
                },
                {
                    name: 'smalldesktop',
                    width: 1280
                },
                {
                    name: 'medium',
                    width: 1188
                },
                {
                    name: 'tabletl',
                    width: 1024
                },
                {
                    name: 'btwtabllandp',
                    width: 848
                },
                {
                    name: 'tabletp',
                    width: 768
                },
                {
                    name: 'mobilel',
                    width: 480
                },
                {
                    name: 'mobilep',
                    width: 320
                }
            ]
        }
    });
</script>

<?php require_once(PARTIALS . "/pageend.view.php"); ?>
