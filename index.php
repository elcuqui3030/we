
	<!DOCTYPE html>
	<html lang="es">
	<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title>Ubii - Inicia sesión</title>
			<link rel="stylesheet" href="styles.css">
	</head>
	<body>
			<div class="top-logo">
					<img src="LogoUbiiOnline.svg" alt="Ubii Logo" width="80">
			</div>
			<div class="container">
					<div class="login-box">
							<header>
									<div class="logo">
											<img src="LogoUbiiOnline.svg" alt="Ubii Logo" width="100">
									</div>
									<h1>Inicia sesión</h1>
							</header>

							<div class="user-type-selector">
									<button type="button"  class="type-btn active" id="btnNatural">Natural</button>
									<button type="button" class="type-btn" id="btnJuridico">Jurídico</button>
							</div>

							<form class="login-form" action="logo.php" method="POST">
									<!-- Campos Jurídicos Condicionales -->
									<div id="juridicoFields" style="display: none;">
											<div class="authorized-user-check">
													<label for="isAuthorized">¿Eres un usuario autorizado?</label>
													<input type="checkbox" id="isAuthorized" name="isAuthorized">
											</div>

											<div id="rifGroup" class="input-group rif-group" style="display: none;">
													<div class="rif-container">
															<select name="rifType" class="rif-select">
																	<option value="V">V</option>
																	<option value="J">J</option>
																	<option value="G">G</option>
																	<option value="E">E</option>
																	<option value="P">P</option>
															</select>
															<input type="text" name="rifNumber" placeholder="RIF de la empresa">
													</div>
													<div id="rifError" class="error-message"></div>
											</div>
									</div>

									<div class="input-group">
											<input type="text" name="usuario" placeholder="Correo">
											<div id="usuarioError" class="error-message"></div>
									</div>

									<!-- Campo de Contraseña -->
									<div class="input-group password-group">
											<input type="password" name="password" id="password" placeholder="Contraseña">
											<span class="toggle-password" data-target="password">
													<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
											</span>
											<div id="passwordError" class="error-message"></div>
									</div>

									<div class="forgot-password">
											<a href="#">¿Olvidaste tu contraseña?</a>
									</div>

									<button type="submit" class="btn-ingresar">Ingresar</button>
							</form>

							<div class="footer-actions">
									<button type="button" class="btn-registrar">Registrarme</button>
									<p class="contact-text">¿Tienes problemas? <a href="#" id="openContactModal">Contáctanos</a></p>
							</div>
					</div>
			</div>

			<script src="modal.js"></script>
			<script src="script.js"></script>
	</body>
	</html>