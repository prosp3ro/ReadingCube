<?php require_once(PARTIALS . "/head.view.php"); ?>
<?php require_once(PARTIALS . "/header.view.php"); ?>

<div class="container">
    <div class="mt-5">
        <h2>List of available books</h2>
    </div>

    <div class="mt-4 mb-5">
        <table class="table table-light table-hover table-bordered" id="booksDatatable">
            <thead class="table-success">
                <td>Book</td>
                <td>Author</td>
                <td>Year</td>
                <td class="text-center">Book details</td>
            </thead>
            <tbody>
                <?php foreach ($books as $book) : ?>
                    <tr>
                        <td>
                            <?= $book['book_name']; ?>
                        </td>
                        <td>
                            <?= $book['book_author']; ?>
                        </td>
                        <td>
                            <?= $book['book_year']; ?>
                        </td>
                        <td class="text-center">
                            <a class="btn btn-primary" href="/item/<?= $book['id'] ?>" role="button">Details</a>
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
        "pageLength": 25,
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
