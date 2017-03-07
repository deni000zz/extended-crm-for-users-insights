=== Extended CRM for Users Insights ===
Contributors: denizz
Tags: crm, users, user, user groups, user notes, groups, notes, custom field, user meta, management, user management, users insights
Requires at least: 4.4
Tested up to: 4.7
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Extends the CRM functionality of Users Insights - adds new management options to the user groups, user notes and custom user fields features

== Description ==
Extends the default CRM functionality and user management of the [Users Insights plugin](https://usersinsights.com/?utm_source=wprepo&utm_campaign=crm) with the following features:

* User Notes: adds an option to create sticky notes. Each note in the [user profile section](https://usersinsights.com/wordpress-user-profile-pages/) has an option to be sticked to top. The sticky notes are in a different color so they can be different from the regular notes.
* User Notes: adds a "Last Note Date" field to the Users Insights users table. It shows the date of the last note and allows to order and [filter the user list](https://usersinsights.com/wordpress-users-smart-filters/) by the last note date. This can be useful for the user management if you need to see the latest user notes.
* User Notes: adds a "Note Content" filter that allows searching by note content
* Custom User Fields : improves the creation and management of the [custom user fields](https://usersinsights.com/wordpress-custom-user-fields/) by adding an option to select existing user meta keys. It lists all of the existing user meta keys, except the WordPress core keys and the private keys (starting with _). Just like the regular custom user fields, you can use these custom fields to filter or order the user list.
* User Groups: adds an option to select an icon when creating a [user group](https://usersinsights.com/wordpress-user-groups/). After that, the group is displayed with the icon in the users table and user profile section. The group icons can be managed from the User Groups section of Users Insights.
* User Groups: extends the default color options with darker colors, so that when icons are used for user groups, they can be better visible with darker colors.

[Extended CRM for Users Insights on GitHub](https://github.com/deni000zz/extended-crm-for-users-insights) - help us improve Extended CRM for Users Insights, pull requests are welcome on GitHub

== Installation ==
1. Make sure that the [Users Insights plugin](https://usersinsights.com/) is installed and activated.
2. Log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.
3. In the search field type “Extended CRM For Users Insights” and click Search Plugins. Once you’ve found the plugin you can view details about it such as the point release, rating and description. Most importantly of course, you can install it by simply clicking “Install Now”.

== Screenshots ==
1. Users Insights table with Group Icons
2. Applying icons to the user groups
3. Filtering users by group
4. Sticky user notes
5. Selecting an existing user meta field in the Custom Fields section
6. Last Note Date field - adding a filter that filters the users by the last note date field


== Changelog ==

= 1.2.0 =

Release Date: March 7th, 2017

* Added a filter that allows searching by note content

= 1.1.2 =

Release Date: April 8th, 2016

* WP 4.5 compatibility fix: detect the new edit term page name - https://make.wordpress.org/core/2016/03/07/changes-to-the-term-edit-page-in-wordpress-4-5/

= 1.1.1 =

Release Date: March 11th, 2016

* Added support for version 2.1.0 of Users Insights

= 1.1.0 =

Release Date: February 9th, 2016

* Added a "Last Note Date" field to the Users Insights users table. It shows the date of the last note and allows to order and filter the user list by the last note date
* Added a link to the Users Insights plugin site in the dashboard notification that is displayed when Users Insights is not activated
* Improved the description of the plugin and added a link to the GitHub page