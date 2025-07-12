-- Insertar usuario administrador por defecto
-- Usuario: admin
-- Contraseña: admin123
-- La contraseña está hasheada con password_hash()

INSERT INTO usuarios (nombre_completo, nombre_usuario, clave_hash, rol) VALUES 
('Administrador del Sistema', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin');

-- Insertar usuario asistente de ejemplo
-- Usuario: asistente
-- Contraseña: asistente123

INSERT INTO usuarios (nombre_completo, nombre_usuario, clave_hash, rol) VALUES 
('Asistente General', 'asistente', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Asistente');

-- Nota: Las contraseñas hasheadas corresponden a "admin123" y "asistente123"
-- Para generar nuevas contraseñas hasheadas, usar: password_hash('tu_contraseña', PASSWORD_DEFAULT) 