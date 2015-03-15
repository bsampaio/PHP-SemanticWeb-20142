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
    require './products.php';
    
    $personUrl = $_SERVER['SERVER_NAME'].'/ontology/person#';
    
    
    function criarClienteRdf($rdfname, $data){
        $filename = 'ontology/person/'.$rdfname.'.rdf';
        $mode = "w";
        $myfile = fopen($filename, $mode);
        fwrite($myfile, $data);
        fclose($myfile);
        
        return 'ontology/person/'.$rdfname.'.rdf';
    }
?>
<form id="customer" action="./#about" method="POST" class="form-horizontal" role="form">
    <div class="col-lg-4">
        <h2>Customer</h2>
            <div class="form-group">
            <label class="control-label" for="uri">URI</label>
            <input type="text" class="form-control" readonly="readonly" name="uri" id="uri" value=""/>
            </div>
            <div class="form-group">
            <label class="control-label" for="document">Document</label>
            <input type="text" class="form-control" name="document" required="required" id="document" value="" placeholder="3.333.333-ES"/>
            </div>
            <div class="form-group">
            <label for="firstName">First Name</label>
            <input type="text" class="form-control" name="firstName" required="required" id="firstName" value="" placeholder="José"/>
            </div>
            <div class="form-group">
            <label for="lastName">Last Name</label>
            <input type="text" class="form-control" name="lastName" required="required" id="lastName" value="" placeholder="Da Silva"/>
            </div>
            <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" required="required" id="email" value="" placeholder="josedasilva@hotmail.com"/><br/>
            </div>
    </div>
    <div class="col-lg-4 col-lg-offset-2">
        <h2>Album</h2>
        
            <div class="form-group">
                <label for="album_uri_suffix">Album URI Suffix</label>
                    <input type="text" class="form-control" name="album_uri_suffix" required="required" id="album_uri_suffix" value="" placeholder="I_See_Fire"/><br/>
                    <a class="btn btn-default" id="album_search">Search Details</a>
            </div>
            
            <?php
                if(isset($_REQUEST['album_uri_suffix'])){
                    findProductQuery($_REQUEST['album_uri_suffix']);
            ?>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" required="required" id="title" value="<?=$_SESSION['disc_array']['data']['title']?>" placeholder=""/>
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <input type="text" class="form-control" name="type" required="required" id="type" value="<?=$_SESSION['disc_array']['data']['type']?>" placeholder=""/>
            </div>
            <div class="form-group">
                <label for="bandName">Band Name</label>
                <input type="text" class="form-control" name="bandName" required="required" id="bandName" value="<?=$_SESSION['disc_array']['data']['bandName']?>" placeholder=""/>
            </div>
            <div class="form-group">
                    <label for="released">Released</label>
                    <input type="text" class="form-control" name="released" required="required" id="released" value="<?=$_SESSION['disc_array']['data']['released']?>" placeholder=""/>
            </div>
            <div class="form-group">
                    <label for="comment">Comment</label>
                    <textarea class="form-control" name="comment" required="required" id="comment" value="" placeholder=""><?=$_SESSION['disc_array']['data']['comment']?></textarea><br/>
            </div>
            <?php
                };
            ?>
        
            <?php
                if(isset($_SESSION['disc_array'])){
            ?>
                <input class="form-control btn-default" value="Submit" type="submit"/>
            <?php
                }
            ?>
    </div>
    
</form>
<?php
    if (isset($_REQUEST['uri'])&&isset($_SESSION['disc_array'])) {

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
        $_SESSION['uri'] = $_REQUEST['uri'];
    }
?>
<?php
    if(isset($data)){
?>
        <a class="form-control btn btn-success" href="<?= criarClienteRdf($_REQUEST['firstName'].$_REQUEST['lastName'], $data)?>">RDF File</a>
<?php
    }
    if(!isset($_REQUEST['uri'])){
?>

<script type="text/javascript">
    $(document).ready(function(){
        $('form#customer').submit(function(){
           var filename = $('input#firstName').val()+$('input#lastName').val();
           var olduri = '/ontology/person#';
           $('input#uri').val(olduri+filename);
        });
        // get values from FORM
        $('a#album_search').click(function(){
            var albumUri = $("input#album_uri_suffix").val();
            $.ajax({
                url: "./products.php",
                type: "POST",
                data: {
                    albumUri: albumUri,
                },
                cache: false,
                success: function(data) {
                    if(data.result==="true"){
                        alert('SHDUHASDUH');
                    }else{
                        alert(data);
                    }
                },
                error: function(err) {
                    // Fail message

                }
            });
        });
    });
</script>        
<?php
    };
?>