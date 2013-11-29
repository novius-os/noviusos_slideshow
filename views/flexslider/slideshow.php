<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

try {
    $old_view = (string) \View::forge('noviusos_slideshow::slideshow', array(
        'slideshow' => $slideshow,
        'size_key'  => $format === 'flexslider-small' ? 'petit' : 'grand',
        'class'		=> \Arr::get($config, 'class', 'slide-home'),
        'height'	=> \Arr::get($config, 'height', '600'),
        'width'		=> \Arr::get($config, 'width', '800'),
        'show_link' => \Arr::get($config, 'slides_with_link', true),
        'slides_preview' => \Arr::get($config, 'slides_preview', true),
    ));

    if (!empty($old_view)) {
        \Log::deprecated(
            'The view noviusos_slideshow::slideshow is deprecated, '.
            'please use noviusos_slideshow::flexslider/slideshow instead.',
            'Chiba.2'
        );
        echo $old_view;
        return;
    }
} catch (\Fuel\Core\FuelException $e) {
}

$slides_preview = \Arr::get($config, 'slides_preview', true);
$show_link = \Arr::get($config, 'slides_with_link', true);

$libs = array('js.jquery', 'js.flexslider', 'css.flexslider');
if ($slides_preview) {
    $libs = array_merge($libs, array('js.flexpreview', 'css.flexpreview'));
}
foreach ($libs as $lib) {
    $lib_url = \Arr::get($config, $lib, null);
    if (empty($lib_url)) {
        continue;
    }
    if (substr($lib, 0, 2) === 'js') {
        \Nos\Nos::main_controller()->addJavascript($lib_url);
    } else {
        \Nos\Nos::main_controller()->addCss($lib_url);
    }
}
$flexslider_config = \Config::loadConfiguration('noviusos_slideshow::formats/flexslider');
\Nos\Nos::main_controller()->addJavascriptInline(\View::forge('noviusos_slideshow::flexslider/javascript', array(
    'flexslider_config' => $flexslider_config,
    'slides_preview' => $slides_preview,
)));
?>
<div class="noviusos_slideshow noviusos_enhancer flex-nav-container <?= \Arr::get($config, 'class', '') ?>">
<div class="flexslider">
    <ul class="slides">
<?php
foreach ($slideshow->images as $image) {
    if (empty($image->medias->image)) {
        continue;
    }

    echo '<li ';
    if ($slides_preview) {
        echo 'data-thumb="', $image->medias->image->urlResized(300, 100), '"';
    }
    echo '>';

    // Image, with or without anchor
    $img = $image->medias->image->htmlImgResized(\Arr::get($config, 'width', 800),
        \Arr::get($config, 'height', 600),
        array(
            'alt' => $image->slidimg_title,
            'title' => $image->slidimg_title,
            'style' => 'margin: 0 auto;',
        ));
    if ($show_link && !empty($image->slidimg_link_to_page_id)) {
        echo $image->page->htmlAnchor(array('text' => $img));
    } else {
        echo $img;
    }

    // Caption
    if (!empty($image->slidimg_title) || !empty($image->slidimg_description)) {
        echo '<p class="flex-caption">';
        if (!empty($image->slidimg_title)) {
            echo '<strong>', htmlspecialchars($image->slidimg_title), '</strong><br />';
        }
        if (!empty($image->slidimg_description)) {
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
