<?php
/**
 * Template Name: Main Content Page
 */
?>

<?php

/**
 *
 *  Return markup for carousel text
 */
function get_carousel_text($text_content)
{
    $text = "";
    if ($text_content != "" )
    {
        $template_dir_uri = get_template_directory_uri();
        $title = get_custom_sub_field_text('sub_page_title');
        $text = <<< _TEXT
        <div class="text-area">
            <span class="text mCustomScrollbar">
                <div>
                    $text_content
                </div>
            </span>
            <div class="text-controls">
                <table>
                    <tr>
                        <td>
                            <img class="sub-page-show-carousel" src="$template_dir_uri/Assets/to_carousel.png" alt=""/>
                        </td>
                        <td>
                            <img src="$template_dir_uri/Assets/prev_disabled.png" class="carousel-left-arrow" alt=""/>
                        </td>
                        <td class="sub-page-title">$title</td>
                        <td>
                            <img src="$template_dir_uri/Assets/next_disabled.png" class="carousel-right-arrow" alt=""/>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
_TEXT;
    }
    return($text);
}

/*
 *
 *  Return markup for carousel image items
 */
function get_carousel_image_items($images)
{
    if ($images == NULL)
    {
        return("");
    }
    $item_class = "item active";
    $carousel_items_markup = "";
    $template_dir_uri = get_template_directory_uri();
    foreach($images as $image)
    {
        $url = $image['url'];
        $img_item = "<div class=\"sub-page-carousel-cover lazy\" data-original=\"$url\" style=\"background-image:url('$template_dir_uri/Assets/tiny.png');\"></div>";    
        $carousel_item = "<div class=\"" . $item_class . "\">" . $img_item . "</div>" . PHP_EOL;
        $carousel_items_markup .= $carousel_item;
        $item_class = "item";
    }
    return($carousel_items_markup);
}

/*
 *
 *  Return markup for carousel
 */
function get_carousel($carousel_id, $images, $carousel_text)
{
    if (!is_array($images) || count($images) < 1)
        return("");
    $template_dir_uri = get_template_directory_uri();
    $to_text_markup = $to_carousel_markup = $text_markup = "";
    $text_markup = get_carousel_text($carousel_text);
    $title = get_custom_sub_field_text('sub_page_title');
    $title_cell = $to_text_cell = $prev_image_cell = $next_image_cell = "";
    if ($title != "" && count($images) >= 1)
        $title_cell = '<td class="sub-page-title">' . $title .'</td>';
    if ($carousel_text != "")
        $to_text_cell = '<td><img class="sub-page-show-text" src="' . $template_dir_uri . '/Assets/to_text.png" alt=""/></td>';
    if (count($images) > 1)
    {
        $prev_image_cell = '<td><img src="' . $template_dir_uri . '/Assets/prev.png" class="carousel-left-arrow" alt=""/></td>';
        $next_image_cell = '<td><img src="' . $template_dir_uri . '/Assets/next.png" class="carousel-right-arrow" alt=""/></td>';
    }
    $to_text_markup = <<< _TO_TEXT_MARKUP
        <a class="anchor-next-slide" href="#$carousel_id" data-slide="next"></a>
        <a class="anchor-prev-slide" href="#$carousel_id" data-slide="prev"></a>
        <table>
            <tr>
                $to_text_cell
                $prev_image_cell
                $title_cell
                $next_image_cell
            </tr>
        </table>
_TO_TEXT_MARKUP;
    $carousel_items_markup = get_carousel_image_items($images);
    
    $carousel_markup = <<< _CAROUSEL_MARKUP
    <div id="$carousel_id" style="visibility:hidden;" class="carousel slide" data-ride="carousel" data-interval="false">
        $text_markup
        $to_carousel_markup
        <div class="carousel-inner">
            $carousel_items_markup
        </div> <!-- carousel-inner -->
        <div class="sub-page-carousel-frame"></div>
        <div class="carousel-controls">
            $to_text_markup
        </div>
    </div>
_CAROUSEL_MARKUP;
    return($carousel_markup);
}

/*
 *
 *  output (echo) markup for sub page
 */
function echo_sub_page_markup($carousel_markup, $background_url, $border_markup)
{
    $template_dir_uri = get_template_directory_uri();
    $markup = <<< _MARKUP
    <div class="row sub-page-cover">
        <div  style="position:relative;">
            <div class="fullscreen-cover lazy_backgrounds" data-original="$background_url" style="background-image:url('$template_dir_uri/Assets/tiny.png');">
            </div>
            $border_markup
            $carousel_markup
        </div>
    </div>
_MARKUP;
    echo $markup;
}
/*
 *
 *  output (echo) markup for sub page
 */
function echo_sub_pages()
{
$carousel_id = 1;
$border_markup = "<div class=\"bg-border\"> </div>";
while (have_rows('subPageRepeater') ) : the_row();
    echo_sub_page_markup(
            get_carousel("carousel_".$carousel_id, get_sub_field('carousel'), get_custom_sub_field_text('sub_page_text')),
            get_sub_field('background_image')['url'],
            $border_markup
    );
    $carousel_id++;
endwhile;
}
?>

<?php get_header(); ?>
    <?php echo_sub_page_markup("", get_field('top_background')['url'], ""); ?>
    <?php echo_sub_pages(); ?>
<?php get_footer(); ?>