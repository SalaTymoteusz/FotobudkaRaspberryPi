<?php 
/*
Template Name: Szablon - Kod
*/

use WTM\Model\Template;
use WTM\Model\Validate;

global $post;


  

 $user = wp_get_current_user();
if ( in_array( 'administrator', (array) $user->roles ) ) {
    $user_version = 'admin';
}
elseif(in_array( 'editor', (array) $user->roles )){
    $user_version = 'editor';
}
elseif(in_array( 'subscriber', (array) $user->roles )){
    $user_version = 'subscriber';
}
else{
 wp_redirect('https://fotobudkaraspberry.pl/wp-admin');
 exit();
}
 ?>
        <script>
            function id(v) { return document.getElementById(v); }
    function loadbar() {
        var ovrl = id("load-overlay"),
            prog = id("progress"),
            stat = id("progstat"),
            img = document.images,
            c = 0,
            tot = img.length;
        if (tot == 0) return doneLoading();

        function imgLoaded() {
            c += 1;
            var perc = ((100 / tot * c) << 0) + "%";
            prog.style.width = perc;
            stat.innerHTML = "Ładowanie " + perc;
            if (c === tot) return doneLoading();
        }
        function doneLoading() {
            ovrl.style.opacity = 0;
            setTimeout(function () {
                ovrl.style.display = "none";
            }, 700);
        }
        for (var i = 0; i < tot; i++) {
            var tImg = new Image();
            tImg.onload = imgLoaded;
            tImg.onerror = imgLoaded;
            tImg.src = img[i].src;
        }
    }
    document.addEventListener('DOMContentLoaded', loadbar, false);
        </script>   
<?php get_header(); ?>

