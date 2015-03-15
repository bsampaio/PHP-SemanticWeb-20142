<?php
    session_start();
?>   
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Semantic Web - IFES - 2014/2</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/grayscale.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>
</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

    <!-- Navigation -->
    <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top">
                    <i class="fa fa-play-circle"></i>  <span class="light">Semantic</span> Web
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
                <ul class="nav navbar-nav">
                    <!-- Hidden li included to remove active class from about link when scrolled up past about section -->
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#order">Order</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#rdf">Graph Dump</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#about">About</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Intro Header -->
    <header class="intro">
        <div class="intro-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <h1 class="brand-heading">Semantic Web</h1>
                        <p class="intro-text">A test from Semantic Web - IFES.<br>Created by Breno Grillo & Diego Pasti.</p>
                        <a href="#order" class="btn btn-circle page-scroll">
                            <i class="fa fa-angle-double-down"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Order Section -->
    <section id="order" class="container content-section">
        <div class="row">
            <div class="col-lg-12">
                <?php 
                    include_once './rdfform.php';
                ?>
            </div>
        </div>
        
    </section>
    
        
    </section>
    <!-- RDF Section -->
    <section id="rdf" class="container content-section">
        <div class="row">
            <div class="col-lg-12">
                <?php
                    if(isset($_SESSION['rdfPath'])){
                ?>
                <h2>RDF Content <a href="<?=$_SESSION['rdfPath']?>" class="btn btn-warning pull-right" target="_blank">RDF File Download</a> </h2>
                <pre class="prettyprint">
                    <?=$_SESSION['rdfData']?>   
                </pre>
                <?php 
                    
                    } ?>
            </div>
        </div>
    </section>
    
    <!-- About Section -->
    <section id="about" class="container content-section">
        <div class="row">
            <div class="col-lg-12">
                <?php 
                    include_once './about.php';
                ?>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p>Copyright &copy; <a href="http://bsampaio.tk" target="_blank">Breno Grillo</a> 2014</p>
        </div>
    </footer>

    <script type="text/javascript">
        
        $('input#title').attr("readonly",true);
        $('input#type').attr("readonly",true);
        $('input#released').attr("readonly",true);
        $('textarea#comment').attr("readonly",true);
        $('input#price').attr("readonly",true);
        
        $('a#album_search').click(function(){
            var albumUri = $("input#album_uri_suffix").val();
            var bandName = $("input#bandName").val();
            $.ajax({
                url: "./products.php",
                dataType: "json",
                type: "POST",
                data: {
                    albumUri: albumUri,
                    bandName: bandName
                },
                cache: false,
                success: function(data) {
                    if(data.result==="true"){
                        $('input#title').val(data.data.title);
                        $('input#type').val(data.data.type);
                        $('input#released').val(data.data.released.date);
                        $('textarea#comment').html(data.data.comment);
                        $('input#price').val(Math.floor(Math.random() * 20) + 10);
                        
                        $('#formSubmit').removeAttr("disabled");
                    }else{
                        $('input#title').attr("readonly",false);
                        $('input#type').attr("readonly",false);
                        $('input#released').attr("readonly",false);
                        $('textarea#comment').attr("readonly",false);
                        
                        $('input#price').val(Math.floor(Math.random() * 20) + 10);
                        
                        $('#formSubmit').removeAttr("disabled");
                    }
                },
                error: function(err) {
                    alert('Nao foi possivel obter os dados da DBPedia.org')
                    $('input#title').attr("readonly",false);
                    $('input#type').attr("readonly",false);
                    $('input#released').attr("readonly",false);
                    $('textarea#comment').attr("readonly",false);

                    $('input#price').val(Math.floor(Math.random() * 20) + 10);
                    
                    $('#formSubmit').removeAttr("disabled");
                }
            });
        });
    </script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="js/jquery.easing.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/grayscale.js"></script>

</body>

</html>
