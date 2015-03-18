<?php
    session_start();
/**
     * Construct a FOAF document with a choice of serialisations
     *
     * This example is similar in concept to Leigh Dodds' FOAF-a-Matic.
     * The fields in the HTML form are inserted into an empty
     * EasyRdf\Graph and then serialised to the chosen format.
     *
     * @package    EasyRdf
     * @copyright  Copyright (c) 2009-2013 Nicholas J Humfrey
     * @license    http://unlicense.org/
     */

require_once "vendor/autoload.php";

$clientUrl = $_SERVER['SERVER_NAME'].'/ontology/client.owl';
$albumUrl = $_SERVER['SERVER_NAME'].'/ontology/album.owl';
$transactionUrl = $_SERVER['SERVER_NAME'].'/ontology/transaction.owl';

function criarClienteRdf($rdfname, $data){
    $mode = "w";
    $myfile = fopen($rdfname, $mode);
    fwrite($myfile, $data);
    fclose($myfile);

    return $rdfname;
}

$graph = new \EasyRdf\Graph();

#Inserir o namespace de client para utilizar na montagem do RDF/XML
\EasyRdf\RdfNamespace::set('client', $clientUrl);
\EasyRdf\RdfNamespace::set('album', $albumUrl);
\EasyRdf\RdfNamespace::set('transaction', $transactionUrl);


//Variaveis do cliente
$uriTransaction = 'resource/'.$_POST['uri'].'.rdf';
$document = $_POST['document'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];

//Variáveis do álbum
$title = $_POST['title'];
$type = $_POST['type'];
$bandName = $_POST['bandName'];
$released = $_POST['released'];
$comment = $_POST['comment'];
$price = $_POST['price'];

# 1st Technique
$me = $graph->resource($uriTransaction, 'transaction:Request');
$me->set('client:document', $document);
$me->set('client:name', $firstName.$lastName);
if (isset($email)) {
    $emailResource = $graph->resource("mailto:".$email);
    $me->add('client:email', $emailResource);
}

# 2nd Technique
$graph->addLiteral($uriTransaction, 'client:firstName', $firstName);
$graph->addLiteral($uriTransaction, 'client:lastName', $lastName);

# Criando o rdf do pedido

$graph->addLiteral($uriTransaction, 'album:price', $price);
$graph->addLiteral($uriTransaction, 'album:comment', $comment);
$graph->addLiteral($uriTransaction, 'album:released', $released);
$graph->addLiteral($uriTransaction, 'album:bandName', $bandName);
$graph->addLiteral($uriTransaction, 'album:type', $type);
$graph->addLiteral($uriTransaction, 'album:title', $title);


# Finally output the graph
$data = $graph->serialise('rdfxml');
if (!is_scalar($data)) {
    $data = var_export($data, true);
}

$rdfPath = criarClienteRdf($uriTransaction, $data);

$jsonResponse = array(
  'success' => true,
  'rdfPath' => $rdfPath,
  'rdfDataHTML' => $graph->dump('html'),
  'redirectLocation' => '#rdf'
);

echo json_encode($jsonResponse);