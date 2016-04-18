document.querySelector('form').onsubmit = function (e){
	this.pw.value = md5(this.login.value + md5(this.pw.value));
};
