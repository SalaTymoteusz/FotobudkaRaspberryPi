<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if(!is_admin()) return;


?>

<style>
    .form-group {
        position: relative;
    }

    .form-control {
        cursor: pointer;
        font-weight: bold;
        display: block;
        margin: 20px 0 10px;
    }

    #meta_title {
        padding: 3px 50px 3px 8px;
        font-size: 1.7em;
        line-height: 100%;
        height: 1.7em;
        width: 100%;
        outline: 0;
        margin: 0 0 3px;
        background-color: #fff;
    }

    #meta-title-strlen,
    #meta-description-strlen {
        position: absolute;
        top: 38px;
        font-size: 1.25em;
        right: 10px;
    }

    #meta-title-strlen.has-error,
    #meta-description-strlen.has-error {
        color: #DC3232;
    }

    #meta-title-strlen.has-warning,
    #meta-description-strlen.has-warning {
        color: #F56E28;
    }

    #meta-title-strlen.has-success,
    #meta-description-strlen.has-success {
        color: #46B450;
    }

    #meta_description {
        padding-right: 40px;
    }

    .widefat-container {
        border: 1px solid #ddd;
        -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, .07);
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, .07);
        background-color: #fff;
        color: #32373c;
        outline: 0;
        -webkit-transition: 50ms border-color ease-in-out;
        transition: 50ms border-color ease-in-out;
        padding: 10px 6px 0px 6px;
    }

    .widefat-container>* {
        vertical-align: top;
    }

    .widefat-container>input[type="text"] {
        border: none;
        background-color: rgba(0, 0, 0, .07);
        padding: 6px;
        height: 32.2px;
        display: inline-block;
        margin: 1px 0 10px;
    }

    .widefat-container span {
        padding: 6px 3px 6px 7px;
        border-radius: 3px;
        color: #fff;
        background-color: #0085ba;
        position: relative;
        margin-right: 5px;
        display: inline-block;
        margin-bottom: 10px;
        box-shadow: 0 1px 0 #006799;
        text-shadow: 0 -1px 1px #006799, 1px 0 1px #006799, 0 1px 1px #006799, -1px 0 1px #006799;
        border-color: #0073aa #006799 #006799;
        -webkit-box-shadow: 0 1px 0 #006799;
        white-space: nowrap;
        border-width: 1px;
        border-style: solid;
        outline: none;
    }

    .widefat-container span:last-of-type {
        margin-right: 6px;
    }

    .widefat-container span a {
        color: #fff;
        right: 3px;
        text-decoration: none;
        display: inline-block;
        padding: 0 4px;
        opacity: 0.5;
        cursor: pointer;
    }

    .input-group {
        vertical-align: top;
        display: table;
        width: 100%;
    }

    .input-group>* {
        display: table-cell;
    }

    .input-group>span {
        font-size: 13px;
        line-height: 20px;
        height: 20px;
        padding: 3px 7px;
        background: #F4F4F4;
        border: #DFDFDF solid 1px;
        width: 170px;
    }

    .input-group>input {
        height: 28.5px;
    }




    .switch-field {
        overflow: hidden;
        margin-top: 20px;
    }

    .switch-title {
        float: left;
        margin: 7px 15px 7px 0;
    }

    .switch-field input {
        position: absolute !important;
        clip: rect(0, 0, 0, 0);
        height: 1px;
        width: 1px;
        border: 0;
        overflow: hidden;
    }

    .switch-field label {
        float: left;
    }

    .switch-field label {
        display: inline-block;
        width: 60px;
        background-color: #e4e4e4;
        color: rgba(0, 0, 0, 0.6);
        font-size: 14px;
        font-weight: normal;
        text-align: center;
        text-shadow: none;
        padding: 6px 14px;
        border: 1px solid rgba(0, 0, 0, 0.2);
        -webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
        -webkit-transition: all 0.1s ease-in-out;
        -moz-transition: all 0.1s ease-in-out;
        -ms-transition: all 0.1s ease-in-out;
        -o-transition: all 0.1s ease-in-out;
        transition: all 0.1s ease-in-out;
    }

    .switch-field label:hover {
        cursor: pointer;
    }

    .switch-field input:checked+label {
        background-color: #0085BA;
        -webkit-box-shadow: none;
        box-shadow: none;
        color: #fff;
    }

    .switch-field label:first-of-type {
        border-radius: 4px 0 0 4px;
    }

    .switch-field label:last-of-type {
        border-radius: 0 4px 4px 0;
    }
