# BTC application

Скопіюйте з репозиторію додаток
```bash
git clone https://github.com/nick-mad/btc.git
cd btc 
```
Замніть значення у файлі docker-compose.yml значення середовища  `MAIL_SERVER`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_USERNAME` на відповідні параметри вашого SMTP серверу

Для запуску додатку в Docker потрібно виконати
```bash
docker compose up --build
```

Після цього додаток буде доступний за посиланням `http://localhost/`

`api/rate` Отримати поточний курс BTC до UAH

Отримуємо курс з стороннього сервісу, якщо цей сервіс недоступний, то намагаэмось отримати дані з іншого сервісу.
Якщо не вдалося отримати поточний курс, то віддаємо помилку 400 Invalid status value.

`api/subscribe` Підписати email на отримання поточного курсу

Для реалізації додатку було використано фреймворк slimphp, з використанням skeleton-api.
Каркас реалізовує invokable контролери, та роботу з сущностями на основі патерну reposotory.
