# NamelessMC v2 Changelog

## [Unreleased](https://github.com/NamelessMC/Nameless/compare/v2.0.0...v2)
> [Milestone](https://github.com/NamelessMC/Nameless/milestone/18)


## [2.0.0](https://github.com/NamelessMC/Nameless/compare/v2.0.0-pr13...v2) - 2022-08-05
> [Milestone](https://github.com/NamelessMC/Nameless/milestone/17)

### Added
- Translation updates
- Add info about OAuth providers to debug link
- Respect light/dark mode for captcha popups
- Added `ProfileUtils::getOfflineModeUuid()` & `ProfileUtils::formatUuid()`
- Added `discordWebhookFormatter` event so modules can define how a Discord webhook embed looks
- Add `userGroupAdded` and `userGroupRemoved` events to Discord webhook formatter
- Block banned IPs from registering

### Changed
- Better Discord widget error display
- Light/dark mode for StaffCP statistics graph
- Hide StaffCP stats on mobile
- Renamed `OAuth` class to `NamelessOAuth` to avoid conflicts with OAuth PHP extension
- Add warning to StaffCP dashboard when HTTPs is misconfigured
- Add warnings for old versions of MySQL/MariaDB
- Track avatar validity HTTP requests in debug bar
- Only track queries when debug mode is enabled, this gives performance increases of ~15%
- Allow server to send correct Minecraft verify command
- Use `return [...` in config files instead of `$conf = [...`
  - Backwards compatibility will be removed in 2.0.1 to ensure all sites have been updated
- Use case insensitive header handling
- "OR" in login page is no longer hardcoded
- Better TinyMCE image uploading errors + logging
- Add form token to announcement order change
- Captcha key failure will still allow values in DB to be updated, incase the test request fails
- Support 2FA with OAuth2 logging in
- Only show source code option in staffcp pages in text editor

### Fixed
- Fix MariaDB version detection
- Fix editing topic title not showing up
- Fix MC banner image listing
- Fix installer failing when migrations were silently not run
- Fix `nl2_users_integrations.username` column not being nullable
- Include `CGIPassAuth On` in the default `.htaccess` file
- Fix MCAssoc file path
- Fix exception when cloning a group
- Fix error when `nl2_mc_servers.bedrock` is null
- Fix `Util::determineOrder()` for empty arrays
- Fix long sentences in statistic widget not going onto a new line
- Fix maintenance mode message not appearing
- Fix DefaultRevamp dropdown not respecting light/dark mode
- Fix edge case exception when no `$_active_page` is set in `Pages` class
- Fix issue with `Forum::getLatestDiscussions()` returning an array of objects randomly
- Fix missing `ROOT_PATH` definition in Phinx migration file
- Fix exception when captcha provider is null
- Fix exception when `nl2_email_errors.user_id` is null while viewing logs in StaffCP
- Fix forum label types page formatting in StaffCP
- Fix debug link modal still being rendered on exception page when they don't have permission to generate debug link
- Fix editing posts strict comparision which prevented users from editing their own post sometimes
- Fix avatar related exception on maintenance mode page
- Fix `Timeago` values being globally cached for all users on news posts
- Use `.png` instead of `.svg` for default fallback avatar links
  - Using `.svg` would break on Discord embeds
- Fix forum signature overflow
- Fix Oauth login when maintenance mode is enabled
- Fix `createAnnouncement` webhook
- Don't cast Discord snowflakes to integers (would break on 32bit systems)
- Fix `AssetResolver` adding an asset more than once if several assets depend on it
- Fix TinyMCE spoiler plugin not being added properly if a custom path is set
- Fix error when creating group sync rules with only 1 external service
- Fix website -> Discord group sync issues
- Fix /rewrite_test not being accessible during installation
- Fix exception when no user tries to login with OAuth and no Nameless user is linked
- Fix some special chars in DB/email password getting escaped
- Fix forum post avatars not matching rest of site
- Fix Twitter and Facebook widget
- Fix Discord role IDs length
- Fix issues converting groups and reports from v1
- Handle OAuth2 errors properly during login/registration
- Fix exception when trying to login with non-existent oauth account
- Add slashes to saved config values
  - Passwords with slashes would escape the strings

### Deprecated
*These will be removed in 2.1.0*
- The entire `Queries` class has been deprecated, read each of the methods' documentation for more information
- Using `Config::get('path/to/value')` syntax is deprecated. Use `Config::get('path.to.value')` instead
- `Hash::unique()`, use `SecureRandom::alphanumeric()` instead
- `Pages->getActivePage()` is deprecated, no alternative was created
- `Paginator->setValues()` is deprecated, set the values within the constructor instead
- `User->getGroupClass()` is deprecated, use `User->getGroupStyle()` instead
- `DB->selectQuery()` is deprecated, use `DB->query()` instead
- `Util::isTrustedProxiesConfigured()` is deprecated, use `HttpUtils::isTrustedProxiesConfigured()` instead
- `Util::getTrustedProxies()` is deprecated, use `HttpUtils::getTrustedProxies()` instead
- `Util::getRemoteAddress()` is deprecated, use `HttpUtils::getRemoteAddress()` instead
- `Util::getProtocol()` is deprecated, use `HttpUtils::getProtocol()` instead
- `Util::getPort()` is deprecated, use `HttpUtils::getPort()` instead
- `Util::isExternalURL()` is deprecated, use `URL::isExternalURL()` instead
- `Util::getSelfURL()` is deprecated, use `URL::getSelfURL()` instead
- `Util::replaceAnchorsWithText()` is deprecated, use `URL::replaceAnchorsWithText()` instead
- `Util::stringToURL()` is deprecated, use native `urlencode()` for better non-latin support
- `Util::truncate()` is deprecated, use `Text::truncate()` instead
- `Util::renderEmojis()` is deprecated, use `Text::renderEmojis()` instead
- `Util::bold()` is deprecated, use `Text::bold()` instead

### Breaking Changes
- "Advanced" event listeners have been removed.


## [2.0.0 pr-13](https://github.com/NamelessMC/Nameless/compare/v2.0.0-pr12...v2.0.0-pr13) - 2022-06-04
> [Milestone](https://github.com/NamelessMC/Nameless/milestone/14)

### Added
- Discord + Google OAuth support
- New user verification system
- New cookie consent module
- New hook system for forum/private message post rendering, including better codeblock + mentions support
- Automatic user language detection
- Custom date format support
- Webhook for reports, announcements, bans, warnings
- API additions including new ban user endpoint

### Changed
- Contact page removed
- Update to Font Awesome 6
- Use Guzzle for HTTP requests
- Migration to Composer + Yarn
- Database migration system + new upgrade class
- Improved database indexing
- Show unsupported banner to Internet Explorer users
- ListUsers API endpoint pagination
- Replace API error codes with namespaced strings
- Removed CKEditor

### Fixed
- PHP 8 improvements
- Fix issue using certain characters in URLs
- Frontend and backend template updates
- Many more bugfixes


## [2.0.0 pr-12](https://github.com/NamelessMC/Nameless/compare/v2.0.0-pr11...v2.0.0-pr12) - 2021-09-10
> [Milestone](https://github.com/NamelessMC/Nameless/milestone/16)

### Added
- Add Thai translation
- Add new administrator permission for full access
- Add new forum settings page including link location option
- Add ability to log in with either username or email
- Allow disabling placeholders feature
- Add notice to StaffCP update tab if not using PHP 7.4+
- Add per-user dark mode toggle
- Add `reported_username` and `reported_uid` fields to createReport endpoint

### Changed
- Increase max length of custom pages URL + title
- Convert many GET requests to POST
- Discord sync rework
- In-game sync rework
- Remove legacy X-UA-Compatible header
- API verification command renamed from `/validate` to `/verify`
- Make hook fire even if no hooks are there
- Remove password length limit
- Translation updates
- Default panel template dark mode improvements
- Default Revamp dark mode improvements
- Convert forum queries to DB query()

### Fixed
- Display non-alphanumeric characters in forum URLs
- General placeholder fixes
- General Authme fixes
- Fix legacy panel template update alert style
- Only send password reset email if user is already active
- PHPMailer autoloader PHP 8 compatibility
- Fix warning modal not showing
- Prevent IP banning yourself
- Prevent showing validate message when API verification is disabled
- Update strings for Discord slash commands
- Change installer DB password field to password type
- Fix latest profile posts link
- Fix duplicate `rel=""` attribute when using `rel="preload"`
- Only clear 2fa state when not on 404 page
- Fix StaffCP redirect after re-authentication
- Exclude `.DS_Store` files from endpoint scan
- PHP 8 improvements


## [2.0.0 pr-11](https://github.com/NamelessMC/Nameless/compare/v2.0.0-pr10...v2.0.0-pr11) - 2021-06-27
> [Milestone](https://github.com/NamelessMC/Nameless/milestone/15)

### Added
- Add Danish translation
- Add support for upward dropdowns in Default Revamp template
- Add Default Revamp dark mode support for top attached headers
- Add setting to initialise charset
- Add environment variables to hide options in general configuration
- Add method to set multiple config values at once

### Changed
- Validate email port before updating config in StaffCP
- Remove extra column from nl2_query_results
- Change placeholder tables primary keys
- Improve placeholder error message
- Translation updates
- Add index on `nl2_posts.topic_id` column

### Fixed
- Fix StaffCP Minecraft integration section
- Fix invalid language during installation
- Add SSL check before activating secure cookies
- Fix missing favicons directory
- Fix invalid token with AuthMe login
- Fix profile settings when forum is disabled


## [2.0.0 pr-10](https://github.com/NamelessMC/Nameless/compare/v2.0.0-pr9...v2.0.0-pr10) - 2021-08-25
> [Milestone](https://github.com/NamelessMC/Nameless/milestone/13)

- Add ability to upload favicon
- Add Smarty security policy
- Add support for environment variable based debugging
- Auto detect user timezone when registering
- Avatar rework and add Crafthead support
- Captcha rework including Recaptcha v3 invisible support
- Ensure cookie_secure is set in PHP config
- Error handling revamp
- Increase label HTML length
- Move captcha/https/www configuration to config.php
- Placeholder API integration
- Remove unsafe tags when editing posts
- Validation revamp
- API updates - including group sync fixes
- Default Revamp and Default panel template fixes
- htaccess fixes and improvements
- MCAssoc fixes
- Server status widget fixes
- Translation updates
- PHP 8 improvements


## [2.0.0 pr-9](https://github.com/NamelessMC/Nameless/compare/v2.0.0-pr8...v2.0.0-pr9) - 2020-12-30
> [Milestone](https://github.com/NamelessMC/Nameless/milestone/12)

### Added
- Add new panel template
- Add Default Revamp settings including dark mode
- Add player heads to server status widget
- Add ability to re-order servers in play tab
- Add announcement HTML support
- Add ability to search for users by email address in StaffCP
- Add ability to resend validation email
- Add ability to set default labels for forums
- Add option to use Gravatar
- Add concept of module dependencies
- Add getActivePage method
- Add new SEO page including Google Analytics integration
- Add drag ordering for groups and Minecraft servers
- Translation updates

### Changed
- Make groups field in serverInfo endpoint optional

### Fixed
- Fix multibyte character string length checks
- Fix widgets intended for homepage showing on all pages
- Fix validateUser hook sending empty Discord message
- Fix API endpoints
- Fix latest profile posts widget profile links
- Fix API URL adding two http prefixes
- Fix StaffCP Registration settings not submitting
- Fix webhooks listing twice
- Fix API URL in StaffCP API tab having leading/trailing whitespace
- Fix `group_id` column error upon registration and AuthMe linking
- Fix StaffCP sidebar link conflicts
- Fix error if user has no group
- PHP 8 improvements


## [2.0.0 pr-8](https://github.com/NamelessMC/Nameless/compare/v2.0.0-pr7...v2.0.0-pr8) - 2020-12-25
> [Milestone](https://github.com/NamelessMC/Nameless/milestone/4)

### Added
- Add Discord rank sync
- Add Chinese (Simplified), French, Russian, Spanish (ES) and Turkish translations
- Update existing translations
- New user/group systems - mostly backend changes
- API rework - modules can now register custom endpoints
- New widget location - can choose between left or right hand side
- Add basic custom page feature - no navbar/footer
- Add ability to open custom page in new tab
- Add "topics following" page
- Add email content editor
- Add mass emailing
- Add ability to receive emails for followed topic updates
- Add required custom profile fields to registration page
- Add announcement system
- Add ability to specify rel, as, onload attributes for CSS and defer, async attributes for JS
- Add server status widget
- Add latest profile post widget
- Add ability to change "More" dropdown message
- Add force 2FA option
- Multiple webhook support
- Add highlighting to profile post links
- Add ability to add CSS to group usernames
- Allow specifying "Can view other users' topics" forum permission for guests
- Add hCaptcha

### Changed
- Remove legacy v1 API
- Remove old Default template
- New installer
- Update StaffCP users list table to be async
- Discord topic webhook improvements
- Remove dropdowns from DefaultRevamp template mobile sidebar
- Start PHP 8 compatibility - still a work in progress

### Fixed
- Many bugfixes
- Fix same-site redirect forums showing redirect warning
- Fix MCAssoc integration


## [2.0.0 pr-7](https://github.com/NamelessMC/Nameless/compare/v2.0.0-pr6...v2.0.0-pr7) - 2020-01-05

### Added
- Add Italian translation
- Add support for custom plural forms in languages
- Add API method to return list of usernames + their UUIDs
- Add reCAPTCHA to login screen
- Add 403 page
- Add more variables to Statistics widget
- Add ability for template to define a settings page
- Add per-user template support
- Add login link to maintenance mode page
- Add view all panel templates link variable
- Add support for Twitter cards
- Allow changing group username colour from text input
- Add ability to use multiple webhooks
- Add last seen, online to view topic and subforum descriptions to view forum

### Changed
- Update Czech translation
- Update Dutch translation
- Update German translation
- Update Lithuanian translation
- Update Norwegian translation
- Update Polish translation
- Update Portuguese translation
- Update Romanian translation
- Update Spanish translation
- Default Revamp template CSS tweaks
- Remove unused condition in Default Revamp navbar template file
- Add missing panel template creation to updater
- Navbar icons updated to Font Awesome 5
- Default Revamp navbar updates
- Update Default Revamp remember me label
- Update Default template thumbtack icon
- Update Discord widget description
- Switch Default Revamp authme templates around
- Remove API debug line
- Switch registration validation around
- Hide staff groups for "post validation group"
- Allow redirect custom pages to be accessed directly
- Allow use of forgot password in maintenance mode
- Editor updates
- Allow editing root user's secondary groups
- Assign logged-in user title
- Add www to getSelfURL if force www is enabled and www is not in hostname
- Add multiple groups to the profile page
- Change topic cancellation buttons to yes/no
- Remove Default Revamp template credits
- Redirect to StaffCP Forums overview when updating a forum
- Add categories to move topic dropdown
- Change API URL to non-friendly URL
- Add UUID to forum post variables
- Remove old code

### Fixed
- Fix search bar showing in wrong place if no topics in forum
- Fix Default Revamp delete post button
- Fix Default Revamp delete profile post button
- Fix broken topic breadcrumb link
- Fix incorrect last online date for users registered through the legacy v1 API
- Remove : appearing after server IP if port is empty
- Prevent infinite parent forum configuration loop
- Fix plural form not working in some cases for time phrases
- Fix Default template broken edit button icon
- Fix pre setup widgets and banner on new installations
- Fix Default Revamp alerts page title
- Fix recaptcha not showing in Default Revamp template
- Fix latest posts sometimes showing duplicates
- Fix timezones for reports
- Add terms + conditions to complete signup
- Fix guests not being able to view redirect pages directly
- Add banned and active to listUsers response
- Prevent editor modifying inserted URLs automatically
- Fix non-friendly URL sitemap generation
- Fix widget configuration alignment and text
- Prevent fatal error if Mojang API query returns null
- Bring forum search in line with forum title
- Add option to display IP address for each server on status page
- Fixed fids and gids being too small in forum_topic_labels table
- Add support for Twitter cards in Default Revamp template
- Inline item content in online staff widget
- Make avatar clickable in view topic template
- Don't show topic count/latest topic info for redirect forums
- Include subforums in move topic dropdown
- Fix MentionsParser
- Remove avatars with different extensions and add timestamp to prevent caching issues
- Escape avatar updated variable just in case
- Fix default group potentially breaking when creating a new group
- Fix API verification issue
- Prevent re-querying for permission checks
- Fix Default Theme nav style issue
- Fix names with special characters having no avatar
- Sort groups in order
- Add int check to DB Custom bind param
- Allow explicitly setting pagination values
- Only show email notice in StaffCP with permission
- Stop cookie notice automatically closing
- Fix More dropdown only showing if a custom page is adding to it
- Prevent fatal error with insufficient forum permissions


## [2.0.0 pr-6](https://github.com/NamelessMC/Nameless/compare/v2.0.0-pr5...v2.0.0-pr6) - 2019-03-10

### Added
- Add Lithuanian and Polish languages
- Allow popover hover in StaffCP
- Add forum drag reordering
- Add subforum indentations in StaffCP
- Add $template object to all loadPage calls
- Add topic + post counts as global variables
- Add topic label to news posts
- New default template
- Add installer converter support
- Add ability to switch panel template
- Add ability to upload custom profile banners
- Add "Show nickname instead of username" option to online users widget
- Add statistics widget
- Add ability to upload + choose new homepage banner
- Add user title variable to online staff + users widgets
- Add author IDs to messaging template variables

### Changed
- Translation updates
- Update nav ordering
- Rename UserCP to Account
- Add indentation to panel sidebar child links
- Shorten Debugging + Maintenance in sidebar to Maintenance
- Update user list date sort
- Change exif to full requirement
- Improve registration nickname label
- Improve login username label
- Auto enable some widgets on installation
- Change front end editor

### Fixed
- Fix panel user edit Markdown issue
- Fixed install language
- Fix v1 upgrader issue
- Fix apostrophes not showing when editing posts
- Add timeout to dashboard player count graph
- Fix sidebar links not expanding on iOS
- Fix terms page footer
- Fix error when deleting user
- Fix users not being promoted after validating
- Fix users table width sometimes being too large
- Fix post validation group not updating
- Update login username title if Minecraft is disabled
- Show normal avatar if Minecraft is disabled on profile pages
- Fix non-friendly URL subfolder login redirect
- Replace &nbsp and &bull in Discord hook
- Fix post link potentially being incorrect
- Fix nickname not updating in StaffCP if disabled
- Move MCAssoc JS to after include
- Fix disabling reactions
- Fix custom page editor not having Source tab
- Fix registration username required message in some situations
- Use generic Minecraft query exception
- Fix date issue when updating forum latest post/topic
- Prevent editing own secondary groups without permission
- Don't save last page if maintenance mode is disabled
- Don't set register page as last page
- Fix subdirectory profile page banner issue
- Fix online users widget issue
- Fix registration nicknames not working
- Add topic placeholders
- Use new external server query
- Fix user punishment alert
- Remove config path issues on user settings page
- Fix navigation error
- Fix rewrite test in installer
- Add forum title variable to view topic template
- Fix error when replying to a topic that at least 1 user is following
- Remove unneeded panel template files
- Update CKEditor
- Update getSelfURL to detect HTTPS more reliably
- Order parent forum dropdown
- Allow switching a forum between forum + category
- Fix broken icon
- Fix incorrect last online date for users registered through the API
- Only show PM suggested user list after 3 characters typed


## [2.0.0 pr-5](https://github.com/NamelessMC/Nameless/compare/v2.0.0-pr4...v2.0.0-pr5) - 2018-10-08

### Added
- New staff panel
- RTL language support
- API rank sync
- Full multi language alert support
- Follow topics
- Click-to-copy IP
- Sitemap generator
- Ability for templates to toggle editor dark mode
- Other minor additions
- Minecraft server status page
- Navbar + forum icons
- User popovers

### Changed
- Template + module system changes
- Translation updates

### Fixed
- Many bugfixes
- Auto close spoilers


## [2.0.0 pr-4](https://github.com/NamelessMC/Nameless/compare/v2.0.0-pr3...v2.0.0-pr4) - 2018-05-30

### Added
- Update + add new translations
- Add new core permission system
- Add missing permissions to installer update script
- Add functionality to "Can view other users' topics?" permissions
- Add reCAPTCHA configuration
- Add default 25565 port to banner query
- Add user avatar to navbar
- Add stringToURL function to Util class
- Add view count to profile pages
- Add private profiles
- Bypass private profile permission in Users & Groups
- Allow a custom message when registration is disabled
- Add redirect forums
- Add option to change MySQL storage engine
- Add author's group to latest announcements
- Add default server IP variable to index page
- Add permission to nicknames
- Allow viewing sticky topics in only view own topic forums
- Add email field to contact form
- Allow custom HTML lang attribute on 404 pages
- Add forum post graph to UserCP
- Add custom error handler
- Add cookie notice
- Update log class
- Add online staff + online members widgets
- Add privacy policy page + default policy
- Add card to view topic template
- Add example v2 nginx config
- Add option to test and set friendly URLs in installer
- Add missing editor emoji file

### Changed
- Update route check
- Update DataTables
- Allow spaces in alphanumeric check
- Prevent changing own group in AdminCP -> Users tab without permission
- Update default template to Bootstrap 4 beta 2
- Add `.idea` to gitignore
- Automatically remove template if it doesn't exist
- Update AdminCP users private profile query
- Update external query API
- Display dropzone debugging info in console
- Update AdminCP user creation
- Default request scheme to http if not set
- Add root path constant to all file includes
- Update AdminCP modules file check
- Update installer login
- Allow templates to add <style> tags to head
- Show all groups in post validation group dropdown
- Update getLatestNews function
- Update module installation
- Update editor emojis
- Update 404/error back buttons to work on Safari
- Check for mysql/mysqlnd PHP extensions
- Add all user groups to Smarty variable on profile page
- Optimise images
- Update nginx example config

### Fixed
- Fix ' in database details not being entered into config correctly
- Remove non-alphanumeric characters from forum URLs
- Fix subforums not loading
- Fix image overflow in homepage news posts
- Fix issues with Minecraft banner MOTD colours
- Fix AdminCP overview chart issue
- Add line break between forum posts
- Add missing Javascript files to template
- Fix incorrect last reply in forum index
- Fix broken pagination in forum view
- Fix Discord widget size
- Add legacy v1 API
- Add 6h, 12h, 1d, 15d to graph options
- Set port to 25565 if none is specified
- Fix homepage server offline message
- Fix API reports alert, and order reports by last update time
- Fix nickname changing in AdminCP not working
- Fix broken "Remember Me" button on login page
- Fix unique username check in UserCP, and minor profile changes
- Preserve private profile setting value when private profiles are disabled
- Remove container inside container
- Reset permissions variables in AdminCP forum permission editing
- Fix share dropdown overflow
- Fix timezone related forum post date issue
- Fix issue on profile page
- Fix new topic post timezone
- Fix maintenance login link
- Fix URL for reports from API
- Fix AdminCP night mode editor text colour
- Fix missing database setting
- Fix new topic anti spam check
- Fix card body on homepage
- Add error message for sending emails too quickly
- Fix default language for root user
- Allow languages to specify their own meta charset content
- Fix broken topics with "banner" or "profile" in title
- Update mcassoc return link
- Prevent saving 404 not found page as last page
- Add homepage URL as Smarty variable
- Fix potential AuthMe connector issues
- Move CSS from core to default template
- Update default template to Bootstrap 4.0.0
- Add Bootswatch themes to default template
- Update navbar template
- Update timeline on profile pages
- Replace panels in AdminCP with cards
- Fix fatal error if $ping doesn't exist
- Make default server query variables available on all pages
- Fix : sometimes showing after server IP on homepage
- Add SET NAMES command to custom DB class
- Fix broken "Minecraft Service Status"
- Disable server banners if exif_imagetype isn't installed
- Allow viewing error logs in AdminCP
- Add extra user related variables to topic view
- Modify topic view Smarty variables
- Update admin group permissions within installer upgrade
- Add initial, untested API implementation
- Allow API to be accessed when maintenance mode is enabled
- Start on hook system, add Discord webhook
- Change "inputted" to "entered" to make phrase clearer
- Fix issue with enabling the first forum web hook
- Allow adding class to <html> via template
- Fix issue with incorrect API key
- Update default template initialisation
- Update route check in default template
- Fix AdminCP users nickname issue
- Start alternative method of account validation through API
- Add default_group column to groups table
- Add message if invalid email provided in installer
- Fix incorrect login redirect for non-friendly URLs in a subdirectory
- Fix API registration default group
- Fix AdminCP night mode table font colour
- Add JS/CSS/subdirectory support to template editor
- Change unicode charset to utf8mb4
- Fix debugging being permanently disabled
- Allow disabling mcassoc integration once enabled
- Fix last IP being reset when editing users in the AdminCP
- Temporarily remove reset password link in AdminCP
- Allow switching between usernames + emails for logins
- Add username sync option to API using serverInfo method
- Fix wrong translation showing for forum stats
- Fix signin details being remembered if tfa is cancelled
- Change update zip obtained for updater
- Fix login issue
- Allow changing path within installer
- Remove built-in forum online user list
- Remove user logs upon account deletion
- Remove more user data upon account deletion
- Fix update execute issues


## [2.0.0 pr-3](https://github.com/NamelessMC/Nameless/compare/v2.0.0-pr2...v2.0.0-pr3) - 2017-10-06

### Added
- Add Romanian and Swedish translations
- Add missing profile post term to all languages
- Update Minecraft server banners and allow custom backgrounds
- Allow dismissing maintenance mode alert
- Add port field to email configuration
- Add template alert language entries
- Create default label types
- Add back button to edit group page
- Add widget system, along with Twitter and Facebook widgets
- Add latest post widget
- Add header to latest posts widget template
- Add player count graphs
- Add function to get avatar from UUID
- Add friendlier forum URLs
- Allow deleting templates
- Add Discord widget
- Add user doesn't exist page
- Add missing language files
- Allow blocking users
- Add signature to UserCP settings
- Add secondary user groups
- Update server query internal
- Add user punishments
- Allow editing/deleting profile wall posts

### Changed
- Update existing translations
- Update Bootstrap version to v4 alpha 6
- Only display 5 forum posts on profile pages
- Update installer requirements
- Update DataTables Bootstrap plugin to v4
- Update .nav-list CSS
- Add rel="nofollow noopener" and target="_blank" to every external link
- Update updater URL
- Update email content type to html
- Update group tags within installer to use badges instead of labels
- Update NamelessMC footer link
- Add background-size: cover; to background image
- Add user related variables
- Display module author
- Update Paginator class, allowing for custom pagination CSS
- Update site name in two factor authentication
- Prevent viewing template files
- Create htaccess in cache directory
- Tidy installer language file includes
- Track topic views via session if cookies are disabled

### Fixed
- Fix server banner issues
- Add missing font file
- Fix white screen if no avatar is uploaded
- Fix AdminCP forums page navbar
- Fix potentially broken AdminCP link with non-friendly URLs
- Check if username already exists in Authme connector
- Fix banner issues with PHP < 7.0
- Fix issue with maintenance mode message editor
- Fix incorrect forum order
- Fix login page in maintenance mode without friendly URLs
- Fix avatar size in news section
- Fix potential overflow in user dropdown
- Update forum module, fixing installation check
- Fix no forums showing for guests
- Fix navbar issue
- Fix editor Emoji icon and tabs
- Fix pagination issue with no non-sticky topics in forum
- Fix widget issue with maintenance mode for non-admins
- Fix error display issue in change password template
- Fix match password for forgot password page
- Fix potential undefined offset issue
- Fix potential undefined variable error
- Fix expired Discord invite
- Fix successful login alert
- Add missing include to user messaging file
- Add extra template variables to ModCP reports
- Reshuffle view topic buttons
- Update server player count query
- Update punishment link in AdminCP
- Fix incorrect banner URL
- Remove port 443 from getSelfURL() if https is enabled
- Fix issue with custom pagination classes
- Add custom page system
- Fix custom page icons
- Add ability to re-order navbar
- Fix spoilers + emojis on custom pages
- Add module name to widget list
- Add option to enable page load timer
- Add missing view to ModCP punishments
- Fix incorrect pagination link
- Move AdminCP navbar contents into container


## [2.0.0 pr-2](https://github.com/NamelessMC/Nameless/compare/v2.0.0-pr1...v2.0.0-pr2) - 2017-06-07

### Added
- Add forum labels
- Add maintenance mode
- Allow installer translations
- Add Minecraft server query to homepage
- Allow editing + deleting Minecraft servers
- Add forgot password functionality
- Add forum topic label permissions
- Add forum search
- Add contact page, new email class and email errors + testing
- Add registration email verification toggle in AdminCP
- Add Minecraft server query error logging
- Add Minecraft server banners
- Allow purging Minecraft query errors
- Add avatar configuration, including the ability to upload a default avatar
- Add force-HTTPS option to AdminCP
- Add switch to toggle error reporting
- Add card colour for Minecraft service status "Slow"
- Add topics and posts to AdminCP overview stats graph
- Allow per-user timezones
- Add AuthMe integration and Minecraft service status
- Add default database address to installer
- Allow logging in whilst in maintenance mode

### Changed
- Remove unnecessary header template file
- Minor forum updates
- Update Mojang API URL
- Update panels in AdminCP night theme
- Redirect to previous page after login
- Update profile template
- Update core Bootstrap version
- Update default template Bootstrap version
- Update htaccess
- Allow use of HTML in report content
- Separate core JS into templates
- Change popover placement in AdminCP
- Allow creating new instances of custom DB class
- Update AdminCP registrations graph
- Update PasswordHash class
- Update form tokens
- Update Font Awesome
- Apply GeSHi to new topics and edited posts
- Remove deprecated mcrypt requirement, along with a now unused function
- Increase query interval
- Update admin auth screen layout
- Minor forum SEO improvements
- Define page on register page
- Update post editor
- Update cp_dark.css
- Prevent admin account switch in AdminCP
- Update installer and configuration methods

### Fixed
- Fix issue with editing user in AdminCP
- Prevent non-moderators replying to locked topics
- Prevent signature image overflow
- Fix registration issue
- Fix recaptcha issues in upgrade script
- Fix incorrect "last user" information in PMs
- Small AdminCP label fixes
- Fix signin issue with numeric usernames
- Fix negative reactions not creating
- Display success message upon sending PM reply
- Display errors if unable to create config + cache
- Fix installation detection
- Fix installer not loading
- Fix permission check for configuration file
- Fix invalid charset in installer
- Fix AdminCP mobile navbar toggler not showing
- Fix AdminCP auth + password changing
- Fix error in 404 page
- Fix potentially incorrect news ordering
- Fix minor login/logout issues
- Fix AdminCP security log sorting
- Fix post word wrapping and temporarily disable the code editor
- Fix sub-server queries
- Fix invalid MySQL column default value
- Fix timezone offsets not displaying minutes correctly
- Complete mcassoc integration
- Add terms and conditions page, and the ability to modify them
- Update installer
- Fix AdminCP -> Minecraft PHP notice


## [2.0.0 pr-1](https://github.com/NamelessMC/Nameless/compare/v1.0.15...v2.0.0-pr1) - 2016-12-30
- Initial release
