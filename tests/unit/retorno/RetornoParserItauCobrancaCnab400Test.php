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

use CnabParser\Parser\Layout;
use CnabParser\Model\Retorno;
use CnabParser\Input\RetornoFile;

class RetornoParserItauCobrancaCnab400Test extends \PHPUnit_Framework_TestCase
{
	public function testRetornoFileInstanceSuccess()
	{
		$layout = new Layout(__DIR__.'/../../../config/itau/cnab400/cobranca.yml');
		$this->assertInstanceOf('CnabParser\Parser\Layout', $layout);

		$retornoFile = new RetornoFile($layout, __DIR__.'/../../data/cobranca-itau-cnab400.ret');
		$this->assertInstanceOf('CnabParser\Input\RetornoFile', $retornoFile);
	}

	public function testRetornoGenerateModelSuccess()
	{
		$layout = new Layout(__DIR__.'/../../../config/itau/cnab400/cobranca.yml');
		$retornoFile = new RetornoFile($layout, __DIR__.'/../../data/cobranca-itau-cnab400.ret');

		$this->assertEquals(1, $retornoFile->getTotalLotes());

		// gerou corretamente?
		$retorno = $retornoFile->generate();
		$this->assertInstanceOf('CnabParser\Model\Retorno', $retorno);
		$this->assertInstanceOf('StdClass', $retorno->header_arquivo);
		$this->assertNotEmpty($retorno->lotes);

		// testa valores do retorno
		// verifica header
		$this->assertEquals(0, $retorno->header_arquivo->tipo_registro);
		$this->assertEquals(2, $retorno->header_arquivo->codigo_retorno);
		$this->assertEquals('RETORNO', $retorno->header_arquivo->literal_retorno);
		$this->assertEquals(1, $retorno->header_arquivo->codigo_servico);
		$this->assertEquals('COBRANCA', $retorno->header_arquivo->literal_servico);
		$this->assertEquals(111, $retorno->header_arquivo->agencia);
		$this->assertEquals(0, $retorno->header_arquivo->zeros);
		$this->assertEquals(12345, $retorno->header_arquivo->conta);
		$this->assertEquals(0, $retorno->header_arquivo->dac);
		$this->assertEquals('', $retorno->header_arquivo->brancos_01);
		$this->assertEquals('Teste de Retorno', $retorno->header_arquivo->nome_empresa);
		$this->assertEquals(341, $retorno->header_arquivo->codigo_banco);
		$this->assertEquals('BANCO ITAU S.A.', $retorno->header_arquivo->nome_banco);
		$this->assertEquals(220813, $retorno->header_arquivo->data_geracao);
		$this->assertEquals(1600, $retorno->header_arquivo->densidade);
		$this->assertEquals('BPI', $retorno->header_arquivo->unidade_densidade);
		$this->assertEquals(112, $retorno->header_arquivo->numero_sequencial_arquivo);
		$this->assertEquals(210613, $retorno->header_arquivo->data_credito);
		$this->assertEquals('', $retorno->header_arquivo->brancos_02);
		$this->assertEquals(1, $retorno->header_arquivo->numero_sequencial_registro);

		// verifica trailer
		$this->assertEquals(9, $retorno->trailer_arquivo->tipo_registro);
		$this->assertEquals(2, $retorno->trailer_arquivo->codigo_retorno);
		$this->assertEquals(1, $retorno->trailer_arquivo->codigo_servico);
		$this->assertEquals(341, $retorno->trailer_arquivo->codigo_banco);
		$this->assertEquals('', $retorno->trailer_arquivo->brancos_01);
		$this->assertEquals(0, $retorno->trailer_arquivo->quantidade_titulos_simples);
		$this->assertEquals(0, $retorno->trailer_arquivo->valor_total_simples);
		$this->assertEquals('00000000', $retorno->trailer_arquivo->aviso_bancario_01);
		$this->assertEquals('', $retorno->trailer_arquivo->brancos_02);
		$this->assertEquals(0, $retorno->trailer_arquivo->quantidade_titulos_vinculada);
		$this->assertEquals(0, $retorno->trailer_arquivo->valor_total_vinculada);
		$this->assertEquals('00000000', $retorno->trailer_arquivo->aviso_bancario_02);
		$this->assertEquals('                                                  000000000000000000000000000000', $retorno->trailer_arquivo->brancos_03);
		$this->assertEquals(0, $retorno->trailer_arquivo->quantidade_titulos_direta);
		$this->assertEquals(0, $retorno->trailer_arquivo->valor_total_direta);
		$this->assertEquals('00000000', $retorno->trailer_arquivo->aviso_bancario_03);
		$this->assertEquals(112, $retorno->trailer_arquivo->controle_arquivo);
		$this->assertEquals(4, $retorno->trailer_arquivo->quantidade_detalhes);
		$this->assertEquals(1895.76, $retorno->trailer_arquivo->valor_total_informado);
		$this->assertEquals('', $retorno->trailer_arquivo->brancos_04);
		$this->assertEquals(6, $retorno->trailer_arquivo->numero_sequencial_registro);

		$this->assertEquals(4, $retorno->getTotalTitulos());
	}
}