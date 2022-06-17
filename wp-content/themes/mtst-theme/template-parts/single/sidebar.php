<?php if ( is_active_sidebar( 'sidebar-single' ) ) : ?>
    <div id="your-sidebar" class="widget-area" role="complementary">
    <?php dynamic_sidebar( 'sidebar-single' ); ?>
    </div>
<?php endif; ?>