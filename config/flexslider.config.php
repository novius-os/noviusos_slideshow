<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

\Log::deprecated(
    'The config file noviusos_slideshow::flexslider is deprecated, '.
    'please use noviusos_slideshow::formats/flexslider instead.',
    'Chiba.2'
);

$flexslider_config = \Config::loadConfiguration('noviusos_slideshow::formats/flexslider');

return $flexslider_config;
