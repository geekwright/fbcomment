FBComments Module Version 1.0 for XOOPS/ImpressCMS

Purpose
=======
FBComments adds Facebook comments and like button functions 
to your CMS with little or no template and/or coding changes. 
A system to customize the Open Graph Protocol data exposed 
to Facebook by your site is also included.

Installation
============
Copy the fbcomment directory of the installation archive to 
the modules directory of your site, then install like any 
other module in your site's administration area.

The module administration pages include a help file with a
quick start guide for configuration and usage.

Usage
=====
This module does not have a real main page at this time. It 
consists only of blocks and the necessary scripts to support 
those blocks. Attempts to use the fbcomment/index.php will
result in an empty page. It is recommended to turn it off
in the main menu in modules administration.

Group access permissions to the FBComments module are required 
to use the blocks, above and beyond the block display permission. 
Interacting with the blocks records the URL, action and datetime
via AJAX style calls to scripts in the fbcomment directory.
These use the standard mainfile inclusion which adds a layer of
additional protections (such as protector) but requires access
permissions to work as intended.

Also note, in normal operation, Facebook will pull data from 
your site as the anonymous user. 

Module administration permissions enable in-block editing of 
Open Graph meta data. Click on the Open Graph icon in the 
lower right of a FBComments block display to access it.

Three blocks are available.

Comment     - Facebook comments
Like        - Facebook like button
Combo       - Facebook like button and comments in a single block

These blocks can be cloned as needed.

Notes
=====

This module has been tested in XOOPS version 2.5.5. and ImpressCMS
version 1.2.7 and 1.3.4.

This module was developed by geekwright, LLC. Report any bugs
or issues to richard@geekwright.com
