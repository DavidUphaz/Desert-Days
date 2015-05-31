<?php
/**
 * Template Name: Availability
 */
?>

<?php

function echo_rates()
{
    echo '<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 rtl-grid">';
    echo get_custom_field_text('rates');
    echo '</div>';
}

function echo_calendars()
{
    echo '<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 rtl-grid">';
    echo    '<ul class="nav nav-tabs">';
    $counter = 1;
    $active_tab = 'class="active"';
    $repeater_name = resolve_custom_field_name('calendars');
    while (have_rows($repeater_name) ) : the_row();
        echo    '<li ' . ($counter == 1 ? $active_tab : '') . '><a data-toggle="tab" href="#' . 't_' . $counter . '">' . get_sub_field('calendar_label') . '</a></li>';
        $counter++;
    endwhile;
    echo    '</ul>';
    $counter = 1;
    $active_tab = ' in active';
    echo    '<div class="tab-content" style="padding-top:20px;">';
    while (have_rows($repeater_name) ) : the_row();
        echo    '<div id="t_' . $counter .'" class="tab-pane fade '. ($counter == 1 ? $active_tab : '') .'">';
        echo get_sub_field('calendar');
        echo    '</div>';
        $counter++;
    endwhile;
    echo     '</div>';
    echo '</div>';   
}

function echo_contact()
{
    echo '<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 rtl-grid">';
    echo get_custom_field_text('contact');
    echo '</div>';
}
function echo_body()
{
    echo '<div class="row lazy_backgrounds" data-original="' . get_field('background')['url'] . '" style="min-height:100vh; padding-top:200px;padding-right:200px;padding-left:100px;background-position: center;background-size: cover;">';
    echo_rates();  
    echo_calendars();
    echo_contact();
    echo '</div>';
}
?>

<?php get_header(); ?>
<?php echo_body(); ?>
<?php get_footer(); ?>