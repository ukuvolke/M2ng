<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<style>
		.leaderboardModal{
			width: 100vw;
			height: 100vh;

			top: 0;
			left: 0;

			display: flex;
			justify-content: center;
			align-items: center;

			position: absolute;

			background-color: #aaaa
		}
	</style>
</head>
<body>

	<div class="input">
		<div id="next"></div>
		<input id="typField" disabled />
		<div id="prevVal"></div>
	</div>

	<p id="res"><span id="min">0</span> : <span id="sec">00</span> : <span id="msec">000</span> +<span id="pen">0</span></p>

	<div class="leaderboardModal" style="display: none;">
		<form id="leaderboardForm"  action="">
			<input id="name" placeholder="nimi" type="">
			<button type="submit">Lisa Edetabelisse</button>
		</form>
	</div>


<script src="stopper.js"></script>
<script>
	async function advFetch(url = '', met = 'GET', data = {}) {
		if(met == 'GET'){
			const response = await fetch(url, {
				method: 'GET',
				mode: 'cors',
				cache: 'no-cache',
				credentials: 'same-origin',
				redirect: 'follow',
				referrerPolicy: 'no-referrer',
			});
			return await response.json();
		} else if(met == 'POST'){
			const response = await fetch(url, {
				method: 'POST',
				mode: 'cors',
				cache: 'no-cache',
				credentials: 'same-origin',
				headers: {
					'Content-Type': 'application/json'
				},
				redirect: 'follow',
				referrerPolicy: 'no-referrer',
				body: JSON.stringify(data)
			});
			return await response.json();
		} else {
			console.error("method MUST be POST or GET")
		}
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

	function toSym(code, key){
		switch(code){
			case 48:  return "0"; break;
			case 49:  return "1"; break;
			case 50:  return "2"; break;
			case 51:  return "3"; break;
			case 52:  return "4"; break;
			case 53:  return "5"; break;
			case 54:  return "6"; break;
			case 55:  return "7"; break;
			case 56:  return "8"; break;
			case 57:  return "9"; break;
			case 59:  return ";"; break;
			case 61:  return "="; break;
			case 163: return "#"; break;
			case 173: return "-"; break;
			case 188: return ","; break;
			case 190: return "."; break;
			case 191: return "/"; break;
			case 192: return "`"; break;
			case 219: return "["; break;
			case 220: return "\\"; break;
			case 221: return "]"; break;
			case 222: return "'"; break;
			default:  return key; break;
		}
	}

	function Typ(val, text){
		this.val = val,
		this.text = text,
		this.done = 0
	}

	const typField = document.querySelector("#typField")
	const prevVal = document.querySelector("#prevVal")
	const next = document.querySelector("#next")

	let mistakes = 0;

	const id = <?= filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT) ?>

	advFetch("./exercises/" + id + ".json").then(data => {
		let typs = [];

		shuffle(data.lines).forEach(typ => {
			typs.push(new Typ(typ.question, typ.answer))
		})

		var now = 0;
		next.innerText = typs[now].val;

		typField.disabled = false;
		typField.focus()

		typField.addEventListener("keydown", e => {
			if(!timer){
				startWatch()
			}

			if(data.type == "hotkeys" && e.keyCode != 13){
				e.preventDefault()

				e.target.value = (e.ctrlKey  ? "Ctrl + ": "" ) + (e.shiftKey ? "Shift + ": "" ) + (e.altKey ? "Alt + ": "" ) + toSym(e.keyCode, e.key)
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
							curTime = min + ":" + sec + ":" + msec

							min_txt.innerText = min
							sec_txt.innerText = sec
							msec_txt.innerText = msec

							leaderboardForm.parentElement.style.display = ""


							stopWatch()
						}
					}else{
						mistakes++;
						document.getElementById("pen").innerText++
					}
				}
				typField.value = "";
			}
		})
	})

	let leaderboardForm = document.getElementById("leaderboardForm")
	leaderboardForm.addEventListener("submit", e => {
		e.preventDefault()
		let name = document.getElementById("name").value
		advFetch("leaderboard.php", "POST", [id, name, curTime, mistakes])
		leaderboardForm.parentElement.style.display = "none"
	})

</script>

</body>
</html>
