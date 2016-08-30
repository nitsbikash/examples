<?php


/*
 $Id: tracker.php,v 1.29 2012/07/04 07:22:22 bobbitt Exp $

 Mike Bobbitt - Milnet.ca - Mike@Army.ca

 Copyright (C) 2010 Army.ca Technologies, Inc., All Rights Reserved

 Shows tracking information on specific users.
 */

// Place this script in the same directory as SSI.php, or set the path below
if (file_exists(dirname(__FILE__) . '/SSI.php')) {
	$ssifile = dirname(__FILE__) . '/SSI.php';
} else {
	$ssifile = "/var/www/html/forums/SSI.php";
}

// Are we running on Milnet.ca or standalone?
if (preg_match("/(milnet|army|navy|air-force).ca$/i", $_SERVER["HTTP_HOST"])) {
	$armyca = 1;
} else {
	$armyca = 0;
}

if ($armyca) {
	include_once "/var/www/army.ca/includes/header.php";
} else {
	require ($ssifile);

	// Set this to restrict access. Currently only admins are allowed.
	$isstaff = $context['user']['is_admin'];
}

if (!$isstaff) {
	echo "ERROR: You are not an admin - ACCESS DENIED. ($isstaff)";
	if ($armyca) {
		include "$include_dir/footer.php";
	}
	exit (1);
}

// download variables
if (isset ($_REQUEST["function"])) {
	$function = $_REQUEST["function"];
}

if (isset ($_REQUEST["u"])) {
	$u = $_REQUEST["u"];
}

if (isset ($_REQUEST["user"])) {
	$user = $_REQUEST["user"];
}

echo "<h1>Track User Activity</h1>\n";
echo "This script tracks the recent actions of all users in The Watch List.<br /><br />\n";

// Warning group IDs (primary)
$watchlist = "23";

// Show "lookup user" form
if (!$function) {
	echo<<<HTML
<div class="highlight">Track User</div><br /><br />
<form>
Username, Display Name or User ID #: <input type="text" name="user" />
<input type="hidden" name="function" value="trackuser" />
<br />
<input type="submit" value="Track User">
</form>
<br />
HTML;
}

if ($function == "trackuser") {
	// it's a userid, not a username
	if (preg_match("/^\d+$/", $user)) {
		$u = $user;
	} else {
		// Generate query
		$result = $smcFunc['db_query'] ('', "SELECT m.id_member FROM {db_prefix}members AS m WHERE m.member_name = {string:user} OR m.real_name = {string:user}", array (
			'user' => $user
		));
		$res = $smcFunc['db_fetch_assoc'] ($result);
		$u = $res['id_member'];
	}

	if (!$u) {
		echo<<<HTML
<br />
<br />
WARNING: User $user not found. Please search again.
<br />
<br />
HTML;
	} else {
		$function = "track";
	}
}

