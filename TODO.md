# TODO LIST

1. Corrigir parser de Retorno em Input\RetornoFile

lotes = array( // cada item é um lote )
// um lote é
lote = array(
	codigo_lote => 1
	header_lote => array()
	trailer_lote => array()
	titulos => array(
		// cada item é um titulo
		array(
			// cada item é um segmento
			"T" => array(),
			"U" => array(),
			...
		),
	)
)

2. Verificar segmentos opcionais por existência, se não existir não adiciona na classe model
