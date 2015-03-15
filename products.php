<?php
    session_start();
    /**
     * Making a SPARQL SELECT query
     *
     * This example creates a new SPARQL client, pointing at the
     * dbpedia.org endpoint. It then makes a SELECT query that
     * returns all of the countries in DBpedia along with an
     * english label.
     *
     * Note how the namespace prefix declarations are automatically
     * added to the query.
     *
     * @package    EasyRdf
     * @copyright  Copyright (c) 2009-2013 Nicholas J Humfrey
     * @license    http://unlicense.org/
     */
    
    $albumName = $_POST['albumUri'];

    require_once "vendor/autoload.php";
    
    // Setup some additional prefixes for DBpedia
    \EasyRdf\RdfNamespace::set('category', 'http://dbpedia.org/resource/Category:');
    \EasyRdf\RdfNamespace::set('res', 'http://dbpedia.org/resource/');
    \EasyRdf\RdfNamespace::set('dbo', 'http://dbpedia.org/ontology/');
    \EasyRdf\RdfNamespace::set('dbprop', 'http://dbpedia.org/property/');
    
    $sparql = new \EasyRdf\Sparql\Client('http://dbpedia.org/sparql');
    
    $result = $sparql->query(
        'SELECT ?title ?type ?bandName ?released ?comment WHERE{'.
            '?res dbprop:name '.'"'.$albumName.'"@en; '.
                              'dbprop:type ?type ;'.
                              'rdfs:label ?title;'.
                              'dbprop:relyear ?released;'.
                              'rdfs:comment ?comment;'.
                              'dbprop:artist ?band .'.
           '?band dbprop:name ?bandName .'.
            'FILTER ( lang(?comment) = "en")'.
        '}'.
        'LIMIT 1'
    );
    
    $query = 'SELECT ?title ?type ?bandName ?released ?comment WHERE{'.
            '?res dbprop:name '.'"'.$albumName.'"@en; '.
                              'dbprop:type ?type ;'.
                              'rdfs:label ?title;'.
                              'dbprop:released ?released;'.
                              'rdfs:comment ?comment;'.
                              'dbprop:artist ?band .'.
           '?band dbprop:name ?bandName .'.
            'FILTER ( lang(?comment) = "en")'.
        '}'.
        'LIMIT 1';
    
    foreach ($result as $row) {
       $resultArray = array('title' => $row->title->getValue(),
                            'type' => $row->type->getValue(),
                            'bandName' => $row->bandName->getValue(),
                            'released' => $row->released->getValue(),
                            'comment' => $row->comment->getValue()
                    );
       $data = array(
           'result' => 'true',
           'data' => $resultArray,
           'query' => $query
       );
       $_SESSION['albumFinded'] = 'true';
       echo json_encode($data);
    }
    
?>