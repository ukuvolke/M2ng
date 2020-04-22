<?php

$filPost = filter_input_array(INPUT_POST, [
	'name' => FILTER_SANITIZE_STRING,
	'desc' => FILTER_SANITIZE_STRING,
	'type' => FILTER_SANITIZE_STRING,

	'lines' => [
		'filter' => FILTER_SANITIZE_STRING,
		'flags'  => FILTER_REQUIRE_ARRAY
	]
]);

$id = (count(scandir("./exercises")) - 2);

file_put_contents("./exercises/" . $id  . ".json", json_encode($filPost))

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Upload</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
</head>
<body>
	<form id="form" action="" method="POST">
		<input name="name" placeholder="Nimi" type="" required>
		<input name="desc" placeholder="Kirjeldus" type="" required>

		<br>

		<label for="normal">Küsimused</label>
		<input id="normal" name="type" value="normal" type="radio" checked required>

		<br>

		<label for="hotkeys">Kiirklahvid</label>
		<input id="hotkeys" name="type" value="hotkeys" type="radio" required>

		<br>

		<div id="questions">
			<div id="questionLine">
				<input name="lines[0][question]" placeholder="küsimus" type="" required>
				<input class="answ" name="lines[0][answer]" placeholder="vastus" type="" required>
			</div>
		</div>
		<button>Submit</button>
		<button type="button" onclick="addQuestion()">Add question</button>
	</form>

	<script>
		const questions = document.querySelector("#questions");
		const questionLine = document.querySelector("#questionLine");

		const radios = document.querySelectorAll("#form > input[type=radio]");

		const questionElms = document.querySelectorAll("#questions .answ");

		let counter = 1;
		let writeType = "normal";

		function addQuestion(){
			const clone = questionLine.cloneNode(true)
			clone.id = ""
			Array.from(clone.children).forEach((i, index) => {
				i.name = "lines[" + counter + "][" + i.name.split("[")[2]
			})

			questions.appendChild(clone)
			counter++;
		}

		Array.from(questionElms).forEach(i => {
			i.addEventListener("keydown", e => {
				if(writeType == "hotkeys"){
					e.preventDefault()
					e.target.value = (e.ctrlKey  ? "Ctrl + ": "" ) + (e.shiftKey ? "Shift + ": "" ) + (e.altKey ? "Alt + ": "" ) + e.key.toLowerCase()
					return false;
				}
			})
		})


		Array.from(radios).forEach(i => {
			i.addEventListener("change", e => {
				if(e.target.id == "hotkeys"){
					writeType = "hotkeys";
				}else{
					writeType = "normal"
				}
			})
		})
	</script>
</body>
</html>
