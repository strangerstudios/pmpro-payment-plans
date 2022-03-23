=== Paid Memberships Pro - Payment Plans ===
Contributors: strangerstudios
Tags: paid memberships pro, pmpro, payment plan, payments
Requires at least: 5.0
Tested up to: 5.9
Stable tag: 0.1.1

This allows you to offer more than one pricing structure on the same membership level. 

== Description ==

This Add On allows you to offer more than one pricing structure on the same membership level. 
Multiple pricing structures, such as a monthly and annual/yearly option, helps your membership appeal to a broader range of prospective buyers.

== Installation ==

= Prerequisites =
1. You must have Paid Memberships Pro installed and activated on your site.

= Download, Install and Activate! =
1. Download the latest version of the plugin.
1. Unzip the downloaded file to your computer.
1. Upload the /pmpro-approvals/ directory to the /wp-content/plugins/ directory of your site.
1. Activate the plugin through the 'Plugins' menu in WordPress.

= How to Use =
1. Create a membership level and set the pricing on the level to the default or most popular payment you offer. For example, set the main level up as a fixed price per month.
1. Then, locate the “Payment Plans” section and click the “Add Payment Plan” button.
1. Expand the individual payment plan to adjust the name, pricing details, and any additional settings related to expiration or trials.
1. Add additional payment plans if you’d like to offer more than one. The default level pricing will be shown first in the list with any additional active payment plans shown below.
1. Save the membership level and browse to the checkout page for that level to see your plans in action.

View full documentation at: https://www.paidmembershipspro.com/add-ons/pmpro-payment-plans/

== Changelog ==
= 0.1.1 - 2022-03-23 =
* ENHANCEMENT: The selected payment plan will now be remembered at checkout when the page is reloaded. (@JarrydLong)
* ENHANCEMENT: Radio buttons used to select payment plans at checkout now utilize the `pmpro_get_element_class()` function. (@JarrydLong)
* BUG FIX/ENHANCEMENT: Stripe Payment Request button will now show price for selected payment plan. (@JarrydLong)
* BUG FIX: Now checking that a valid payment plan is selected at checkout. (@JarrydLong)
* BUG FIX: Now only clearing payment plan settings when this pluin is deleted and when PMPro is set to delete settings on uninstall. (@JarrydLong)
* REFACTOR: Created new function `pmpropp_get_plan()` to simplify expected behavior of `pmpropp_return_payment_plans()`. (@ideadude)


= 0.1 - 2022-03-17 =
* Initial version.
