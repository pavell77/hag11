<?php
// Підключення до бази даних
$servername = "localhost";
$username = "root"; // root без пароля в WAMP
$password = "";
$dbname = "users_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(['error' => 'Помилка підключення до бази даних: ' . $e->getMessage()]);
    exit();
}

// Функція для отримання всіх користувачів
function getUsers($conn) {
    $stmt = $conn->prepare("SELECT id, name, surname, position FROM users");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Функція для додавання нового користувача
function addUser($conn, $name, $surname, $position) {
    $stmt = $conn->prepare("INSERT INTO users (name, surname, position) VALUES (:name, :surname, :position)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':surname', $surname);
    $stmt->bindParam(':position', $position);
    if ($stmt->execute()) {
        return $conn->lastInsertId();
    }
    return false;
}

// Функція для отримання даних користувача за ID
function getUserById($conn, $id) {
    $stmt = $conn->prepare("SELECT id, name, surname, position FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Функція для оновлення даних користувача
function updateUser($conn, $id, $name, $surname, $position) {
    $stmt = $conn->prepare("UPDATE users SET name = :name, surname = :surname, position = :position WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':surname', $surname);
    $stmt->bindParam(':position', $position);
    return $stmt->execute();
}

// Функція для видалення користувача
function deleteUser($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

// Обробка AJAX запитів
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    switch ($action) {
        case 'getUsers':
            $users = getUsers($conn);
            echo json_encode($users);
            break;
        case 'addUser':
            $name = $_POST['name'] ?? '';
            $surname = $_POST['surname'] ?? '';
            $position = $_POST['position'] ?? '';
            if (!empty($name) && !empty($surname) && !empty($position)) {
                $userId = addUser($conn, $name, $surname, $position);
                if ($userId) {
                    echo json_encode(['status' => 'success', 'message' => 'Користувача додано', 'id' => $userId]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Помилка при додаванні користувача']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Будь ласка, заповніть всі поля']);
            }
            break;
        case 'getUser':
            $id = $_POST['id'] ?? '';
            if (!empty($id) && is_numeric($id)) {
                $user = getUserById($conn, $id);
                if ($user) {
                    echo json_encode(['status' => 'success', 'user' => $user]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Користувача не знайдено']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Невірний ID користувача']);
            }
            break;
        case 'updateUser':
            $id = $_POST['user_id'] ?? '';
            $name = $_POST['name'] ?? '';
            $surname = $_POST['surname'] ?? '';
            $position = $_POST['position'] ?? '';
            if (!empty($id) && is_numeric($id) && !empty($name) && !empty($surname) && !empty($position)) {
                if (updateUser($conn, $id, $name, $surname, $position)) {
                    echo json_encode(['status' => 'success', 'message' => 'Дані користувача оновлено']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Помилка при оновленні користувача']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Невірні дані для оновлення']);
            }
            break;
        case 'deleteUser':
            $id = $_POST['id'] ?? '';
            if (!empty($id) && is_numeric($id)) {
                if (deleteUser($conn, $id)) {
                    echo json_encode(['status' => 'success', 'message' => 'Користувача видалено']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Помилка при видаленні користувача']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Невірний ID користувача для видалення']);
            }
            break;
        default:
            echo json_encode(['error' => 'Невідома дія']);
            break;
    }
} else {
    echo json_encode(['error' => 'Доступ заборонено']);
}

$conn = null; // Закриття з'єднання з базою даних