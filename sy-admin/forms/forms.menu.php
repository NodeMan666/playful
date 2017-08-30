<ul class="sidemenus">
<li <?php if(empty($_REQUEST['action'])) { print "class=\"on\""; } ?>><a href="index.php?do=forms">List Forms</a></li>
<li <?php if($_REQUEST['action'] == "editForm") { print "class=\"on\""; } ?>><a href="" onclick="editform(); return false;">Create New Form</a></li>
<!-- <li <?php if($_REQUEST['action'] == "captcha") { print "class=\"on\""; } ?>><a href="index.php?do=forms&action=captcha">CAPTCHA Settings</a></li> -->
</ul>
