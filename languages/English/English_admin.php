<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source:  $
|     $Revision$
|     $Date$
|     $Author$
+----------------------------------------------------------------------------+
*/

define("EST_AWARN_NOCOUNTY1A", "NO COUNTIES FOUND");
define("EST_AWARN_NOCITY1A", "NO CITIES/COUNTIES FOUND");
define("EST_AWARN_CLICKHERE", "Click Here to fix this");


define("EST_AWARN_000", "Your login credentials do not allow");
define("EST_AWARN_001", "User Permission Denied");
define("EST_AWARN_002", "you to accesss this plugin.");
define("EST_AWARN_003", "Please contact the Main Site Administraitor to assign you to one of the following classes: Estate Agent, Estate Manager, or Estate Admin");

define("EST_AWARN_004", "If you are the Main Admin, you need to assign yourself to the User Class Estate Admin. You can also delegate other Admins whom you want to have full access to this plugin to this User Class.");
define("EST_AWARN_005", "If you are NOT a Main Admin, you need to ask the Main admin to assign you to the appropriate User Class");


define("EST_AWARN_006", "YOU DO NOT HAVE AGENT PERMISSIONS!");
define("EST_AWARN_007", "YOU DO NOT HAVE AN AGENT ID!");
define("EST_AWARN_008", "YOU ARE NOT ASSIGNED TO AN AGENCY!");
define("EST_AWARN_009", "YOU ARE NOT ASSIGNED TO A COMPANY!");

define("EST_AWARN_010", "uploading of files");
define("EST_AWARN_011", "deleting files");
define("EST_AWARN_012", "modifying data");
define("EST_AWARN_013", "you to delete this property");

define("EST_UNINSTALL1","Any user who is an Admin AND had Permissions to access this plugin - and ONLY this plugin - have had their Admin rights removed. ");
define("EST_UNINSTALL2","Main Admins should NOT have been affected.");
define("EST_UNINSTALL3","Please review your e107 Users to confirm their current Admin status.");

define("EST_AMENU_MENUTIT1", "Property");
define("EST_AMENU_PROPLIST", "Listings");
define("EST_AMENU_AGENCIES","Agencies");
define("EST_AMENU_AGENCY","Agency");
define("EST_AMENU_NEWPROP", "Add/Edit Property");
define("EST_AMENU_CITYLIST", "Cities");
define("EST_AMENU_NEWCITY", "Add/Edit City");

define("EST_AGT_CLEARDATA","Clear Agent Data?");
define("EST_AGT_PROPIC","Profile Image");
define("EST_AGT_USEPROPIC","Use Profile Image?");


define("EST_COMP_IMPORTDTA","Import Data From");
define("EST_COMP_IMPORTDTA1","Select a Company from which to import data presets");
define("EST_COMP_NOZONCATS","There are no Zoning Categories");
define("EST_COMP_SELZONIMP","Import Zoning");
define("EST_COMP_SELZONIMP1","Import Selected Zoning Categories and Presets");

define("EST_PRESETS_SELZONKEEP","Zoning Categories");
define("EST_PRESETS_SELZONINGSAVEHINT","Save Zoning Categories");
define("EST_PRESETS_SELZONEDITTTL","Add & Edit Zoning Categories");


define("EST_AGT_UPAGTDTA","Update Agent Data?");


define("EST_ERR_500","Server Errors: See Javascript Console for details");
define("EST_ERR_AGENTIDZERO","Save Agent First");
define("EST_ERR_AGENTIDZERO1","Agent Profile must be saved before you can add");
define("EST_ERR_AGYIDZERO","Save Agency First");
define("EST_ERR_AGYIDZERO1","Agency Data must be saved before you can add");
define("EST_ERR_COMPIDZERO","Save Company First");
define("EST_ERR_COMPIDZERO1","Company must be saved before you can add");

define("EST_ERR_DATANOTARRAY","Data sent is not in Array Format");
define("EST_ERR_DUPEAGENT","Duplicate Agent Information.");
define("EST_ERR_DUPEAGENT1","Agent Name submitted already exists.");
define("EST_ERR_DUPEAGENCY","Duplicate Agency Information.");
define("EST_ERR_DUPEAGENCY1","Agency Name submitted already exists.");
define("EST_ERR_DUPEUSER","Duplicate User Information.");
define("EST_ERR_DUPEUSER1","User Display Name, Login Name, Real Name, or Email submitted already exists.");
define("EST_ERR_FAILADDNEW","Failed to Add New");
define("EST_ERR_FAILDELETE","Failed to Delete");
define("EST_ERR_FILEDIR1","File Directory");
define("EST_ERR_FINISETUP","Finish Setup");

define("EST_ERR_ISNOTDIR","is not a valid Directory");
define("EST_ERR_ISNOTWRITE","is not a writable");

define("EST_ERR_KEYFIELD1","Key Field Not Found");
define("EST_ERR_KEYFIELD2","DB Index Field Not Found");
define("EST_ERR_LOSTRESULT","Database Updated but form Element not defined");


define("EST_ERR_NOAGENCIES0","No Agencies");
define("EST_ERR_NOAGENCIES1","There are no agencies in the system!");
define("EST_ERR_NOAGENCIES2","Please click the + button to create your first Agency");
define("EST_ERR_NOCONDATA","Contact Data must be  at least 3 characters");
define("EST_ERR_NOCONTKEYS","Missing Contact Type Data");
define("EST_ERR_NOCONTKEYS1","No Contact Types have been defined. ");
define("EST_ERR_NOCONTYPE","Please set Contact Type");

