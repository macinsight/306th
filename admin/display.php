<?php

function displayHead()
{
    echo '
    <head>
        <script src="/assets/js/color-modes.js"></script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="github.com/macinsight">
        <title>The 306th | Your next ArmA 3 unit</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <link href="css/cover.css" rel="stylesheet">
        <link href="css/custom.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    </head>'
}
function displayThemeSelector()
{
    echo '<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">';
    echo '<symbol id="check2" viewBox="0 0 16 16">';
    echo '<path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />';
    echo '</symbol>';
    echo '<symbol id="circle-half" viewBox="0 0 16 16">';
    echo '<path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z" />';
    echo '</symbol>';
    echo '<symbol id="moon-stars-fill" viewBox="0 0 16 16">';
    echo '<path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z" />';
    echo '<path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z" />';
    echo '</symbol>';
    echo '<symbol id="sun-fill" viewBox="0 0 16 16">';
    echo '<path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z" />';
    echo '</symbol>';
    echo '</svg>';
    echo '<div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">';
    echo '<button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">';
    echo '<svg class="bi my-1 theme-icon-active" width="1em" height="1em">';
    echo '<use href="#circle-half"></use>';
    echo '</svg>';
    echo '<span class="visually-hidden" id="bd-theme-text">Toggle theme</span>';
    echo '</button>';
    echo '<ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">';
    echo '<li>';
    echo '<button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">';
    echo '<svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em">';
    echo '<use href="#sun-fill"></use>';
    echo '</svg>';
    echo 'Light';
    echo '<svg class="bi ms-auto d-none" width="1em" height="1em">';
    echo '<use href="#check2"></use>';
    echo '</svg>';
    echo '</button>';
    echo '</li>';
    echo '<li>';
    echo '<button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">';
    echo '<svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em">';
    echo '<use href="#moon-stars-fill"></use>';
    echo '</svg>';
    echo 'Dark';
    echo '<svg class="bi ms-auto d-none" width="1em" height="1em">';
    echo '<use href="#check2"></use>';
    echo '</svg>';
    echo '</button>';
    echo '</li>';
    echo '<li>';
    echo '<button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">';
    echo '<svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em">';
    echo '<use href="#circle-half"></use>';
    echo '</svg>';
    echo 'Auto';
    echo '<svg class="bi ms-auto d-none" width="1em" height="1em">';
    echo '<use href="#check2"></use>';
    echo '</svg>';
    echo '</button>';
    echo '</li>';
    echo '</ul>';
    echo '</div>';
}

function displayNavbar()
{
    echo '<div class="container sticky-top" id="navbar">';
    echo '<nav class="navbar navbar-expand-lg bg-body-tertiary rounded" id="navigator">';
    echo '<div class="container-fluid">';
    echo '<a class="navbar-brand" href="#">306th Assault Brigade</a>';
    echo '<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbartoggler" aria-controls="navbartoggler" aria-expanded="false" aria-label="Toggle navigation">';
    echo '<span class="navbar-toggler-icon"></span>';
    echo '</button>';
    echo '<div class="collapse navbar-collapse" id="navbartoggler">';
    echo '<ul class="navbar-nav me-auto mb-2 mb-lg-0">';
    echo '<a href="#intro" class="nav-link nav-item">Home</a>';
    echo '<a href="modlist.php" class="nav-link nav-item">Modlist</a>';
    echo '<a href="#intro" class="nav-link nav-item">Home</a>';
    echo '</ul>';
    echo '<div class="d-lg-flex col-lg-3 justify-content-lg-end">';
    echo '<a class="icon-link" href="https://discord.gg/D3U3e9seZ7">';
    echo '<button type="submit" class="btn btn-primary"><span class="bi-discord" width="48" height="48"></span></button>';
    echo '</a>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</nav>';
    echo '</div>';
}


function displayFooter()
{
    echo '<div class="container" id="footer">';
    echo '<footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">';
    echo '<p class="col-md-4 mb-0 text-body-secondary">&copy; 2023 macinsight <a href="//github.com/macinsight"><span class="bi-github"></span></a></p>';
    echo '<ul class="nav col-md-5 justify-content-end">';
    echo '<a href="#images" class="nav-item nav-link px-2 text-body-secondary">Gallery</a>';
    echo '<a href="#about" class="nav-item nav-link px-2 text-body-secondary">About</a>';
    echo '<a href="#units" class="nav-item nav-link px-2 text-body-secondary">Units</a>';
    echo '<a href="" class="nav-item nav-link px-2 text-body-secondary">Discord</a>';
    echo '<li class="nav-item"><a href="imprint.html" class="nav-link px-2 text-body-secondary">Imprint</a></li>';
    echo '<a href="//units.arma3.com/unit/306thbrigade" class="nav-item nav-link px-2 text-body-secondary">306th AB on A3 Units</a>';
    echo '</ul>';
    echo '</footer>';
    echo '</div>';
}