<?php
/********************************************************************************
 *                                                                              *
 *  (c) Copyright 2015 The Open University UK                                   *
 *                                                                              *
 *  This software is freely distributed in accordance with                      *
 *  the GNU Lesser General Public (LGPL) license, version 3 or later            *
 *  as published by the Free Software Foundation.                               *
 *  For details see LGPL: http://www.fsf.org/licensing/licenses/lgpl.html       *
 *               and GPL: http://www.fsf.org/licensing/licenses/gpl-3.0.html    *
 *                                                                              *
 *  This software is provided by the copyright holders and contributors "as is" *
 *  and any express or implied warranties, including, but not limited to, the   *
 *  implied warranties of merchantability and fitness for a particular purpose  *
 *  are disclaimed. In no event shall the copyright owner or contributors be    *
 *  liable for any direct, indirect, incidental, special, exemplary, or         *
 *  consequential damages (including, but not limited to, procurement of        *
 *  substitute goods or services; loss of use, data, or profits; or business    *
 *  interruption) however caused and on any theory of liability, whether in     *
 *  contract, strict liability, or tort (including negligence or otherwise)     *
 *  arising in any way out of the use of this software, even if advised of the  *
 *  possibility of such damage.                                                 *
 *                                                                              *
 ********************************************************************************/
 ?>

<div id="tagcloud" style="background:transparent;clear:both; float:left; width: 100%;margin-top:10px;">
	<ul>
	<?php
		$items = array();

		$next = array();
		$next[0] = $LNG->ADMIN_MANAGE_NEWS_LINK;
		$next[1] = "javascript:loadDialog('managethemes','".$CFG->homeAddress."ui/admin/newsmanager.php', 750, 600);";
		$next[2] = $LNG->ADMIN_BUTTON_HINT;
		array_push($items, $next);

		$next = array();
		$next[0] = $LNG->ADMIN_REGISTER_NEW_USER_LINK;
		$next[1] = "javascript:loadDialog('adminregister','".$CFG->homeAddress."ui/admin/adminregister.php', 750, 600);";
		$next[2] = $LNG->ADMIN_BUTTON_HINT;
		array_push($items, $next);

		$next = array();
		$next[0] = $LNG->REGSITRATION_ADMIN_MANAGER_LINK;
		$next[1] = "javascript:loadDialog('managerrequest','".$CFG->homeAddress."ui/admin/registrationmanager.php', 850, 600);";
		$next[2] = $LNG->ADMIN_BUTTON_HINT;
		array_push($items, $next);

		//$next = array();
		//$next[0] = $LNG->SPAM_ADMIN_MANAGER_SPAM_LINK;
		//$next[1] = "javascript:loadDialog('spammanager','".$CFG->homeAddress."ui/admin/spammanager.php', 750, 600);";
		//$next[2] = $LNG->ADMIN_BUTTON_HINT;
		//array_push($items, $next);

		$next = array();
		$next[0] = $LNG->SPAM_USER_ADMIN_MANAGER_SPAM_LINK;
		$next[1] = "javascript:loadDialog('spamusermanager','".$CFG->homeAddress."ui/admin/spammanagerusers.php', 750, 600);";
		$next[2] = $LNG->ADMIN_BUTTON_HINT;
		array_push($items, $next);

		$next = array();
		$next[0] = $LNG->ADMIN_NEWS_USERS;
		$next[1] = "javascript:window.location.replace('".$CFG->homeAddress."ui/admin/userregistration.php');";
		$next[2] = $LNG->ADMIN_BUTTON_HINT;
		array_push($items, $next);

		//$next = array();
		//$next[0] = $LNG->HOMEPAGE_STATS_LINK;
		//$next[1] = "javascript:window.location.replace('".$CFG->homeAddress."ui/stats');";
		//$next[2] = $LNG->ADMIN_STATS_BUTTON_HINT;
		//array_push($items, $next);


		$i = 0;
		foreach($items as $item) {
			$colour = "";
			$colourBorder="";
			$backcolor = "";

			if ($i == 0 || $i % 3 == 0) {
				$colour = "themelist2colour";
				$colourBorder="themelist2border";
				$backcolor = "themelist2back";
			} else if ($i == 1 || $i % 3 == 1) {
				$colour = "resourcebackgradient";
				$colourBorder="resourceborder";
				$backcolor = "resourceback";
			} else if ($i == 2 || $i % 3 == 2) {
				$colour = "themelist1colour";
				$colourBorder="themelist1border";
				$backcolor = "themelist1back";
			}

			$i++;
			$classes = $colour." ".$colourBorder." ".$backcolor." themelist";
			$classes2 = $colour." themelistinner";

			echo '<div class="'.$classes.'" style="margin-right:10px;" onclick=".$item[1]." onmouseover="this.className=\'themelist plainbackgradient plainborder plainback \';" onmouseout="this.className=\''.$classes.'\';" title="'.$item[2].'"><div class="'.$classes2.'" onclick="'.$item[1].'" onmouseover="this.className=\'themelistinner plainbackgradient\';" onmouseout="this.className=\''.$classes2.'\';"><table style="text-align:center;font-weight:bold;width:100%;height:100%" class="themebutton"><tr><td valign="middle">'.$item[0].'</td></tr></table></div></div>';
		}
	?>
	</ul>
</div>