define("EST_ERR_NOMAP","Option Key->Value Map not defined");
define("EST_ERR_NOTAUTH1","You are Not authorized to do this");
define("EST_ERR_NOUSERSINCLASS","THERE ARE NO USERS ASSIGNED TO ANY ESTATE USER CLASSES");
define("EST_ERR_NOUSERSINCLASS1","Please assign at least one user (yourself) to the Estate Admin userclass (you may assign yourself to Estate Manager and Estate Agent classes too, but it is not required).");

define("EST_ERR_PROPIDZERO","Property must be saved first");
define("EST_ERR_SETUPINCOMP","You must finish the Initial Setup before you can access other parts of this plugin.");
define("EST_ERR_TABLE1","Database Table Not Found");
define("EST_ERR_TABLE2","Field Count Mismatch");
define("EST_ERR_UPLNOREAD","Unable to open and examine uploaded file");
define("EST_ERR_UPLNOPHP","PHP files Not Allowed");
define("EST_ERR_UPLNOEXT","Unknown file type: No file Extension");
define("EST_ERR_UPFILEREM","Uploaded file removed");

define("EST_ERR_CLASSMIS1","Some special User Classes are missing");
define("EST_ERR_CLASSMIS2","This plugin relies on these user classes in order to function properly. Please add the missing classes to your e107 User Class list");


define("EST_ERR_UPFILEREMFAIL","Uploaded file NOT removed");
define("EST_ERR_FILENOTFOUND","File NOT Found");

define("EST_ERR_FILENOTALLOWED","File Type Not Allowed");
define("EST_ERR_FILENOTSAVED","Failed to save file");

define("EST_ERR_DBMEDIAREMOVED","Media Database Entry Removed");


define("EST_EVNT_DETAILS","Add Event details if needed");
define("EST_EVNT_END","End Date & Time");
define("EST_EVNT_NAME","Event Name");
define("EST_EVNT_START","Start Date & Time");
define("EST_EVNT_TEXTLAB","Event Details");
define("EST_EVNT_TYPE","Event Type");

define("EST_FEAT_NAMEPLCH","Bedroom, Bathroom, Outdoor Living, etc");

define("EST_GEN_ACTIVE1", "Scheduled Publication");
define("EST_GEN_ACTIVE2", "Live Now - Ignore Publication Schedule");
define("EST_GEN_ACCESS","Access");
define("EST_GEN_ADDED","Added");
define("EST_GEN_ADDEDAS","Added as");
define("EST_GEN_ADDEDNEW","Added New");
define("EST_GEN_ADDEDTO","Added to");
define("EST_GEN_ADDNEWUSER","Add New User");
define("EST_GEN_ADDNEWUSERHLP","Who can add a New User through this plugin.");
define("EST_GEN_ADMINACCLEVEL", "Admin Access Level");
define("EST_GEN_ADMINUSERS", "Admin Users");
define("EST_GEN_ADDNEW", "Add New");
define("EST_GEN_ADDUSERS","Add Users as Agents");
define("EST_GEN_ADDNEWROOM", "Add New Room");
define("EST_GEN_AGENTLOGNAME","Agent Login Name");
define("EST_GEN_AGENTSAGENCIES", "Agents & Agencies");
define("EST_GEN_ANDIS","and is");
define("EST_GEN_ANDISNOT","and is NOT");
define("EST_GEN_ASSUSRCLASSES","Assign User Classes");
define("EST_GEN_CANTDELZONE","Cannot Delete Zoning Category");
define("EST_GEN_CLKCUSTOM","Click Custom Label");
define("EST_GEN_CLKHLDCUSTOM","Click & Hold for Custom Label");
define("EST_GEN_COMPANYPLCH", "eg. Sundance Realty");
define("EST_GEN_CREATEAGENT", "Create Agent Profile for");
define("EST_GEN_CUSTOMPROFIMG", "Custom Profile Image");


define("EST_GEN_DATAPRESETS","Data Presets");
define("EST_GEN_DATASOURCE","Data Source");
define("EST_GEN_DBRECORDSREMOVED","Database Records Removed");
define("EST_GEN_DBNOTUPDATED","Database NOT Updated");
define("EST_GEN_DBUPDATED","Database Updated");
define("EST_GEN_DELETEDPROPERTY","Deleted Property");
define("EST_GEN_DELZONECAT","Delete Zoning Category Results");
define("EST_GEN_DOESEXIST","does exist");
define("EST_GEN_DOESNOTEXIST","does NOT exist");
define("EST_GEN_ELEMNT","Element");
define("EST_GEN_FEATURESFOR","Features For");
define("EST_GEN_FEATURELISTDTA","Feature List Data");
define("EST_GEN_FIELD","Field");
define("EST_GEN_FIRST","First");
define("EST_GEN_FIRSTTIME","First Time");
define("EST_GEN_FRONTENDACCESS","Front End Access");
define("EST_GEN_FORM","Form");
define("EST_GEN_GENERALOPTS", "General Options");
define("EST_GEN_GENERALOPTSHLP1", "Set general options for this plugin");
define("EST_GEN_GROUP", "Group");
define("EST_GEN_GROUPS", "Groups");
define("EST_GEN_HIDEOTHERUSERS", "Hide Other Users");
define("EST_GEN_IMPORTANT","IMPORTANT!");


