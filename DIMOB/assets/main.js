document.addEventListener('DOMContentLoaded', function () {
	const loginForm = document.getElementById('loginForm');
	if (loginForm) {
		loginForm.addEventListener('submit', function (e) {
			e.preventDefault();
			// aqui você pode adicionar chamada ao backend
			alert('Login enviado (simulado)');
			// exemplo: window.location.href = 'index.html';
		});
	}

	const signupForm = document.getElementById('signupForm');
	if (signupForm) {
		signupForm.addEventListener('submit', function (e) {
			e.preventDefault();
			const pw = document.getElementById('password').value;
			const pw2 = document.getElementById('password2').value;
			if (pw !== pw2) {
				alert('As senhas não coincidem');
				return;
			}
			// aqui você pode enviar dados ao backend
			alert('Conta criada (simulado)');
			window.location.href = 'login.html';
		});
	}
});
