document.addEventListener('DOMContentLoaded', function () {
let currentCategory = 'all'; // По умолчанию все категории
let offset = 0;              // Начальная позиция
const limit = 6;             // Количество записей за один запрос

// Обработчик кликов по категориям
document.querySelectorAll('.head div[data-category]').forEach(category => {
category.addEventListener('click', function () {
 currentCategory = this.getAttribute('data-category');
 offset = 0; // Сброс смещения при смене категории
 loadProducts(currentCategory, offset, true);
});
});

// Обработчик кнопки "Все кейсы"
document.querySelector('.selected').addEventListener('click', function () {
currentCategory = 'all'; // Сброс категории к "все"
offset = 0;              // Сброс смещения
loadProducts(currentCategory, offset, true);
});

// Обработчик кнопки "Загрузить еще"
document.getElementById('loadMore').addEventListener('click', function () {
offset += limit;
loadProducts(currentCategory, offset, false);
});

// Функция загрузки продуктов (AJAX)
function loadProducts(category, offset, clear) {
console.log(`Загрузка продуктов для категории: ${category}, смещение: ${offset}`);
const xhr = new XMLHttpRequest();
xhr.open('POST', my_ajax_object.ajax_url, true);  // Используем admin-ajax.php URL
xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
xhr.onload = function () {
 if (this.status === 200) {
     console.log(`Ответ от сервера: ${this.responseText}`);
     const response = JSON.parse(this.responseText);
     if (clear) {
         document.querySelector('.cases').innerHTML = '';
     }
     document.querySelector('.cases').insertAdjacentHTML('beforeend', response.html);
     if (response.has_more === false) {
         document.getElementById('loadMore').style.display = 'none';
     } else {
         document.getElementById('loadMore').style.display = 'block';
     }
 } else {
     console.log('Ошибка загрузки данных с сервера');
 }
};
xhr.send(`action=load_products&category=${category}&offset=${offset}&limit=${limit}&nonce=${my_ajax_object.nonce}`);
}

// Первоначальная загрузка всех записей
loadProducts('all', offset, true);
});


