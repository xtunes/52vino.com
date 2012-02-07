<?php 
//This plugin creates an entry in the options database. When the plugin will be deleted, this code will automatically delete the database entry from the options Wordpress table.
delete_option('wpimageflow2_options'); 
?>