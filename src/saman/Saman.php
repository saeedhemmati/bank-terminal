<?php

namespace BankTerminal\Saman;

class Saman
{
    private function generateResNum(): string
    {
        return hash('sha256', time());
    }

    /**
     * @param String $amount
     */
    public function getToken($amount = '')
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
    }
}
