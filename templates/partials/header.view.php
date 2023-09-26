<header class="p-3 text-white bg-dark">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                    <use xlink:href="#bootstrap"></use>
                </svg>
            </a>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="/" class="nav-link px-2 <?= urlIs("/") ?>">Home</a></li>
                <li><a href="/about-us" class="nav-link px-2 <?= urlIs("/about-us") ?>">About Us</a></li>
                <li><a href="/faq" class="nav-link px-2 <?= urlIs("/faq") ?>">FAQ</a></li>
                <li><a href="/contact" class="nav-link px-2 <?= urlIs("/contact") ?>">Contact</a></li>
            </ul>

            <!-- <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3"> -->
            <!--   <input type="search" class="form-control form-control-dark" placeholder="Search..." aria-label="Search"> -->
            <!-- </form> -->

            <?php if (isset($user)) : ?>
                <div class="dropdown text-end">
                    <a href="#" class="d-block text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://github.com/mdo.png" alt="mdo" width="28" height="28" class="rounded-circle me-2">
                        <?= htmlspecialchars($user["username"]) ?>
                    </a>
                    <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
                        <!-- <li><a class="dropdown-item" href="#">Settings</a></li> -->
                        <li><a class="dropdown-item" href="/edit-profile">Profile settings</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="/logout">Log out</a></li>
                    </ul>
                </div>
            <?php else : ?>
                <form action="/login" method="get">
                    <button type="submit" class="btn btn-outline-light me-2" id="loginButton">Login</button>
                </form>
                <form action="/register" method="get">
                    <button type="submit" class="btn btn-warning">Register</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</header>
