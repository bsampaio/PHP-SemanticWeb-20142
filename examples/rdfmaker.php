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

    require_once realpath(__DIR__.'/..')."/vendor/autoload.php";
    require_once __DIR__."/html_tag_helpers.php";
    
    $personUrl = realpath(__DIR__.'/..').'/ontology/person#';
    
    function criarClienteRdf($rdfname, $data){
        $filename = realpath(__DIR__.'/..').'/ontology/person/'.$rdfname.'.rdf';
        $mode = "w";
        $myfile = fopen($filename, $mode);
        fwrite($myfile, $data);
        fclose($myfile);
        
        return '/ontology/person/'.$rdfname.'.rdf';
    }


    $format_options = array();
    foreach (\EasyRdf\Format::getFormats() as $format) {
        if ($format->getSerialiserClass()) {
            $format_options[$format->getLabel()] = $format->getName();
        }
    }
?>
<html>
<head><title>Cadastro de Clientes - RDF</title></head>
<body>
<h1>Cadastro de Clientes</h1>

<?= form_tag(null, array('method' => 'POST')) ?>

<h2>Identificador</h2>
<?= labeled_text_field_tag('uri', $personUrl.'BrenoGrillo', array('size'=>40)) ?><br />

<h2>Detalhes</h2>
<?= labeled_text_field_tag('document', '3.123.332-ES', array('size'=>12)) ?><br />
<?= labeled_text_field_tag('firstName', 'Breno') ?><br />
<?= labeled_text_field_tag('lastName', 'Grillo') ?><br />
<?= labeled_text_field_tag('email', 'breno@example.com') ?><br />


<?= submit_tag() ?>
<?= form_end_tag() ?>


<?php
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