</style>
<div>
    <?php

    if( $post->ID !== get_option('page_on_front')){
        ?>
    <div class="form-group switch-field">
        <div class="form-control switch-title">Czy indeksować stronę?</div>
        <input type="radio" id="switch_left" autocomplete="off" name="meta_index" value="1" <?php echo (int)$meta_index
            ? ' checked ' : '' ?> />
        <label for="switch_left">Tak</label>
        <input type="radio" id="switch_right" autocomplete="off" name="meta_index" value="0" <?php echo
            !(int)$meta_index ? ' checked ' : '' ?> />
        <label for="switch_right">Nie</label>
    </div>

    <div class="meta-title-wrap form-group">
        <label class="form-control" for="meta_title">Tytuł meta</label>
        <input name="meta_title" id="meta_title" class="widefat" value='<?php echo $meta_title; ?>'
            placeholder="<?php echo \WTM\Controllers\Seo::get_document_title($post) ?>" autocomplete="off" type="text">
        <?php
        $class = 'has-error';
        if(strlen($meta_title) >= 10 && strlen($meta_title) < 35)
        $class = 'has-warning';
        elseif(strlen($meta_title) >= 35  && strlen($meta_title) < 70)
        $class = 'has-success';
        ?>
        <span id="meta-title-strlen" class="<?php echo $class;?>">
            <?php echo strlen($meta_title);?>
        </span>
        <p class="description">Tytuł wyświetlany w nagłówku strony (jęśli pusty wyświetlany jest domyślny tytuł
            stron) (zalecana długość od 35 do 70 znaków).</p>
    </div>
    <?php
    }
    ?>
    <div class="form-group">
        <label class="form-control" for="key_words">Słowa kluczowe</label>
        <div class="widefat-container">
            <?php 
        if(isset($key_words) && !empty($key_words)){
            $key_words_array = explode(',',$key_words);
            foreach ($key_words_array as $word) {
                printf('<span>%s<a href="#">x</a></span>',$word);
            }
        }?><input type="text" placeholder="Dodaj słowo kluczowe" autocomplete="off"><input type="hidden"
                class="widefat" name="key_words" id="key_words" value="<?php echo $key_words; ?>">
        </div>
        <p class="description">Aby szybciej dodać słowa kluczowe wklej lub wpisz tekst oddzielony "," i wciśnij
            "enter".</p>
    </div>
    <div class="form-group">
        <label class="form-control" for="meta_description">Opis meta</label>
        <textarea name="meta_description" id="meta_description" class="widefat" rows="2"
            autocomplete="off"><?php echo $meta_description; ?></textarea>
        <?php
        $class = 'has-error';
        if(strlen($meta_title) >= 50 && strlen($meta_title) < 69)
        $class = 'has-warning';
        elseif(strlen($meta_title) >= 70  && strlen($meta_title) < 320)
        $class = 'has-success';
        ?>
        <span id="meta-description-strlen" class="<?php echo $class;?>">
            <?php echo strlen($meta_description);?>
        </span>
        <p class="description">Opis meta znadujący sie pod tytułem na stronie wyszukiwania Google (zaleca dłogość od
            70 do 320 znaków).</p>
    </div>
    <div class="form-group">
        <label class="form-control" for="slider">Slider</label>
        <div class="input-group">
            <span for="slider">[sliderplugin id="slider_id"]</span>
            <input name="slider" id="slider" class="widefat" autocomplete="off" value='<?php echo $slider; ?>'
                type="text">
        </div>
        <span class="description">wprowadź shortcode slidera</span>
    </div>
</div>

<script>
    jQuery(document).on('keypress', '.widefat-container > input[type="text"]', function (e) {
        var $this = jQuery(this);
        if (e.keyCode == 13 && $this.val().trim() != '') {
            e.preventDefault();
            var key_words = $this.val().split(',');
            if (key_words.length) {
                $.each(key_words, function (i, val) {
                    val = val.trim();

                    var span = '<span>' + val + '<a href="#">x</a></span>';
                    $this.before(span).val('');
                    makeValueFromSpan();
                })
            }
        }
    })
    jQuery(document).on('click', '.widefat-container span a', function (e) {
        e.preventDefault();
        jQuery(this).parent('span').remove();
        makeValueFromSpan();
    })

    function makeValueFromSpan() {
        counter = jQuery('.widefat-container span').length;
        if (counter > 0) {
            var value = '';
            jQuery('.widefat-container span').each(function (i, val) {
                text = jQuery(this).text().substring(0, jQuery(this).text().length - 1);

                value += text;
                value += (i != counter - 1) ? ',' : '';
            });
            jQuery('.widefat-container input[type="hidden"]').val(value);
        } else jQuery('.widefat-container input[type="hidden"]').val('');
    }


    jQuery('#slider').on('paste input', function () {
        val = jQuery(this).val();
        val = val.replace(/\'/g, '\"');
        jQuery(this).val(val);
    })

    jQuery('#meta_title').on('paste input', function () {
        val = jQuery(this).val();


        var classname = 'has-error';
        if (val.length >= 10 && val.length < 35)
            classname = 'has-warning';
        else if (val.length >= 35 && val.length < 70)
            classname = 'has-success';

        jQuery('#meta-title-strlen').text(val.length)[0].className = classname;
    });


    jQuery('#meta_description').on('paste input', function () {
        val = jQuery(this).val();


        var classname = 'has-error';
        if (val.length >= 50 && val.length < 69)
            classname = 'has-warning';
        else if (val.length >= 70 && val.length < 320)
            classname = 'has-success';

        jQuery('#meta-description-strlen').text(val.length)[0].className = classname;
    });
</script>