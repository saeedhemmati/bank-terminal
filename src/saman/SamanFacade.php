<?php

namespace BankTerminal\Saman;

use Illuminate\Support\Facades\Facade;

class SamanFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'saman';
    }
}
