<?php
// Copyright (c) 2016 Glauber Portella <glauberportella@gmail.com>

// Permission is hereby granted, free of charge, to any person obtaining a
// copy of this software and associated documentation files (the "Software"),
// to deal in the Software without restriction, including without limitation
// the rights to use, copy, modify, merge, publish, distribute, sublicense,
// and/or sell copies of the Software, and to permit persons to whom the
// Software is furnished to do so, subject to the following conditions:

// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.

// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
// FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
// DEALINGS IN THE SOFTWARE.

// Author: Anderson Danilo <contato@andersondanilo.com>
// 
namespace CnabParser\Format;

class Picture
{
    const REGEX_VALID_FORMAT = '/(?P<tipo1>X|9)\((?P<tamanho1>[0-9]+)\)(?P<tipo2>(V9)?)\(?(?P<tamanho2>([0-9]+)?)\)?/';

    public static function validarFormato($format)
    {
        if (\preg_match(self::REGEX_VALID_FORMAT, $format)) {
            return true;
        } else {
            return false;
        }
    }

    public static function getLength($format)
    {
        $m = array();
        if (preg_match(self::REGEX_VALID_FORMAT, $format, $m)) {
            return (int) $m['tamanho1'] + (int) $m['tamanho2'];
        } else {
            throw new \InvalidArgumentException("'$format' is not a valid format");
        }
    }

    public static function parseNumber($value)
    {
        $value = preg_replace('/[^0-9.]/', '', $value);
        $value = preg_replace('/^0+/', '', $value);
        if ($value) {
            return $value;
        } else {
            return '0';
        }
    }

    public static function encode($value, $format, $options)
    {
        $m = array();
        if (\preg_match(self::REGEX_VALID_FORMAT, $format, $m)) {
            if ($m['tipo1'] == 'X' && !$m['tipo2']) {
                $value = \substr($value, 0, $m['tamanho1']);

                return \str_pad($value, (int) $m['tamanho1'], ' ', STR_PAD_RIGHT);
            } elseif ($m['tipo1'] == '9') {
                if ($value instanceof \DateTime) {
                    if (@$options['date_format']) {
                        $value = strftime($options['date_format'], $value->getTimestamp());
                    } else {
                        if ((int) $m['tamanho1'] == 8) {
                            $value = $value->format('dmY');
                        }

                        if ((int) $m['tamanho1'] == 6) {
                            $value = $value->format('dmy');
                        }
                    }
                }

                if (!is_numeric($value)) {
                    $msg = "%svalor '$value' não é número, formato requerido $format.";

                    if (!empty(@$options['register_desc'])) {
                        $msg = sprintf($msg, "{$options['register_desc']} > %s");
                    }

                    if (!empty(@$options['field_desc'])) {
                        $msg = sprintf($msg, "{$options['field_desc']}: ");
                    }
                    throw new \Exception($msg);
                }

                $value = self::parseNumber($value);
                $exp = explode('.', $value);
                if (!isset($exp[1])) {
                    $exp[1] = 0;
                }
                if ($m['tipo2'] == 'V9') {
                    $tamanho_left = (int) $m['tamanho1'];
                    $tamanho_right = (int) $m['tamanho2'];
                    $valor_left = \str_pad($exp[0], $tamanho_left, '0', STR_PAD_LEFT);
                    if (strlen($exp[1]) > $tamanho_right) {
                        $extra = strlen($exp[1]) - $tamanho_right;
                        $extraPow = pow(10, $extra);
                        $exp[1] = round($exp[1] / $extraPow);
                    }
                    $valor_right = \str_pad($exp[1], $tamanho_right, '0', STR_PAD_RIGHT);

                    return $valor_left.$valor_right;
                } elseif (!$m['tipo2']) {
                    $value = self::parseNumber($value);

                    return \str_pad($value, (int) $m['tamanho1'], '0', STR_PAD_LEFT);
                } else {
                    $msg = "%s$format' is not a valid format";

                    if (!empty(@$options['register_desc'])) {
                        $msg = sprintf($msg, "{$options['register_desc']} > %s");
                    }

                    if (!empty(@$options['field_desc'])) {
                        $msg = sprintf($msg, "{$options['field_desc']}: ");
                    }
                    throw new \InvalidArgumentException($msg);
                }
            }
        } else {
            throw new \InvalidArgumentException("'$format' is not a valid format");
        }
    }

    public static function decode($value, $format, $options)
    {
        $m = array();
        if (preg_match(self::REGEX_VALID_FORMAT, $format, $m)) {
            if ($m['tipo1'] == 'X' && !$m['tipo2']) {
                return rtrim($value);
            } elseif ($m['tipo1'] == '9') {
                if ($m['tipo2'] == 'V9') {
                    $tamanho_left = (int) $m['tamanho1'];
                    $tamanho_right = (int) $m['tamanho2'];
                    $valor_left = self::parseNumber(substr($value, 0, $tamanho_left));
                    $valor_right = substr($value, $tamanho_left, $tamanho_right);
                    if ((float) $valor_right > 0) {
                        return $valor_left . "." . $valor_right;
                    } else {
                        return self::parseNumber($valor_left);
                    }
                } elseif (!$m['tipo2']) {
                    return self::parseNumber($value);
                } else {
                    $msg = "%s$format' is not a valid format";

                    if (!empty(@$options['field_desc'])) {
                        $msg = sprintf($msg, "{$options['field_desc']}: ");
                    }
                    throw new \InvalidArgumentException($msg);
                }
            }
        } else {
            $msg = "%s$format' is not a valid format";

            if (!empty(@$options['field_desc'])) {
                $msg = sprintf($msg, "{$options['field_desc']}: ");
            }
            throw new \InvalidArgumentException($msg);
        }
    }
}
