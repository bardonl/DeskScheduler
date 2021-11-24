<?php

namespace App\Http\Controllers;

class PythonController extends Controller
{
    public function runTest()
    {

        try {
            echo exec("python3 /var/www/html/DeskScheduler/python/new.py 2>&1");
        } catch (exception $e){
            var_dump($e);
        }

    }
}
