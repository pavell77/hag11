$(document).ready(function() {
    // Функція для завантаження списку користувачів
    function loadUsers() {
        $.ajax({
            url: 'app/api.php',
            type: 'POST',
            dataType: 'json',
            data: { action: 'getUsers' },
            success: function(response) {
                $('#userTableBody').empty();
                if (response && response.length > 0) {
                    $.each(response, function(index, user) {
                        $('#userTableBody').append(`
                            <tr>
                                <td>${user.id}</td>
                                <td>${user.name}</td>
                                <td>${user.surname}</td>
                                <td>${user.position}</td>
                                <td>
                                    <button class="edit-btn" data-id="${user.id}">Редагувати</button>
                                    <button class="delete-btn" data-id="${user.id}">Видалити</button>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    $('#userTableBody').append('<tr><td colspan="5">Немає користувачів</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error("Помилка завантаження користувачів:", error);
                $('#userTableBody').empty().append('<tr><td colspan="5">Помилка завантаження даних</td></tr>');
            }
        });
    }

    // Завантаження користувачів при завантаженні сторінки
    loadUsers();

    // Обробка відправки форми
    $('#userForm').submit(function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        const action = $('#user_id').val() ? 'updateUser' : 'addUser';
        $.ajax({
            url: 'app/api.php',
            type: 'POST',
            dataType: 'json',
            data: formData + '&action=' + action,
            success: function(response) {
                if (response.status === 'success') {
                    $('#message').text(response.message).show();
                    $('#error-message').hide();
                    $('#userForm')[0].reset();
                    $('#user_id').val(''); // Очистити приховане поле
                    loadUsers();
                } else {
                    $('#error-message').text(response.message).show();
                    $('#message').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error("Помилка відправки форми:", error);
                $('#error-message').text('Помилка при відправці даних').show();
                $('#message').hide();
            }
        });
    });

    // Обробка натискання кнопки "Редагувати"
    $('#userTable').on('click', '.edit-btn', function() {
        const userId = $(this).data('id');
        $.ajax({
            url: 'app/api.php',
            type: 'POST',
            dataType: 'json',
            data: { action: 'getUser', id: userId },
            success: function(response) {
                if (response.status === 'success' && response.user) {
                    $('#name').val(response.user.name);
                    $('#surname').val(response.user.surname);
                    $('#position').val(response.user.position);
                    $('#user_id').val(response.user.id);
                    // Змінити текст кнопки "Додати" на "Зберегти"
                    $('#userForm button[type="submit"]').text('Зберегти');
                } else {
                    alert(response.message || 'Помилка при отриманні даних користувача');
                }
            },
            error: function(xhr, status, error) {
                console.error("Помилка отримання даних користувача для редагування:", error);
                alert('Помилка при отриманні даних для редагування');
            }
        });
    });

    // Обробка натискання кнопки "Видалити"
    $('#userTable').on('click', '.delete-btn', function() {
        const userId = $(this).data('id');
        if (confirm('Ви впевнені, що хочете видалити цього користувача?')) {
            $.ajax({
                url: 'app/api.php',
                type: 'POST',
                dataType: 'json',
                data: { action: 'deleteUser', id: userId },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#message').text(response.message).show();
                        $('#error-message').hide();
                        loadUsers();
                    } else {
                        $('#error-message').text(response.message).show();
                        $('#message').hide();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Помилка видалення користувача:", error);
                    $('#error-message').text('Помилка при видаленні користувача').show();
                    $('#message').hide();
                }
            });
        }
    });

    // Скидання форми при натисканні кнопки "Додати" (якщо були зміни)
    $('#userForm').on('reset', function() {
        $('#user_id').val('');
        $('#userForm button[type="submit"]').text('Додати користувача');
    });
});