<?php require_once("../resources/config.php"); ?>

<?php include(TEMPLATE_FRONT . DS . "header.php") ?>


    
    <!-- Page Content -->
    <div class="container">

        <!-- Jumbotron Header -->
        <header class="jumbotron hero-spacer">
            <h1>A Warm Welcome!</h1>
            <p>whatever...................</p>
            <p><a class="btn btn-primary btn-large">Call to action!</a>
            </p>
        </header>

        <hr>

        <!-- Title -->
        <div class="row">
            <div class="col-lg-12">
                <h3>Books of the author:</h3>
            </div>
        </div>
        <!-- /.row -->

        <!-- Page Features -->
        <div class="row text-center">

            <?php get_products_in_cat_page(); ?>

            

        </div>
        <!-- /.row -->

        
    </div>
    <!-- /.container -->

<?php include(TEMPLATE_FRONT . DS . "footer.php") ?>