<?php if($user_version == 'admin'){?> 
        <div class="sr-only" id="user_version_id"><?php  echo($user->ID); ?></div>
        <div class="sr-only" id="user_version"><?php  echo('admin'); ?></div>
<main class="l-main">

    <div class="col-12 logo-panel"><?php global $site_name; ?>

        <?php $custom_logo_id = get_theme_mod( 'custom_logo' ); ?>
        <?php if(Validate::check_variable($custom_logo_id)): ?>
            <?php $image = wp_get_attachment_image_src( $custom_logo_id , 'full' ); ?>

        <a href="<?php echo home_url(); ?>" title="<?php echo $site_name; ?>"
            <?php echo isset($class) ? ' class="'.$class.'"' : ''; ?>>
            <img class="img-fluid" src="<?php echo $image[0]; ?>" alt="<?php echo $site_name; ?>">
        </a>
        <?php endif; ?>

    </div>

<div id="load-overlay">

    <div class="loader"><img src="/wp-content/uploads/2020/01/loader.gif"/></div>
    <div id="progstat"></div>
    <div id="progress"></div>

</div>

    <div class="col-12"><a href="<?php echo wp_logout_url( home_url()); ?>" class="g-button p-2 m-2" title="Wyloguj">Wyloguj</a><a href="<?php echo admin_url(); ?>" class="g-button p-2 m-2" title="cms">CMS</a></div>

    <div class="content-wrapper content-wrapper--with-bg">
        <h2 class="page-title">Sesje</h2>
        <div class="page-content position-relative"> 
           <div onclick="refreshList()" class="refresh-button g-button"><i class="fas fa-sync-alt"></i>Odśwież</div>
           <h3 class="sub-title">Lista sesji</h3>
           <table class="session-list">
               <tr class="session-box header-row">
                   <td> Nazwa sesji </td>
                   <td> Użytkownik </td>
                   <td> Status </td>
                   <td> Akcje </td>
                   <td> Zdjęcia </td>
               </tr>
           </table>
            <table class="session-list" id="current-session-box">
           </table>
           <div class="row p-4"><input type="text" class="live-search-box p-2 mr-5" placeholder="Wyszukaj"><div class="open-session-popup g-button"><i class="mr-2 fas fa-plus"></i>Dodaj nową sesję</div></div>
           <div class="session-list live-search-list" id="session-list">
           </div>         
        </div>

        <h3 class="sub-title mt-4">Zdjęcia</h3>
        <div class="page-content image-list" id="image-list">
        </div>

        <h2 class="page-title  mt-4">Logi</h2>
        <pre class="page-content console" id="console_panel"> 
        </pre>

    </div>
</main>
<div class="popup-overlay d-none">
    <div class="popup-session">

    <div class="popup-wrap">
        <span class="close-popup-session">&times</span>
    <div class="row">
        <label class="col-6" for="new-session-name">Podaj nazwę nowej sesji: </label>
        <input class="col-6" type="text" id="new-session-name">
    </div>

    <div class="row mt-3">
        <label class="col-6 sr-only" for="select-user-for-session">Wybierz użytkownika</label>
        <select class="form-control" id="new-session-user">
            <option value="" disabled selected>Użytkownicy</option>
            <?php

                        $args = array(
                            'role'    => 'editor',
                            'orderby' => 'user_nicename',
                            'order'   => 'ASC'
                        );
                        $users = get_users( $args );
                        foreach ( $users as $user ) { ?>
            
                            <option value="<?php echo $user->ID; ?>"><?php echo $user->data->user_nicename; ?></option>
                    <?php }
                    
                        ?>
        </select>
    </div>

    <div class="add-new-session" onclick="createSession()">
        <i class="fas fa-plus"></i>Dodaj sesję
    </div>

    </div>

    </div>
</div>
<!-- ...................................................... -->
<?php }elseif($user_version == 'editor'){ ?>

 
<main class="l-main">

    <div class="col-12 logo-panel"><?php global $site_name; ?>

        <?php $custom_logo_id = get_theme_mod( 'custom_logo' ); ?>
        <?php if(Validate::check_variable($custom_logo_id)): ?>
            <?php $image = wp_get_attachment_image_src( $custom_logo_id , 'full' ); ?>

        <a href="<?php echo home_url(); ?>" title="<?php echo $site_name; ?>"
            <?php echo isset($class) ? ' class="'.$class.'"' : ''; ?>>
            <img class="img-fluid" src="<?php echo $image[0]; ?>" alt="<?php echo $site_name; ?>">
        </a>
        <?php endif; ?>

    </div>

    <div id="load-overlay">

        <div class="loader"><img src="/wp-content/uploads/2020/01/loader.gif"/></div>
        <div id="progstat"></div>
        <div id="progress"></div>

    </div>

    <div class="col-12"><a href="<?php echo wp_logout_url( home_url()); ?>" class="g-button p-2 m-2" title="Wyloguj">Wyloguj</a></div>

    <div class="sr-only" id="user_version_id"><?php  echo($user->ID); ?></div>
    <div class="sr-only" id="user_version"><?php  echo('user'); ?></div>
    <h2 class="page-title sr-only">Użytkownik: <?php  echo($user->data->display_name); ?></h2>
    <h2 class="page-title sr-only" >ID: <?php  echo($user->ID); ?></h2>
    <h2 class="page-title sr-only">Email: <?php  echo($user->data->user_email); ?></h2>
    
    <div class="content-wrapper content-wrapper--with-bg">
        <h2 class="page-title">Sesje</h2>
        <div class="page-content position-relative"> 
           <div onclick="refreshList()" class="refresh-button g-button"><i class="fas fa-sync-alt"></i>Odśwież</div>
           <h3 class="sub-title">Lista sesji</h3>
           <table class="session-list">
               <tr class="session-box header-row">
                   <td> Nazwa sesji </td>
                   <td> Użytkownik </td>
                   <td> Status </td>
                   <td> Akcje </td>
                   <td> Zdjęcia </td>
               </tr>
           </table>
            <table class="session-list" id="current-session-box">
           </table>
           <div class="row p-4"><input type="text" class="live-search-box p-2 mr-5" placeholder="Wyszukaj"><div class="open-session-popup g-button"><i class="mr-2 fas fa-plus"></i>Dodaj nową sesję</div></div>
           <div class="session-list live-search-list" id="session-list">
           </div>         
        </div>

        <h3 class="sub-title mt-4">Zdjęcia</h3>
        <div class="page-content image-list" id="image-list">
        </div>

        <h2 class="page-title  mt-4">Logi</h2>
        <pre class="page-content console" id="console_panel"> 
        </pre>

    </div>
</main>
<div class="popup-overlay d-none">
    <div class="popup-session">

    <div class="popup-wrap">
        <span class="close-popup-session">&times</span>
    <div class="row">
        <label class="col-6" for="new-session-name">Podaj nazwę nowej sesji: </label>
        <input class="col-6" type="text" id="new-session-name">
    </div>

    <div class="row mt-3">
        <label class="col-6 sr-only" for="select-user-for-session">Wybierz użytkownika</label>
        <select class="form-control" id="new-session-user">
            <option value="<?php echo $user->ID; ?>" selected><?php echo $user->data->user_nicename; ?></option>
        </select>
    </div>

    <div class="add-new-session" onclick="createSession()">
        <i class="fas fa-plus"></i>
    </div>

    </div>

    </div>
</div>
<?php }elseif($user_version == 'subscriber'){ ?>
    
    <div class="subscriber-message">
        <div class="log-sub"><a href="<?php echo wp_logout_url( home_url()); ?>" class="g-button p-2 m-2" title="Wyloguj">Wyloguj</a></div>
        <div class="msg-text"><?php _e('Cieszymy się, że zdecydowałeś/aś się na współpracę z nami. Aby zakończyć proces rejestracji skontaktuj się z administratorem','fotobudka') ?><div class="d-flex justify-content-center align-items-center mt-3"><a href="tel:111222333" class="g-button p-2 mr-2">Zadzwoń</a> <a href="mailto:mail@fotobudkaraspberry.pl" class="g-button p-2">Napisz</a></div></div>
        
    </div>
<?php } ?>
<?php get_footer(); ?>