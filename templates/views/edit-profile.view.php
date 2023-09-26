<?php require_once(PARTIALS . "/head.view.php"); ?>
<?php require_once(PARTIALS . "/header.view.php"); ?>

<div class="container">
    <div class="row mt-5">
        <!-- <div class="col-md-5 border-right"> -->
        <!--     <div class="d-flex flex-column align-items-center text-center p-3 py-2"> -->
        <!--         <img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg"> -->
        <!--         <span class="font-weight-bold">username</span> -->
        <!--         <span class="text-black-50">email</span><span> </span> -->
        <!--     </div> -->
        <!-- </div> -->

        <div class="w-25 col">
            <h3 class="mb-5">Edit username or email</h3>

            <form action="/edit-profile" method="post" id="edit-profile">
                <div class="mb-3">
                    <label for="newUsername" class="form-label">Username</label>
                    <input type="text" class="form-control" name="newUsername" id="newUsername" placeholder="<?= htmlspecialchars($userData["username"]) ?>">
                </div>
                <div class="mb-3">
                    <label for="newEmail" class="form-label">Email address</label>
                    <input type="newEmail" class="form-control" name="newEmail" id="newEmail" placeholder="<?= htmlspecialchars($userData["email"]) ?>">
                    <div class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Type your password to continue</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>
                <?= $captcha->renderCaptcha(); ?>
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                <button type="submit" class="mt-2 btn btn-primary">Submit</button>
            </form>
        </div>
        <div class="w-25 col">
            <h3 class="mb-5">Change password</h3>

            <form action="/update-password" method="post" id="change-password">
                <div class="mb-3">
                    <label for="current_password" class="form-label">Current password</label>
                    <input type="password" class="form-control" name="current_password" id="current_password" required>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">New password</label>
                    <input type="password" class="form-control" name="new_password" id="new_password" required>
                </div>
                <div class="mb-3">
                    <label for="new_password_confirmation" class="form-label">Confirm password</label>
                    <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation" required>
                </div>
                <?= $captcha->renderCaptcha(); ?>
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                <button type="submit" class="mt-2 btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>


<?php require_once(PARTIALS . "/scripts.view.php"); ?>
<?php require_once(PARTIALS . "/footer.view.php"); ?>
<?php require_once(PARTIALS . "/pageend.view.php"); ?>
