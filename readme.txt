=== Plugin Name ===
Contributors: Yohann Joyeux (happy technology)
Requires at least: 5.0
Tested up to: 5.4
Requires PHP: 5.6
Stable tag: 1.0.0
Tags: elementor, json, dynamic data, api, webservice
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html


== Description ==

This plugin allows Wordpress editors to integrate a JSON source (url or raw data) in any Elementor plugin widgets.
Sometime you need dynamic data and not static content, that's why I have created this plugin in order to integrate dynamic content to Elementor widgets from any JSON url.
This is the perfect solution for the creation of a web prototype with json data, validate it with business and then implement and connect the dynamique webservice.

== Installation ==

Elementor plugin is mandatory, as this plugin is an extension of Elementor plugin.

1. Install Elementor plugin or check that you have already installed it.
1. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress


== Frequently Asked Questions ==

= How it works =

1. Once plugin activated, you will find a section named "Happy Json Integration" on the "Content" tab of your Elementor widget. For example "Toggle" widget.
2. Set the JSON content : url on field "JSON URL" or raw data on field "JSON Content". Example https://jsonplaceholder.typicode.com/users .
3. Create a new "Widget Settings Items" with button "+ ADD ITEM".
3.1 Fill field "Widget Settings Name" : with the value of settings field you want to feed with json content. For example "tabs" for Toggle widget.
3.2 Fill field "JSON paths" : with the path on json flow (use "." for separator like root.elements). For the our json example let it empty because elements are on the root level.
3.3 (Optional) Fill field "JSON array instructions" if json path target an Array of elements. Set the widget property value like myproperty:elementName. For our json example tab_title:name and tab_content:email separated by EOL. (If array like {key1:value1,key2:value2,...}, you can use myproperty:key, myproperty:value).

= How could I know the my widget settings property =

1. Activate the switcher button "Debug mode".
2. Open your browser javascript console (F12) you will see the log into the console "DATA=>", find under settings elements, the content section of your widget. For our Toggle example : "tabs".
3. Check also your wordpress logs file you will see the same logs below "DATA=>".
4. You will also find the JSON data under "JSON=>" for debug purpose.


== Changelog ==

= 1.0.0 =
* Official Release.
