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

namespace CnabParser;

use CnabParser\Parser\Layout;
use CnabParser\Model\HeaderArquivo;
use CnabParser\Model\Lote;
use CnabParser\Model\TrailerArquivo;

abstract class IntercambioBancarioAbstract implements \JsonSerializable
{
    /**
     * Header Arquivo
     * @var CnabParser\Model\HeaderArquivo
     */
    public $header;
    /**
     * Trailer Arquivo
     * @var CnabParser\Model\TrailerArquivo
     */
    public $trailer;

    /**
     * Array de lotes
     * @var Array of CnabParser\Model\Lote
     */
    public $lotes;

	/**
	 * @var CnabParser\Parser\Layout
	 */
	protected $layout;

    public function __construct(Layout $layout)
    {
        $this->layout = $layout;
        $this->header = new HeaderArquivo();
        $this->trailer = new TrailerArquivo();
        $this->lotes = array();
    }

	/**
	 * @return CnabParser\Parser\Layout
	 */
	public function getLayout()
	{
		return $this->layout;
	}

    public function inserirLote(Lote $lote)
    {
        $this->lotes[] = $lote;
        return $this;
    }

    public function removerLote($sequencial)
    {
        $found = -1;

        foreach ($this->lotes as $indice => $lote) {
            if ($lote->sequencial == $sequencial) {
                $found = $indice;
                break;
            }
        }

        if ($found > -1) {
            unset($this->lotes[$found]);
        }

        return $this;
    }

    public function limparLotes()
    {
        $this->lotes = array();
        return $this;
    }

    public function jsonSerialize()
    {
        $headerArquivo = $this->header->jsonSerialize();
        $trailerArquivo = $this->trailer->jsonSerialize();
        $lotes = $this->lotes;

        return array_merge(
            array('header_arquivo' => $headerArquivo),
            array('lotes' => $lotes),
            array('trailer_arquivo' => $trailerArquivo)
        );
    }
}