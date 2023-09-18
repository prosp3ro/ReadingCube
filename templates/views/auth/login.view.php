<?php require_once(PARTIALS . "/head.view.php"); ?>
<?php require_once(PARTIALS . "/navbar.view.php"); ?>

<div class="container">
    <div class="mt-5">
        <form action="/login" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" name="email" id="email">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</div>


<?php require_once(PARTIALS . "/scripts.view.php"); ?>
<?php require_once(PARTIALS . "/footer.view.php"); ?>
<?php require_once(PARTIALS . "/pageend.view.php"); ?>