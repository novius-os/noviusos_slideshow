ALTER TABLE `nos_slideshow`
  ADD `slideshow_created_by_id` INT UNSIGNED NULL AFTER `slideshow_updated_at` ,
  ADD `slideshow_updated_by_id` INT UNSIGNED NULL AFTER `slideshow_created_by_id`;
