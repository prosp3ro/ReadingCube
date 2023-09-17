<?php require_once(PARTIALS . "/head.view.php"); ?>
<?php require_once(PARTIALS . "/navbar.view.php"); ?>

<div class="container">
    <p class="mt-5 h1 font-monospace text-success text-center">Hey there</p>

    <div class="mt-5">
        <form action="/login" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" name="email" id="email">
                <div class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <div class="mt-5 mb-5">
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
