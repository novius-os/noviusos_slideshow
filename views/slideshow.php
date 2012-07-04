<?php
\Nos\Nos::main_controller()->addJavascript('static/apps/slideshow/js/jquery.flexslider.js');
\Nos\Nos::main_controller()->addCss('static/apps/slideshow/css/flexslider.css');
if ($slides_preview)
{
    \Nos\Nos::main_controller()->addJavascript('static/apps/slideshow/js/jquery.novius_flexpreview.js');
    \Nos\Nos::main_controller()->addCss('static/apps/slideshow/css/flexpreview.css');
}
 ?>
<script type="text/javascript" charset="utf-8">
<?php
    \Config::load('slideshow::flexslider', 'flexslider');
    $config = \Config::get('flexslider');
?>
    $(window).load(function()
    {
        $('.flexslider').flexslider(<?php echo json_encode($config); ?>)<?php if ($slides_preview) echo '.novius_flexpreview()';?>;
    });
</script>
<div class="flex-nav-container <?=$class?>">
<div class="flexslider">
    <ul class="slides">
<?php

    foreach ( $slideshow->images as $image )
    {
        if ( empty($image->medias->image) )
        {
            continue;
        }

        echo '<li ';
        if ($slides_preview) echo 'data-thumb="', $image->medias->image->get_public_path_resized(300, 100), '"';
        echo '>';
        if ($show_link && $image->slidimg_link_to_page_id) echo '<a href="'. $image->page->get_href().'">';
        echo '<img style="margin: 0 auto;" src="', $image->medias->image->get_public_path_resized($width,$height), '" alt="', htmlspecialchars($image->slidimg_title), '" title="', htmlspecialchars($image->slidimg_title), '" />';
        if ($show_link && $image->slidimg_link_to_page_id) echo '</a>';
        if ( !empty($image->slidimg_title) || !empty($image->slidimg_description) )
        {
            echo '<p class="flex-caption">';
            if ( !empty($image->slidimg_title) )
            {
                echo '<strong>', htmlspecialchars($image->slidimg_title), '</strong><br />';
            }
            if ( !empty($image->slidimg_description) )
            {
                echo htmlspecialchars($image->slidimg_description);
            }
            echo '</p>';
        }
        echo '</li>';
    }

?>
  </ul>
</div>
</div>
