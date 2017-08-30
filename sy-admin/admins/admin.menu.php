<ul class="sidemenus">
<li <?php if((empty($_REQUEST['view']))== true) { print "class=\"on\""; } ?>><a href="?do=admins">Administrators</a></li>
<li <?php if($_REQUEST['view'] == "logins"){ print "class=\"on\""; } ?>><a href="?do=admins&view=logins">Log In Log</a></li>
</ul>