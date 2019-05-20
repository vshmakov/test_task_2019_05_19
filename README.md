Test task
===============


System requirements
----------------

- PHP 7.2
- Composer
- node.js
- npm
- PostgreSQL

Installation
----------------

- git clone https://gitlab.org/shm-vadim/test_task_2019_05_19
- cd test_task_2019_05_19
- composer install --no-scripts
- Create .env.local and set there your own DATABASE_URL like in .env
- bin/console doctrine:database:create && bin/console doctrine:migrations:migrate -n && bin/console doctrine:fixtures:load -n
- composer run-script auto-scripts
- npm install
- npm run dev
- bin/console server:run
- Open http://localhost:8000 in yor browser.

Task description
----------------

Необходимо Создать приложение-задачник.

Задачи состоят из:
- имени пользователя;
- е-mail;
- текста задачи;

Стартовая страница - список задач с возможностью сортировки по имени пользователя, email и статусу. 
Вывод задач нужно сделать страницами по 3 штуки (с пагинацией). Видеть список задач и создавать новые может любой посетитель без регистрации.

Сделайте вход для администратора (логин "admin", пароль "123"). Администратор имеет возможность поставить галочку о выполнении. 
Выполненные задачи в общем списке выводятся с соответствующей отметкой.

В приложении нужно реализовать с помощью Symfony 4. Хранить задачи в Postgres. Верстка на bootstrap, к дизайну особых требований нет. Реализацию залить на gitlab и предоставить ссылку. Реальное demo не обязательно, но будет плюсом.