define("EST_GEN_LISTYPES", "Listing Types");
define("EST_GEN_LOGO", "Logo");
define("EST_GEN_MAINADMIN", "Main Admin");
define("EST_GEN_MAPOPTS", "Map Options");
define("EST_GEN_MAPOPTSHLP1", "Set map options for this plugin");
define("EST_GEN_MISSING","Missing");
define("EST_GEN_NAMEADDRESS","Name & Address");
define("EST_GEN_NEWAGENT", "New Agent Profile");
define("EST_GEN_NEWCOMPANY", "New Company");
define("EST_GEN_NEWUSERAGENT","New User/Agent");
define("EST_GEN_NONADMINACCESS", "Non-Admin Access");
define("EST_GEN_NONADMINLISTINGS", "Non-Admin Listings");
define("EST_GEN_NONAGENTLISTINGS","Non-Agent Listings");
define("EST_GEN_NONAGENTLISTINGHLP1","Set options for listings posted by non-agents");
define("EST_GEN_NONAGENTAPPROVED","Auto Approve");
define("EST_GEN_NONAGENTAPPROVEDHLP","Automatically Approve New Non-Agent Listing Submissions");
define("EST_GEN_NONAGENTEXP","Expires After");
define("EST_GEN_NONAGENTEXPHLP","Set how long Non-Agent Listings will remain visible");
define("EST_GEN_NONAGENTIMGCT","Max Images");
define("EST_GEN_NONAGENTIMGCTHLP","Set the maximum number of images a Non-Agent can upload per listing (min: 3, max 18).");
define("EST_GEN_NONAGENTMOD","Moderated By");
define("EST_GEN_NONAGENTMODHLP","Choose who can Moderate Non-Agent Listings");
define("EST_GEN_NOCHANGEADMIN", "You cannot change a Main Admin");
define("EST_GEN_NOAGENCYFOUND0","Agency Not Found");
define("EST_GEN_NOAGENCYFOUND1","Was not found in the database. If you know that the Agency ID is correct, you may not have permissions to edit the data.");
define("EST_GEN_NONADMINUSERS", "Non-Admin Users");
define("EST_GEN_NOTALLOWEDADDUSER","Your Access Level does not allow you to add a New User to this website though this plugin.");
define("EST_GEN_DBNOCHANGES","No Changes were made to");
define("EST_GEN_NONEFOUND","None Found");
define("EST_GEN_NOTASSIGNEDAGENCY","Not assigned to an Agency");
define("EST_GEN_NOTADDEDAS","NOT added as");
define("EST_GEN_NOTDEFINED","Not Defined");

define("EST_GEN_PLUGADMINONLY","NOTICE: THE ESTATE PLUGIN IS VISIBLE ONLY TO ADMINS");
define("EST_GEN_PLUGNOTINST","ADMIN NOTICE: THE ESTATE PLUGIN IS NOT INSTALLED");
define("EST_GEN_PRIVATESHOWINGS","Private Showings");
define("EST_GEN_PRESETS","Presets");
define("EST_GEN_PREFERENCES","Preferences");
define("EST_GEN_PROFILEVISIB","Profile Visibility");



define("EST_GEN_REMOVEAGT", "Remove Agent from all Agencies");
define("EST_GEN_REMOVEAGTC1", "Click OK to Delete this Agent's Profile. This will remove the Agent from all of their Listings as well.");
define("EST_GEN_REMOVEAGTLOC","Remove Agent From Assigned List");
define("EST_GEN_REMAGENCY1", "Click OK to Remove this Agency's Profile. You will have an option to reassign any listings and Agents to another Agency.");
define("EST_GEN_REMAGENCYX", "You cannot remove this Agency - there are no other Agencies to which you can assign your Agents and Listings.");

define("EST_GEN_REORDER","Re-order");
define("EST_GEN_RESETDEF","Reset to Default");
define("EST_GEN_SCHEDULING","Scheduling");

define("EST_GEN_SCHEDULEOPTS","Scheduling Options");
define("EST_GEN_SCHEDULEOPTSHLP1","Set Default Schedule Options");

define("EST_GEN_SEO", "SEO");
define("EST_GEN_SHOW", "Show");
define("EST_GEN_SUCCESS", "Success");
define("EST_GEN_SHOWALLUSERS", "Show All Users");

define("EST_GEN_UPDATED","Updated");
define("EST_GEN_UPDATEDWITH","Updated with");
define("EST_GEN_UPDATEREQ","Update Required");
define("EST_GEN_UPFOLDER","Upload folder");
define("EST_GEN_USERAVATAR", "User Avatar");
define("EST_GEN_USERCLASS","User Class");
define("EST_GEN_USER","User");
define("EST_GEN_USERS","Users");
define("EST_GEN_USERSASSIGNED", "Users Assigned");
define("EST_GEN_USERLIST","User List");
define("EST_GEN_USERSAVAIL", "Available Users");
define("EST_GEN_USERLOGIN", "User Login");
define("EST_GEN_VISIBLETO","Visible To");
define("EST_GEN_WRITABLE","writable");
define("EST_GEN_YOURSELF","Yourself");


define("EST_GRP_GROUPPLCH", "Main Floor, Second Floor, Exterior, etc");


define("EST_HINT_NONAGENTLISTINGS","Allow users to post their own properties to this website.");



//EST_HLPMNU_INIT13
define("EST_INST_FIRSTRUN1","It looks like this is the first time you are using this plugin. Every Listing requires an Agent with approptiate Login credentials. These credentials include being an Admin with access to the Estate Agency Plugin (which you do) as well as being assigned to one of new User Classes used by this plugin.");
define("EST_INST_FIRSTRUN2","It looks like you are a Main Admin, you have full access to this plugin. You can also create Agent Profiles for other Users of your website by assigning them to one or more of the following User Classes:");
define("EST_INST_FIRSTRUN3","Once this is done, return here to finish the initial setup by creating a new Agent Profile for yourself and other Agents you want to have access.");
define("EST_INST_FIRSTRUN4","Please ask the Main Admin to assign the appropriate User Class and create an Agent Profile for yourself based on your role within this website.");
define("EST_INST_FIRSTRUN5","Once the Main Admin has done this, you will then be able to access the Estate Agency Plugin.");

define("EST_INST_INITSETUP","Initial Setup");
define("EST_INST_LOOKFORADMWPERMS","Looking for Admins with permissions for this plugin");

