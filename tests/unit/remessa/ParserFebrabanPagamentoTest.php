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
use CnabParser\Model\Lote;
use CnabParser\Output\RemessaFile;

class ParserFebrabanPagamentoTest extends \PHPUnit_Framework_TestCase
{
	public function testDeveInstanciarLayout()
	{
		$layout = new Layout(__DIR__.'/../../../config/febraban/cnab240/pagamentos.yml');
		$this->assertInstanceOf('CnabParser\Parser\Layout', $layout);
	}

	public function testRemessaPagamentosOk()
	{
		$remessaLayout = new Layout(__DIR__.'/../../../config/febraban/cnab240/pagamentos.yml');
		$remessa = new Remessa($remessaLayout);
		$this->assertInstanceOf('CnabParser\Model\Remessa', $remessa);
		
		// preenche campos
		$remessa->header->codigo_banco = 341;
		$remessa->header->exclusivo_febraban_01 = '';
		$remessa->header->tipo_inscricao_empresa = 2;
		$remessa->header->numero_inscricao_empresa = '05346078000186';
		$remessa->header->codigo_convenio_banco = '0';
		$remessa->header->agencia_mantenedora_conta = '2932';
		$remessa->header->digito_verificador_agencia = '';
		$remessa->header->numero_conta_corrente = '24992';
		$remessa->header->digito_verificador_conta = '9';
		$remessa->header->digito_verificador_agencia_conta = '';
		$remessa->header->nome_empresa = 'MACWEB SOLUTIONS LTDA';
		$remessa->header->nome_banco = 'BANCO ITAU SA';
		$remessa->header->exclusivo_febraban_02 = '';
		$remessa->header->codigo_remessa_retorno = '1';
		$remessa->header->data_geracao_arquivo = date('dmY');
		$remessa->header->hora_geracao_arquivo = date('His');
		$remessa->header->numero_sequencial_arquivo = '1';
		$remessa->header->densidade_gravacao_arquivo = '1600';
		$remessa->header->reservado_banco_01 = '';
		$remessa->header->reservado_empresa_01 = '';
		$remessa->header->exclusivo_febraban_03 = '';

		// criar um novo lote de serviço para a remessa
		// informando o código sequencial do lote
		$lote = $remessa->novoLote(1);

		// header lote
		$lote->header->codigo_banco = 341;
		$lote->header->lote_servico = $lote->sequencial;
		$lote->header->tipo_servico = 30;
		$lote->header->forma_lancamento = '02';
		$lote->header->exclusivo_febraban_01 = '';
		$lote->header->tipo_inscricao_empresa = 2;
		$lote->header->numero_inscricao_empresa = '05346078000186';
		$lote->header->codigo_convenio_banco = '';
		$lote->header->agencia_mantenedora_conta = '2932';
		$lote->header->digito_verificador_agencia = '';
		$lote->header->numero_conta_corrente = '24992';
		$lote->header->digito_verificador_conta = '9';
		$lote->header->digito_verificador_agencia_conta = '';
		$lote->header->nome_empresa = 'MACWEB SOLUTIONS LTDA';
		$lote->header->mensagem = '';
		$lote->header->logradouro = 'RUA GUAJAJARAS';
		$lote->header->numero = '910';
		$lote->header->complemento = 'SALA 1203';
		$lote->header->cidade = 'BELO HORIZONTE';
		$lote->header->cep = '30180';
		$lote->header->complemento_cep = '100';
		$lote->header->estado = 'MG';
		$lote->header->indicativo_forma_pagamento_servico = '01';
		$lote->header->exclusivo_febraban_02 = '';
		$lote->header->codigos_ocorrencias_retorno = '';
	
		// detalhes
		$detalhe = $lote->novoDetalhe();
		// segmento a
		$detalhe->segmento_a->codigo_banco = 341;
		$detalhe->segmento_a->lote_servico = 1;
		$detalhe->segmento_a->tipo_registro = 3;
		$detalhe->segmento_a->numero_sequencial_registro_lote = 1;
		$detalhe->segmento_a->codigo_segmento_registro_detalhe = 'A';
		$detalhe->segmento_a->tipo_movimento = 0;
		$detalhe->segmento_a->codigo_instrucao_movimento = '00';
		$detalhe->segmento_a->codigo_camara_centralizadora = '700';
		$detalhe->segmento_a->codigo_banco_favorecido = 341;
		$detalhe->segmento_a->agencia_mantenedora_conta_favorecido = 3158;
		$detalhe->segmento_a->digito_verificador_agencia = '';
		$detalhe->segmento_a->numero_conta_corrente = 38094;
		$detalhe->segmento_a->digito_verificador_conta = 3;
		$detalhe->segmento_a->digito_verificador_agencia_conta = '';
		$detalhe->segmento_a->nome_favorecido = 'GLAUBER PORTELLA';
		$detalhe->segmento_a->numero_documento_atribuido_empresa = '12345';
		$detalhe->segmento_a->data_pagamento = date('dmY');
		$detalhe->segmento_a->tipo_moeda = 'BRL';
		$detalhe->segmento_a->quantidade_moeda = 1;
		$detalhe->segmento_a->valor_pagamento = '15000';
		$detalhe->segmento_a->numero_documento_atribuido_banco = '123456';
		$detalhe->segmento_a->data_real_efetivacao_pagamento = date('dmY');
		$detalhe->segmento_a->valor_real_efetivacao_pagamento = '15000';
		$detalhe->segmento_a->outras_informacoes = '';
		$detalhe->segmento_a->complemento_tipo_servico = '06';
		$detalhe->segmento_a->codigo_finalidade_ted = '123456';
		$detalhe->segmento_a->complemento_finalidade_pagamento = '0';
		$detalhe->segmento_a->exclusivo_febraban_01 = '0';
		$detalhe->segmento_a->aviso_favorecido = '0';
		$detalhe->segmento_a->codigos_ocorrencias_retorno = '00';
		
		// segmento b
		$detalhe->segmento_b->codigo_banco = 341;
		$detalhe->segmento_b->lote_servico = 1;
		$detalhe->segmento_b->tipo_registro = 3;
		$detalhe->segmento_b->numero_sequencial_registro_lote = 1;
		$detalhe->segmento_b->codigo_segmento_registro_detalhe = 'B';
		$detalhe->segmento_b->exclusivo_febraban_01 = '';
		$detalhe->segmento_b->tipo_inscricao_favorecido = 1;
		$detalhe->segmento_b->numero_inscricao_favorecido = '05771095613';
		$detalhe->segmento_b->logradouro = 'RUA ALVARENGA';
		$detalhe->segmento_b->numero = 40;
		$detalhe->segmento_b->complemento = '';
		$detalhe->segmento_b->bairro = 'GUARANI';
		$detalhe->segmento_b->cidade = 'BELO HORIZONTE';
		$detalhe->segmento_b->cep = '31814';
		$detalhe->segmento_b->complemento_cep = '500';
		$detalhe->segmento_b->estado = 'MG';
		$detalhe->segmento_b->data_vencimento_nominal = date('dmY');
		$detalhe->segmento_b->valor_documento_nominal = '1500';
		$detalhe->segmento_b->valor_abatimento = '0';
		$detalhe->segmento_b->valor_desconto = '0';
		$detalhe->segmento_b->valor_mora = '0';
		$detalhe->segmento_b->valor_multa = '0';
		$detalhe->segmento_b->codigo_documento_favorecido = '05771095613';
		$detalhe->segmento_b->aviso_favorecido = 0;
		$detalhe->segmento_b->exclusivo_siape_01 = 0;
		$detalhe->segmento_b->codigo_ispb = 60701190;

		// segmento c
		$detalhe->segmento_c->codigo_banco = 341;
		$detalhe->segmento_c->lote_servico = 1;
		$detalhe->segmento_c->tipo_registro = 3;
		$detalhe->segmento_c->numero_sequencial_registro_lote = 1;
		$detalhe->segmento_c->codigo_segmento_registro_detalhe = 'C';
		$detalhe->segmento_c->exclusivo_febraban_01 = '';
		$detalhe->segmento_c->valor_ir = '0';
		$detalhe->segmento_c->valor_iss = '0';
		$detalhe->segmento_c->valor_iof = '0';
		$detalhe->segmento_c->valor_outras_deducoes = '0';
		$detalhe->segmento_c->valor_outros_acrescimos = '0';
		$detalhe->segmento_c->agencia_favorecido = 3158;
		$detalhe->segmento_c->digito_verificador_agencia = '';
		$detalhe->segmento_c->numero_conta_corrente = 38094;
		$detalhe->segmento_c->digito_verificador_conta = 3;
		$detalhe->segmento_c->digito_verificador_agencia_conta = '';
		$detalhe->segmento_c->valor_inss = '0';
		$detalhe->segmento_c->exclusivo_febraban_02 = '';

		$lote->inserirDetalhe($detalhe);

		// trailer lote
		$lote->trailer->codigo_banco = 341;
		$lote->trailer->lote_servico = $lote->sequencial;
		$lote->trailer->exclusivo_febraban_01 = '';
		$lote->trailer->quantidade_registros_lote = 1;
		$lote->trailer->somatoria_valores = '10000';
		$lote->trailer->somatoria_quantidade_moedas = '1';
		$lote->trailer->numero_aviso_debito = '0';
		$lote->trailer->exclusivo_febraban_02 = '';
		$lote->trailer->codigos_ocorrencias_retorno = '';
		
		// apos definir o lote insere na remessa
		$remessa->inserirLote($lote);

		// trailer arquivo
		$remessa->trailer->codigo_banco = 341;
    	$remessa->trailer->lote_servico = 9999;
    	$remessa->trailer->tipo_registro = 9;
    	$remessa->trailer->exclusivo_febraban_01 = '';
    	$remessa->trailer->quantidade_lotes_arquivo = 1;
    	$remessa->trailer->quantidade_registros_arquivo = 1;
    	$remessa->trailer->quantidade_contas_conciliacao_lotes = 1;
    	$remessa->trailer->exclusivo_febraban_02 = '';

		// gera arquivo
		$remessaFile = new RemessaFile($remessa);
		$this->assertInstanceOf('CnabParser\Output\RemessaFile', $remessaFile);
		$remessaFile->generate(__DIR__.'/../../out/febraban-pagamento-cnab240.rem');
	}
}