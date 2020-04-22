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

function createAlias(name, val){
	aliases[name] = val
}

function Typ(val, text){
	this.val = val,
	this.text = text,
	this.done = 0
}

const typField = document.querySelector("#typField")
const prevVal = document.querySelector("#prevVal")
const next = document.querySelector("#next")

var aliases = {};

var typs = [];

advFetch("aliases/aliases.json").then(data => {
	for(i in data){
		createAlias(i, data[i]);
	}
})

advFetch("typs/git.json").then(data => {
	_.shuffle(data) .forEach(typ => {
		for(i in typ){
			typs.push(new Typ(i, typ[i]))
		}
	})
}).then(()=> {
	var now = 0;
	next.innerText = typs[now].val;

	typField.disabled = false;
	typField.focus()

	typField.addEventListener("keypress", e => {
		if(e.keyCode == 13){
			var res = typField.value;
			var resArr = res.split(" ");

			// Check if alias and subsitute
			for(var alias in aliases){
				resArr.forEach((word, i) =>{
					if(word == alias){
						resArr[i] = aliases[alias];
					}
				})
			}
			res = resArr.join(" ");

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
