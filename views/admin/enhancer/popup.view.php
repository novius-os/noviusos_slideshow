<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

Nos\I18n::current_dictionary('noviusos_slideshow::common');

?>
<p style="margin-bottom: 0.5em;">
    <label><?= __('Select a slideshow:') ?>&nbsp;
<?php
if ( !empty($params['slideshows']) ) {
    echo '<select name="slideshow_id">';
    foreach ($params['slideshows'] as $slideshow) {
        echo '<option value="', $slideshow->slideshow_id, '" ',
            ( \Fuel\Core\Input::get('slideshow_id', 0) === $slideshow->slideshow_id ? 'selected="selected"' : '' ), '>',
            $slideshow->slideshow_title, '</option>';
    }
    echo '</select>';
}
?>
    </label>
</p>
<p style="margin-bottom: 0.5em;">
    <label><?= __('Format:') ?>&nbsp;
<?php
if ( !empty($params['sizes']) ) {
    if (count($params['sizes']) === 1) {
        echo '<input type="hidden" name="size" value="'.current(array_keys($params['sizes'])).'" />';
    } else {
        echo '<select name="size">';
        foreach ($params['sizes'] as $key => $s) {
            echo '<option value="', $key, '" ',
                ( \Fuel\Core\Input::get('size', 0) === $key ? 'selected="selected"' : '' ), '>',
                \Arr::get($s, 'label', $key), '</option>';
        }
        echo '</select>';
    }
}
?></label></p>
