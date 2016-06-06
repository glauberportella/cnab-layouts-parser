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

namespace CnabParser\Output;

use CnabParser\IntercambioBancarioRemessaFileAbstract;
use CnabParser\Model\Lote;

class RemessaFile extends IntercambioBancarioRemessaFileAbstract
{
	const CNAB_EOL = "\r\n";

	public function generate($path)
	{
		// header arquivo
		$headerArquivo = $this->encodeHeaderArquivo();

		// lotes
		$lotes = $this->encodeLotes();

		// trailer arquivo
		$trailerArquivo = $this->encodeTrailerArquivo();
		

		$data = array(
			$headerArquivo,
			$lotes,
			$trailerArquivo,
		);

		$data = implode(self::CNAB_EOL, $data);
		$data .= self::CNAB_EOL;

		file_put_contents($path, $data);
	}

	protected function encodeHeaderArquivo()
	{
		if (!isset($this->model->header))
			return;

		$layout = $this->model->getLayout();
		$layoutRemessa = $layout->getRemessaLayout();
		return $this->encode($layoutRemessa['header_arquivo'], $this->model->header);
	}

	protected function encodeLotes()
	{
		$encoded = array();

		foreach ($this->model->lotes as $lote) {
			// header lote
			if (!empty($lote->header))
				$encoded[] = $this->encodeHeaderLote($lote);

			// detalhes
			$encoded[] = $this->encodeDetalhes($lote);

			// trailer lote
			if (!empty($lote->trailer))
				$encoded[] = $this->encodeTrailerLote($lote);
		}
		
		return implode(self::CNAB_EOL, $encoded);
	}

	protected function encodeHeaderLote(Lote $model)
	{
		if (!isset($model->header) || empty($model->header))
			return;

		$layout = $model->getLayout();
		return $this->encode($layout['header_lote'], $model->header);
	}

	protected function encodeDetalhes(Lote $model)
	{
		if (!isset($model->detalhes))
			return;

		$layout = $model->getLayout();

		$encoded = array();

		foreach ($model->detalhes as $detalhe) {
			foreach ($detalhe as $segmento => $obj) {
				$segmentoEncoded = $this->encode($layout['detalhes'][$segmento], $detalhe->$segmento);
				$encoded[] = $segmentoEncoded;
			}
		}

		return implode(self::CNAB_EOL, $encoded);
	}

	protected function encodeTrailerLote(Lote $model)
	{
		if (!isset($model->trailer) || empty($model->trailer))
			return;

		$layout = $model->getLayout();
		return $this->encode($layout['trailer_lote'], $model->trailer);
	}

	protected function encodeTrailerArquivo()
	{
		if (!isset($this->model->trailer))
			return;

		$layout = $this->model->getLayout();
		$layoutRemessa = $layout->getRemessaLayout();
		return $this->encode($layoutRemessa['trailer_arquivo'], $this->model->trailer);
	}
}