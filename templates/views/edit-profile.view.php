<?php require_once(PARTIALS . "/head.view.php"); ?>
<?php require_once(PARTIALS . "/header.view.php"); ?>

<div class="container rounded bg-white mt-5 mb-5">
    <h2 class="ms-2 mt-2">Edit profile</h2>

    <div class="row">
        <!-- <div class="col-md-5 border-right"> -->
        <!--     <div class="d-flex flex-column align-items-center text-center p-3 py-2"> -->
        <!--         <img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg"> -->
        <!--         <span class="font-weight-bold">username</span> -->
        <!--         <span class="text-black-50">email</span><span> </span> -->
        <!--     </div> -->
        <!-- </div> -->
        <div class="col-md-7 border-right">
            <div class="p-3 py-3">
                <div class="row mt-3">
                    <div>
                        <label class="labels">Mobile Number</label>
                        <input type="text" class="form-control" placeholder="enter phone number" value="">
                    </div>
                    <div>
                        <label class="labels">Address Line 1</label>
                        <input type="text" class="form-control" placeholder="enter address line 1" value="">
                    </div>
                    <div>
                        <label class="labels">Address Line 2</label>
                        <input type="text" class="form-control" placeholder="enter address line 2" value="">

                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary profile-button" type="button">Save Profile</button>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require_once(PARTIALS . "/scripts.view.php"); ?>
<?php require_once(PARTIALS . "/footer.view.php"); ?>
<?php require_once(PARTIALS . "/pageend.view.php"); ?>