define("EST_INST_FISRTAGENT","Added New Agent Profile for");
define("EST_INST_FISRTAGENTFAIL","This plugin was unable to find any Main Admin User Profiles and was not able to create default Agent Profiles for anyone");


define("EST_INST_SETCLASS1","This plugin uses different access levels to prevent users from changing data that does not belong to them. Any user assigned to these special user classes must also be at least a regular Admin with privilages that include the Estate plugin. It is possible to make any user an Admin and then grant access only to this plugin to keep them out of other sensitive areas of your website. Here are what users with these access levels can do:");

define("EST_INST_CLASS0","An Estate Agent can only Create, Edit, and Remove their own Listings and Edit their own Agent Profile");
define("EST_INST_CLASS1","An Estate Manager can Create, Edit, and Remove Listings and Agents within their Agency, and Edit their Agency Profile");
define("EST_INST_CLASS2","An Estate Admin can Create, Edit, & Remove Listings, Create and Edit Agencies, and Create, Edit, Remove Agents and Managers.");
define("EST_INST_CLASS3","Main Admins have full access to everything and are the only users who can assign your e107 website Users as Estate Admins.");

define("EST_INST_SETCLASS2","In addition to the above rules, Estate Admins and Estate Managers can create new User Profiles (Website Login detsails) directly within this  plugin and assign Users as an Estate Agent, Estate Manager, or Estate Admin, depending of the Access Level of the User who is creating the new Login.");

define("EST_INST_FIRSTTIME1","This is your first time using the Estate Plugin. Before you can start you need to create your own Agent Profile for yourself, even if you are not an actual Agent. You will be able to create additional Agent Profiles for other Users once this step is done.");
define("EST_INST_FIRSTTIME2","Keep in mind that all Agents have to be an Admin with permission to access the Estate Plugin and assigned to the appropriate User Class (Estate Agent for Agents, Estate Manager for Managers). Any user who is a Main Admin will have full control over this plugin.");

define("EST_INST_SETCLASS3","It looks like you are a Main Admin. When you click the Configure button you will be guided through the Initial Setup steps to create your first Agency and Agent Profile.");

define("EST_INST_SETCLASS4","Only a Main Admin can initialize this plugin. Please contact a Main Admin to finish the initial setup.");


define("EST_INST_SETUP1","Please create your first Agent Profile for yourself. Every Agent needs to be linked to a Company and Agency in order to access this plugin. You will be able to add other companies, agency locations, and agents later.");
define("EST_INST_SETUP2","(unknown define)");

define("EST_INST_NOPDTAIMP1","There are no other Companies from which you can import Zoning Categories and Preset Data. Click the Edit button next to the Zoning Filter above to add new Zoning Categories for your Company.");
define("EST_INST_NOPDTAIMP2","When you save your new Zoning Categories, new Data Presets will be automatically added to this form without needing to click the update button.");



define("EST_INST_AGENTS1","Sample Agents & Agencies added to Database. USERID #1 has been added as Agent 1 ");
define("EST_INST_AGENTS2","ERROR: Sample Agents & Agencies were NOT added to Database");

define("EST_INST_FEATURES1","Sample Listing Types, Features & Categories added to Database");
define("EST_INST_FEATURES2","ERROR: Sample Listing Types, Features & Categories were NOT added to Database");

define("EST_INST_STATES1","Sample US States added to Database");
define("EST_INST_STATES2","ERROR: Sample US States were NOT added to Database");

define("EST_INST_SAMPLEPROP1","Sample Property Listing added to Database");
define("EST_INST_SAMPLEPROP2","ERROR: Sample Property Listing was NOT added to Database");

define("EST_INST_SAMPLEMEDIA0","Failed to copy sample images to");
define("EST_INST_SAMPLEMEDIA1","Directory is NOT accessable");
define("EST_INST_SAMPLEMEDIA2","Media Database was not initialized");
define("EST_INST_SAMPLEMEDIA3","Some Sample Images Failed to copy over to the Media Folder");
define("EST_INST_SAMPLEMEDIA4"," File not found");
define("EST_INST_SAMPLEMEDIA5"," Not a File");
define("EST_INST_SAMPLEMEDIA10","Successfully copied sample images to the Media Folder");
define("EST_INST_SAMPLIST","A sample Listing with Images has been added for you to explore to see how this plugin works.");


define("EST_MYAGENTPROFILE","My Agent Profile");


define("EST_PLCH75","A little about me");

define("EST_PLCH85","My Company Info");
define("EST_PLCH95","eg. Main Office");
define("EST_PLCH97","Additional Agency Info");





define("EST_PREF_ADMINONLY","Close ".EST_PLUGNAME." to public");
define("EST_PREF_ADMINONLYHLP","If enabled, only Admins can view this plugin");



