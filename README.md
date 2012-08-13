# Ho_OfflineMaintenance fork from ArsOnIt_OfflineMaintenance
http://www.magentocommerce.com/magento-connect/maintenance-page-artson-it.html

This module changes the following:

1. Ability to use default 503 error pages instead of needing to add custom HTML. Take a look at the excellent Magebase article: [http://magebase.com/magento-tutorials/customizing-the-magento-error-report-and-maintenance-page/](Customizing the Magento Error Report and Maintenance Page).

2. Ability to exclude URL's by default. Think about api pages, postback pages, etc. I need feedback on this, does anyone have a list with all the pages
that should be enabled by default?

3. Listens to Developer Client Restrictions. If your IP is present in that list you can always view the website.
Because of this, I've moved the settings to the Developer config page.

4. Disable when developer mode detected

5. Add IP's with table instead of comma separated (add row, delete row, etc.)