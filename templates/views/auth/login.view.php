<?php require_once(PARTIALS . "/head.view.php"); ?>
<?php require_once(PARTIALS . "/header.view.php"); ?>

<div class="container">
    <div class="mt-4 mb-4">
        <h1>Login</h1>
    </div>

    <?php if ($registerMessage === "success") : ?>
        <p class="fw-bold text-success mb-4">You have been successfully registered. Now you can log in.</p>
    <?php endif ?>

    <div class="w-25">
        <form action="/login" method="post" id="login">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" name="email" id="email" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <?= $captcha->renderCaptcha(); ?>
            <button type="submit" class="btn btn-primary mt-2">Submit</button>
        </form>
    </div>
</div>

<?php require_once(PARTIALS . "/scripts.view.php"); ?>

<script src="js/validation/login.js"></script>

<?php require_once(PARTIALS . "/footer.view.php"); ?>
<?php require_once(PARTIALS . "/pageend.view.php"); ?>
