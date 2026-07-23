<?php

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$host = 'localhost';
$db   = 'modo_carrera';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexion a la base de datos', 'detalle' => $e->getMessage()]);
    exit;
}
function responder($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function error($mensaje, $code = 400) {
    responder(['error' => $mensaje], $code);
}

function body() {
    $data = json_decode(file_get_contents('php://input'), true);
    return $data ?? [];
}

$metodo   = $_SERVER['REQUEST_METHOD'];
$endpoint = $_GET['endpoint'] ?? '';

switch ($endpoint) {
case 'equipos':
        if ($metodo === 'GET') {
            if (isset($_GET['id'])) {
                $stmt = $pdo->prepare("SELECT * FROM equipos WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $equipo = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$equipo) error('Equipo no encontrado', 404);
                responder($equipo);
            } else {
                $stmt = $pdo->query("SELECT * FROM equipos ORDER BY id");
                responder($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
        }

        
        if ($metodo === 'POST') {
            $d = body();
            if (empty($d['nombre']) || empty($d['liga']) || !isset($d['presupuesto'])) {
                error('Faltan campos: nombre, liga, presupuesto');
            }
            $stmt = $pdo->prepare("INSERT INTO equipos (nombre, liga, presupuesto) VALUES (?, ?, ?)");
            $stmt->execute([$d['nombre'], $d['liga'], $d['presupuesto']]);
            responder(['mensaje' => 'Equipo creado', 'id' => $pdo->lastInsertId()], 201);
        }

         if ($metodo === 'PUT') {
            if (!isset($_GET['id'])) error('Falta el parametro id');
            $d = body();
            $stmt = $pdo->prepare("UPDATE equipos SET nombre = ?, liga = ?, presupuesto = ? WHERE id = ?");
            $stmt->execute([$d['nombre'] ?? null, $d['liga'] ?? null, $d['presupuesto'] ?? null, $_GET['id']]);
            if ($stmt->rowCount() === 0) error('Equipo no encontrado o sin cambios', 404);
            responder(['mensaje' => 'Equipo actualizado']);
        }

        if ($metodo === 'DELETE') {
            if (!isset($_GET['id'])) error('Falta el parametro id');
            $stmt = $pdo->prepare("DELETE FROM equipos WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            if ($stmt->rowCount() === 0) error('Equipo no encontrado', 404);
            responder(['mensaje' => 'Equipo eliminado']);
        }
        break;

    case 'jugadores':
        if ($metodo === 'GET') {
            if (isset($_GET['id'])) {
                $stmt = $pdo->prepare("SELECT * FROM jugadores WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $jugador = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$jugador) error('Jugador no encontrado', 404);
                responder($jugador);
            } else {
                $stmt = $pdo->query("SELECT * FROM jugadores ORDER BY id");
                responder($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
        }

        if ($metodo === 'POST') {
            $d = body();
            if (empty($d['nombre']) || empty($d['posicion']) || !isset($d['valor_mercado']) || !isset($d['media_global'])) {
                error('Faltan campos: nombre, posicion, valor_mercado, media_global');
            }
            $stmt = $pdo->prepare("INSERT INTO jugadores (nombre, posicion, valor_mercado, media_global, equipo_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$d['nombre'], $d['posicion'], $d['valor_mercado'], $d['media_global'], $d['equipo_id'] ?? null]);
            responder(['mensaje' => 'Jugador creado', 'id' => $pdo->lastInsertId()], 201);
        }

          if ($metodo === 'PUT') {
            if (!isset($_GET['id'])) error('Falta el parametro id');
            $d = body();
            $stmt = $pdo->prepare("UPDATE jugadores SET nombre = ?, posicion = ?, valor_mercado = ?, media_global = ?, equipo_id = ? WHERE id = ?");
            $stmt->execute([
                $d['nombre'] ?? null,
                $d['posicion'] ?? null,
                $d['valor_mercado'] ?? null,
                $d['media_global'] ?? null,
                $d['equipo_id'] ?? null,
                $_GET['id']
            ]);
            if ($stmt->rowCount() === 0) error('Jugador no encontrado o sin cambios', 404);
            responder(['mensaje' => 'Jugador actualizado']);
        }
        if ($metodo === 'DELETE') {
            if (!isset($_GET['id'])) error('Falta el parametro id');
            $stmt = $pdo->prepare("DELETE FROM jugadores WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            if ($stmt->rowCount() === 0) error('Jugador no encontrado', 404);
            responder(['mensaje' => 'Jugador eliminado']);
        }
        break;
    case 'jugadores_buscar':
        if ($metodo !== 'GET') error('Metodo no permitido', 405);
        if (!isset($_GET['nombre'])) error('Falta el parametro nombre');
        $stmt = $pdo->prepare("SELECT * FROM jugadores WHERE nombre LIKE ?");
        $stmt->execute(['%' . $_GET['nombre'] . '%']);
        responder($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

     case 'jugadores_valor':
        if ($metodo !== 'GET') error('Metodo no permitido', 405);
        $min = $_GET['min'] ?? 0;
        $max = $_GET['max'] ?? 999999999999;
        $stmt = $pdo->prepare("SELECT * FROM jugadores WHERE valor_mercado BETWEEN ? AND ? ORDER BY valor_mercado DESC");
        $stmt->execute([$min, $max]);
        responder($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'jugadores_posicion':
        if ($metodo !== 'GET') error('Metodo no permitido', 405);
        if (!isset($_GET['posicion'])) error('Falta el parametro posicion');
        $stmt = $pdo->prepare("SELECT * FROM jugadores WHERE posicion = ?");
        $stmt->execute([$_GET['posicion']]);
        responder($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

   case 'jugadores_top':
        if ($metodo !== 'GET') error('Metodo no permitido', 405);
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
        $stmt = $pdo->prepare("SELECT * FROM jugadores ORDER BY media_global DESC LIMIT ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        responder($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

   case 'equipo_jugadores':
        if ($metodo !== 'GET') error('Metodo no permitido', 405);
        if (!isset($_GET['id'])) error('Falta el parametro id');
        $stmt = $pdo->prepare("
            SELECT j.*, e.nombre AS equipo_nombre
            FROM jugadores j
            JOIN equipos e ON j.equipo_id = e.id
            WHERE e.id = ?
        ");
        $stmt->execute([$_GET['id']]);
        responder($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

   case 'equipos_presupuesto':
        if ($metodo !== 'GET') error('Metodo no permitido', 405);
        $stmt = $pdo->query("SELECT * FROM equipos ORDER BY presupuesto DESC");
        responder($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'transferencias':
        if ($metodo !== 'GET') error('Metodo no permitido', 405);
        $stmt = $pdo->query("
            SELECT t.id, j.nombre AS jugador, 
                   eo.nombre AS equipo_origen, 
                   ed.nombre AS equipo_destino, 
                   t.monto_pagado, t.fecha
            FROM transferencias t
            JOIN jugadores j ON t.jugador_id = j.id
            LEFT JOIN equipos eo ON t.equipo_origen_id = eo.id
            JOIN equipos ed ON t.equipo_destino_id = ed.id
            ORDER BY t.fecha DESC
        ");
        responder($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'transferir':
        if ($metodo !== 'POST') error('Metodo no permitido', 405);
        $d = body();
        if (empty($d['jugador_id']) || empty($d['equipo_destino_id']) || !isset($d['monto_pagado'])) {
            error('Faltan campos: jugador_id, equipo_destino_id, monto_pagado');
        }

        $stmtJugador = $pdo->prepare("SELECT * FROM jugadores WHERE id = ?");
        $stmtJugador->execute([$d['jugador_id']]);
        $jugador = $stmtJugador->fetch(PDO::FETCH_ASSOC);
        if (!$jugador) error('Jugador no encontrado', 404);

        $stmtDestino = $pdo->prepare("SELECT * FROM equipos WHERE id = ?");
        $stmtDestino->execute([$d['equipo_destino_id']]);
        $destino = $stmtDestino->fetch(PDO::FETCH_ASSOC);
        if (!$destino) error('Equipo destino no encontrado', 404);

        if ($destino['presupuesto'] < $d['monto_pagado']) {
            error('El equipo destino no tiene presupuesto suficiente', 400);
        }

        $equipoOrigenId = $jugador['equipo_id'];
        $fecha = $d['fecha'] ?? date('Y-m-d');

        $pdo->beginTransaction();
        try {
            $pdo->prepare("UPDATE equipos SET presupuesto = presupuesto - ? WHERE id = ?")
                ->execute([$d['monto_pagado'], $d['equipo_destino_id']]);

            $pdo->prepare("UPDATE jugadores SET equipo_id = ? WHERE id = ?")
                ->execute([$d['equipo_destino_id'], $d['jugador_id']]);

            $pdo->prepare("INSERT INTO transferencias (jugador_id, equipo_origen_id, equipo_destino_id, monto_pagado, fecha) VALUES (?, ?, ?, ?, ?)")
                ->execute([$d['jugador_id'], $equipoOrigenId, $d['equipo_destino_id'], $d['monto_pagado'], $fecha]);

            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            error('Error al procesar la transferencia: ' . $e->getMessage(), 500);
        }

        responder(['mensaje' => 'Transferencia realizada con exito'], 201);
        break;

    default:
        error('Endpoint no encontrado. Usa ?endpoint=NOMBRE. Consulta el README para la lista completa.', 404);
}
