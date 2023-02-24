<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Iodev\Whois\Factory;


class DomainController extends Controller
{

    public function store()
    {
        //Вылавливание данных из клиента
        $whois = Factory::get()->createWhois();
        // Отлавливание данных из клиента
        $domains = request()->all(); // приходит массив 

        // Массив для собирания проверенных данных
        $backArray = [];

        // Функция для валидации данных на домен
        function isValidArr($elem)
        {
            return (preg_match('/^[a-z\d][a-z\d-]{0,62}$/i', $elem) &&
                !preg_match('/-$/', $elem));
        };

        // Перебор массива, так как ключи массива являются даныыми из клиента, мы перебрали и проводили манипуляции над ключами
        foreach ($domains as $key => $arr) {
            $key = str_replace('_', '.', $key);
            // Проверка домена на 
            $isAvailable = $whois->isDomainAvailable($key);
            if (isValidArr($key)) {
                return print "$key не валидный домен!";
            }
            $backArray[] = [
                'domain' => $key,
                'isAvailable' => $isAvailable,
                'expirationDate' => $isAvailable ?  null : date("d.m.Y H:i:s", $whois->loadDomainInfo($key)->expirationDate),
            ];
        };

        // Возвращаем массив с проверенными данными 
        return $backArray;
    }
}
