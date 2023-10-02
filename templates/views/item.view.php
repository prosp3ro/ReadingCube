<?php require_once(PARTIALS . "/head.view.php"); ?>
<?php require_once(PARTIALS . "/header.view.php"); ?>

<div class="container">
    <div class="mt-5">
        <div>
            <h1>Book information</h1>
        </div>
        <div class="mt-4">
            <strong>Name:</strong> <?= htmlspecialchars($item["book_name"]) ?>
            <br>
            <strong>Author:</strong> <?= htmlspecialchars($item["book_author"]) ?>
            <br>
            <strong>Year of release:</strong> <?= htmlspecialchars($item["book_year"]) ?>
            <br>
            <strong>Description:</strong> Lorem ipsum dolor sit amet consectetur, adipisicing elit. Vitae voluptates maiores sequi exercitationem harum omnis animi deserunt ducimus amet eos magnam, soluta quam. Fuga minima illum hic quos, excepturi explicabo?
        </div>
    </div>
</div>

<?php require_once(PARTIALS . "/scripts.view.php"); ?>
<?php require_once(PARTIALS . "/footer.view.php"); ?>
<?php require_once(PARTIALS . "/pageend.view.php"); ?>
