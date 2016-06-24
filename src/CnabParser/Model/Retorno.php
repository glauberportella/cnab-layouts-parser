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

namespace CnabParser\Model;

use StdClass AS DataContainer;
use CnabParser\Model\Linha;

class Retorno
{
	/**
	 * @var DataContainer
	 */
	public $header_arquivo;

	/**
	 * @var DataContainer
	 */
	public $trailer_arquivo;

	/**
	 * @var Array of DataContainer (header_lote(1),detalhes(1)(n),trailer_lote(1) ... header_lote(m),detalhes(1)(n),trailer_lote(m))
	 */
	public $lotes;

	public function __construct()
	{
		$this->header_arquivo = new DataContainer();
		$this->trailer_arquivo = new DataContainer();
		$this->lotes = array();
	}

	public function decodeHeaderLote(Linha $linha)
	{
		$dados = array();
		
		$layout = $linha->getTipo() === 'remessa'
			? $linha->getLayout()->getRemessaLayout()
			: $linha->getLayout()->getRetornoLayout();
		
		$campos = $layout['header_lote'];
		
		foreach ($campos as $nome => $definicao) {
			$dados[$nome] = $linha->obterValorCampo($definicao);
		}

		return $dados;
	}

	public function decodeTrailerLote(Linha $linha)
	{
		$dados = array();
		
		$layout = $linha->getTipo() === 'remessa'
			? $linha->getLayout()->getRemessaLayout()
			: $linha->getLayout()->getRetornoLayout();
		
		$campos = $layout['trailer_lote'];
		
		foreach ($campos as $nome => $definicao) {
			$dados[$nome] = $linha->obterValorCampo($definicao);
		}

		return $dados;
	}

	public function getTotalLotes()
	{
		return count($this->lotes);
	}

	public function getTotalTitulos()
	{
		$total = 0;

		foreach ($this->lotes as $lote) {
			$total += count($lote['titulos']);
		}

		return $total;
	}
}