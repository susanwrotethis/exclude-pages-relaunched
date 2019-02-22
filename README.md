# Exclude Pages Relaunched

Exclude Pages Relaunched adds a checkbox to exclude the page from menus and page lists.

## Description

The plugin adds a checkbox to the page editor which you can check to exclude pages from page lists and menus. Inspired by the original Exclude Pages plugin by Simon Wheatley but rewritten for extensibility.

**Features**

* Works on multisite as well as single installations
* Provides several action and filter hooks
* Contains language support

## Action Hooks

This plugin has three action hooks:

The **swt_epr_set_transient** hook in the swt_epr_set_transient() function lets you apply the list of IDs of excluded pages to other plugins. For instance, a search or sitemap plugin may have the capability to exclude pages. Instead of setting those values manually, you can use the hook to do it automatically.

The **swt_epr_display_meta_box** hook in the swt_epr_display_meta_box() function lets you add other fields to the Excluded Pages meta box. You can use it to add related fields, such as additional checkboxes to hide the page from search results or a sitemap.

The **swt_epr_update_postmeta** hook in the swt_epr_update_postmeta() function lets you add the ID of the individual page being updated to other functions. Its purpose is similar to **swt_epr_set_transient**, except that it holds the value for only the page currently being updated rather than the IDs for all pages that are being excluded.

## Filter Hooks

This plugin has four filter hooks:

The **swt_epr_excludes_save_post_types** filter in the swt_epr_update_postmeta() function lets you specify if you want to sanitize and save the value for posts or custom post types. By default it won't save the data if the item being edited is not a page.

The **swt_epr_excluded_ids** filter in the swt_epr_get_excluded_ids() function lets you filter the array of excluded IDs. This can be used in special use cases if you need to override the exclude.

The **swt_epr_get_pages_excluded_ids** filter in swt_epr_exclude_from_get_pages() and the **swt_epr_nav_menu_items_excluded_ids** filter in swt_epr_exclude_from_nav_menu_items() have similar purposes, but they are applied right before excluded items are removed from the page list or nav menu.

## Installation

Download the current release of this plugin as a zip file. Make sure the file is named exclude-pages-relaunched.zip.

* In the WordPress admin, go to Plugins > Add New. On multisite, this is under the network admin.
* Click the Upload Plugin button at the top of the page and browse for the zip file.
* Upload the zip file.
* Once the plugin is installed, activate it. On multisite, this can be network activated or activated on individual sites.

## Frequently Asked Questions

**Does it work with Gutenberg?**

Yes. The checkbox will appear in the page controls to the right of the content.

**Will this hide pages on the back end?**

No, only on the front end. It's possible to add an excluded page to a menu, but it will not display on the site.

**Does it hide the page from search results, too?**

No, but you could use the action hooks to extend it to do that.

**Does it work on posts or custom post types?**

Not out of the box, but you can extend it to add it to almost any type.

**Can excluding pages affect page speed?**

Possibly, if you have a very large site. The plugin has to loop through the list of pages or menu items to determine by each item's ID number whether it should be excluded. Nav menus are normally not very large and not likely to be significantly affected, but a site with hundreds of pages could be if you are listing them all.

The plugin was written to store the excluded page IDs in a way that they can be retrieved quickly and not affect performance. A developer who needs to exclude pages on a very large site could extend this plugin with custom widgets or other functionality that exclude the pages when getting them rather than removing them after the entire list has been retrieved.

## Changelog

### 1.0

* New release