// Track a user
if ($function == "track") {
	// Get username again
	$result = $smcFunc['db_query'] ('', "SELECT m.real_name FROM {db_prefix}members AS m WHERE m.id_member = {int:id_member}", array (
		'id_member' => $u
	));
	$res = $smcFunc['db_fetch_assoc'] ($result);
	$real_name = $res['real_name'];
	$smcFunc['db_free_result'] ($result);

	echo "Tracking <a href=\"$scripturl?action=profile;u=$u\">$real_name</a>...<br />\n";

	echo "<br />Jump to: <a href=\"#boards\">Boards</a> || <a href=\"#topics\">Topics</a> || <a href=\"#monitor\">Monitored Topics</a> || <a href=\"#admin\">Admin Actions</a> || <a href=\"#errors\">Errors</a> || <a href=\"./tracker.php\">Tracker Home</a>";

	echo "<a name=\"boards\"><h2>Current Action</h2></a>";

	// Get the user's current action
	$result = $smcFunc['db_query'] ('', "SELECT * FROM {db_prefix}log_online AS o WHERE o.id_member = {int:id_member} ORDER BY o.log_time DESC LIMIT 1", array (
		'id_member' => $u
	));

	echo "<table>";

	while ($res = $smcFunc['db_fetch_assoc'] ($result)) {
		echo "<tr><td>";

		// Decode current action
		global $sourcedir;

		include_once ("$sourcedir/Who.php");
		echo determineActions($res['url']);

		echo "</td><td>";
		if ($res['log_time']) {
			echo date("Y-m-d H:i:s", $res['log_time']);
		}
		echo "</td></tr>\n";
	}
	echo "</table>";
	$smcFunc['db_free_result'] ($result);

	echo "<a name=\"boards\"><h2>Recent Boards</h2></a>";

	// Get the user's board log
	$result = $smcFunc['db_query'] ('', "SELECT * FROM {db_prefix}log_boards AS b WHERE b.id_member = {int:id_member}", array (
		'id_member' => $u
	));

	echo "<table>";

	while ($res = $smcFunc['db_fetch_assoc'] ($result)) {
		// Get the board title
		$result2 = $smcFunc['db_query'] ('', "SELECT b.name FROM {db_prefix}boards AS b WHERE b.id_board = {int:id_board}", array (
			'id_board' => $res['id_board']
		));
		$res2 = $smcFunc['db_fetch_assoc'] ($result2);
		$name = $res2['name'];
		$smcFunc['db_free_result'] ($result2);

		echo "<tr><td><a href=\"$scripturl/board," . $res['id_board'] . ".0.html\">$name</a></td><td>";
		if (isset ($res['log_time'])) {
			echo date("Y/m/d H:i:s", $res['log_time']);
		}
		echo "</td></tr>\n";
	}
	echo "</table>";
	$smcFunc['db_free_result'] ($result);

	echo "<a name=\"topics\"><h2>Recent Topics</h2></a>";

	// Get the user's topic log
	$result = $smcFunc['db_query'] ('', "SELECT * FROM {db_prefix}log_topics AS t WHERE t.id_member = {int:id_member} ORDER BY t.id_topic DESC", array (
		'id_member' => $u
	));

	echo "<table>";

	while ($res = $smcFunc['db_fetch_assoc'] ($result)) {
		// Get the topic title
		$result2 = $smcFunc['db_query'] ('', "SELECT m.subject FROM {db_prefix}messages AS m WHERE m.id_topic = {int:id_topic}", array (
			'id_topic' => $res['id_topic']
		));
		$res2 = $smcFunc['db_fetch_assoc'] ($result2);
		$subject = $res2['subject'];
		$smcFunc['db_free_result'] ($result2);

		echo "<tr><td><a href=\"$scripturl/topic," . $res['id_topic'] . ".0.html\">$subject</a></td><td>";
		if (isset ($res['log_time'])) {
			echo date("Y/m/d H:i:s", $res['log_time']);
		}
		echo "</td></tr>\n";
	}
	echo "</table>";
	$smcFunc['db_free_result'] ($result);

	echo "<a name=\"monitor\"><h2>Monitored Topics/Boards</h2></a>";

	// Get the user's notifications list
	$result = $smcFunc['db_query'] ('', "SELECT * FROM {db_prefix}log_notify AS n WHERE n.id_member = {int:id_member}", array (
		'id_member' => $u
	));

	while ($res = $smcFunc['db_fetch_assoc'] ($result)) {
		// Get the topic title
		if (isset ($res['id_topic'])) {
			$result2 = $smcFunc['db_query'] ('', "SELECT m.subject FROM {db_prefix}messages AS m WHERE m.id_topic = {int:id_topic}", array (
				'id_topic' => $res['id_topic']
			));
			$res2 = $smcFunc['db_fetch_assoc'] ($result2);
			$subject = $res2['subject'];

			if (!$subject) {
				$subject = "Not a valid topic.";
			}
			$smcFunc['db_free_result'] ($result2);
			echo "<a href=\"$scripturl/topic," . $res['id_topic'] . ".0.html\">$subject</a><br />\n";
		}

		if (isset ($res['id_board'])) {
			// Get the board title
			$result2 = $smcFunc['db_query'] ('', "SELECT b.name FROM {db_prefix}boards AS b WHERE b.id_board = {int:id_board}", array (
				'id_board' => $res['id_board']
			));
			$res2 = $smcFunc['db_fetch_assoc'] ($result2);
			$subject = $res2['name'];
			$smcFunc['db_free_result'] ($result2);
			echo "<a href=\"$scripturl/board," . $res['id_board'] . ".0.html\">$subject</a><br />\n";
		}
	}
	$smcFunc['db_free_result'] ($result);

	echo "<a name=\"admin\"><h2>Admin Actions</h2></a>";

	// Get the user's admin actions log
	$result = $smcFunc['db_query'] ('', "SELECT * FROM {db_prefix}log_actions AS a WHERE a.id_member = {int:id_member} ORDER BY a.log_time ASC", array (
		'id_member' => $u
	));

	echo<<<HTML
<table>
<tr>
<th>Datestamp</th><th>Action</th><th>Details</th><th>IP Address</th>
</tr>
HTML;

	while ($res = $smcFunc['db_fetch_assoc'] ($result)) {
		echo "<tr><td>";
		echo date("Y/m/d H:i:s", $res['log_time']) . "</td><td>";
		echo $res['action'] . "</td><td>";
		include_once ("$sourcedir/Who.php");
		echo determineActions($res['extra']) . "</td><td>";
		echo $res['ip'] . "</td><td>";
		echo "</tr>";
	}
	$smcFunc['db_free_result'] ($result);
	echo "</table>";

	echo "<a name=\"errors\"><h2>Recent Errors</h2></a>";

	// Get the user's error log
	$result = $smcFunc['db_query'] ('', "SELECT * FROM {db_prefix}log_errors AS e WHERE e.id_member = {int:id_member} ORDER BY e.log_time DESC", array (
		'id_member' => $u
	));

	echo "<table>";

	while ($res = $smcFunc['db_fetch_assoc'] ($result)) {
		echo "<tr><td>" . $res['message'] . "</td><td>";
		echo date("Y/m/d H:i:s", $res['log_time']);
		echo "</td></tr>\n";
	}
	echo "</table>";
	$smcFunc['db_free_result'] ($result);

	if ($armyca) {
		include_once "$include_dir/footer.php";
	}
	exit (0);
}

// Get list of users in The Watch List
if ($armyca) {
	$result = $smcFunc['db_query'] ('', "SELECT * FROM {db_prefix}members AS m WHERE m.additional_groups LIKE \"%$watchlist%\"", array ());

	echo "Select the user you want to track:<br /><br />\n";

	while ($res = $smcFunc['db_fetch_assoc'] ($result)) {
		echo "<a href=\"$scripturl?action=profile;u=" . $res['id_member'] . "\"><img align=\"middle\" src=\"http://army.ca/forums/Themes/default/images/icons/profile_sm.gif\" border=\"0\"></a> <a href=\"?function=track;u=" . $res['id_member'] . "\">" . $res['real_name'] . "</a><br />\n";
	}

	$smcFunc['db_free_result'] ($result);

	include "$include_dir/footer.php";
}
?>
