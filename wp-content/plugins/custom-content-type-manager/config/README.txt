DO NOT modify any of the files in this folder!  Its contents will get overwritten if/when 
the plugin is updated.

Instead, copy this folder (or parts of it) to wp-content/uploads/cctm 
(or where-ever your uploads folder is).  Any file inside of that config folder there 
will override the config file here.

For example, if you want to customize the configuration for the image search parameters,
you'd copy the image_search_parameters.php file from here and place it at 
wp-content/uploads/cctm/config/image_search_parameters.php

The user created files are given precedence over the system ones, and your customizations
won't be erased if/when you upgrade the plugin.