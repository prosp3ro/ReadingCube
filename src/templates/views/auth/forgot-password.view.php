<?php require_once PARTIALS . "/head.view.php"; ?>
<?php require_once PARTIALS . "/header.view.php"; ?>

<div class="container">
    <div class="mt-5">
        <h1>Reset Your Password</h1>
    </div>

    <div class="w-25 mt-4">
        <form action="/forgot-password" method="post" class="row g-3" id="register">
            <div>
                <label for="new_password" class="form-label">New Password</label>
                <input type="new_password" class="form-control" name="new_password" id="new_password" required>
            </div>
            <div>
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
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
