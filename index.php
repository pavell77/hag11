<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Редагування Користувачів</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <style>
        body { font-family: sans-serif; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], select { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .edit-btn, .delete-btn { padding: 5px 10px; margin-left: 5px; cursor: pointer; border: 1px solid #ccc; border-radius: 4px; }
        .edit-btn { background-color: #28a745; color: white; }
        .delete-btn { background-color: #dc3545; color: white; }
        .edit-btn:hover, .delete-btn:hover { opacity: 0.8; }
        .error-message { color: red; margin-top: 10px; }
        .success-message { color: green; margin-top: 10px; }
    </style>
</head>
<body>
    <h1>Редагування Користувачів</h1>

    <div id="message" class="success-message" style="display: none;"></div>
    <div id="error-message" class="error-message" style="display: none;"></div>

    <form id="userForm">
        <div class="form-group">
            <label for="name">Ім'я:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="surname">Прізвище:</label>
            <input type="text" id="surname" name="surname" required>
        </div>
        <div class="form-group">
            <label for="position">Посада:</label>
            <select id="position" name="position" required>
                <option value="">Оберіть посаду</option>
                <option value="програміст">програміст</option>
                <option value="менеджер">менеджер</option>
                <option value="тестувальник">тестувальник</option>
            </select>
        </div>
        <input type="hidden" id="user_id" name="user_id">
        <button type="submit">Додати користувача</button>
    </form>

    <h2>Список користувачів</h2>
    <div id="userTableContainer">
        <table id="userTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ім'я</th>
                    <th>Прізвище</th>
                    <th>Посада</th>
                    <th>Дії</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                </tbody>
        </table>
    </div>

    <script src="js/script.js"></script>
</body>
</html>