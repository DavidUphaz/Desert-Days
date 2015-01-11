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
    $pages = get_pages( array( 'sort_column' => 'menu_order') );
    foreach($pages as $page){
        $active = ($page->ID === get_the_ID() ? 'class="active"' : '');
        $label = get_field('page_title_'.language_code(), $page->ID);
        if (IsNullOrEmptyString($label))
            $label = $page->post_title;
        echo '<li '.$active.'><a href="'.add_language_to_url(get_page_link($page->ID)).'">'.$label.'</a></li>'.PHP_EOL;
    }
    
    $google_maps_url = 'javaScript:popup("http://maps.google.com/maps?output=embed&q=Tzukim,+Israel&hl=en&ll=30.708781,35.101318&spn=2.772088,3.460693&sll=37.0625,-95.677068&sspn=38.554089,89.296875&oq=tzuk&hnear=Tzukim,+Israel&t=m&z=8")';
    echo '<li><a style="padding:0;" href="'.home_url().'"><img src="'.get_template_directory_uri().'/Assets/logo.png" alt=""></a></li>'.PHP_EOL;
    echo '<li><a style="padding:0;" href="#"><img src="'.get_template_directory_uri().'/Assets/language.png" alt=""></a></li>'.PHP_EOL;
    echo '<li><a style="padding:0;" href=\'' . $google_maps_url .'\'><img src="'.get_template_directory_uri().'/Assets/google_maps.png" alt=""></a></li>'.PHP_EOL;
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
