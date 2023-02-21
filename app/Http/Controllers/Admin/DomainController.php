<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Iodev\Whois\Factory;


class DomainController extends Controller
{

    public function store()
    {
        //Вылавливание данных из клиента
        $whois = Factory::get()->createWhois();
        $domains = request('title');
        $backArray = [];
        $parserData = explode(",", $domains);
        //Функция валидации
        function isValidArr($elem)
        {
            return (preg_match('/^[a-z\d][a-z\d-]{0,62}$/i', $elem) &&
                !preg_match('/-$/', $elem));
        };

        foreach ($parserData as $arr) {

            $arr = trim($arr);
            $isAvailable = $whois->isDomainAvailable($arr);
            if (isValidArr($arr)) {
                return print "$arr не валидный домен!";
            }
            $backArray[] = [
                'domain' => $arr,
                'isAvailable' => $isAvailable,
                'expirationDate' => $isAvailable ?  null : date("d.m.Y H:i:s", $whois->loadDomainInfo($arr)->expirationDate),
            ];
        };

        return $backArray;
    }
}
