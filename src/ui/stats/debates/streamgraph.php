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
include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');

$nodeid = required_param("nodeid",PARAM_ALPHANUMEXT);
$node = getNode($nodeid);

$nodes = array();
array_push($nodes, $node);

$checkNodes = array();
$conSet = getDebate($nodeid, 'long');
if (!$conSet instanceof Error) {
	$countj = count($conSet->connections);
	for ($j=0; $j<$countj;$j++) {
		$con = $conSet->connections[$j];
		$from = $con->from;
		if (in_array($from->nodeid, $checkNodes) === FALSE) {
			$checkNodes[$from->nodeid] = $from->nodeid;
			array_push($nodes, $from);
		}
	}
}

$nodeCheck = array();
$totalnodes = 0;

$count = count($nodes);

$typeArray = array($LNG->ISSUE_NAME,$LNG->SOLUTION_NAME,$LNG->PRO_NAME, $LNG->CON_NAME);
$coloursArray = array("#DFC7EB", "#A4AED4", "#A9C89E", "#D46A6A");
$dateArray = array();

//error_log($count);

for ($i=0; $i<$count; $i++) {
	$node = $nodes[$i];
	$datekey = date('d/m/y', $node->creationdate);
	//$datekey = $node->creationdate*1000;
	$nodetype = getNodeTypeText($node->role->name, false);
	if (in_array($nodetype, $typeArray)) {
		if (!array_key_exists($datekey, $dateArray)) {
			$typearray = array();
			$typearray[$LNG->ISSUE_NAME] = 0;
			$typearray[$LNG->SOLUTION_NAME] = 0;
			$typearray[$LNG->PRO_NAME] = 0;
			$typearray[$LNG->CON_NAME] = 0;
			$typearray[$nodetype] = 1;
			$dateArray[$datekey] = $typearray;
		} else {
			$typearray = $dateArray[$datekey];
			$typearray[$nodetype] = $typearray[$nodetype]+1;
			$dateArray[$datekey] = $typearray;
		}
	}
}

// sort by date
function datesortstacked($datekeyA, $datekeyB) {
	$a = DateTime::createFromFormat("d/m/y", $datekeyA);
	$b = DateTime::createFromFormat("d/m/y", $datekeyB);
	if ($a == $b) $r = 0;
	else $r = ($a > $b) ? 1: -1;
	return $r;
}
uksort($dateArray, "datesortstacked");

//error_log(print_r($dateArray, true));

$data = array();
$finalArray = array();

foreach ($typeArray as $next) {
	foreach ($dateArray as $key => $innerdata) {
		foreach ($innerdata as $type => $typecount) {
			if ($type == $next) {
				if (!array_key_exists($next, $finalArray)) {
					$nextarray = array();
					array_push($nextarray, array($key, $typecount));
					$finalArray[$next] = $nextarray;
				} else {
					$nextarray = $finalArray[$next];
					array_push($nextarray, array($key, $typecount));
					$finalArray[$next] = $nextarray;
				}
			}
		}
	}
}

foreach ($finalArray as $key => $values) {
	$next = new stdClass();
	$next->key = $key;
	$next->values = $values;
	array_push($data, $next);
}

include_once($HUB_FLM->getCodeDirPath("ui/headerstats.php"));

?>

<script type='text/javascript'>
var NODE_ARGS = new Array();

Event.observe(window, 'load', function() {
	NODE_ARGS['data'] = <?php echo json_encode($data); ?>;

	var bObj = new JSONscriptRequest('<?php echo $HUB_FLM->getCodeWebPath("ui/networkmaps/stats-streamgraph.js.php"); ?>');
    bObj.buildScriptTag();
    bObj.addScriptTag();
});
</script>

<div style="float:left;margin:5px;margin-left:10px;">
	<h1 style="margin:0px;margin-bottom:5px;"><?php echo $dashboarddata[$pageindex][0]; ?>
		<span><img style="padding-left:10px;vertical-align:middle;" title="<?php echo $LNG->STATS_DASHBOARD_HELP_HINT; ?>" onclick="if($('vishelp').style.display == 'none') { this.src='<?php echo $HUB_FLM->getImagePath('uparrowbig.gif'); ?>'; $('vishelp').style.display='block'; } else {this.src='<?php echo $HUB_FLM->getImagePath('rightarrowbig.gif'); ?>'; $('vishelp').style.display='none'; }" src="<?php echo $HUB_FLM->getImagePath('uparrowbig.gif'); ?>"/></span>
	</h1>
	<div class="boxshadowsquare" id="vishelp" style="font-size:12pt;"><?php echo $dashboarddata[$pageindex][5]; ?></div>

	<div id="streamgraph-div" style="float:left;width:100%;height:100%;"></div>
</div>

<?php
	include_once($HUB_FLM->getCodeDirPath("ui/footerstats.php"));
?>