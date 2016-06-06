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
use CnabParser\Model\Remessa;
use CnabParser\Output\RemessaFile;

class ParserCEFCobrancaCnab400Test extends \PHPUnit_Framework_TestCase
{
	public function testDeveInstanciarLayout()
	{
		$layout = new Layout(__DIR__.'/../../../config/cef/cnab400/cobranca_sigcb.yml');
		$this->assertInstanceOf('CnabParser\Parser\Layout', $layout);
	}

	public function testRemessaOk()
	{
		$remessaLayout = new Layout(__DIR__.'/../../../config/cef/cnab400/cobranca_sigcb.yml');
		$remessa = new Remessa($remessaLayout);
		$this->assertInstanceOf('CnabParser\Model\Remessa', $remessa);

		// header arquivo
		$remessa->header->codigo_registro = 1;
		$remessa->header->codigo_remessa = 1;
		$remessa->header->literal_remessa = 'REMESSA';
		$remessa->header->codigo_servico = 1;
		$remessa->header->literal_servico = 'COBRANCA';
		$remessa->header->codigo_agencia = 1234;
		$remessa->header->codigo_beneficiario = 123456;
		$remessa->header->nome_empresa = 'MACWEB SOLUTIONS LTDA';
		$remessa->header->codigo_banco = 104;
		$remessa->header->nome_banco = 'C ECON FEDERAL';
		$remessa->header->data_geracao = date('dmy');
		$remessa->header->numero_sequencial_remessa = 1;
		$remessa->header->numero_sequencial_registro = 1;

		$lote = $remessa->novoLote();
		
		$detalhe = $lote->novoDetalhe();
		// segmento tipo 1
		$detalhe->segmento_1->codigo_registro = 1;
		$detalhe->segmento_1->tipo_inscricao = 2;
		$detalhe->segmento_1->numero_inscricao = '05346078000186';
		$detalhe->segmento_1->codigo_agencia = 1234;
		$detalhe->segmento_1->codigo_beneficiario = 123456;
		$detalhe->segmento_1->id_emissao = 2;
		$detalhe->segmento_1->id_postagem = 3;
		$detalhe->segmento_1->taxa_permanencia = 0;
		$detalhe->segmento_1->identificacao_titulo_empresa = 'TESTE123';
		$detalhe->segmento_1->nosso_numero = 24000000000000001;
		$detalhe->segmento_1->brancos_01 = '';
		$detalhe->segmento_1->mensagem = 'TESTE';
		$detalhe->segmento_1->carteira = 2;
		$detalhe->segmento_1->codigo_ocorrencia = '01';
		$detalhe->segmento_1->numero_documento_cobranca = 'TESTE123';
		$detalhe->segmento_1->vencimento = '999999'; // contra-apresentação
		$detalhe->segmento_1->valor = 15060;
		$detalhe->segmento_1->codigo_banco = 104;
		$detalhe->segmento_1->agencia_cobradora = '00000';
		$detalhe->segmento_1->especie = '01';
		$detalhe->segmento_1->aceite = 'N';
		$detalhe->segmento_1->data_emissao = date('dmy');
		$detalhe->segmento_1->instrucao_1 = 2;
		$detalhe->segmento_1->instrucao_2 = 0;
		$detalhe->segmento_1->juros_mora = 0;
		$detalhe->segmento_1->data_desconto = date('dmy');
		$detalhe->segmento_1->valor_desconto = 0;
		$detalhe->segmento_1->valor_iof = 38;
		$detalhe->segmento_1->abatimento = 0;
		$detalhe->segmento_1->tipo_inscricao_pagador = 1;
		$detalhe->segmento_1->numero_inscricao_pagador = '0577095613';
		$detalhe->segmento_1->nome_pagador = 'GLAUBER PORTELLA';
		$detalhe->segmento_1->endereco_pagador = 'R ALVARENGA,40';
		$detalhe->segmento_1->bairro_pagador = 'GUARANI';
		$detalhe->segmento_1->cep_pagador = '31814500';
		$detalhe->segmento_1->cidade_pagador = 'BELO HORIZONTE';
		$detalhe->segmento_1->uf_pagador = 'MG';
		$detalhe->segmento_1->data_multa = date('dmy');
		$detalhe->segmento_1->valor_multa = 0;
		$detalhe->segmento_1->sacador_avalista = 'GLAUBER PORTELLA';
		$detalhe->segmento_1->instrucao_3 = '00';
		$detalhe->segmento_1->prazo = 30;
		$detalhe->segmento_1->codigo_moeda = 1;
		$detalhe->segmento_1->numero_sequencial_registro = 2;

		// segmento tipo 2
		$detalhe->segmento_2->codigo_registro = 2;
		$detalhe->segmento_2->tipo_inscricao = 2;
		$detalhe->segmento_2->numero_inscricao = '05346078000186';
		$detalhe->segmento_2->codigo_agencia = 1234;
		$detalhe->segmento_2->codigo_beneficiario = 123456;
		$detalhe->segmento_2->nosso_numero = 24000000000000001;
		$detalhe->segmento_2->carteira = '02';
		$detalhe->segmento_2->codigo_ocorrencia = '01';
		$detalhe->segmento_2->uso_exclusivo_02 = '';
		$detalhe->segmento_2->codigo_banco = 104;
		$detalhe->segmento_2->mensagem_1 = 'MENSAGEM LN1';
		$detalhe->segmento_2->mensagem_2 = 'MENSAGEM LN2';
		$detalhe->segmento_2->mensagem_3 = 'MENSAGEM LN3';
		$detalhe->segmento_2->mensagem_4 = '';
		$detalhe->segmento_2->mensagem_5 = '';
		$detalhe->segmento_2->mensagem_6 = '';
		$detalhe->segmento_2->numero_sequencial_registro = 3;

		$lote->inserirDetalhe($detalhe);
		$remessa->inserirLote($lote);

		// trailer arquivo
		$remessa->trailer->codigo_registro = 9;
		$remessa->trailer->numero_sequencial_registro = 4;

		// gera arquivo
		$remessaFile = new RemessaFile($remessa);
		$this->assertInstanceOf('CnabParser\Output\RemessaFile', $remessaFile);
		$remessaFile->generate(__DIR__.'/../../out/cef-cobranca400.rem');
	}
}