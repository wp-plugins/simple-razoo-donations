=== Simple Razoo Donations ===
Contributors: wiredimpact 
Tags: razoo, donation, shortcode, donate, forms
Requires at least: 2.8
Tested up to: 3.4.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple Razoo Donations allows you to easily embed the Razoo Donation Widget and accept donations on your website without typing a line of code.

== Description ==

**Why is the Simple Razoo Donations Plugin Helpful?**

* You can easily include the [Razoo Donation Widget](http://www.razoo.com/p/donationWidget "Razoo Donation Widget") on your website and start accepting donations without typing any code at all.
* You can place the donation form into the middle of any post or page using a button within the WordPress editor.  The donation form is added right into the editor as a WordPress shortcode that looks like "[razoo_donation_form]".
* The detailed settings page allows you to easily update the content, design and donation options.
* Since the donation form is embedded within the settings page, you can see any adjustments immediately after making updates and saving your changes.
* We include simple instructions on how to get up and running to start accepting donations online.
* You can use shortcode attributes to customize individual donation forms for different locations on your website.
* We promise to respond to every support request, period.

**Clearing Up Confusion About the Term "Widget"**

We want to clear up any confusion about the name given to the donation form by Razoo. The name "Razoo Donation Widget" includes the word widget, but that does not refer to a WordPress widget. There is no widget functionality included with this plugin.

**Thanks**

A special thanks to [Zaus](http://profiles.wordpress.org/zaus/) and [AtlanticBT](http://profiles.wordpress.org/atlanticbt/) for providing the foundation for this plugin.

**Visit the Plugin's Webpage**

For more details about using the plugin visit [http://wiredimpact.com/simple-razoo-donations/](http://wiredimpact.com/simple-razoo-donations/ "Simple Razoo Donations Webpage").


== Installation ==

**How Do I Install the Plugin?**

The easiest way to install the Simple Razoo Donations plugin is to go to Plugins >> Add New in the WordPress backend and search for "Simple Razoo Donations." On the far right side of the search results, click "Install."  If that doesn't work follow the steps below.

1. Download the Simple Razoo Donations plugin and unzip the files.
1. Upload the simple-razoo-donations folder to the /wp-content/plugins/ directory.
1. Activate the Simple Razoo Donations plugin through the "Plugins" menu in WordPress.

**How Do I Add the Donation Form to My Website?**

Now that you've installed the plugin you're ready to add the donation form to your website.  To do so, follow the steps outlined here.

1. Configure your donation form settings by going to Settings >> Simple Razoo Donations in the WordPress backend.
1. Place your cursor within the WordPress editor's text where you want the Razoo donation form to be added.
1. Click the Razoo icon in the WordPress editor toolbar (looks like a globe broken into pieces). The Razoo shortcode reading "[razoo_donation_form]" will be added in the editor.
1. Click the blue "Publish" or "Update" button to save your changes and add the Razoo donation form to your live website.


== Frequently Asked Questions ==

Here are some frequently asked questions about how to use the plugin.

= How do I customize the donation form for different pages? =

If you want to put different versions of the donation form on different pages of your website, you can use shortcode attributes to override the default options you provided on the settings page. You only need to include attributes for the defaults you wish to override. For example, if every donation should go to the same organization, you will never need to use the "id" attribute in your shortcode. An example shortcode with all attributes looks like this:

`[razoo_donation_form id="United-Way-of-America" title="Support United Way" short_description="Help us help the community" long_description="United Way has been supporting communities since the late 1800s and now supports communities in countries around the world." color="#000000" image="true" donation_options="20=Donor|30=Sponsor|50=All Star Contributor"]`

Here is a breakdown of how to use each attribute:

* id: The ID for your organization according to Razoo. When on your organization's landing page it's the text that comes right after "/story/" in the URL. For example, the United Way of America's ID is "United-Way-Of-America". You can view their ID at [http://www.razoo.com/story/United-Way-Of-America](http://www.razoo.com/story/United-Way-Of-America "United Way of America at Razoo").
* title: The title will show up in big letters at the top of the donation form.
* short_description: A short description of your organization or an ask for people to donate. This text shows up just below the title.
* long_description: This is also called the "more info" section and can be much longer, describing more about your organization and where the donors' money will go. This text shows up when users click the "More info" link on the donation form.
* color: Provide the color you want for the donation form in [hexadecimal format (#000000)](http://www.w3schools.com/html/html_colors.asp "Hexadecimal Tutorial"). You should match this closely to your website's colors.
* image: use "true" to show the main image for your organization on the donation form.
* donation_options: Add the donation options you want to offer potential donors within a pipe (|) separated list of values and labels (ie. donation_options="20=Donor|30=Sponsor|50=All Star Contributor")

= How do I use the shortcode in a template file? =

To add the shortcode directly to a template file use the code:

`<?php echo do_shortcode('[razoo_donation_form]'); ?>`


== Screenshots ==

1. Razoo donation form outputted on a website
2. Settings page for your organization's defaults
3. WordPress editor button to add the donation form
4. Shortcode appears after you click the Razoo button on the editor


== Changelog ==

= 0.1.1 =
* Fixed "Settings" link on plugins page to point correctly.

= 0.1 =
* Initial release

== Upgrade Notice ==

= 0.1.1 =
This version fixes bug where "Settings" link on plugins page didn't point correctly.
