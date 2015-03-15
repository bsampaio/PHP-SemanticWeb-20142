<div class="col-lg-12">
    <form id="customer" action="./rdfmaker.php" method="POST" class="form-horizontal" role="form">
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
        <div class="col-lg-6 col-lg-offset-2 pull-right">
            <h2>Album</h2>

                <div class="form-group">
                    <label for="album_uri_suffix">Album Name</label>
                        <input type="text" class="form-control" name="album_uri_suffix" required="required" id="album_uri_suffix" value="" placeholder="Strangeland"/><br/>
                        <a class="btn btn-default" id="album_search">Search Details</a>
                </div>

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" name="title" required="required" id="title" value="" placeholder=""/>
                </div>
                <div class="form-group">
                    <label for="type">Type</label>
                    <input type="text" class="form-control" name="type" required="required" id="type" value="" placeholder=""/>
                </div>
                <div class="form-group">
                    <label for="bandName">Band Name</label>
                    <input type="text" class="form-control" name="bandName" required="required" id="bandName" value="" placeholder=""/>
                </div>
                <div class="form-group">
                        <label for="released">Released</label>
                        <input type="text" class="form-control" name="released" required="required" id="released" value="" placeholder=""/>
                </div>
                <div class="form-group">
                        <label for="comment">Comment</label>
                        <textarea class="form-control" name="comment" required="required" id="comment" value="" placeholder=""></textarea><br/>
                </div>
                <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" class="form-control" name="price" required="" id="price" value="" placeholder="10"/><br/>
                </div>
            <div class="col-lg-12">
                <input type="submit" id="formSubmit" class="form-control btn btn-default" disabled="disabled" value="Submit"/>
            </div>
        </div>  
    </form>
    
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('form#customer').submit(function(){
           var filename = $('input#firstName').val()+$('input#lastName').val();
           var olduri = '/ontology/person#';
           $('input#uri').val(olduri+filename);
        });
    });
</script>