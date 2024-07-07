# estate
A plugin for the e107 Content management System to manage Real Estate Agencies, Agents, and Listings. This plugin started as a way for an individual Real Estate agent to easily list their properties for sale and has evolved into one that can handle multiple agents and agencies. 

The _Estate_ plugin is ready fore BETA testing. The Current repository should be mostly usable if anyone wants to try it out. A live production site using this plugin can be viewed here https://www.sandpiperhome.org 

IF YOU HAVE DOWNLOADED THE RELEASE ZIP FILE, PLEASE BE SURE TO CHANGE THE ROOT FOLDER TO "estate" before uploading to your website: Remove any v1.0.0-beta.1 or any other characters after "estate" 

MANUAL UPDATING ON YOUR WEBSITE DURING DEVELOPMENT:

Download ALL files and replace all existing files on your website with current. Run your Check Database Validity on the Estate tables and fix any entries that are found. Then go to the Preference section of the Estate Plugin and Save your preferences.


STILL NEEDED TO FINISH: 
Community/Sub-division/Village data: need to build the forms for Features, Images, and Details 
 

DEVELOPMENT/REVISION HISTORY:

7 July 2024: Added Moderator functions for Non-Agent Listing Submissions. Prefs allow Admin to choose to Auto Approve or Moderator Approve Member submissions. Agent Listings do not require Approval. If a Non-Agent submits a new listing or updates an already approved listing and Moderator Approve is selected, an email notifying the Moderators is sent. Admins can select individual Estate Managers, Estate Admins, and Website Admins as Moderators to contact. 

27 June 2024: Added e_dashboard, added DB Fields, and expanded features. Required after update: DB Update & Scan Plugin Directories


26 June 2024: Many refinements to the overall plugin, including PHP 8.2.2 compatibility (I still have more to check).



16 June 2024: Initiaded built in Contact Form system where visitors can contact Agents/Sellers directly through the Listing View page. Sellers and Agents will recieve an email and PM (if enabled) of these communications. The Contact Form includes presets for 4 different types of communication (Request a Showing, Make An Offer, Sell My Property, and Other Question) and each of these presets fills out a standardized email template. Special Hidden form elements and JavaScript trap bots to reduce unwanted spam. 


5 May 2024: Almost ready for a stable release! Still have to finish coding Community Information Forms and add some UI elements for Likes/Saves and Communication History.  

REQUIREMENTS:

e107 v2.3.3 or later

Tested with PHP 7.4.33, later Versions of PHP may cause problems (I'm working on that)


FEATURES

Assign Users as Agents, Agency Managers, or Agency Admins.

User validation prevents unauthorized access to other Admin areas outside of this plugin.

Built In Quick Add User form to create a new User and Agent profile from within the Estate plugin.

Custom Agent Profiles keyed to User Profiles.

Muti-table Database to store common information shared between property listings

In-form editable Dropdown menus and other shared data options.

Integrated Leaflet Map display of property listings and agency locations.

Multi-file Image Uploading via AJAX

Separate "Room-based" Image Galleries and Property Gallery

Drag and Drop Image re-ordering.

In-browser image cropping through integrated Cropperjs by Chen Fengyuan (https://fengyuanchen.github.io/cropperjs/).

Dynamic Sidebar and Inline-Help based on User Access Level and Enabled Presets. 

Comprehensive Help Sidebar displays relavent Help for current form and selected tabs.

Front-end Templates are customizable through the Estate Preferences form.

Extensive Help system.

Front-end Quick Add/Edit functionality

Non-Agent Private listings (For Sale By Owner) available to a selected User Class

Agent/Seller email Contact Form with notifications through PM integration
