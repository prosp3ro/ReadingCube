<nav class="navbar navbar-expand-lg pt-2 pb-2 navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/"><?= APP_NAME ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <!--  if user is not logged in then show this information -->
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="/register">Register</a></li>
                <li class="nav-item bg-primary"><a class="nav-link active" aria-current="page" href="/login">Login</a></li>
            </ul>
        </div>
    </div>
</nav>
