# [СВЕТ](http://svettiflo.ru/) #

Первый российский онлайн-кинотеатр для слепых заработал по адресу [svettiflo.ru](http://svettiflo.ru/). Сайт разработан [«Теплицей социальных технологий»](http://te-st.ru) для некоммерческой организации «Свет».

Онлайн-кинотеатр «Свет» работает на технологии тифлокомментирования. Тифлокомментарий в кино – это дополнительная звуковая дорожка, содержащая закадровое описание видеоряда. Тифлокомментарии составляются по определенным строгим правилам, позволяющим максимально раскрыть происходящее на экране, и озвучиваются профессиональным диктором в паузы между диалогами действующих лиц.

Онлайн-кинотеатр работает на платформе WordPress и соответствует уровню ААА – самому высокому стандарту доступности, описанному в Руководстве по созданию веб-содержания для людей с различными ограничениями WCAG 2.0 (Web Content Accessibility Guidelines).


# Установка

Тема оформления устанавливается в соответствии я обычным процессом установки тем для WordPress. Для корректной работы требуется также установка следующих плагинов.

* [Advanced Custom Fields Pro](http://www.advancedcustomfields.com/)
* [Онлайн Лейка](http://www.advancedcustomfields.com/)
* [Simple CSS for widgets](https://wordpress.org/plugins/simple-css-for-widgets/)

Рекомендуется также использовать плагины
 
* [Cyr to Lat Enhanced](https://wordpress.org/plugins/cyr3lat/)
* [Disable comments](https://wordpress.org/plugins/disable-comments/)

Для создания элементов контента (страницы, элементы каталога, новости) - используются стандартные средства WordPress.

Для корректного отображения элементов шаблонов, необходимо создать следующие мета-поля (используя возможности плагина ACF)

**Для главной страницы**

* news_per_page - тип: Текст - количество новостей, выводимых на главной
* bottom_content - тип: Область текста - содержание для нижней секции страницы

**Для записей**

* show_thumbnail - тип: Истина/Ложь - переключатель, позволяющий скрывать миниатюру на странице отдельной записи (поста)

**Для старинц**

* page_blocks тип: Гибкое содержание - гибридное поле, позволяющее создавать блоки контента, встроенные в страницу

Блок _pic_block_ - описывает блок ссылки с изображением и заголовком, содержит внутренние поля

* block_pic - Изображение
* connected_page - Связанная страница
* block_title - Заголовок
* block_link - Ссылка блока
* block_color - Цветовая гамма (радио-кнопка со значениями: blue, red)

Блок _img_block_ - описывает блок ссылки с изображением, содержит внутренние поля

* block_pic - Изображение
* block_link - Ссылка блока

Блок _text_block_ - описывает текстовый блок, содержит внутренние поля

* block_desc - Текстовое содержание
* connected_page - Связанная страница
* block_title - Заголовок
* block_link - Ссылка блока
* block_color - Цветовая гамма (радио-кнопка со значениями: blue, red)

**Для фильмов (элементов каталога - типа записей film)**

* film_year - тип: Текст - Год выпуска
* film_country - тип: Текст - Страна
* film_duration - тип: Текст - Продолжительность
* film_genre - тип: Текст - Жанр
* film_director - тип: Текст - Режиссер
* film_crew - тип: Область текста - В ролях
* film_video - тип: Область текста - Код вставки видео

