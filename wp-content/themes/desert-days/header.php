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
<script>
    function langDropDown()
    {
        jQuery('.dropdown-toggle').dropdown();
    }
</script>
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
    $carouselIndex = 1;
    echo '@media screen and (max-width:767px) {';
    while (have_rows('subPageRepeater') ) : the_row();
        echo PHP_EOL.'#carousel_'.$carouselIndex.'{left: 0; top: 0;}';
        $carouselIndex++;
    endwhile;
    echo '}';
    echo PHP_EOL.'</style>'.PHP_EOL;
}

function the_menu_items()
{
    $google_maps_url = 'javaScript:popup(\'http://maps.google.com/maps?output=embed&q=Tzukim,+Israel&hl=en&ll=30.708781,35.101318&spn=2.772088,3.460693&sll=37.0625,-95.677068&sspn=38.554089,89.296875&oq=tzuk&hnear=Tzukim,+Israel&t=m&z=8\')';
    echo '<li><div class="dropdown">';
    echo '  <a style="display:block;" href="' . $google_maps_url . '">';
    echo '      <img style="display:block;" src="'.get_template_directory_uri().'/Assets/location.png" alt="">';
    echo '  </a>';
    echo '  <a style="display:block;" id="language-label" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">';
    echo '      <img style="display:block;" src="'.get_template_directory_uri().'/Assets/language.png" alt="">';
    echo '  </a>';
    echo '  <ul class="dropdown-menu" role="menu" aria-labelledby="language-label">';
    $lang_code = language_code();
    $_languages = languages();
    foreach( array_keys($_languages) as $lang_key)
    {
        if ($lang_key != $lang_code)
        {
            echo '      <li><a href="' . add_language_to_url(current_url(), $lang_key) . '">' . $_languages[$lang_key] .'</a></li>';
        }
    }
    echo '  </ul>';
    echo '</div></li>'.PHP_EOL;
    $pages = get_pages( array( 'sort_column' => 'menu_order') );
    $counter = 0;
    foreach($pages as $page){
        $active = ($page->ID === get_the_ID() ? 'active' : '');
        $parity = $counter++ % 2 == 0 ? 'even-item' : 'odd-item';
        $label = get_field('page_title_'.language_code(), $page->ID);
        $hide_on_attr = ' hide-page-on="' . get_field('hide_page_on', $page->ID) . '" ';
        if (IsNullOrEmptyString($label))
            $label = $page->post_title;
        echo '<li ' . $hide_on_attr . (!empty($active) ? 'class="' . $active . '" ' : '') . '><a class="' . $parity . '" href="'.add_language_to_url(get_page_link($page->ID)).'">'.$label.'</a></li>'.PHP_EOL;
    }
    
    echo '<li><a style="padding:0 !important;" href="'.home_url().'"><img src="'.get_template_directory_uri().'/Assets/logo.png" alt=""></a></li>'.PHP_EOL;
}
?>

 <body <?php body_class('desert-days-text'); ?>>
    <a class="my-navbar-toggle">
        <img src="<?php echo get_template_directory_uri(); ?>/Assets/show_bar.png" alt="">
    </a>
    <div class="navbar navbar-default navbar-fixed-top navbar-transp" role="navigation">
        <div class="navbar-desert-days" style="border:0 !important;">
            <ul class="nav navbar-nav">
                <?php the_menu_items() ?>
            </ul>
        </div>
    </div>
    <div id="top" class="container" style="width:auto;">
