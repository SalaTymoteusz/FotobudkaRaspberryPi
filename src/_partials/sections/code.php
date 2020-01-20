<?php

use WTM\Model\Validate;
use WTM\Model\Image;
if ( ! defined( 'ABSPATH' ) ) exit; 

?>
<div class="container code">
  
  <div class="demo-flex-spacer"></div>

  <form class="webflow-style-input" onsubmit="getThreeImages()" id="code_form">
    <input class="" type="text" id="code_input" name="code_input" placeholder="Wprowadź kod"></input>
    <button type="submit"><i class="icon ion-android-arrow-forward"></i></button>
  </form>

</div>