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

	/** TODO
	public function testRetornoGenerateModelSuccess()
	{
	}
	*/
}