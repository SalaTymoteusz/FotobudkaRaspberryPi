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
elseif(is_user_logged_in()){
    $user_version = 'user';
 }
else{
 wp_redirect('https://fotobudkaraspberry.pl/wp-admin');
 exit();
}
 ?>
           
<?php get_header(); ?>

<?php if($user_version == 'admin'){?> 
        <div class="sr-only" id="user_version_id"><?php  echo($user->ID); ?></div>
    <div class="sr-only" id="user_version"><?php  echo('admin'); ?></div>
<main class="l-main">
    <div class="content-wrapper content-wrapper--with-bg">
        <h2 class="page-title">Sesje</h2>
        <div class="page-content position-relative"> 
           <div onclick="refreshList()" class="refresh-button">Odśwież<i class="ml-3 fas fa-sync-alt"></i></div>
           <h3 class="sub-title">Lista sesji</h3>
           <table class="session-list">
               <tr class="session-box header-row">
                   <td>
                       Nazwa sesji
                   </td>
                   <td>
                       Użytkownik
                   </td>
                   <td>
                       Status
                   </td>
                   <td>
                       Akcje
                   </td>
                   <td>
                       Zdjęcia
                   </td>
               </tr>
           </table>
            <table class="session-list" id="current-session-box">
           </table>
           <div class="row p-4"><input type="text" class="live-search-box p-2 mr-5" placeholder="Wyszukaj"><div class="open-session-popup"><i class="mr-2 fas fa-plus"></i>Dodaj nową sesję</div></div>
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
        <i class="fas fa-plus"></i>
    </div>

    </div>

    </div>
</div>
<?php }elseif($user_version == 'user'){ ?>

 
<main class="l-main">
    <div class="sr-only" id="user_version_id"><?php  echo($user->ID); ?></div>
    <div class="sr-only" id="user_version"><?php  echo('user'); ?></div>
    <h2 class="page-title">Użytkownik: <?php  echo($user->data->display_name); ?></h2>
    <h2 class="page-title" >ID: <?php  echo($user->ID); ?></h2>
    <h2 class="page-title">Email: <?php  echo($user->data->user_email); ?></h2>
    <div class="content-wrapper content-wrapper--with-bg">
        <h2 class="page-title">Sesje</h2>
        <div class="page-content position-relative"> 
           <div onclick="refreshList()" class="refresh-button">Odśwież<i class="ml-3 fas fa-sync-alt"></i></div>
           <h3 class="sub-title">Aktualna sesja</h3>
            <div class="session-list" id="current-session-box">
           </div>
           <h3 class="sub-title">Lista sesji</h3>
           <div class="row pl-4"><input type="text" class="live-search-box p-2 mr-5" placeholder="Wyszukaj"><div class="open-session-popup"><i class="mr-2 fas fa-plus"></i>Dodaj nową sesję</div></div>
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
                        if($user_version == 'admin'){
                            $args = array(
                            'role'    => 'editor',
                            'orderby' => 'user_nicename',
                            'order'   => 'ASC'
                        );
                        $users = get_users( $args );

                        foreach ( $users as $user ) { ?>
            
                            <option value="<?php echo $user->ID; ?>"><?php echo $user->data->user_nicename; ?></option>
                    <?php } ?>


                        <?php }elseif($user_version == 'user'){?>
                            <option value="<?php echo $user->ID; ?>"><?php echo $user->data->user_nicename; ?></option>
                       <?php } ?>
                        
                        
        </select>
    </div>

    <div class="add-new-session" onclick="createSession()">
        <i class="fas fa-plus"></i>
    </div>

    </div>

    </div>
</div>
<?php } ?>
<?php get_footer(); ?>