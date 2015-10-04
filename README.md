# mybb-wordpress-theme

**Wordpress Theme** is a MyBB forum plugin that provides only the visual integration with your main Wordpress page.
It uses a special prepared Wordpress template page, where the MyBB forum will be injected as an HTML **iframe** element.
The Wordpress template page can contain all of Wordpress benefits like: nice header, menu, widgets, static content. The MyBB forum will
be injected into that template page which will provide the **visual integration** only.

It has been checked that the plugin works with MyBB 1.8.5. and 1.8.6.


Instructions on how to install and configure the plugin.

* Download the latest **Wordpress Theme** plugin release version from Github.
* Unpack the release distribution into your forum **inc/plugins** directory
* Go to your Wordpress and create a static page with the following content (the content will be replaced with the MyBB iframe):

[MYBB-GOES-HERE]

* Remember the URL of your Wordpress forum page (created above)
* Go back to your MyBB forum administration panel and activate the **Wordpress Theme** plugin
* Configure the plugin:
** enter the **Wordpress theme page URL**
** change the **Wordpress theme cache time in minutes** (if needed)
