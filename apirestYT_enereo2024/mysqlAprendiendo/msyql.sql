SELECT MAX(PacienteId) + 1 INTO @max_id FROM pacientes;


SET @query = CONCAT('ALTER TABLE pacientes AUTO_INCREMENT = ', @max_id);
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @max_id = NULL;