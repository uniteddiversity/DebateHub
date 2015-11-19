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
 /** Author: Michelle Bachler, KMi, The Open University **/

include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($HUB_FLM->getCodeDirPath("core/io/catalyst/analyticservices.php"));
require_once($HUB_FLM->getCodeDirPath("core/io/catalyst/catalyst_jsonld_reader.class.php"));

$groupid = required_param("groupid",PARAM_ALPHANUMEXT);

$url = $CFG->homeAddress.'api/conversations/'.$groupid;
$data = array();

$jitterArray = array(-0.01, -0.02, -0.03, 0, 0.03, 0.02, 0.01);

// GET METRICS
$metric = 'interest_space_post_coordinates';
$reply = getMetrics($url, $metric);

$replyObj = json_decode($reply);

if (!isset($replyObj[0]->error)) {
	$topicspacedata = $replyObj[0]->data;
	$groups = array();
	foreach($topicspacedata as $nodearray) {
		$nodeString = $nodearray[0];
		$nodeid = $nodearray[0];
		if (strpos($nodeString, '/') !== FALSE) {
			$bits = explode('/', $nodeString);
			$nodeid = $bits[count($bits)-1];
			$node = getNode($nodeid);
			if (!$node instanceof Error) {
				$nodeString = $node->name;
				$role = $node->role->name;
				$homepage = "";
				if (isset($node->homepage) && $node->homepage != "") {
					$homepage = $node->homepage;
				}

				$next = array(
					"x" => jitterme($nodearray[1]),
					"y" => jitterme($nodearray[2]),
					"size" => 10,
					"shape" => "circle",
					"id" => $nodeid,
					"name" => $nodeString,
					"nodetype" => $role,
					"homepage" => $homepage,
					"color" => "#282DF8"
				);

				if (!array_key_exists($role, $groups)) {
					$groups[$role] = array();
				}

				array_push($groups[$role], (object)$next);
			}
		}
	}

	foreach($groups as $key => $values) {
		$next = array(
			"key" => $key,
			"values" => $values
		);
		array_push($data, (object)$next);
	}
}

function jitterme($num) {
	global $jitterArray;
	$rand = rand(1, 7);
	$finalnum = $num+$jitterArray[$rand-1];
	return $finalnum;
}

include_once($HUB_FLM->getCodeDirPath("ui/headerstats.php"));
?>
<script type='text/javascript'>
var NODE_ARGS = new Array();

Event.observe(window, 'load', function() {
	NODE_ARGS['data'] = <?php echo json_encode($data); ?>;

	var bObj = new JSONscriptRequest('<?php echo $HUB_FLM->getCodeWebPath("ui/networkmaps/stats-scatterplot.js.php"); ?>');
    bObj.buildScriptTag();
    bObj.addScriptTag();
});
</script>

<div style="float:left;margin:5px;margin-left:10px;">
	<h1 style="margin:0px;margin-bottom:5px;"><?php echo $dashboarddata[$pageindex][0]; ?>
		<span><img style="padding-left:10px;vertical-align:middle;" title="<?php echo $LNG->STATS_DASHBOARD_HELP_HINT; ?>" onclick="if($('vishelp').style.display == 'none') { this.src='<?php echo $HUB_FLM->getImagePath('uparrowbig.gif'); ?>'; $('vishelp').style.display='block'; } else {this.src='<?php echo $HUB_FLM->getImagePath('rightarrowbig.gif'); ?>'; $('vishelp').style.display='none'; }" src="<?php echo $HUB_FLM->getImagePath('uparrowbig.gif'); ?>"/></span>
	</h1>
	<div class="boxshadowsquare" id="vishelp" style="font-size:12pt;"><?php echo $dashboarddata[$pageindex][5]; ?></div>

	<div id="scatterplot-div" style="clear:both;float:left;height:100%;"></div>
</div>

<?php
include_once($HUB_FLM->getCodeDirPath("ui/footerstats.php"));
?>