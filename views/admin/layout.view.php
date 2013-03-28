<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

echo '<table class="img_enclosure">';
foreach ($fieldset->field() as $field) {
    $field->is_expert() && $field->set_type('hidden')->set_template('{field}');
}
echo $fieldset->build_hidden_fields();
foreach ($fieldset->field() as $field) {
    echo $field->build();
}
echo '</table>';