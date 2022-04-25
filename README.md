### Постановка

> Реализовать Rest API эндпоинт для проведения транзакции начисления условных единиц (денег или баллов) от одного
> пользователя на счет другого. Баланс не может быть отрицательным. В БД проекта должно быть как минимум две обязательных
> таблицы: пользователи и транзакции.
>
> В рамках этой задачи эндпоинт должен быть доступен без авторизации.
>
> Написать интеграционные тесты, покрывающие как минимум два любых тесткейса (например успешную транзакцию и неуспешную)
> .
>
> Подумать, какие подводные камни могут возникнуть при использовании этого эндпоинта в реальном проекте.
>
> Код должен быть оформлен по стандарту PSR-12. Использовать Php 7.4, Последнюю минорную версию Symfony 5, любую удобную
> реляционную СУБД. Для тестов использовать PhpUnit. Должна быть возможность развернуть проект локально и запустить тесты.

### Проблемы

1. Конкурентные запросы на списание. Возможно могут понадобиться изоляции транзакций. Дополнительный
   контроль, чтобы User->amount не ушёл в минус и общие суммы списания не расходились с реальным остатком.
2. Для выборки транзакций понадобятся индексы (для фильтра по пользователям, суммам, датам, что там ещё бизнесу может
   понадобиться для отчётов). Это негативно скажется на производительности вставок/обновления. Решений больше одного (
   мат.представления, другая база (кликхаус или что-то подобное), т.д.), нужно смотреть по конкретной ситуации.

### Установка

Предполагается, что на локальной машине установлен docker и docker-compose.

В корневой директории выполнить:

1. `docker-compose up --build`
3. `docker-compose exec php bin/run_init.sh`

Сайт поднимется на http://localhot:8088. При необходимости можно исправить внешний порт в `docker-compose.yaml:20`.

### Тесты

> При запущенном `docker-compose up --build`

Выполнить в корневой директории `docker-compose exec php bin/run_tests.sh`