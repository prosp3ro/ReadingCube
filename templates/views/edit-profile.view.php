<?php require_once(PARTIALS . "/head.view.php"); ?>
<?php require_once(PARTIALS . "/header.view.php"); ?>

<div class="container">
    <div class="row mt-5">
        <div class="col-lg-6 line-break">
            <h3>Edit username or email</h3>
            <p class="mt-3 lh-lg">
                Current username: <strong><?= htmlspecialchars($user["username"]) ?></strong><br>
                Current email: <strong><?= htmlspecialchars($user["email"]) ?></strong>
            </p>

            <div class="form-group mt-4">
                <form action="/edit-profile" method="post" id="edit-profile">
                    <div class="mb-3">
                        <label for="newUsername" class="form-label">New username</label>
                        <input type="text" class="form-control" name="newUsername" id="newUsername">
                    </div>
                    <div class="mb-3">
                        <label for="newEmail" class="form-label">New email address</label>
                        <input type="newEmail" class="form-control" name="newEmail" id="newEmail">
                        <div class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Type your password to continue <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                    <?= $captcha->renderCaptcha(); ?>
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <button type="submit" class="mt-2 btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
        <div class="col-lg-6 line-break">
            <h3>Change password</h3>

            <div class="form-group mt-4">
                <form action="/update-password" method="post" id="change-password">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Your current password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="current_password" id="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="new_password" id="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation" required>
                    </div>
                    <?= $captcha->renderCaptcha(); ?>
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <button type="submit" class="mt-2 btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>


<?php require_once(PARTIALS . "/scripts.view.php"); ?>
<?php require_once(PARTIALS . "/footer.view.php"); ?>
<?php require_once(PARTIALS . "/pageend.view.php"); ?>
