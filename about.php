<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<h2 class="text-center">About</h2>
<div class="col-sm-6">
    <p>
        O referido sistema foi desenvolvido com o intúito de provar as habilidades e conhecimentos que adiquirimos durante as aulas de Web Semântica. <br/>
        Nesse trabalho, fo simulada uma loja de discos com dados abertos. Significa que os dados das transações serão disponibilizados em RDF para consultas posteriores.
        Dessa forma também será possível realizar consultas do tipo SPARQL nos arquivos ou fazer navegação em forma de grafos. No exemplo desenvolvido, não foi possível consultar os dados do RDF gerado por SPARQL, por limitação da ferramenta utilizada.<br/>
        O próximo parágrafo entrará em detalhes à respeito da aplicação e suas capacidades.
    </p>
</div>
<div class="col-sm-6 pull-right">
    <p>
        No sistema utilizamos como linguagem back-end o PHP. Como framework de Web Semântica, foi utilizado o EasyRDF e para o front-end e comunicação assíncrona utilizamos Bootstrap CSS e Jquery com Ajax respectivamente.
        O EasyRDF nos permitiu fazer uma consulta SPARQL na DBPedia.org que nos dá como resultado os detalhes de um determinado álbum musical, passando como parâmetros apenas o nome do álbum e seu artista relacionado. Também foi possível criar um RDF a partir de informações dadas pelo usuário e complementar com os dados da DBPedia, criando o RDF resultante. Uma representação do grafo resultante é mostrada na sessão "Graph Dump".<br/>
        Os dados são salvos na sessão do usuário. Portanto, o "Graph Dump" é reiniciado caso a sessão seja encerrada. Os RDFs resultantes são guardados no caminho /ontology/firstNameLastNameAlbumTitle.rdf
    </p>
</div>