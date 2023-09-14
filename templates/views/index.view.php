<?php require_once(PARTIALS . "/head.view.php"); ?>
<?php require_once(PARTIALS . "/navbar.view.php"); ?>

<div class="container">
    <div class="mt-5">
        <table class="table" id="booksDatatable">
            <thead>
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
    });
</script>

<?php require_once(PARTIALS . "/pageend.view.php"); ?>
