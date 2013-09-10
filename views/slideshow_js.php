<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

\Log::deprecated('The view noviusos_slideshow::slideshow_js is deprecated, '.
    'please use noviusos_slideshow::flexslider/javascript instead.', 'Chiba.2');

echo \View::forge('noviusos_slideshow::flexslider/javascript', array(
    'flexslider_config' => $config,
    'slides_preview' => $slides_preview,
));
