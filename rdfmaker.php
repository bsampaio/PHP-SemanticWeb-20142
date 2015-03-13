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

    <h2>Customer</h2>

    <form action="./#about" method="POST" class="form-horizontal" role="form">
        <div class="form-group">
        <label class="control-label" for="uri">URI</label>
        <input type="text" class="form-control" name="uri" id="uri" value="" placeholder="<?=$personUrl.'JoséDaSilva'?>"/>
        </div>
        <div class="form-group">
        <label class="control-label" for="document">Document</label>
        <input type="text" class="form-control" name="document" id="document" value="" placeholder="3.333.333-ES"/>
        </div>
        <div class="form-group">
        <label for="firstName">First Name</label>
        <input type="text" class="form-control" name="firstName" id="firstName" value="" placeholder="José"/>
        </div>
        <div class="form-group">
        <label for="lastName">Last Name</label>
        <input type="text" class="form-control" name="lastName" id="lastName" value="" placeholder="Da Silva"/>
        </div>
        <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" name="email" id="email" value="" placeholder="josedasilva@hotmail.com"/><br/>
        <?php
            if(!(isset($_REQUEST['firstName'])&&isset($_REQUEST['firstName']))){
        ?>
            <input class="form-control btn-default" value="Submit" type="submit"/>
        <?php
            }
        ?>
        </div>
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
    if((isset($_REQUEST['firstName'])&&isset($_REQUEST['firstName']))){
?>
        <a class="form-control btn btn-success" href="<?= criarClienteRdf($_REQUEST['firstName'].$_REQUEST['lastName'], $data)?>">RDF File</a>
<?php
    }
?>
