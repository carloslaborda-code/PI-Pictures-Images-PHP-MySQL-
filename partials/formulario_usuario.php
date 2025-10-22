<?php if (!defined('FROM_INDEX')) { header("HTTP/1.0 403 Forbidden"); exit("Acceso directo no permitido."); } ?>

<form class="formulario-registro" action="<?php echo htmlspecialchars($action_url); ?>" method="post" enctype="multipart/form-data" novalidate>
    <label for="username">Nombre de Usuario:</label>
    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" <?php echo $modo_edicion ? 'readonly' : ''; ?>>
    <?php if (!empty($errores['username'])): ?>
        <p class="error" style="color: red;"><?php echo $errores['username']; ?></p>
    <?php endif; ?>

    <label for="email">Dirección de Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
    <?php if (!empty($errores['email'])): ?>
        <p class="error" style="color: red;"><?php echo $errores['email']; ?></p>
    <?php endif; ?>

    <?php if ($modo_edicion): ?>
        <!-- Solo en la página "Mis Datos" se muestra la contraseña actual para validar cambios -->
        <label for="current_password">Contraseña Actual:</label>
        <input type="password" id="current_password" name="current_password" required>
        <?php if (!empty($errores['current_password'])): ?>
            <p class="error" style="color: red;"><?php echo $errores['current_password']; ?></p>
        <?php endif; ?>

        <!-- Campos de "Nueva Contraseña" y "Repetir Nueva Contraseña" para modificar la contraseña -->
        <label for="password">Nueva Contraseña:</label>
        <input type="password" id="password" name="password">
        <?php if (!empty($errores['password'])): ?>
            <p class="error" style="color: red;"><?php echo $errores['password']; ?></p>
        <?php endif; ?>

        <label for="confirm_password">Repetir Nueva Contraseña:</label>
        <input type="password" id="confirm_password" name="confirm_password">
        <?php if (!empty($errores['confirm_password'])): ?>
            <p class="error" style="color: red;"><?php echo $errores['confirm_password']; ?></p>
        <?php endif; ?>
    <?php else: ?>
        <!-- Solo en la página de registro se muestra "Contraseña" y "Repetir Contraseña" -->
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <?php if (!empty($errores['password'])): ?>
            <p class="error" style="color: red;"><?php echo $errores['password']; ?></p>
        <?php endif; ?>

        <label for="confirm_password">Repetir Contraseña:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <?php if (!empty($errores['confirm_password'])): ?>
            <p class="error" style="color: red;"><?php echo $errores['confirm_password']; ?></p>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Los demás campos del formulario -->
    <label for="sexo">Sexo:</label>
    <select id="sexo" name="sexo">
        <option value="">Selecciona</option>
        <option value="masculino" <?php echo ($sexo === 'masculino') ? 'selected' : ''; ?>>Masculino</option>
        <option value="femenino" <?php echo ($sexo === 'femenino') ? 'selected' : ''; ?>>Femenino</option>
        <option value="otro" <?php echo ($sexo === 'otro') ? 'selected' : ''; ?>>Otro</option>
        <option value="prefiere_no_decirlo" <?php echo ($sexo === 'prefiere_no_decirlo') ? 'selected' : ''; ?>>Prefiero no decirlo</option>
    </select>
    <?php if (!empty($errores['sexo'])): ?>
        <p class="error" style="color: red;"><?php echo $errores['sexo']; ?></p>
    <?php endif; ?>

    <label for="birthdate">Fecha de Nacimiento:</label>
    <input type="date" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($birthdate ?? ''); ?>" required>
    <?php if (!empty($errores['birthdate'])): ?>
        <p class="error" style="color: red;"><?php echo $errores['birthdate']; ?></p>
    <?php endif; ?>

    <label for="city">Ciudad:</label>
    <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($city ?? ''); ?>">
    <?php if (!empty($errores['city'])): ?>
        <p class="error" style="color: red;"><?php echo $errores['city']; ?></p>
    <?php endif; ?>

    <label for="profile_picture">Foto de Perfil:</label>
    <input type="file" id="profile_picture" name="profile_picture" accept="image/*">

    <?php if ($modo_edicion && !empty($foto_actual) && $foto_actual !== 'default-profile.JPG'): ?>
        <p>Foto Actual:</p>
        <img src="/daw/practica_dawpr7/image/profiles/<?php echo htmlspecialchars($foto_actual); ?>" 
            alt="Foto de perfil" width="100">
        <label>
            <input type="checkbox" name="eliminar_foto" value="1"> Eliminar foto actual
        </label>
    <?php endif; ?>




    <label for="pais">País:</label>
    <select id="pais" name="pais" required>
        <option value="">Selecciona tu país</option>
        <?php foreach ($paises as $pais): ?>
            <option value="<?php echo htmlspecialchars($pais['IdPais']); ?>" <?php echo ($pais_id == $pais['IdPais']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($pais['NomPais']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php if (!empty($errores['pais'])): ?>
        <p class="error" style="color: red;"><?php echo $errores['pais']; ?></p>
    <?php endif; ?>

    <button type="submit"><?php echo $modo_edicion ? 'Guardar Cambios' : 'Registrarse'; ?></button>
</form>
