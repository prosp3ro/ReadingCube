<?php require_once PARTIALS . "/head.view.php"; ?>
<?php require_once PARTIALS . "/header.view.php"; ?>

<div class="container">
    <div class="mt-5">
        <h1>Reset your password</h1>
    </div>

    <div class="w-25 mt-4">
        <form action="/forgot-password" method="post" class="row g-3" id="register">
            <div>
                <label for="email" class="form-label">Enter your email to search for your account.</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>

<?php require_once PARTIALS . "/scripts.view.php"; ?>
<?php require_once PARTIALS . "/footer.view.php"; ?>
<?php require_once PARTIALS . "/pageend.view.php"; ?>
