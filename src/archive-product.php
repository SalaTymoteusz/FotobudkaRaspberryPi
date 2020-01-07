<?php 

use WTM\Model\Template;
use WTM\Model\Validate;
get_header(); 

?>
<?php 
$archive = new stdClass();
$archive->post_title = get_field('title_archive_page','option') ?? get_the_archive_title();
$archive->post_excerpt = get_field('excerpt_archive_page','option');
$archive->post_thumbnail = get_field('thumbnail_archive_page','option');
$archive->post_content = get_field('description_archive_page','option');

Template::get_template('_partials/page/page-top', [
    'post' => $archive
]);
?>

<?php if(Validate::check_variable($archive->post_content)): ?>
<section id="section-archive-content" class="section section-desc">
    <div class="container position-relative">
        <?php
        Template::get_template('_partials/section/section-desc',[
            'title' => $archive->post_title,
            'show_title' => 0,
            'description' => $archive->post_content,
            'thumb_description' => false,
            'more_description' => false,
        ]);
        ?>
    </div>
</section>
<?php endif; ?>

<section id="section-archive-products" class="section">
    <div class="container position-relative">
        <?php if(have_posts()): ?>
        <ul id="products" class="row no-gutters p-0">
            <?php while(have_posts()): ?>
            <?php
            the_post();
            $product = get_post();

            Template::get_template('_partials/product/product-list-item', [
                'title' => $product->post_title,
                'link' => get_the_permalink($product),
                'thumbnail' => get_post_thumbnail_id($product->ID) ?? false,
                'new_window' => true,
            ]); 
            ?>
            <?php endwhile; ?>
        </ul>

        <?php else: ?>
        <div class="alert alert-info">
            <p class="mb-0"><?php _e('Brak produktów do wyświetlenia','fotobudka'); ?></p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>