=== Decoupled JSON Content ===
Contributors: dingo_bastard, mustra
Donate link: https://infinum.co/
Tags: json, decoupled, json, content, content, json content, react, angular, speed, fast json
Tested up to: 4.9.4
Stable tag: 1.0.0
Requires at least: 4.4
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A faster alternative to the default REST API provided by WordPress for the usage with decoupled (headless) WordPress approach

== Description ==

Since the WordPress version 4.4 WordPress added REST API to the core. With the version 4.7 came major improvements that basically allowed WordPress to be used as a unified Model/Controller part of the MVC application. The problem with the default approach to the decoupled WordPress (or headless as some call it) is that accessing the default REST endpoints in WordPress is really slow. The situation gets worse the more plugins you add to your WordPress installation. Hitting the particular endpoint means loading the entire WordPress core. And that is not acceptable if you want to have fast and responsive web applications.

What our approach is using is twofold: We only load the minimum core capabilities, and manually call the necessary files for the necessary task we want to execute. After that, we also store the pulled data in a transient, which is used later on when fetching the data. Transients are great because once you set them up, they won't be called from the database directly, but from the server's memory, making the content fetching even faster.

All the transients can be manually deleted, and we added filters to which you can hook with a third party plugin if you want to add or change something.

We also added a useful custom menu route that can be used to tell the view part of the application what is the menu structure in the WordPress (menu routes are still not part of the WordPress REST API at the time of this plugins release).

== Installation ==

1. Place `decoupled-json-content` folder in the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Recreate all the endpoints in transient cache in Api Settings > Rebuild Cache

# How to use

After installing and activating a plugin you will have API Settings menu. This has two submenus - 'APIs List' which holds a list of available endpoints - the endpoints are general, e.g. for pages it can look something like `wp-content/plugins/decoupled-json-content/page/rest-routes/page.php?slug=&type=`. In addition to that, every page and post will have additional column in the post/page list which denote if the post/page has an existing cached JSON. Other submenu is called 'Rebuild Cache' which will rebuild all the caches for the pages/posts and custom post types in the database.

== Possible issues ==

The transients are updated on page/post save using `save_post` hook. It could happen (although we didn't have this issue so far), that other plugins that add some content or data on `save_post` hook whouldn't have this data exposed in the JSON content. If this happens, just rebuld the cache after post save in the 'Rebuild Cache' menu.

If you had content when installing this plugin you won't have any JSON for this post. In this case you also need to go to the 'Rebuild Cache' menu and resave it.

== Hooks ==

We included several hooks that you can use to extend the functionality of the plugin:

Menu:
* `djc_remove_menu_prefix_slash` - Remove prefix slash in menu items, accepts boolean. Default is true.
* `djc_set_menu_posts_slug` - Change the page slug for your blog posts. Default is 'blog'
* `djc_set_general_endpoint` - Add new items the settings page general. The return value has to be array with keys: title, url and note(optional).

Items:
* `djc_set_items_featured_image` - Override the items featured image.
* `djc_set_items_tags` - Override the items tags.
* `djc_set_items_category` - Override the items category.
* `djc_set_items_post_format` - Override the items format.
* `djc_set_items_page_template` - Override the items template.
* `djc_set_items_custom_fields` - Override the items custom fields.
* `djc_set_items_append` - Append new data to items. The return value has to be array with key and value.
* `djc_set_items_allowed_post_types` - Set post types you want to use of items. Default is 'post', 'page'. The return value has to be array.
* `djc_set_items_endpoint` - Add new items the settings page items. The return value has to be array with keys: title, url and note(optional).

List:
* `djs_set_lists_endpoint_query` - set Wp_Query arguments you want to use in list. The return value has to be array with default WP Query arguments. Action-filter value from `djs_set_lists_endpoint` hook must be prepended to the `djs_set_lists_endpoint_query` hook. Check details in the example.
* `djs_set_lists_endpoint` - Add new items the settings page list. The return value has to be array with keys: title, action-filter.

djs_set_lists_endpoint_query and djs_set_lists_endpoint go in combination.
Example:
```php
function new_enpodint_function($default) {
  $default[] = 
    array(
    'title'         => 'New List',
    'action-filter' => 'new_action_filter',
  );
  return $default;
}
add_filter( 'djs_set_lists_endpoint', 'new_enpodint_function' );

function new_args_function() {
  return array(
    'post_type'      => 'page',
    'posts_per_page' => 1,
  );
}
add_filter( 'djs_set_lists_endpoint_query_`new_action_filter`', 'new_args_function' );

```

== Changelog ==

= 1.0 =
* Initial release

== Credits ==

JSON post parser is maintained and sponsored by
[Infinum](https://www.infinum.co).

<img src="https://infinum.co/infinum.png" width="264">

== License ==

Decoupled JSON Content is Copyright © 2017 Infinum. It is free software, and may be redistributed under the terms specified in the LICENSE file.

== Upgrade Notice ==

Fixes rare fatal error when using debug log

== Donate ==

We don't need your donations. Give it to charity instead. And check out our work at [Infinum](https://www.infinum.co).

== Screenshots ==

1. Settings: List of all available endpoints
2. Settings: Action to rebuild all endpoints to transient
3. Endpoint: Page json endpoint
4. Endpoint: Menu json endpoint
5. Listing: Column added to see if endpoint is cached and available
