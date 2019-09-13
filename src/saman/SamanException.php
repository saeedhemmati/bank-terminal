<?php

namespace BankTerminal\Saman;

class SamanException
{
    private $exceptions = [
      '-1' => 'خطا در پردازش اطلاعات ارسالی',
      '-3' => 'وروددی ها حاوری کاراکترهای غیر مجاز می باشد.',
      '-4' => 'کلمه عبور یا کد فروشنده اشتباه است.',
      '-18' => 'آدرس ip فروشنده نامعتبر است و یا رمز تابع reverse transaction اشتباه است.',
    ];

    protected function getExceptionMessage(string $exceptionCode): ?string
    {
        if (array_key_exists($exceptionCode, $this->exceptions))
        {
            return $this->exceptions[$exceptionCode];
        }
        return null;
    }
}
