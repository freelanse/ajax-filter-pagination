<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<div class="selected"><span>Все кейсы</span></div>
<?php
$catalog_nav_items = get_terms([
    'taxonomy' => 'product-categories',
    'parent'  => 0,
]);
?>
<?php foreach ($catalog_nav_items as $item) : ?>
    <div data-category="<?php echo $item->slug; ?>"><span><?php echo $item->name; ?></span></div>
<?php endforeach; ?>
</div>
<div class="cases wrapper">
    <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post(); ?>
            <?php echo get_template_part('product-content'); ?>
        <?php endwhile; ?>
    <?php endif; ?>
</div>
<button id="loadMore">Загрузить еще</button>
