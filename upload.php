<?php
$action = strtolower(filter_input(INPUT_POST, "action", FILTER_SANITIZE_STRING));

if(isset($action) && $action == "submit"){
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

	file_put_contents("./exercises/" . $id  . ".json", json_encode($filPost));

	header("Location: ../");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Upload</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<style>
		html, body{
			height: 100%;
			margin: 0;
		}

		* {
			box-sizing: border-box;
			margin: 0;
			padding: 0;
		}

		body{
			background-image: url("./backgroung.svg");
			background-size: cover;
			padding: 50px;
		}

		#form {
			max-width: 1100px;
			margin: auto;
		}

		#form input::placeholder {
			color: #000f;
			opacity: 1;
		}

		#form input {
			padding: 10px;
			text-align: center;

			background: rgba(255, 255, 255, 0.6);
			border: 1px #949292 solid;
		}

		#form button {
			border: 0;
			border-radius: 13px;

			margin: 10px 5px;
			padding: 5px 40px;

			background: rgba(82, 77, 76, 0.4);

			color: #000;
		}

		.name{
			width: 100%;
			display: flex;
			justify-content: space-around;
		}

		.name input{
			flex: 1;
			margin: 5px;
		}

		#questions {
			max-height: 230px;
			overflow: auto;
		}

		#questions > div  {
			width: 100%;
			display: flex;
			justify-content: space-around;
		}

		#questions input {
			flex: 1;
			margin: 5px;
		}

		.radio {
			margin: 5px;

		}

	</style>
</head>
<body>
	<form id="form" action="" method="POST">
		<div class="name">
			<input name="name" placeholder="Nimi" type="" required>
			<input name="desc" placeholder="Kirjeldus" type="" required>
		</div>

		<div class="radio">
			<div>
				<label for="normal">Küsimused</label>
				<input id="normal" name="type" value="normal" type="radio" checked required>
			</div>

			<div>
				<label for="hotkeys">Kiirklahvid</label>
				<input id="hotkeys" name="type" value="hotkeys" type="radio" required>
			</div>
		</div>

		<div id="questions">
			<div id="questionLine">
				<input name="lines[0][question]" placeholder="Küsimus" type="" required>
				<input class="answ" name="lines[0][answer]" placeholder="Vastus" type="" required>
			</div>
		</div>
		<button name="action" value="submit">Vasta</button>
		<button type="button" onclick="addQuestion()">Lisa Küsimus</button>
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
				i.value = "";
			})


			clone.children[1].addEventListener("keydown", e => {
				if(writeType == "hotkeys"){
					e.preventDefault()
					e.target.value = (e.ctrlKey  ? "Ctrl + ": "" ) + (e.shiftKey ? "Shift + ": "" ) + (e.altKey ? "Alt + ": "" ) + e.key.toLowerCase()
					return false;
				}
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
