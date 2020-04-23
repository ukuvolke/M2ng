<?php
	$in = json_decode(file_get_contents("php://input"));

	$file = "leaderboards/" . $in[0] . ".json";

	if(file_exists($file)){
		$curCon = file_get_contents($file);

		$curConArr = explode("\n", $curCon);

		file_put_contents($file, '[' . "\n" . $curConArr[1] . ', {"name": "' . $in[1] . '", "time": "' . $in[2] . '", "mistakes": ' . $in[3] . '}' . "\n" . ']');

	}else{
		file_put_contents($file, '[' . "\n" . '{"name": "' . $in[1] . '", "time": "' . $in[2] . '", "mistakes": ' . $in[3] . '}' . "\n" . ']');
	}

	echo '{"success": 1}';
?>
