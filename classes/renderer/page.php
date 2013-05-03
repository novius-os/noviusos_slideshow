<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

namespace Nos\Slideshow;

class Renderer_Page extends \Nos\Page\Renderer_Selector
{
    public function build()
    {
        $template = $this->template ?: $this->fieldset()->form()->get_config('field_template');
        $this->template = '{field}';
        $parent = parent::build();
        $this->template = $template;
        return $this->template(\View::forge('noviusos_slideshow::admin/renderer/page_selector', array(
            'page_renderer' => $parent,
            'value' => (string) (int) $this->value,
        ), false));
    }
}
