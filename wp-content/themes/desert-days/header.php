<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Desert Days
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="shortcut icon" type="image/ico" href="<?php echo get_template_directory_uri().'/favicon.png'; ?>" />
<?php wp_head(); ?>
<?php carousel_positions(); ?>
</head>

<?php


/*
 *
 *  functions for menu markup generation
 */

function carousel_positions()
{
    echo '<style>'.PHP_EOL;
    $carouselIndex = 1;
    while (have_rows('subPageRepeater') ) : the_row();
        echo PHP_EOL.'#carousel_'.$carouselIndex.'{';
        echo 'left: '.get_sub_field('carousel_left_position').'%; top: '.get_sub_field('carousel_top_position').'%;'.'}';
        $carouselIndex++;
    endwhile;
    echo PHP_EOL.'</style>'.PHP_EOL;
}

function the_menu_items()
{
    $pages = get_pages();
    foreach($pages as $page){
        $active = ($page->ID === get_the_ID() ? 'class="active"' : '');
        echo '<li '.$active.'><a href="'.get_page_link($page->ID).'">'.$page->post_title.'</a></li>'.PHP_EOL;
    }
    echo '<li><a style="padding:0;" href="'.home_url().'"><img src="'.get_template_directory_uri().'/Assets/logo.png" alt=""></a></li>'.PHP_EOL;
}
?>

 <body <?php body_class('desert_days_font'); ?>>
    <a class="showNavbar navbar-toggle">
        <img src="<?php echo get_template_directory_uri(); ?>/Assets/show_bar.png" alt="">
    </a>
    <div class="navbar navbar-default navbar-fixed-top navbar-transp" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <a type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <img src="<?php echo get_template_directory_uri(); ?>/Assets/show_bar.png" alt="">
                </a>
            </div>
            <div class="navbar-collapse collapse" style="border:0 !important;">
                <ul class="nav navbar-nav">
                    <?php the_menu_items() ?>
                </ul>
            </div><!--/.nav-collapse -->
	</div>
    </div>
    <div id="top" class="container" style="width:auto;">
