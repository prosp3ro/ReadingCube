<?php require_once(PARTIALS . "/head.view.php"); ?>
<?php require_once(PARTIALS . "/header.view.php"); ?>

<div class="container">
    <h2 class="my-5">Edit profile</h2>

    <div class="row">
        <!-- <div class="col-md-5 border-right"> -->
        <!--     <div class="d-flex flex-column align-items-center text-center p-3 py-2"> -->
        <!--         <img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg"> -->
        <!--         <span class="font-weight-bold">username</span> -->
        <!--         <span class="text-black-50">email</span><span> </span> -->
        <!--     </div> -->
        <!-- </div> -->

        <div class="w-25">
            <form action="/register" method="post" id="register">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" id="username" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                    <div class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                </div>
                <div class="g-recaptcha" data-sitekey="<?= GOOGLE_RECAPTCHA_SITE_KEY ?>"></div>
                <button type="submit" class="mt-2 btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>


<?php require_once(PARTIALS . "/scripts.view.php"); ?>
<?php require_once(PARTIALS . "/footer.view.php"); ?>
<?php require_once(PARTIALS . "/pageend.view.php"); ?>
