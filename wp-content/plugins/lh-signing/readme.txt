=== LH Signing ===
Contributors: shawfactor, mattsenate
Donate link: https://lhero.org/portfolio/lh-signing/
Tags: list, lists, email, email-validation, validation, petition, petitions, register, campaign, sign, signature, event, events
Requires at least: 3.0
Tested up to: 4.8
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

LH Signing allows you to create configurable validated lists like petitions or event sign ups. It is the only self hosted solution in WP

== Description ==

This plugin is can be used for petitions, events, or any verified list and as far as I know is unique in WordPress.

Creating a petition/list/signup is as easy as adding a shortcode to a post or page (or CPT). From there additional editors are available to configure easch aspect of the sign up. Everything is completely self hosted and all list members become users of your site (not a third parties).

Available shortcodes:

[lh_signing_form] (enable signatories on any post, page, or custom post type)

Unconfirmed signatories: [lh_signing_unconfirmed_count]

Confirmed signatories: [lh_signing_confirmed_count]

Total signatories: [lh_signing_total_count]

[lh_signing_unconfirmed_list] (unformated list)

[lh_signing_confirmed_list] (unformated list)

You can customise the display of the signing form ([lh_signing_form]) via a number of shortcode arguments more information is available [here](https://lhero.org/portfolio/lh-signing/ "Additional Plugin documentation")

== Installation ==

1. Upload the `lh-signing` folder to the `/wp-content/plugins/` directory
1. Install the WordPress Posts 2 Posts plugin
1. Activate both plugins through the 'Plugins' menu in WordPress
1. Go the the editor and add the shortcode [lh_signing_form] to posts, pages or the cpts that you wish to sign, see faq for display attributes
1. You will then see some configuration options and 4 metaboxes, these configure the behaviour and messages of the various confirmation steps (absolutely everything can be configured)

== Frequently Asked Questions ==

= What could you use this for? =
You could use this plugin to create petitions with verified lists of users who have signed the petition. Or alternatively event sign ups or any sort of lists where the user needs to be verified

= How does the verification work? =

Visitors who are not logged in enter their email address and are sent an email with a verification link, which they click to verify their agreement. Users who are logged in can automatically add their signature by simply submitting the form.

= What post types does this work on? =

By default both posts and pages can be signed. To add a custom post type or indeed remove a post type use the filter: lh_signing_posttypes_filter and add or remove post types to the array

= Can this be spammed? =
This plugin uses JavaScript nonces to protect against spam. This means JavaScript must be activated by visitors, 99% of visitors do. This prevents automated spam.

= Can I customise the email templates that is used for the confirmation emails? =
Yes if you wish to customise the format of the html emails that are sent by this plugin, more the lh_signing-template.php file to your theme or child theme folder. From there you can edit its content. Styles in the head are automatically moved into the body when the email is sent.

= Can I send emails to confirmed users? =
Yes if you enable the Email Confirmed Users option you can populate a message that will be sent to users that are logged in or have confirmed their email address.


= What does the registration page id mean? =
Where an email is added to a new signing a new user is created with a role of unconfirmed and when the user confirms their "signature", this role is changed to the default role for the site (usually subscriber), as such this plugin can/could be used for registering new users. If the registration page id corresponds to a page which contain the [lh_signing_form] short code, then this page will become the  default registration page for the site.

= What does the registration page id mean? =
As this plugin creates anew user (with a role of unconfirmed) and when they submit the form with a valid email. When this user confirms their "signature", this role is changed to the default role for the site (usually subscriber), as such this plugin can/could be used for registering new users. If the registration page id corresponds to a page which contain the [lh_signing_form] short code, then this page will become the  default registration page for the site.


= What does the password reset page id mean? =
This plugin can also be used for password resets. More documentation on this will added soon.

= This plugin is slow to register the signature, why is that? =
The initial signing involves sending a confirmation email, if this is delivered via an external provider (eg via SMTP), this may take a few seconds. To work around this the plugin supports queuing the email, you can se whether you want email queuing or not in the configuration.

== Changelog ==

= 1.00 =
* Initial release

= 1.10 =
* Custom Meta Box

= 1.12 =
* Bug Box

= 1.20 =
* Error handling, autop, and shortcodes

= 1.21 =
* Changed readme

= 1.30 =
* Added various features

= 2.00 =
* Automatically allow post types, removed settings

= 2.10 =
* Added email template

= 2.20 =
* Added Signing Widget plus improvements

= 2.21 =
* Cleaner functions

= 2.30 =
* Autologon etc

= 2.40 =
* Regularised functions, DRY principles

= 2.50 April 05, 2016 =
* Added export function

= 2.51 April 06, 2016 =
* Minor bug fix

= 2.52 April 07, 2016 =
* Removed testing code

= 2.53 April 18, 2016 = 
* Another minor bugfix

= 2.60 June 01, 2016 =
* Conditionally show admin boxes

= 2.61 June 22, 2016 =
* Update links

= 2.62 June 25, 2016 =
* Added shortcodes, thanks  mattsenate

= 2.63 June 27, 2016 =
* Fixed widget problem

= 2.64 August 12, 2016 =
* Minor shortcode fix

= 2.65 September 22, 2016 =
* Added option to send email to confirmed users, added email queuing

= 2.66 October 22, 2016 =
* Added documentation

= 2.71 January 03, 2017 =
* Major code reorganisation

= 2.73 January 03, 2017 =
* Bug Fix

= 2.74 January 05, 2017 =
* Shortcode attributes

= 2.75 January 06, 2017 =
* Minor fixes

= 2.76 January 10, 2017 =
* Javascript improvement

= 2.77 January 13, 2017 =
* div for messages

= 2.79 January 13, 2017 =
* minor fixes

= 2.80 January 14, 2017 =
* error reporting off for css templating

= 2.81 June 10, 2017 =
* various improvements

= 2.82 July 16, 2017 =
* Added fname and lname autofill, and extra filters

= 2.83 August 01, 2017 =
* register_form filter added