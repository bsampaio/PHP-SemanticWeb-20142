<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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
    
    
    function criarClienteRdf($rdfname, $data){
        $filename = 'ontology/person/'.$rdfname.'.rdf';
        $mode = "w";
        $myfile = fopen($filename, $mode);
        fwrite($myfile, $data);
        fclose($myfile);
        
        return 'http://'.$_SERVER['SERVER_NAME'].'/ontology/person/'.$rdfname.'.rdf';
    }
?>
<html>
    <head>
        <title>Cadastro de Clientes - RDF</title>
    </head>
    <body>
        <h1>Cadastro de Clientes</h1>

        <form method="POST">
            <h2>Identificador</h2>
            <label>URI</label>
            <input type="text" name="uri" id="uri" value="" placeholder="<?=$personUrl.'JoséDaSilva'?>"/>
            <h2>Detalhes</h2>
            <label>Document</label>
            <input type="text" name="document" id="document" value="" placeholder="3.333.333-ES"/>
            <label>First Name</label>
            <input type="text" name="firstName" id="firstName" value="" placeholder="José"/>
            <label>Last Name</label>
            <input type="text" name="lastName" id="lastName" value="" placeholder="Da Silva"/>
            <label>Email</label>
            <input type="email" name="email" id="email" value="" placeholder="josedasilva@hotmail.com"/>
            <input type="submit">
        </form>

<?php
    $format_options = array();
    foreach (\EasyRdf\Format::getFormats() as $format) {
        if ($format->getSerialiserClass()) {
            $format_options[$format->getLabel()] = $format->getName();
        }
    }
    
    if (isset($_REQUEST['uri'])) {

        $graph = new \EasyRdf\Graph();
        
        #Inserir o namespace de person para utilizar na montagem do RDF/XML
        \EasyRdf\RdfNamespace::set('person', $personUrl);

        # 1st Technique
        $me = $graph->resource($_REQUEST['uri'], 'person:Cliente');
        $me->set('person:document', $_REQUEST['document'].' '.$_REQUEST['firstName'].' '.$_REQUEST['lastName']);
        $me->set('person:name', $_REQUEST['firstName'].' '.$_REQUEST['lastName']);
        if ($_REQUEST['email']) {
            $email = $graph->resource("mailto:".$_REQUEST['email']);
            $me->add('person:email', $email);
        }

        # 2nd Technique
        $graph->addLiteral($_REQUEST['uri'], 'person:firstName', $_REQUEST['firstName']);
        $graph->addLiteral($_REQUEST['uri'], 'person:lastName', $_REQUEST['lastName']);

        
        # Finally output the graph
        $data = $graph->serialise('rdfxml');
        if (!is_scalar($data)) {
            $data = var_export($data, true);
        }
        
    }
?>
<?php
    echo 'Right Button > Save As...';
    if(isset($_REQUEST['firstName'])&&isset($_REQUEST['firstName'])){
?>
    <br/><a href="<?= criarClienteRdf($_REQUEST['firstName'].$_REQUEST['lastName'], $data)?>">RDF File</a>
<?php
    }
?>
</body>
</html>
