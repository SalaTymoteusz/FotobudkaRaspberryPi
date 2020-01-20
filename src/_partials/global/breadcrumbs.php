<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset($breadcrumbs) || empty($breadcrumbs) ) return;

?>

<nav id="breadcrumb" aria-label="breadcrumb" class="container">
    <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">

        <?php foreach($breadcrumbs as $pos => $breadcrumb): ?>
        <li class="breadcrumb-item<?php echo (isset($breadcrumb['active']) && $breadcrumb['active'] == true) ? ' active' : ''; ?>"
            itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"
            <?php echo (isset($breadcrumb['active']) && $breadcrumb['active'] == true) ? ' aria-current="page"' : ''; ?>>

            <?php if(isset($breadcrumb['link']) && !empty($breadcrumb['link'])): ?>
            <a itemprop="item" href="<?php echo $breadcrumb['link'];?>" title="<?php echo $breadcrumb['title']; ?>">
                <?php else: ?>
                <span itemprop="item">
                    <?php endif; ?>

                    <?php echo $breadcrumb['title']; ?>


                    <?php if(isset($breadcrumb['link']) && !empty($breadcrumb['link'])): ?>
            </a>
            <?php else: ?>
            </span>
            <?php endif; ?>

            <meta itemprop="name" content="<?php echo $breadcrumb['title']; ?>">
            <meta itemprop="position" content="<?php echo $pos+1; ?>">
        </li>
        <?php endforeach; ?>
    </ol>
</nav>