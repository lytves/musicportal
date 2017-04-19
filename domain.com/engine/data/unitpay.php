<?php

class Config
{
    // Ваш секретный ключ (из настроек проекта в личном кабинете unitpay.ru )
    const SECRET_KEY = '...';
    // МИНИМАЛЬНАЯ !!!  Стоимость товара в руб.
    const ITEM_PRICE = 60;

    // Параметры соединения с бд
    // Хост
    const DB_HOST = 'localhost';
    // Имя пользователя
    const DB_USER = 'domain';
    // Пароль
    const DB_PASS = 'KgnunGT8';
    // Назывние базы
    const DB_NAME = 'domain';
}
