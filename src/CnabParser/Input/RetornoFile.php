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

namespace CnabParser\Input;

use CnabParser\IntercambioBancarioRetornoFileAbstract;
use CnabParser\Exception\RetornoException;
use CnabParser\Format\Picture;
use CnabParser\Model\Linha;
use CnabParser\Model\Lote;

class RetornoFile extends IntercambioBancarioRetornoFileAbstract
{
	/**
	 * Para retorno o metodo em questao gera o modelo Retorno conforme layout
	 * @param  string $path Não necessario
	 * @return CnabParser\Model\Retorno
	 */
	public function generate($path = null)
	{
		$this->decodeHeaderArquivo();
		$this->decodeTrailerArquivo();
		$this->decodeLotes();
		return $this->model;
	}

	/**
	 * Processa header_arquivo
	 */
	protected function decodeHeaderArquivo()
	{
		$layout = $this->layout->getRetornoLayout();
		$headerArquivoDef = $layout['header_arquivo'];
		$linha = new Linha($this->linhas[0], $this->layout, 'retorno');
		foreach ($headerArquivoDef as $campo => $definicao) {
			$valor = $linha->obterValorCampo($definicao);
			$this->model->header_arquivo->{$campo} = $valor;
		}
	}

	/**
	 * Processa trailer_arquivo
	 */
	protected function decodeTrailerArquivo()
	{
		$layout = $this->layout->getRetornoLayout();
		$trailerArquivoDef = $layout['trailer_arquivo'];
		$linha = new Linha($this->linhas[count($this->linhas) - 1], $this->layout, 'retorno');
		foreach ($trailerArquivoDef as $campo => $definicao) {
			$valor = $linha->obterValorCampo($definicao);
			$this->model->trailer_arquivo->{$campo} = $valor;
		}
	}

	protected function decodeLotes()
	{
		$tipoLayout = $this->layout->getLayout();
		
		if (strtoupper($tipoLayout) === strtoupper('cnab240')) {
			$this->decodeLotesCnab240();
		} elseif (strtoupper($tipoLayout) === strtoupper('cnab400')) {
			$this->decodeLotesCnab400();
		}
	}

	private function decodeLotesCnab240()
	{
		$defTipoRegistro = array(
			'pos' => array(8, 8),
			'picture' => '9(1)',
		);

		$defCodigoLote = array(
			'pos' => array(4, 7),
			'picture' => '9(4)',
		);

		$defCodigoSegmento = array(
			'pos' => array(14, 14),
			'picture' => 'X(1)',
		);

		$defNumeroRegistro = array(
			'pos' => array(9, 13),
			'picture' => '9(5)',
		);

		$indiceDetalhe = 0;
		$codigoLote = null;
		$primeiroCodigoSegmentoLayout = $this->layout->getPrimeiroCodigoSegmentoRetorno();
		$ultimoCodigoSegmentoLayout = $this->layout->getUltimoCodigoSegmentoRetorno();
		
		$lote = null;
		$titulos = array(); // titulos tem titulo
		$segmentos = array();
		foreach ($this->linhas as $index => $linhaStr) {
			$linha = new Linha($linhaStr, $this->layout, 'retorno');
			$tipoRegistro = (int)$linha->obterValorCampo($defTipoRegistro);

			if ($tipoRegistro === IntercambioBancarioRetornoFileAbstract::REGISTRO_HEADER_ARQUIVO)
				continue;
			
			switch ($tipoRegistro) {
				case IntercambioBancarioRetornoFileAbstract::REGISTRO_HEADER_LOTE:
					$codigoLote = $linha->obterValorCampo($defCodigoLote);
					$lote = array(
						'codigo_lote' => $codigoLote,
						'header_lote' => $this->model->decodeHeaderLote($linha),
						'trailer_lote' => $this->model->decodeTrailerLote($linha),
						'titulos' => array(),
					);
					break;
				case IntercambioBancarioRetornoFileAbstract::REGISTRO_DETALHES:
					$codigoSegmento = $linha->obterValorCampo($defCodigoSegmento);
					$numeroRegistro = $linha->obterValorCampo($defNumeroRegistro);
					$dadosSegmento = $linha->getDadosSegmento('segmento_'.strtolower($codigoSegmento));
					$segmentos[$codigoSegmento] = $dadosSegmento;
					$proximaLinha = new Linha($this->linhas[$index + 1], $this->layout, 'retorno');
					$proximoCodigoSegmento = $proximaLinha->obterValorCampo($defCodigoSegmento);
					// se codigoSegmento é ultimo OU proximo codigoSegmento é o primeiro
					// entao fecha o titulo e adiciona em $detalhes
					if (strtolower($codigoSegmento) === strtolower($ultimoCodigoSegmentoLayout) ||
						strtolower($proximoCodigoSegmento) === strtolower($primeiroCodigoSegmentoLayout)) {
						$lote['titulos'][] = $segmentos;
						// novo titulo, novos segmentos
						$segmentos = array();
					}
					break;
				case IntercambioBancarioRetornoFileAbstract::REGISTRO_TRAILER_ARQUIVO:
					$this->model->lotes[] = $lote;
					$titulos = array();
					$segmentos = array();
					break;
			}

			/* OLD
			if ($tipoRegistro !== IntercambioBancarioRetornoFileAbstract::REGISTRO_HEADER_LOTE || 
				$tipoRegistro !== IntercambioBancarioRetornoFileAbstract::REGISTRO_DETALHES) {
				continue;
			}

			if ($tipoRegistro === IntercambioBancarioRetornoFileAbstract::REGISTRO_HEADER_LOTE) {
				$codigoLote = $linha->obterValorCampo($defCodigoLote);
				$this->model->lotes[$codigoLote] = array();
			} elseif ($tipoRegistro === IntercambioBancarioRetornoFileAbstract::REGISTRO_DETALHES) {
				$codigoSegmento = $linha->obterValorCampo($defCodigoSegmento);
				$numeroRegistro = $linha->obterValorCampo($defNumeroRegistro);
				
				$chaveSegmento = 'segmento_'.strtolower($codigoSegmento);

				$this->model->lotes[$codigoLote][$indiceDetalhe][$codigoSegmento] = $linha->getDadosSegmento($chaveSegmento);

				if (strtolower($codigoSegmento) === strtolower($ultimoCodigoSegmentoLayout)) {
					$indiceDetalhe++;
				}
			}
			*/
		}
	}

	private function decodeLotesCnab400()
	{

	}
}