<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php 
    wp_head(); 
    ?>

</head>
<body>
    <nav class="legal101-navbar navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Logo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <?php
                    wp_nav_menu(
                        array(
                            'menu' => 'primary',
                            'container' => '',
                            'theme_location' => 'primary',
                            'items_wrap' => '<ul class="legal101-left-nav navbar-nav me-auto mb-2 mb-lg-0">%3$s</ul>',
                        )
                    );
                ?>
                 <?php
                    wp_nav_menu(
                        array(
                            'menu' => 'primary_right',
                            'container' => '',
                            'theme_location' => 'primary_right',
                            'items_wrap' => '<ul class="legal101-right-nav navbar-nav ms-auto mb-2 mb-lg-0">%3$s</ul>',
                        )
                    );
                ?>
            </div>
        </div>
    </nav>