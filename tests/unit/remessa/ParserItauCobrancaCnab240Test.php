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

class ParserItauCobrancaCnab240Test extends \PHPUnit_Framework_TestCase
{
	public function testDeveInstanciarLayout()
	{
		$layout = new Layout(__DIR__.'/../../../config/itau/cnab240/cobranca.yml');
		$this->assertInstanceOf('CnabParser\Parser\Layout', $layout);
	}

	public function testRemessaOk()
	{
		$remessaLayout = new Layout(__DIR__.'/../../../config/itau/cnab240/cobranca.yml');
		$remessa = new Remessa($remessaLayout);
		$this->assertInstanceOf('CnabParser\Model\Remessa', $remessa);
		
		// header arquivo
		$remessa->header->codigo_banco = 341;
		$remessa->header->tipo_inscricao = 2;
		$remessa->header->inscricao_numero = '05346078000186';
		$remessa->header->agencia = 2932;
		$remessa->header->conta = 24992;
		$remessa->header->dac = 9;
		$remessa->header->nome_empresa = 'MACWEB SOLUTIONS LTDA';
		$remessa->header->data_geracao = date('dmY');
		$remessa->header->hora_geracao = date('His');
		$remessa->header->numero_sequencial_arquivo_retorno = 1;

		// criar um novo lote de serviço para a remessa
		// informando o código sequencial do lote
		$lote = $remessa->novoLote(1);
		
		$lote->header->codigo_banco = 341;
		$lote->header->lote_servico = $lote->sequencial;
		$lote->header->tipo_registro = 1;
		$lote->header->tipo_operacao = 'R';
		$lote->header->tipo_servico = '01';
		$lote->header->zeros_01 = 0;
		$lote->header->versao_layout_lote = '030';
		$lote->header->brancos_01 = '';
		$lote->header->tipo_inscricao = 2;
		$lote->header->inscricao_empresa = '05346078000186';
		$lote->header->brancos_02 = '0';
		$lote->header->zeros_02 = 0;
		$lote->header->agencia = 2932;
		$lote->header->brancos_03 = '';
		$lote->header->zeros_03 = 0;
		$lote->header->conta = '24992';
		$lote->header->brancos_04 = '';
		$lote->header->dac = 9;
		$lote->header->nome_empresa = 'MACWEB SOLUTIONS LTDA';
		$lote->header->brancos_05 = '';
		$lote->header->numero_sequencial_arquivo_retorno = 1;
		$lote->header->data_gravacao = date('dmY');
		$lote->header->data_credito = date('dmY');
		$lote->header->brancos_06 = '';

		$detalhe = $lote->novoDetalhe();
		// segmento p
		$detalhe->segmento_p->lote_servico = $lote->sequencial;
		$detalhe->segmento_p->nummero_sequencial_registro_lote = 1;
		$detalhe->segmento_p->codigo_ocorrencia = '01';
		$detalhe->segmento_p->agencia = 2932;
		$detalhe->segmento_p->conta = 24992;
		$detalhe->segmento_p->dac = 9;
		$detalhe->segmento_p->carteira = 109;
		$detalhe->segmento_p->nosso_numero = 12345678;
		$detalhe->segmento_p->dac_nosso_numero = 3;
		$detalhe->segmento_p->numero_documento = 1;
		$detalhe->segmento_p->vencimento = '10052016';
		$detalhe->segmento_p->valor_titulo = 1000;
		$detalhe->segmento_p->agencia_cobradora = 0;
		$detalhe->segmento_p->dac_agencia_cobradora = 0;
		$detalhe->segmento_p->especie = '05';
		$detalhe->segmento_p->aceite = 'N';
		$detalhe->segmento_p->data_emissao = date('dmY');
		$detalhe->segmento_p->data_juros_mora = '11052016';
		$detalhe->segmento_p->juros_1_dia = 0;
		$detalhe->segmento_p->data_1o_desconto = '00000000';
		$detalhe->segmento_p->valor_1o_desconto = 0;
		$detalhe->segmento_p->valor_iof = 38;
		$detalhe->segmento_p->valor_abatimento = 0;
		$detalhe->segmento_p->identificacao_titulo_empresa = '';
		$detalhe->segmento_p->codigo_negativacao_protesto = 0;
		$detalhe->segmento_p->prazo_negativacao_protesto = 0;
		$detalhe->segmento_p->codigo_baixa = 0;
		$detalhe->segmento_p->prazo_baixa = 0;
		// segmento q
		$detalhe->segmento_q->lote_servico = $lote->sequencial;
		$detalhe->segmento_q->nummero_sequencial_registro_lote = 2;
		$detalhe->segmento_q->codigo_ocorrencia = '01';
		$detalhe->segmento_q->tipo_inscricao = 2;
		$detalhe->segmento_q->inscricao_numero = '05346078000186';
		$detalhe->segmento_q->nome_pagador = 'GLAUBER PORTELLA';
		$detalhe->segmento_q->logradouro = 'RUA ALVARENGA';
		$detalhe->segmento_q->bairro = 'GUARANI';
		$detalhe->segmento_q->cep = 31814;
		$detalhe->segmento_q->sufixo_cep = 500;
		$detalhe->segmento_q->cidade = 'BELO HORIZONTE';
		$detalhe->segmento_q->uf = 'MG';
		$detalhe->segmento_q->tipo_inscricao_sacador = 2;
		$detalhe->segmento_q->inscricao_sacador = '05346078000186';
		$detalhe->segmento_q->nome_sacador = 'MACWEB SOLUTIONS LTDA';
		// segmento r opcional nao adicionado
		unset($detalhe->segmento_r);
		// segmento y opcional nao adicionado
		unset($detalhe->segmento_y);
		// insere o detalhe no lote da remessa
		$lote->inserirDetalhe($detalhe);

		// trailer lote
		$lote->trailer->lote_servico = $lote->sequencial;
		$lote->trailer->quantidade_registros_lote = 4; // quantidade de Registros do Lote correspondente à soma da quantidade dos registros tipo 1 (header_lote), 3(detalhes) e 5(trailer_lote)
		$lote->trailer->quantidade_cobranca_simples = 1;
		$lote->trailer->valor_total_cobranca_simples = 10000;
		$lote->trailer->quantidade_cobranca_vinculada = 0;
		$lote->trailer->valor_total_cobranca_vinculada = 0;
		$lote->trailer->aviso_bancario = '00000000';

		// apos definir o lote insere na remessa
		$remessa->inserirLote($lote);

		// trailer arquivo
		$remessa->trailer->total_lotes = 1; // quantidade de Lotes do arquivo correspondente à soma da quantidade dos registros tipo 1 (header_lote).
		$remessa->trailer->total_registros = 6; //total da quantidade de Registros no arquivo correspondente à soma da quantidade dos registros tipo 0(header_arquivo), 1(header_lote), 3(detalhes), 5(trailer_lote) e 9(trailer_arquivo).

		// gera arquivo
		$remessaFile = new RemessaFile($remessa);
		$this->assertInstanceOf('CnabParser\Output\RemessaFile', $remessaFile);
		$remessaFile->generate(__DIR__.'/../../out/itau-cobranca240.rem');
	}
}