define("EST_PREF_CONTACTCC","Allow CC Sender*");
define("EST_PREF_CONTACTCCHLP","Users in this class can opt to have a copy of their email sent to their email address. MAY BE MISUSED, set with caution! REQUIRES your Site Prefs to allow Email Copy Sender.");
define("EST_PREF_CONTACTFORM","Contact Form Access");
define("EST_PREF_CONTACTFORMHLP","Who can use the Estate Plugin's Contact Form");
define("EST_PREF_CONTACTLIFE","Message Life");
define("EST_PREF_CONTACTLIFEHLP","How long the system keeps a record of sent messages. Works with Message Maximum to prevent abuse of the contact system");
define("EST_PREF_CONTACTMAX","Message Maximum");
define("EST_PREF_CONTACTMAXHLP","Sets the Maximum number of messages a visitor can send during the Message Life duration. Applies to All Messages");
define("EST_PREF_CONTACTMODE","Contact Form Mode");
define("EST_PREF_CONTACTMODE0","Agent & Non-Agent Listings - Hide e-mail");
define("EST_PREF_CONTACTMODE1","Agent & Non-Agent Listings - Display e-mail");
define("EST_PREF_CONTACTMODE2","Only Agent Listings - Hide e-mail");
define("EST_PREF_CONTACTMODE3","Only Agent Listings - Display e-mail");
define("EST_PREF_CONTACTMODEHLP","Choose which Listings will include a Contact Form and if the Agent/Seller e-mail address is displayed. Listings that do not include a Contact Form will always display a contacte-mail address");
define("EST_PREF_CONTACTNOTIFY","Agent/Seller Notifications");
define("EST_PREF_CONTACTNOTIFYHLP","Notify the Agent/Seller via email when someone uses the Contact Form or Saves a Listing. Agents/Sellers can always review activity on this website");
define("EST_PREF_CONTACTPHONEREQ","Require Phone Number");
define("EST_PREF_CONTACTPHONEREQHLP","If enabled, the Sender will be required to include their phone number");
define("EST_PREF_CONTACTMAXTO","Maximum Applies To");
define("EST_PREF_CONTACTMAXTO0","All Messages Sent");
define("EST_PREF_CONTACTMAXTO1","Messages Sent Per Property Listing");
define("EST_PREF_CONTACTMAXTO2","All Unread Messages");
define("EST_PREF_CONTACTMAXTO3","Unread Messages Sent Per Property Listing");
define("EST_PREF_CONTACTMAXTOHLP","Maximum Applies To");
define("EST_PREF_CONTACTPROPHLP","");
define("EST_PREF_CONTACTTERMS","Contact Terms");
define("EST_PREF_CONTACTTERMSHLP","The Terms a visitor must agree to to send a message through the Contact Form. If left blank, the default Terms written for this plugin will be used");



define("EST_PREF_DEFAGTHRS","Default Agent Hours");
define("EST_PREF_DEFAGTHRSHLP","The default hours for creating a New Agent. Each Agent can customize their own hours");
define("EST_PREF_DEFCURRENCYHLP","The default Currency selected when creating a New Listing");
define("EST_PREF_DEFCOUNTRYHLP","The default Country selected when creating a New Listing");
define("EST_PREF_DEFEVTLEN","Default Event Lengths");
define("EST_PREF_DEFEVTLENHLP","Set default Event Lengths when adding an event to a property's calendar. Each event can be customized");
define("EST_PREF_DEFHRSHINT0","First Event Start Time");
define("EST_PREF_DEFHRSHINT1","Last Event Start Time");
define("EST_PREF_DEFPUBHRS","Default Public Hours");
define("EST_PREF_DEFPUBHRSHLP","The default Public hours for creating a New Property Listing. Each Property Listing's hours can be customized");

define("EST_PREF_EVENTNAME","Event Name");
define("EST_PREF_EVENTLEN","Event Length");

define("EST_PREF_GALBG","Gallery Background");
define("EST_PREF_HELPINFULL","Expanded Help Menu");
define("EST_PREF_HELPINFULLHLP","If enabled, the Help Menu will always be shown in full height.");

define("EST_PREF_LAYOUT_NOLISTINGS","No Listings To Preview");
define("EST_PREF_LISTINGSAVE","Listing Saves");
define("EST_PREF_LISTINGSAVEHLP","Who can Save Listings on their Device (Uses cookies). ");
define("EST_PREF_LISTPAGESECT","Listing Page Layout");

define("EST_PREF_MAPLISTABOVE","Map Above Listings");
define("EST_PREF_MAPLISTBELOW","Map Below Listings");

define("EST_PREF_MAPAGENCY","Include Agency Pins");
define("EST_PREF_MAPAGENCYHLP","Include nearby Agency Pins in the Map on the View Page");
define("EST_PREF_MAPAGENCY_NO","No Agency Pins");
define("EST_PREF_MAPAGENCY_YES","Include Nearby Agency Pins");

define("EST_PREF_MAPACT1","List Page");
define("EST_PREF_MAPACT1HLP","Include the Map on the Listing Page");

define("EST_PREF_MAPACT2","View Page");
define("EST_PREF_MAPACT2HLP","Include the Map on each property's View page");

define("EST_PREF_MAPMAP_JSSRC","Source For Map Files");
define("EST_PREF_MAPMAP_JSSRCHLP","Choose how the Leaflet Javascript files are loaded. If external, these files are called from a remote server as defined below.");
define("EST_PREF_MAPMAP_JSSRCOPT0","Use Internal Map Files");
define("EST_PREF_MAPMAP_JSSRCOPT1","External Map Files");
define("EST_PREF_MAPKEY","Integrity Key");
define("EST_PREF_MAPKEYRESET","Reset Key");
define("EST_PREF_MAPKEYHLP","This key is provided by Leaflet and used to authenticate the cross-origin javascript files when your website uses the HTTPS protocal");
define("EST_PREF_MAPURL","Remote URL");
define("EST_PREF_MAPURLRESET","Reset URL");
define("EST_PREF_MAPURLHLP","This key is provided by Leaflet and used to authenticate the cross-origin javascript files when your website uses the HTTPS protocal");
define("EST_PREF_MULTICOMP","Multi-Company");
define("EST_PREF_MULTICOMPHLP","When Enabled you canlist Agencies from different Companies. When Disabled all Agencies belong to a single company");




define("EST_PREF_SLIDESHOWACT","Slideshow");
define("EST_PREF_SLIDESHOWACTHLP","If enabled, this will cycle through Pictures on the View Page, otherwise only the first picture will be shown.");

define("EST_PREF_SLIDESHOWDELAY","Start Delay");
define("EST_PREF_SLIDESHOWDELAYHLP","The time in Seconds before the Slideshow will start cycling though the slides");
define("EST_PREF_SLIDESHOWTIMING","Slide Duration");
define("EST_PREF_SLIDESHOWTIMINGHLP","The time in Seconds between slide changes");

