# Slideshows

Novius OS is an Open Source PHP Content Management System designed as an applications platform, hence the ‘OS’ suffix. Check out [Novius OS’ readme](http://github.com/novius-os/novius-os#readme) for more info.

The ‘Slideshows’ application for Novius OS allows you to create and publish slideshows on your website.

It ships with Flexslider 2.3.

## Requirements

* ‘Slideshows’ runs on Novius OS Elche.

## Installation

* [How to install a Novius OS application](http://community.novius-os.org/how-to-install-a-nos-app.html)

## Configuration

### Application

The application configuration is located in [config/slideshow.config.php](config/slideshow.config.php)

If you want to declare different kind of sliders, you will have to edit this config.

If you do so, a select list will show when including the enhancer in a page to select the appropriate style.

It's built this way :

```php
array(
"type-of-slider" => array(
  "view" => , // The view used
  "label" => , // The displayed label in the enhancer
   "config" =>, // (array) Slideshow config
   )
)
```

#### Slideshow config
```php
array(
"slides_with_link" =>, //(bool) Display or not link anchors in the slideshow
"slides_preview" =>, //(bool) Use or not Flexslider Preview
"width" =>, //(int) image width
"height" =>,// (int) image height
"class" =>, //(string) Class of the flexslider container
"js" =>, //(array) List of javascript files to embed
"css" =>, //(array) List of css files to embed
)
```
### Formats

Format configuration are options given to Flexslider, you can find in [config/formats/flexslider.config.php](config/formats/flexslider.config.php) all the available options and their default values.

If you wan't to change animations, texts or things like that, you just have to override this configuration


## Support

* You’ll find help in [the forum](http://forums.novius-os.org/en).

## Demo & further information

* Try ‘Slideshows’ at [demo.novius-os.org](http://demo.novius-os.org/admin).
* [Watch the screencast](http://www.youtube.com/watch?v=mptrVkmsw5g&list=PL49B38887F978ED5E) on YouTube.

## License

‘Slideshows’ is licensed under [GNU Affero General Public License v3](http://www.gnu.org/licenses/agpl-3.0.html) or (at your option) any later version.