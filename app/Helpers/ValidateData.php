<?php

namespace App\Helpers;

class ValidateData
{
    public static function validateCpf(string $cpf): bool
    {
        $cpf = HandleData::onlyNumber($cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    public static function validateCnpj($cnpj): bool
    {
        $cnpj = HandleData::onlyNumber($cnpj);

        if (strlen($cnpj) != 14) return false;

        if (preg_match('/(\d)\1{13}/', $cnpj)) return false;

        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) return false;

        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }

    public static function validateCpfOrCnpj(string $document): bool
    {
        $document = HandleData::onlyNumber($document);
        return strlen($document) > 11 ? self::validateCnpj($document) : self::validateCpf($document);
    }

    public static function validateFloatGreaterThanZero(float $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_FLOAT) && $value > 0;
    }
}
