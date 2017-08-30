<?php 
$wset = doSQL("ms_wall_settings", "*", "  ");
if($_REQUEST['sub'] == "frame") { 
	include $setup['path']."/".$setup['manage_folder']."/room-view/room.frame.php";
} elseif($_REQUEST['sub'] == "frames") { 
	include $setup['path']."/".$setup['manage_folder']."/room-view/room.frames.php";
} elseif($_REQUEST['sub'] == "canvases") { 
	include $setup['path']."/".$setup['manage_folder']."/room-view/room.canvases.php";
} elseif($_REQUEST['sub'] == "rooms") { 
	include $setup['path']."/".$setup['manage_folder']."/room-view/room.rooms.php";
} elseif($_REQUEST['sub'] == "room") { 
	include $setup['path']."/".$setup['manage_folder']."/room-view/room.room.php";
} elseif($_REQUEST['sub'] == "mats") { 
		include $setup['path']."/".$setup['manage_folder']."/room-view/room.mats.php";
} elseif($_REQUEST['sub'] == "savedviews") { 
		include $setup['path']."/".$setup['manage_folder']."/room-view/room.saved.php";
} elseif($_REQUEST['sub'] == "language") { 
		include $setup['path']."/".$setup['manage_folder']."/room-view/room.language.php";
} elseif($_REQUEST['sub'] == "created") { 
		include $setup['path']."/".$setup['manage_folder']."/room-view/room.created.php";
} else { 
		include $setup['path']."/".$setup['manage_folder']."/room-view/room.settings.php";
}

?>