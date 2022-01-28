# Введение
Данное тестовое задание было выполненно на фраемворке laravel, с помощью [WSL](https://docs.microsoft.com/ru-ru/windows/wsl/) подсистемы Ubuntu на Windows 10.
- IDE: VsCode
- PHP 7.4
- Расширения:
1. [Remote-Wsl](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-wsl)
0. [PHP Debug](https://marketplace.visualstudio.com/items?itemName=xdebug.php-debug)
0. [PHP Extension Pack](https://marketplace.visualstudio.com/items?itemName=xdebug.php-pack)
0. [PHP Intelephense](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client)
0. [PHP IntelliSense](https://marketplace.visualstudio.com/items?itemName=zobo.php-intellisense)

# Структура файлов
 
- app/
  - HTTP/
    - Controllers/
       - ControllerParser.php - Главный контроллер проекта.
- resourses/
  - view/
    - layouts/
      - head.blade.php - часть шаблона отвечающий за заголовок html структуры.
    - page/
      - main_title.blade.php - главная странница проекта.
      - parser_load.blade.php - используется для POST запроса с главной страницы.
  - master.blade.php - мастер-шаблон собирающий в себе заголовки и контент html структуры.
- routes/
  - web.php - упрвление роутами.
  
# Запуск
Через терминал в корневой папке проекта.
```php
php artisan serve
```
  




