<?php

namespace BankTerminal\Saman;

use BankTerminal\Saman\SamanException as Exception;

class Saman extends Exception
{
    private function generateRandomString(
      int $length = 32,
      string $keySpace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string
    {
        if ($length < 1)
        {
            throw new \RangeException("Length must be above 1");
        }
        $pieces = [];
        $max = mb_strlen($keySpace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i)
        {
            $pieces []= $keySpace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }

    private function generateResNum(): string
    {
        return hash('sha256',
          $this->generateRandomString(8).
          '-'.
          $this->generateRandomString(4).
          '-'.
          $this->generateRandomString(4).
          '-'.
          $this->generateRandomString(4).
          '-'.
          $this->generateRandomString(12),
        );
    }

    // TODO: implement unit test
    public function getToken(string $amount = ''): string
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
        return $result;
    }

    public function verifyTransaction()
    {
        $transactionState = $_POST['State'];
        $transactionRefNum = $_POST['RefNum'];
        if ($transactionState == 'OK')
        {
            $soapClient = new \SoapClient('https://sep.shaparak.ir/payments/referencepayment.asmx?WSDL');
            $soapProxy = $soapClient->getProxy();
            $verificationResult = $soapProxy->verifyTransaction(
              $transactionRefNum,
              config('saman.merchantId'),
            );
            if ($verificationResult <= 0)
            {
                throw new \Exception('Verification failed: ' . $verificationResult);
            }
        }
    }
}
