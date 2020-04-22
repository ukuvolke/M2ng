<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
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
		}

		nav{
			margin: 0 10px;
			padding: 10px 4px;
			display: flex;
			justify-content: flex-end;
		}

		nav a{
			color: black;
			text-decoration: none;
		}


		.exercises {
			display: flex;
			justify-content: space-around;
			flex-wrap: wrap;
			max-width: 990px;
			margin: 0 auto;
		}

		.exercises > a {
			padding: 10px;
			color: black;
			text-decoration: none;
			text-align: center;
			margin: 10px;

			border-radius: 10px;
			box-shadow: 10px 5px 5px #aaa;
		}

		.exercises .name {
			font-weight: bold;
		}

		.exercises .normal{
			background-color: hsl(104, 100%, 70%);
		}

		.exercises .hotkeys{
			background-color: hsl(10, 100%, 70%);
		}
	</style>
</head>
<body>
	<nav><a href="upload.php">lisa Harjutus</a></nav>

	<div class="exercises">
		<?php foreach(scandir("./exercises/") as $file):?>
			<?php if(! preg_match("/\B\..*/i", $file)): ?>
				<?php
				$filecontent = json_decode(file_get_contents("./exercises/" . $file));
				if($filecontent){ ?>
					<a class="<?= $filecontent->type?>" href="exercise.php?id=<?= explode(".", $file)[0] ?>">
						<h1 class="name"><?= $filecontent->name ?></h1>
						<span class="desc"><?= $filecontent->desc ?></span>
					</a>
				<?php } ?>
			<?php endif;?>
		<?php endforeach; ?>
	</div>
</body>
</html>
