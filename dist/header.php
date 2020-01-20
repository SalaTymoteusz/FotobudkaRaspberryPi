<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<?php 
use WTM\Model\Template;
global $site_name; 
?>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="copyright" content="fotobudka.pl" />
    <meta name="author" content="fotobudka.pl Agencja Interaktywna" />
    <meta name='RATING' content='General' />
    <meta name='REVISIT-AFTER' content='2 days' />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name='MSSmartTagsPreventParsing' content='TRUE' />
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/r29/html5.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?> itemscope="" itemtype="http://schema.org/WebPage">
    <header id="header" class=" w-100">
        <div class="container-fluid d-flex flex-row pr-5 align-items-center">
            <div class="navbar">
                <?php Template::get_template('_partials/header/logo',['class' => 'navbar-brand']); ?>
                <?php Template::get_template_part('header/top-contact'); ?>
                <button type="button" id="nav-menu-btn" data-target=".navigation" class="d-block">
                    <i class="nav-menu-btn-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </i>
                    <span>Menu</span>
                </button>
                <?php Template::get_template_part('header/social-media'); ?>
            </div>
            <div class="navigation-overlay"></div>
            <nav class="d-flex navigation">
                <div class="navigation-wrap">
                    <button type="button" id="nav-menu-close-btn" data-target=".navigation" class="d-block"
                        aria-label="<?php _e('Zamknij Menu','fotobudka'); ?>">
                        <span></span>
                        <span></span>
                    </button>
                    <?php Template::get_template('_partials/header/logo',['class' => 'navbar-logo d-block d-lg-none']); ?>
                    <?php Template::get_template_part('header/social-media'); ?>
                    <?php 
                    wp_nav_menu( [
                        'menu' => 'nav_menu',
                        'menu_id' => 'nav_menu',
                        'depth' => 1,
                        'container' => '',
                        'container_class'=> '',
                        'menu_class' => 'd-flex',
                        'walker' => new WTM\Model\Navbar_Walker()
                    ]);
                    ?>
                </div>
            </nav>
        </div>
    </header>
    <main id="main" itemprop="mainContentOfPage">