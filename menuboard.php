<a class="various" data-fancybox-type="iframe" href="upload.php" style='text-decoration:none;border:0px;'>
        <button type="button" class="menubutton">
                <img src="icons/upload.png" width="35">
        </button>
</a>

<a class="various" data-fancybox-type="iframe" href="add_new_folder.php" style='text-decoration:none;border:0px;'>
        <button type="button" class="menubutton">
                <img src="icons/new_dir.png" width="35">
        </button>
</a>

<a class="share" data-fancybox-type="iframe" href="share.php" style='text-decoration:none;border:0px;'>
        <button type="button" class="menubutton">
                <img src="icons/share.png" width="35">
        </button>
</a>
<?php
if ($id == 1){
	
	echo '
	<a class="admin" data-fancybox-type="iframe" href="admin.php" style="text-decoration:none;border:0px;">
	        <button type="button" class="menubutton">
	                <img src="icons/einstellungen.png" width="35">
	        </button>
	</a>

	';
}
?>
	<a href="logout.php" style='text-decoration:none;border:0px;'>
	        <button type="button" class="menubutton">
	                <img src="icons/logout.png" width="35">
	        </button>
	</a>



