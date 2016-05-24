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

use CnabParser\IntercambioBancarioAbstract;
use CnabParser\Parser\Layout;

class Remessa extends IntercambioBancarioAbstract
{
	public function __construct(Layout $layout)
	{
		parent::__construct($layout);

		$remessaLayout = $this->layout->getRemessaLayout();
		
		if (isset($remessaLayout['header_arquivo'])) {
			foreach ($remessaLayout['header_arquivo'] as $field => $definition) {
				$this->header->$field = (isset($definition['default'])) ? $definition['default'] : '';
			}
		}

		if (isset($remessaLayout['trailer_arquivo'])) {
			foreach ($remessaLayout['trailer_arquivo'] as $field => $definition) {
				$this->trailer->$field = (isset($definition['default'])) ? $definition['default'] : '';
			}
		}
	}

	public function novoLote($sequencial = 1)
	{
		return new Lote($this->layout->getRemessaLayout(), $sequencial);
	}
}