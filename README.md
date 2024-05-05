# estate
A plugin for the e107 Content management System to manage Real Estate Agencies, Agents, and Listings. This plugin started as a way for an individual Real Estate agent to easily list their properties for sale and has evolved into one that can handle multiple agents and agencies. 

The _Estate_ plugin is not ready yet. I have a lot to do before it can be published. The Current repository should be mostly usable if anyone wants to try it out. A live production site using this plugin can be viewed here https://www.sandpiperhome.org

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