define("EST_PREF_TEMPLATES", "Template Layouts");
define("EST_PREF_MENU", "Menu Options");


define("EST_PREF_TEMPLATE_LIST","List Page Template");
define("EST_PREF_TEMPLATE_LISTHLP","Choose a template for the List Page");


define("EST_PREF_TEMPLATE_VIEW","View Page Template");
define("EST_PREF_TEMPLATE_VIEWHLP","Choose a template for the View Page");





define("EST_HLP_FEATURES0","A categorized list of pre-defined features used for your listings");
define("EST_HLP_FEATURES1","The top level is the category (bedroom, batroom, etc) each having a subset of features associated with that category.");
define("EST_HLP_FEATURES2","Each Feature can have a list of Additional Options (eg. Refrigerator > Top Freezer, Bottom Freezer, Side by Side, Ice Maker, In door Water, etc). Click the Toggle next to the Feature to enable Options.");
define("EST_HLP_LISTTYPE0","Changing a Listing Type WILL affect existing Listings.");
define("EST_HLP_SPACESGRP0","Changing a Group Name WILL affect existing Listings.");


define("EST_TT_ADMPERMIS1","Grant access to the Estate Plugin Admin Area");
define("EST_TT_ADMPERMIS2","Make this user an Admin and grant access to this plugin?");
define("EST_TT_FRONTENDFORM","Grants access to front end Listings Form");

define("EST_HLPMNU_AGENTPROF01", "The Agent Profile extends the User Profile and is included with the agent's Listings.");
define("EST_HLPMNU_AGENTPROF02", "An Agent's Profile is also editable from within the Property Listing Form.");
define("EST_HLPMNU_AGENTPROF03", "As a new Agent you need to choose an Agency. Depending on the User Class assigned to you by the website Admin, you may or may not be able to change this after this step.");
define("EST_HLPMNU_AGENTPROF04", "You can change your Agency Assignment at any time, as well as re-assign other agents.");
define("EST_HLPMNU_AGENTPROF05", "You will need to contact an Agency Administrator to change your Agency assignment.");
//EST_HLP_CONTACTS01
define("EST_HLPMNU_AGENTPROF06","Hover your mouse over the Profile Image and click the Source Button to toggle between the User's Avatar or a custom image.");
define("EST_HLPMNU_AGENTPROF07","Any New Custom image will be uploaded when this form is saved.");
define("EST_HLPMNU_AGENTPROF08","Contacts Require a saved Agent Profile and are saved individually from this form.");
define("EST_HLPMNU_AGENTPROF09","Click the Label Button to edit the contact Type or choose from a list of existing Labels.");
define("EST_HLPMNU_AGENTPROF10","Current Contacts include Sort and Delete buttons. Click and drag the Sort button to reorder the contacts. The Delete button will change to a Save button if you change the contact.");

define("EST_HLPMNU_AGCY00","General Information about this agency.");
define("EST_HLPMNU_AGCY01","Hover your mouse over the Logo and click the Source Button to toggle between using this website's logo or uploading a custom Logo.");
define("EST_HLPMNU_AGCY02","You can choose to hide this Agency's profile page from public view. This is usefull for including Independent Agents where no Agency affiliation is desired.");
define("EST_HLPMNU_AGCY10","This is a list of Properties currently assigned to the Agency.");

define("EST_HLPMNU_AGCY20","Agency List");
define("EST_HLPMNU_AGCY21","This is a list of Agencies that you have permissions to access. Use the Dropdown at the top of the list to filter by location.");
define("EST_HLPMNU_AGCY22","button at the top of the list to add a new Agency.");
define("EST_HLPMNU_AGCY23","button for any Agency to change its public Visibility");

define("EST_HLPMNU_CONTACTS01","Click the Label button to choose the Contact Type or create a custom label.");
define("EST_HLPMNU_CONTACTS02","Click the Save button to update the contact. New Contacts will be added to the main list, which can then be sorted by dragging the Up/Down arrows.");

define("EST_HLPMNU_COMMUNITY1","This Tab contains information about the Property's area and the responsibilities the homeowner has to that area as well as amenities.");
define("EST_HLPMNU_COMMUNITY2","Choose a Subdivision to auto-load details about the property's Community. All data on the Property's Community tab is saved with the individual Property.");
define("EST_HLPMNU_COMMUNITY3","Click the Subdivision Edit button to Add/Edit this preset data along with other information about the Subdivision. The data in the Subdivision Sub-Form is saved seperately from the Property data and will not affect other Properties' Community data.");
define("EST_HLPMNU_COMMUNITY4","That said, the Sub-Form also saves information for the Subdivision that is outside the scope of an individual Property, including common ammenities and its own photo gallery, which will be shown with all Properties in that Subdivision.");



define("EST_HLPMNU_DETAILS1","Add general details about the property, including Model Name (optional), Year Built, a brief Summary, and Detailed Description.");
define("EST_HLPMNU_DETAILS2","Clicking on [i]Livable Space[/i] or [i]Roof Size[/i] fields reveals a button with an automatic value calculated from the square footage/meeter of all Spaces. Click this button to set the value in the field.");
define("EST_HLPMNU_DETAILS3","The [i]Livable Space[/i] and [i]Roof Size[/i] fields include a button to choose the unit of measure (Sq Foot or Sq Meeter). These are tied together and changing one will change the other");
define("EST_HLPMNU_DETAILS4","This option also sets the default setting when adding a new Space, though you can set a different measure for each individual Space.");
define("EST_HLPMNU_DETAILS5","[i]Land Size[/i]. Optional. A button next to the field allows you to set the unit of measure. Clicking on the field will reveal options to append ¼, ½, and ¾ to the value in the field.");
define("EST_HLPMNU_DETAILS6","The [i]Summary[/i] field has a limit of 255 characters, including Spaces and Punctuation. This text is used in both the [i]Browse Properties[/i] and [i]Property View[/i] Pages.");
define("EST_HLPMNU_DETAILS7","The [i]Description[/i] field is used only in the [i]Property View[/i] Page, which includes the text in the [i]Summary[/i] field as a preface.");
define("EST_HLPMNU_DETAILS8","This field has unlimited characters and retains paragraph separation. Standard e107 BB Code is allowed, such as ");
define("EST_HLPMNU_DETAILS9a","bold text");
define("EST_HLPMNU_DETAILS9b","italic text");
define("EST_HLPMNU_DETAILS9c","underline");
define("EST_HLPMNU_DETAILS9d","etc.");

