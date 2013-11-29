Плагин "VsPostman"

СТРУКТУРА И НАЗНАЧЕНИЕ ФАЙЛОВ И ПАПОК
======================================

- controllers - папка с контроллерами
  > Base_Controller.php    - базовый контроллер
  > Clients_Controller.php - контроллер для раздела "Клиенты"
  > Mails_Controller.php   - контроллер для раздела "Воронки"
  > Stats_Controller.php   - контроллер для раздела "Статистика"
+ google      - Google API Client Library
+ img         - папка с картинками
+ libs        - папка с js библиотеками
- templates   - папка с шаблонами
  + clients     - шаблоны для раздела "Клиенты"
  + mails       - шаблоны для раздела "Воронки"
  + stats       - шаблоны для раздела "Статистика"
> ajax.php      - обрабатывает AJAX запросы
> readme.txt    - инструкция
> vspostman.php - основной файл плагина

  
РОУТИНГ, КОНТРОЛЛЕРЫ И ВИДЫ
============================
Типовой URL имеет следующий вид: /wp-admin/admin.php?page=vspostman-clients&act=add
где:
  page - vspostman-[ИМЯ_КОНТРОЛЛЕРА] (например: `vspostman-clients` вызывает контроллер Clients_Controller.php)
  act  - метод контроллера action_[ДЕЙСТВИЕ] (например `action_add`)
  
ДОБАВЛЕНИЕ НОВОГО РАЗДЕЛА В ПЛАГИН
===================================
1. В файле vspostman.php в функцию vspostman_admin_menu добавьте:
  add_submenu_page('vspostman_admin', '[НАИМЕНОВАНИЕ_РАЗДЕЛА]', '[НАИМЕНОВАНИЕ_РАЗДЕЛА]', 'manage_options', '[URL]', '[ФУНКЦИЯ_ОБРАБОТЧИК]');
  
2. Добавьте функцию обработчик, например:
function vspostman_menu_mails() {
    // Добавьте вызов контроллера
    echo _controller('Mails_Controller')->action($_REQUEST['act']);    
}