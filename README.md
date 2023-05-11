## Алгоритм работы ##
1. Клонируем репозиторий (git clone https://github.com/slem7451/dean-office.git)
2. Все команды выполнются в корне проекта (Какой-то путь\dean-office)
3. Переключаемся на ветку dev (git checkout dev)
4. Создаем ветку со своим ФИО, например KMA, PES, ADA, AID (git checkout -b FIO)
5. Выполняем команды по установке
6. Созданные файлы редактировать только согласовав это со всеми
7. Оформление делаем, как у остальных, чтобы оно получилось цельным
8. Чужие таблицы БД, как и с файлами, при редактировнии согласовывать со всеми
9. Преред слиянием своей ветки с веткой dev, написать Кузнецову Михаилу Андреевичу 24.10.2001 года рождения
10. Созданные файлы yii2 (Например, User, LoginForm, SignupForm) лучше не менять, а дополнять
11. Кто нарушит пункты, получит по жопе
## Установка ##
1. composer install
2. php init (выбираем 0 (development))
3. создаём у себя БД dean
4. php yii migrate
## Запуск сервера ##
1. php yii serve --docroot="frontend/web/" --port=8888
2. В браузере переходим на http://localhost:8888/
## Полезные ссылки ##
- https://www.yiiframework.com/doc/guide/2.0/ru
- https://metanit.com/php/tutorial
- https://getbootstrap.com (на русском: https://bootstrap5.ru)
- https://smartiqa.ru/courses/git
- https://www.php.net
- https://yiipowered.com/ru
- https://adminlte.io/themes/v3
