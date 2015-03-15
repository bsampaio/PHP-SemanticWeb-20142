<?php
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

$personUrl = $_SERVER['SERVER_NAME'].'/ontology/person#';
$albumUrl = $_SERVER['SERVER_NAME'].'/ontology/album#';

function criarClienteRdf($rdfname, $data){
    $filename = 'ontology/person/'.$rdfname.'.rdf';
    $mode = "w";
    $myfile = fopen($filename, $mode);
    fwrite($myfile, $data);
    fclose($myfile);

    return 'ontology/person/'.$rdfname.'.rdf';
}

$graph = new \EasyRdf\Graph();

#Inserir o namespace de person para utilizar na montagem do RDF/XML
\EasyRdf\RdfNamespace::set('person', $personUrl);

//Variaveis do cliente
$uriCliente = $_POST['uri'];
$document = $_POST['document'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];

//Variáveis do álbum
$

# 1st Technique
$me = $graph->resource($uriCliente, 'person:Cliente');
$me->set('person:document', $document);
$me->set('person:name', $firstName.$lastName);
if (isset($email)) {
    $emailResource = $graph->resource("mailto:".$email);
    $me->add('person:email', $emailResource);
}

# 2nd Technique
$graph->addLiteral($uriCliente, 'person:firstName', $firstName);
$graph->addLiteral($uriCliente, 'person:lastName', $lastName);



# Finally output the graph
$data = $graph->serialise('rdfxml');
if (!is_scalar($data)) {
    $data = var_export($data, true);
}
$_SESSION['uri'] = $uriCliente;