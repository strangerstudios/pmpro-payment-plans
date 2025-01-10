=== Paid Memberships Pro - Payment Plans ===
Contributors: strangerstudios
Tags: paid memberships pro, pmpro, payment plan, payments
Requires at least: 5.4
Tested up to: 6.7
Stable tag: 0.5
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Offer different recurring payment structures on the same level of membership. Users can select a payment plan during the checkout process.

== Description ==

This Add On allows you to offer different recurring payment structures on the same level of membership, such as monthly, quarterly, or annual pricing.

Users can select a payment plan during the checkout process.

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
= 0.5 - 2025-01-10 =
* ENHANCEMENT: Added better support for Group Accounts Add On and Proration Add On. #79 (@MaximilianoRicoTabo)
* ENHANCEMENT: Added logic to allow direct linking to payment plans via query parameter (e.g. `&pmpropp_chosen_plan=2` or `&pmpropp_chosen_plan=L-1-P-4`). #76 (@MaximilianoRicoTabo)
* ENHANCEMENT: Added logic to add Payment Plans information to Site Health. #67 (@MaximilianoRicoTabo)
* BUG FIX: Fixed an issue where discount codes were not being applied correctly for levels with payment plans. #77 (@MaximilianoRicoTabo)
* BUG FIX: Fixed an issue when in some cases the payment plans would save empty values. #80 (@MaximilianoRicoTabo)

= 0.4.1 - 2024-10-24 =
* BUG FIX: Fixed an issue that may have caused payment plans to not be saved correctly. #74 (@dparker1005)

= 0.4 - 2024-09-24 =
* SECURITY: Added extra sanitization to output strings. #71 (@andrewlimaza)
* ENHANCEMENT: Updated the frontend UI for compatibility with PMPro v3.1. #71 (@andrewlimaza, @kimcoleman)
* REFACTOR: Formatting applied to frontend.js #71 (@andrewlimaza)

= 0.3 - 2023-08-24 =
* ENHANCEMENT: General improvements to accessibility for screen readers. (@kimcoleman)
* ENHANCEMENT: Show the payment plan name on the account and billing page instead of just the level's name. (@JarrydLong)
* ENHANCEMENT: Added a filter to allow the payment plan name and cost text to be changed - `pmpropp_plan_cost_text_checkout`. (@andrewlimaza)
* REFACTOR: Refactored code to use default get_option instead of our own wrapper function. (@dwanjuki)
* BUG FIX: Fixed issues where offsite gateways such as PayPal Express, PayFast and other gateways wouldn't show the correct amount charged in the order information. (@JarrydLong)

= 0.2 - 2023-01-03 =
* ENHANCEMENT: Added better UI for inactive plans. (@JarrydLong)
* ENHANCEMENT: Improved support for Addon Packages. (@JarrydLong)
* ENHANCEMENT: Improved logic for determining when to load the javascript on the frontend. (@ipokkel)
* ENHANCEMENT: Improved logic for determining when to run the save payment plan logic in the admin area. (@ipokkel)
* BUG FIX: Fixed an issue in settings where dropdown values would not reflect actual saved settings. (@JarrydLong, @dparker1005)

= 0.1.1 - 2022-03-23 =
* ENHANCEMENT: The selected payment plan will now be remembered at checkout when the page is reloaded. (@JarrydLong)
* ENHANCEMENT: Radio buttons used to select payment plans at checkout now utilize the `pmpro_get_element_class()` function. (@JarrydLong)
* BUG FIX/ENHANCEMENT: Stripe Payment Request button will now show price for selected payment plan. (@JarrydLong)
* BUG FIX: Now checking that a valid payment plan is selected at checkout. (@JarrydLong)
* BUG FIX: Now only clearing payment plan settings when this pluin is deleted and when PMPro is set to delete settings on uninstall. (@JarrydLong)
* REFACTOR: Created new function `pmpropp_get_plan()` to simplify expected behavior of `pmpropp_return_payment_plans()`. (@ideadude)


= 0.1 - 2022-03-17 =
* Initial version.
