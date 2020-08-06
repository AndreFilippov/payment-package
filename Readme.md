### Документация

#### Методы:
- GET: `/paymentmodule/getRateCurrency` - конвертирование валюты в рубли.

##### Параметры:
> `code` - код валюты. Например: `USD`; 

> `amount` - сумма для конвертирования;

##### Ответ:
> `status` - статус запроса `true` или `false`;

>`rate_amount` - конвертированная сумма (только при status = `true`);
---
- POST: `paymentmodule/sendPay` - запрос в сервис для пополнения баланса.

Параметры:
>`method` - код платежной системы [`xyz`,`qwerty`,`oldpay`];

>`amount` - сумма платежа [`xyz`,`qwerty`,`oldpay`];

>`code` - код валюты. По умолчанию `RUB`;

>`name` - имя плательщика (для системы `xyz`. необязательно);

Ответ:
>`success` - в случае успеха;

>`error` - в случае ошибки;
---
#### Настройка платежных систем:
 - XYZ: 
> Url для обращения: host.ru`/paymentmodule/notification/xyz`;

>Секретный ключ указывается в `env` файле параметром: `XyzServiceKey`;
---
 - Qwerty: 
>Url для обращения: host.ru`/paymentmodule/notification/qwerty`;

>Секретный ключ указывается в `env` файле параметром: `QwertyServiceKey`;
---
 - OldPay: 
>Url для обращения: host.ru`/paymentmodule/notification/oldpay`;

>Секретный ключ указывается в `env` файле параметром: `OldPayServiceKey`;
