<?php
/**
 * Template Name: Availability
 */
?>

<?php
function echo_calendars()
{
    $repeater_name = resolve_custom_field_name('calendars');
    $counter = 1;
    $active_tab = ' in active';
    echo        '<div style="float:right;padding-top:10px;margin-bottom:50px;position:relative;">';
    echo        '<div class="tab-content" style="float:right;">';
    while (have_rows($repeater_name) ) : the_row();
        echo        '<div id="t_' . $counter .'" class="tab-pane fade '. ($counter == 1 ? $active_tab : '') .'">';
        echo            get_sub_field('calendar');
        echo        '</div>';
        $counter++;
    endwhile;
        echo    '</div>';
    echo        '<ul class="nav nav-stacked nav-tabs" style="float:right;border: 0;padding:0;">';
    $counter = 1;
    $active_tab = 'class="active"';
    while (have_rows($repeater_name) ) : the_row();
        echo        '<li ' . ($counter == 1 ? $active_tab : '') . '><a data-toggle="tab" href="#' . 't_' . $counter . '">' . get_sub_field('calendar_label') . '</a></li>';
        $counter++;
    endwhile;
    echo        '</ul>';
    echo '<img style="position:absolute; bottom:1px;left:14px;" src="' . get_template_directory_uri() . '/Assets/legend.png" alt="">';
    
    echo    '</div>';
    
}


function echo_body()
{
    $bootstrap_col_class = '"col-xl-12 raatl-grid"';
    //$bootstrap_col_class = '"availability-column rtl-grid"';
    echo '<div class="availability-container-1 lazy_backgrounds" data-original="' . get_field('background')['url'] . '">';
    //echo '<div class="availability-container-2">';
    echo    '<div class='. $bootstrap_col_class . ' >';
    echo '      <div style="display:inline-block;text-align:initial;direction:rtl;">';
    echo        get_custom_field_text('rates_and_contact');
    echo_calendars();
    echo        '</div>';
    echo    '</div>';
    //echo    '</div>';
    echo '</div>';
}
?>

<?php get_header(); ?>
<?php echo_body(); ?>
<?php get_footer(); ?>