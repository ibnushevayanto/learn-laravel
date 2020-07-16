<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function landing()
    {
        return view('Landing.landing');
    }

    public function contact()
    {
        return view('Contact.contact');
    }
    public function test($id, $naga)
    {
        dd(['id' => $id, 'naga' => $naga]);
    }
    public function secret()
    {
        return view('SecretPage.secret');
    }
}
