<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\App;
use Config;
use Session;


use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function changeLanguage($lang){
        $languages = array('en', 'de', 'uk', 'ru');
        if (in_array($lang, $languages)){
            Session::put('locale',$lang);
            return redirect()->back();  
        }
        else return redirect()->back(); 

    }

}
