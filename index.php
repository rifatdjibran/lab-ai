<?php
include "includes/header.php";
include "includes/navbar.php";

$page = $_GET['page'] ?? 'home';

echo '<div class="content-wrapper">';

switch ($page) {
    case 'profil':
        include "public/profil_lab.php";
        break;
    case 'fasilitas':
        include "public/fasilitas.php";
        break;
    default:
        include "public/home.php";
        break;
}

echo '</div>';

include "includes/footer.php";
?>
