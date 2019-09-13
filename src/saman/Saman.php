<?php

namespace BankTerminal\Saman;

use BankTerminal\Saman\SamanException as Exception;

class Saman extends Exception
{
    private function generateResNum(): string
    {
        return hash('sha256',
          rand(11111111, 99999999).
          '-'.
          rand(1111, 9999).
          '-'.
          rand(1111, 9999).
          '-'.
          rand(1111, 9999).
          '-'.
          rand(111111111111, 999999999999),
        );
    }

    // TODO: implement unit test
    public function getToken(string $amount = '')
    {
        $client = new \SoapClient('https://sep.shaparak.ir/Payments/InitPayment.asmx?WSDL');
        $generatedResNum = $this->generateResNum();
        $result = $client->RequestToken(
            config('terminals.saman.merchantId'),
            $generatedResNum,
            $amount,
            '0',
            '0',
            '0',
            '0',
            '0',
            '0',
            'ResNum1',
            'ResNum2',
            '0',
            config('terminals.saman.redirectUrl'),
        );
        $exceptionMessage = $this->getExceptionMessage($result);
        if ($exceptionMessage)
        {
            die($exceptionMessage);
        }
    }
}
