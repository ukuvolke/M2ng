<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
</head>
<body>

	<div class="input">
		<div id="next"></div>
		<input id="typField" disabled />
		<div id="prevVal"></div>
	</div>

<script>
	async function advFetch(url = '') {
		const response = await fetch(url, {
			method: 'GET',
			mode: 'cors',
			cache: 'no-cache',
			credentials: 'same-origin',
			redirect: 'follow',
			referrerPolicy: 'no-referrer',
		});
		return await response.json();
	}

	function shuffle(array) {
		var currentIndex = array.length, temporaryValue, randomIndex;
		while (0 !== currentIndex) {
			randomIndex = Math.floor(Math.random() * currentIndex);
			currentIndex -= 1;

			temporaryValue = array[currentIndex];
			array[currentIndex] = array[randomIndex];
			array[randomIndex] = temporaryValue;
		}
		return array;
	}

	function Typ(val, text){
		this.val = val,
		this.text = text,
		this.done = 0
	}

	const typField = document.querySelector("#typField")
	const prevVal = document.querySelector("#prevVal")
	const next = document.querySelector("#next")

	advFetch("./exercises/<?= (int) $_GET['id']?>.json").then(data => {
		let typs = [];

		shuffle(data.lines).forEach(typ => {
			typs.push(new Typ(typ.question, typ.answer))
		})

		var now = 0;
		next.innerText = typs[now].val;

		typField.disabled = false;
		typField.focus()

		typField.addEventListener("keydown", e => {
			if(data.type == "hotkeys" && e.keyCode != 13){
				e.preventDefault()
				e.target.value = (e.ctrlKey  ? "Ctrl + ": "" ) + (e.shiftKey ? "Shift + ": "" ) + (e.altKey ? "Alt + ": "" ) + e.key.toLowerCase()
			}

			if(e.keyCode == 13){
				var res = typField.value;

				// check Result
				if(res){
					if(res == typs[now].text){
						prevVal.insertAdjacentHTML("afterbegin", "<div><span>" + res + ": " + typs[now].val + "</span></div>");
						now++;
						if(typs[now]){
							next.innerText = typs[now].val;
						}else{
							alert("Done!")
						}
					}else{
						alert("FAIL")
					}
				}
				typField.value = "";
			}
		})
	})
</script>

</body>
</html>
