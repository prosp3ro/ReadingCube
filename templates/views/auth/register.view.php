<?php require_once(PARTIALS . "/head.view.php"); ?>
<?php require_once(PARTIALS . "/header.view.php"); ?>

<div class="container">
    <div class="mt-4 mb-4">
        <h1>Register</h1>
    </div>

    <div class="w-25">
        <form action="/register" method="post" class="row g-3" id="register">
            <div>
                <label for="username" class="form-label d-inline-block">Username</label>
                <div class="input-group">
                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                    <input type="text" class="form-control" id="username" aria-describedby="inputGroupPrepend" required>
                </div>
            </div>
            <div>
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" name="email" id="email" required>
                <div class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div>
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <div>
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
            </div>
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
            <?= $captcha->renderCaptcha() ?>
            <div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>

<?php require_once(PARTIALS . "/scripts.view.php"); ?>

<script src="js/validation/register.js"></script>

<?php require_once(PARTIALS . "/footer.view.php"); ?>
<?php require_once(PARTIALS . "/pageend.view.php"); ?>
