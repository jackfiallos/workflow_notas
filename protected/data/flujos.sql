# Caracteristicas tipo (D1-D2-D3-D4) Nivel #1
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (1, NULL, 1);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (1, NULL, 2);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (1, NULL, 3);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (1, NULL, 4);

# Caracteristicas tipo (D1-D2-D3-D4) Nivel #2 con la regla monto <= 150000
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (2, '<=150000', 1);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (2, '<=150000', 2);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (2, '<=150000', 3);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (2, '<=150000', 4);

# Caracteristicas tipo (D1-D2-D3-D4) Nivel #2 con la regla monto > 150000
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (2, '>150000', 1);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (2, '>150000', 2);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (2, '>150000', 3);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (2, '>150000', 4);

# Caracteristicas tipo (D5) Nivel #1
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (1, '<=50000', 5);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (1, '<=500000', 5);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (1, '>500000', 5);

# Caracteristicas tipo (D5) Nivel #2
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (2, NULL, 5);

# Caracteristicas tipo (D5) Nivel #3
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (3, '<=150000', 5);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (3, '>150000', 5);

# Caracteristicas tipo (DC1-DC2-CC1) Nivel #1
# DC1
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (1, '<=50000', 6);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (1, '<=500000', 6);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (1, '>500000', 6);
# DC2
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (1, '<=50000', 7);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (1, '<=500000', 7);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (1, '>500000', 7);
# CC1
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (1, '<=50000', 8);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (1, '<=500000', 8);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (1, '>500000', 8);

# Caracteristicas tipo (DC1-DC2-CC1) Nivel #2
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (2, NULL, 6);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (2, NULL, 7);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (2, NULL, 8);

# Caracteristicas tipo (DC1-DC2-CC1) Nivel #3
# DC1
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (3, '<=150000', 6);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (3, '>150000', 6);
# DC2
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (3, '<=150000', 7);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (3, '>150000', 7);
# CC1
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (3, '<=150000', 8);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (3, '>150000', 8);

# Caracteristicas tipo (RF1) Nivel #1
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (1, NULL, 9);

# Caracteristicas tipo (RF1) Nivel #2
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (2, '<=50000', 9);
INSERT INTO catFlujos (nivel_aprobacion, expresion, caracteristicasTipo_id) VALUES (2, '>50000', 9);
