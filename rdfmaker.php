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

$clientUrl = $_SERVER['SERVER_NAME'].'/ontology/client#';
$albumUrl = $_SERVER['SERVER_NAME'].'/ontology/album#';
$transactionUrl = $_SERVER['SERVER_NAME'].'/ontology/transaction#';

function criarClienteRdf($rdfname, $data){
    $filename = 'ontology/'.$rdfname.'.rdf';
    $mode = "w";
    $myfile = fopen($filename, $mode);
    fwrite($myfile, $data);
    fclose($myfile);

    return 'ontology/'.$rdfname.'.rdf';
}

$graph = new \EasyRdf\Graph();

#Inserir o namespace de client para utilizar na montagem do RDF/XML
\EasyRdf\RdfNamespace::set('client', $clientUrl);
\EasyRdf\RdfNamespace::set('album', $albumUrl);
\EasyRdf\RdfNamespace::set('transaction', $transactionUrl);


//Variaveis do cliente
$uriCliente = $_POST['uri'];
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
$me = $graph->resource($uri, 'transaction:Request');
$me->set('client:document', $document);
$me->set('client:name', $firstName.$lastName);
if (isset($email)) {
    $emailResource = $graph->resource("mailto:".$email);
    $me->add('client:email', $emailResource);
}

# 2nd Technique
$graph->addLiteral($uriCliente, 'client:firstName', $firstName);
$graph->addLiteral($uriCliente, 'client:lastName', $lastName);

# Criando o rdf do pedido

$graph->addLiteral($uriCliente, 'album:price', $price);
$graph->addLiteral($uriCliente, 'album:comment', $comment);
$graph->addLiteral($uriCliente, 'album:released', $released);
$graph->addLiteral($uriCliente, 'album:bandName', $bandName);
$graph->addLiteral($uriCliente, 'album:type', $type);
$graph->addLiteral($uriCliente, 'album:title', $title);


# Finally output the graph
$data = $graph->serialise('rdfxml');
if (!is_scalar($data)) {
    $data = var_export($data, true);
}

$rdfPath = criarClienteRdf($firstName.$lastName.$albumName, $data);

$_SESSION['rdfPath'] = $rdfPath;
$_SESSION['rdfData'] = $graph->dump('html');

header("Location:./#rdf");