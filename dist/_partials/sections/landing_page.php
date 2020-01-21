<?php

use WTM\Model\Validate;
use WTM\Model\Image;
if ( ! defined( 'ABSPATH' ) ) exit; 



?>

<div class="row m-0">
    <div class="col-xxl-6 col-12 d-flex justify-content-center align-items-center flex-column content_box">
        <h2 class=""><?php echo $section['text'];?></h2>
        <div class="container code">
  
            <div class="demo-flex-spacer"></div>

            <form class="webflow-style-input" onsubmit="getThreeImages()" id="code_form">
                <input class="" type="text" id="code_input" name="code_input" placeholder="Wprowadź kod" ></input>
                <button type="submit"><i class="icon ion-android-arrow-forward"></i></button>
            </form>

        </div>

    </div>
    <div class="col-xxl-6 col-12 pt-5 slide-wrap">
        <div class="background-slide" style="background-image: url(<?php echo $section['image']['sizes']['large']; ?>);">
        
        </div>
    </div>
</div>

  <?php $input = $_GET['code_input'];?>

  
  <?php $download = $_GET['download'];?>
  
<?php //https://fotobudkaraspberry.pl/?code_input=62D0EF ?>

<?php //62D0EF?>
<div class="images_popup <?php if(!isset($input)){ echo 'd-none';}?>" id="<?php if(!empty($input)){echo $input;}?>">
  <div class="wrap_container">
    <div class="close"><span></span><span></span></div>
    <div class="images_list" id="three_images_container"></div>
    <div class="action" id="action_buttons">
      
    </div>
    <?php if(Validate::check_variable($input)){ ?>
      <script>
            var set_code = "<?php echo $_GET['code_input']; ?>"
            console.log(set_code)
            var url = "https://fotobudkaraspberry.pl/getPhoto2.php?series_code=" + set_code;
            var settings = {
                "async": true,
                "crossDomain": true,
                "url": url,
                "method": "GET",
            };

            $.ajax(settings).done(function (response) {
                console.log(response);

                if (response.length == 0) {
                    $('#three_images_container').html('<span class="text-danger w-100 text-center mt-5">Przykro nam, taki kod nie istnieje.</span>')
                } else {
                    //document.getElementById("console_panel").innerHTML = JSON.stringify(response, undefined, 2);
                    console.log(response.length)
                    $('#three_images_container').html('');
                    for (var i = 0; i < response.length; i++) {
                        $('#three_images_container').append('<div class="wrap_image"><div class="frame_button"><i class="far fa-edit"></i></div><a href="' + response[i].url + '" class="image-container ari-fancybox" title="image"  rel="commune" data-fancybox-group="fb_gallery_0_0" data-fancybox="fb_gallery_0_0" style="background-image: url(' + response[i].url + ')"></a></div>')

                    }
                $('#action_buttons').append('<a target="_blank" class="g-button" href="<?php echo 'https://fotobudkaraspberry.pl/downloadImages.php?series_code='.$_GET['code_input']; ?>">Pobierz</a>');
                }
            });
      </script>
<?php } ?>
  </div>
  <div class="frame_container d-none">
      <div class="close_edit"><span></span><span></span></div>
            <div class="image_wrap container" data-filter="default">
             <canvas id="canvas1" height="500px" width="720px"></canvas>
            </div>
            <div class="range-slider d-none">
                <input class="range-slider__range" type="range" value="30" min="0" max="100">
                <span class="range-slider__value">0</span>
            </div>

            <div class="filter_options">
                <div class="option_box" onclick="FiltersGreyscale()">
                    <div class="trigger" id="greyscale">Skala szarości</div>
                </div>
                 <div class="option_box" onclick="FiltersBlackAndWhite(30)">
                    <div class="trigger" id="blackandwhite">Czerń i biel</div>
                </div>
                 <div class="option_box" onclick="FiltersSepia(30)">
                    <div class="trigger" id="sepia">Sepia</div>
                </div>
                 <div class="option_box" onclick="FiltersEmboss()">
                    <div class="trigger" id="emboss">Emboss</div>
                </div>
                <div class="option_box" onclick="FiltersHalftone()">
                    <div class="trigger" id="halftone">Halftone</div>
                </div>
                
            </div>
  </div>
</div>