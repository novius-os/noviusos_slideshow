/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

CREATE TABLE IF NOT EXISTS `nos_slideshow` (
  `slideshow_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `slideshow_title` varchar(255) NOT NULL,
  `slideshow_context` varchar(25) NOT NULL,
  `slideshow_created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `slideshow_updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`slideshow_id`),
  KEY `slideshow_context` (`slideshow_context`),
  KEY `slideshow_created_at` (`slideshow_created_at`),
  KEY `slideshow_updated_at` (`slideshow_updated_at`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nos_slideshow_image` (
  `slidimg_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `slidimg_slideshow_id` varchar(255) NOT NULL,
  `slidimg_position` int(10) NOT NULL,
  `slidimg_title` varchar(255) DEFAULT NULL,
  `slidimg_description` text,
  `slidimg_link_to_page_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`slidimg_id`),
  KEY `slidimg_slideshow_id` (`slidimg_slideshow_id`,`slidimg_position`)
) DEFAULT CHARSET=utf8;