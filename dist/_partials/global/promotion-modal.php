<?php

use WTM\Model\Image;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$image_id = get_field('image_promotion','option'); 

if ( empty($image_id) ) return;


?>

<div class="modal fade" id="modal-promotion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <figure class="promotion-figure">
                    <?php
                    echo Image::get_attachment_image($image_id, 'large', [
                        'class' => 'img-fluid',
                        'preload' => false
                    ]);
                    ?>
                </figure>

            </div>
        </div>
    </div>
</div>