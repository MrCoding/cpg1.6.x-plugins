<?php
/*******************************************************
 Coppermine 1.6.x plugin - FileMove
 *******************************************************
 Copyright (c) 2003-2017 Coppermine Dev Team
 *******************************************************
 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 3
 of the License, or (at your option) any later version.
 *******************************************************
 Ported to CPG 1.6.x June 2017 {ron4mac}
 *******************************************************/

require_once 'plugins/FileMove/include/function.inc.php';

//global $CONFIG;
if (!GALLERY_ADMIN_MODE) cpg_die(ERROR, $lang_errors['access_denied'], __FILE__, __LINE__);

// Get input variables
$DRep = $superCage->get->getRaw('Drep').'/';
$ARep = $superCage->get->getRaw('Arep').'/';

// Display page header
filemoveHeader();

$title = $lang_plugin_FileMove['transfer'].$lang_plugin_FileMove['folder2'].'<b>'.$DRep.'</b>'.$lang_plugin_FileMove['to'].$lang_plugin_FileMove['folder2'].'<b>'.$ARep.'</b>';
starttable('100%', $title);
echo '<tr><td>';

// Process the database
$c = 0;
// Select all images from the source directory
$result = cpg_db_query("SELECT * FROM {$CONFIG['TABLE_PICTURES']} WHERE `filepath`='$DRep'");

while ($row=cpg_db_fetch_array($result)) {
	$file_name = $row['filename'];
	echo "{$file_name}<br />";
	// Physically move the file from one directory to another
	file_move($file_name, $DRep, $ARep);
	$c++;
}
cpg_db_free_result($result);
// Update the database to reflect the new file paths
cpg_db_query("UPDATE {$CONFIG['TABLE_PICTURES']} SET `filepath`='$ARep' WHERE `filepath`='$DRep'");

echo '</td></tr>';
echo "<tr><td align='center'><b>{$c} {$lang_plugin_FileMove['traitement']}</b><br /><a href='index.php'><input type='button' name='ok' value='{$lang_plugin_FileMove['continue']}' /></a></td></tr>";
endtable();

filemoveFooter();