define("EST_HLPMNU_GALLERY1","Add, edit, or remove Media for the Main Gallery of this property.");
define("EST_HLPMNU_GALLERY2","Click the Upload Media button to Add Media - NOTE: Media uploaded directly from the Gallery Tab is separate from other Sections of the Property Listing.");
define("EST_HLPMNU_GALLERY3","The [b]Available Media[/b] section lists all Media for this property that is NOT included in the main gallery.");

define("EST_HLPMNU_GALLERY4","Drag & Drop Media from [b]Available Media[/b] to [b]Media In Use[/b] to build the Property's Media Gallery. ");

define("EST_HLPMNU_GALLERY5","The [b]Media In Use[/b] section lists all media included in the Main Gallery");
define("EST_HLPMNU_GALLERY6","Drag & Drop Media within the [b]Media In Use[/b] section to change their order in the Main Gallery, or drag to the [b]Available Media[/b] section to remove it from the Main Gallery. This order is automatically saved.");


define("EST_HLPMNU_INIT5","Create your first Agent Profile for yourself, even if you are not a participating Agent.");
define("EST_HLPMNU_INIT6","All Agents are attached to an Agency.");
define("EST_HLPMNU_INIT7","Even if you are using this plugin just for yourself as a single Agent, you will still need to create a new Agency profile. You can choose what information is displayed to the public.");
define("EST_HLPMNU_INIT10","Choose what contact options are available for the agent. This information will be visible to the public.");
define("EST_HLPMNU_INIT11","The same options are available for Company and Agency Profiles and information can [i]bubble[/i] down from Company and Agency. If an Agent's Office Phone number is left blank or not included, this plugin will display the Office Phone number for the Agency or Company, if available.");
define("EST_HLPMNU_INIT12","The default Website for a new Agent, Agency, or Company is this website, followed by a special ShortCode for the corresponding section. You can change this to a Custom Page or an external website. Click the Sync button to restore to the default.");
define("EST_HLPMNU_INIT14","(removed)");
define("EST_HLPMNU_INIT15","Like the Agent's Profile Image, the default company logo defaults to the website logo. Hover your mouse over the Company Logo and click the Source Button to upload a custom Company Logo.");
define("EST_HLPMNU_INIT16","Every Company needs to have at least one Agency to which Agents belong. You can have a single Agency (eg. Main Office) or have multiple Agencies (locations), and Agents can be designated to a specific Agency or set to All Agencies. Admins with the User Class Estate Manager can Add, Edit, or Remove Agencies, Agents, and Listings.");
define("EST_HLPMNU_INIT17","The Agency Logo defaults to whatever the Company Logo has been set to. Hover your mouse over the Agency Logo and click the Source Button to upload a custom Agency Logo.");
define("EST_HLPMNU_INIT18","You can choose to include any Agency's location on the vairous Maps displayed either on the Listings Main Page and/or each individual Property Listing Page.");

define("EST_HLPMNU_LISTING1","Add general information about this property's listing, including a unique Listing Name.");
define("EST_HLPMNU_LISTING2","Most Select Dropdown Lists thoughout this plugin include an Edit Button which allows you to quickly Add and Edit Options for that Dropdown.");
define("EST_HLPMNU_LISTING3","Many Dropdown Lists may also filter options in other Dropdown Lists, and therfore may require a selected value before you can Select, Add, or Edit them.");
define("EST_HLPMNU_LISTING4","Required: [i]Zoning Category[/i] selector sets the Primary Category for the Property and is used to filter many other options available in this plugin's forms, such as the [i]Property Type[/i] selector below it.");
define("EST_HLPMNU_LISTING5","Required: The [i]Property Type[/i] selector further categorizes the Property and sets addtional filters used thoughout this plugin's forms.");

define("EST_HLPMNU_PREF_TEMPLATES01","Set options for the List and View Page Templates");
define("EST_HLPMNU_PREF_MENU01","Set Options for the Estate Menu");

define("EST_HLPMNU_PRESETS30","These are lists of preset data used in your listings. You can add & edit this information directly in the Listing Form, too.");
define("EST_HLPMNU_PRESETS33","This populates a drop-down list on the Property Listing Form used to define what type of property it is (Single Family, Apartment, Condo, etc).");
define("EST_HLPMNU_PRESETS34","Spaces Groups are used to group Spaces¹ on the Listing View page");
define("EST_HLPMNU_PRESETZONESEL","Select a Zoning Category to edit the asociated data. Click the Edit Button to add or edit Zoning Categories");
define("EST_HLPMNU_PROPLIST1","Welcome to the Estate Property Listings Plugin for the e107 Website System.");
define("EST_HLPMNU_PROPLIST2","Watch this space for help with each section of the Create/Edit modes.");
define("EST_HLPMNU_PROPLIST3","Listing Page");
define("EST_HLPMNU_PROPLIST4","This page is a listing of all properties that you can access. Click the [+] button above the list to add a new property, or click the edit icon of any listing to edit that property.");
define("EST_HLPMNU_PROPLIST5","By default, properties are sorted by Listing Date, newest first. Click any column headding to sort by that column's values. Use the Filter options to filter the list if needed.");
define("EST_HLPMNU_PROPLIST6","Thumbnails");
define("EST_HLPMNU_PROPLIST7","Thumbnails are cached by your browser and may display in incorrect image if you have updated the file. Click any thumbnail in the list to reload a fresh version of the image.");
define("EST_HLPMNU_PROPLIST8","Double-Click any thumbnail to choose a different thumbnail for this Property");

