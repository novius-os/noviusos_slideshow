<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */
?>
<div style="overflow: hidden">
    <img style="float: left; width: 64px; height: 64px;" src="<?= $src ?>" />
    <h1 style="margin-left: 80px;"><?= strtr(__('Slideshow ‘{{title}}’'), array('{{title}}' => $title)) ?></h1>
    <p style="margin-left: 80px;"><?= strtr(__('(format: {{format}})'), array('{{format}}' => $size)) ?></p>
</div>
