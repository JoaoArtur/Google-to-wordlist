<?php 
	/*
	*	GTW v0.1 - by João Artur
	*/
	require_once 'vendor/autoload.php';
	require_once 'lib/googletowl.php';

	$gtw = new GTW();
	$gtw->adicionar('chave','Digite a palavra-chave');
	$gtw->adicionar('paginas','Quantidade de páginas a buscar');

	if ($gtw->chave != '') {
		$gtw->buscarChave();
	}

	$gtw->adicionar('lista','Digite o arquivo para ser salvo');
	if ($gtw->lista != '') {
		$gtw->salvarLista();
	}

?>