define("EST_HLPMNU_PROPLIST9","Start a new listing by adding some basic information and clicking the \"Save & Continue\" button. ");
define("EST_HLPMNU_PROPLIST9a","You will have the opportunity to change this data in the next step, including adding new options to drop-down selections.");



define("EST_HLPMNU_SCHED0","The Scheduling system automates the publication of this listing when the Status is set to Active. If the Status is set to anything else, then these dates are ignored.");
define("EST_HLPMNU_SCHED5","The Events table lists the Property's Events schecule.");
define("EST_HLPMNU_SCHED6","This is where you can set up dates and times for Private Showings and Open House Events ");
define("EST_HLPMNU_SEO1","Search Engine Optimization Options");
define("EST_HLPMNU_SPACES1","Spaces can be anything. A Bedroom, a Garage, a Patio, even a Common Area in a development.");
define("EST_HLPMNU_SPACES2","Spaces are divided into Custom Groups that define sections of the Property's display page. One group might be Main Floor, another group might be Back Yard or whatever. You get to create Groups when you add a new Space.");
define("EST_HLPMNU_SPACES3","Drag & Drop Spaces and Groups using the Up/Down Icons to change their order, which is saved automatically. You can even re-assign Spaces by dragging them to another Group.");
define("EST_HLPMNU_SPACES4","Groups are saved for each individual property, but are also saved for use in any other property with the same Zoning Type.");
define("EST_HLPMNU_SPACES5","Each space is further defined by assigning it to a custom Category. Like Groups, Categories are saved for use in other Spaces and Property Listings with the same Zoning Type.");
define("EST_HLPMNU_SPACES6","Each space has a list of Features that you can populate with details about that space. These Features are keyed to the Space's Category for use in other Spaces with the same Category, and saved across Property Listings with the same Zoning Type.");
define("EST_HLPMNU_SPACES7","Media can be added to each Space and will be included a mini-gallery. Media added here will also be available to add to the Main Gallery for the Property Listing.");
define("EST_HLPMNU_SPACES8","Like the Main Gallery, Media will be displayed in the mini-gallery in the same order as they appear in the list. Drag & Drop the Thumbnails to rearrange their order.");

define("EST_HLPMNU_USERS00", "A list of all Members of your website, including Yourself, other Admins, and all other Members.");
define("EST_HLPMNU_USERS01", "Grants access to the Admin Area of this plugin. This establishes the User as an Agent of your company.");
define("EST_HLPMNU_USERS02", "<i>Always Checked.</i> Grant Access to the Listings Form from the Front End area of your website.");
define("EST_HLPMNU_USERS03a", "The Estate Preferences currently DO NOT allow Non admin users to post their own listings.");
define("EST_HLPMNU_USERS03b", "The Estate Preferences allows Non admin users to post their own listings without accessing the Admin Area of this website.");
define("EST_HLPMNU_USERS04", "Sets the Access Level of an Admin with Permission to access the Admin Area of this plugin by assigning a special User Class.");
define("EST_HLPMNU_USERS05", "Your own Access Level determines what Level you can set for other users and who you can set them for. ");

define("EST_HLPMNU_USERS07", "A list of Agents currently assigned to this Agency.");
define("EST_HLPMNU_USERS08", "You can add new Agents to this Agency by choosing users from the Non-Admin table.");

define("EST_HLPMNU_USERS11", "Main Admins cannot be changed. Un-checking the Admin Area Access box will remove permission to access the Admin Area of this plugin. If the User is an Admin with permissions for other areas of this website, their Admin Status will remain, otherwise their status as an Admin will be removed.");

define("EST_HLPMNU_USERS20", "Use this form to quickly add a New User and create a new Agent Profile.");
define("EST_HLPMNU_USERS21", "Users added via this form will automatically be made an Admin with Privilages for this Plugin and granted the Estate Agent access level by default.");
define("EST_HLPMNU_USERS22", "Some items for User Data and Agent Data are combined on this form, such as User Display Name and Agent Name.");
define("EST_HLPMNU_USERS23", "You can upload a Profile image that will beadded as that user's e107 User Avatar. A custom Agent Profile image can also be added once the neew user has been created.");




define("EST_INSTADDEDMAINADMIN","has been assigned as an Estate Admin");


define("EST_INSTSETUCLEVOK","Visibility and Management settings MUST BE set to MAIN ADMIN for this plugin's special User Classes. If this is not done, users will be able to set their own access level for themselves on their User Settings page. Setup has attempted to do this for you, results should be listed below.");
define("EST_INSTSETUCLEVNOK1","There were some errors setting the new User Classes");
define("EST_INSTSETUCLEVNOK2","Please go to the User Classes page and set the Visibility and Management settings to Main Admin for ESTATE ADMIN, ESTATE MANAGER, and ESTATE AGENT");

define("EST_INSTRNEXT1","Clicking the button below will take you to your Agent Profile. Be sure to review the Estate Preferences and Agency Locations and visit the Users List to assign members of your website as Estate Agents, Estate Managers, and Estate Admins.");
define("EST_INSTRDONE","Get Started");
define("EST_INSTRDONE1","Start Using the Estate plugin");
