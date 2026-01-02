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

	// social buttons now use server-side redirects; no client handlers needed
});

// Reveal elements on scroll using IntersectionObserver
document.addEventListener('DOMContentLoaded', function () {
	const reveals = document.querySelectorAll('.reveal');
	if ('IntersectionObserver' in window) {
		const obs = new IntersectionObserver((entries) => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					entry.target.classList.add('active');
					obs.unobserve(entry.target);
				}
			});
		}, { threshold: 0.15 });

		reveals.forEach(r => obs.observe(r));
	} else {
		// Fallback: show all
		reveals.forEach(r => r.classList.add('active'));
	}
});

/* Header hide-on-scroll-down (reduced) and show-on-scroll-up */
document.addEventListener('DOMContentLoaded', function () {
	const header = document.querySelector('.navbar');
	if (!header) return;
	let lastY = window.scrollY;
	let headerHeight = header.offsetHeight;

	// Initialize CSS variable and body padding
	function setHeaderHeight() {
		headerHeight = header.offsetHeight;
		document.documentElement.style.setProperty('--header-height', headerHeight + 'px');
		document.body.style.paddingTop = headerHeight + 'px';
	}
	setHeaderHeight();

	// threshold to avoid flicker
	const DELTA = 8;

	let ticking = false;
	function onScroll() {
		const currentY = window.scrollY;
		const delta = currentY - lastY;

		if (delta > DELTA) {
			// scrolling down -> make compact
			header.classList.add('compact');
		} else if (delta < -DELTA) {
			// scrolling up -> restore
			header.classList.remove('compact');
		}

		lastY = currentY;
		ticking = false;
	}

	window.addEventListener('scroll', function () {
		if (!ticking) {
			window.requestAnimationFrame(onScroll);
			ticking = true;
		}
	}, { passive: true });

	window.addEventListener('resize', function () {
		setHeaderHeight();
	});

	// clicking the toggler should restore full header
	const toggler = document.querySelector('.navbar-toggler');
	if (toggler) {
		toggler.addEventListener('click', function () {
			header.classList.remove('compact');
		});
	}
});
