<html lang="en">
    <head>
        <title>Rewrite Test &bull; NamelessMC</title>

        <link rel="stylesheet" href="core/assets/vendor/bootstrap/dist/css/bootstrap.min.css">
    </head>

    <body style="background-color: #eceeef; overflow-y: hidden;">
        <div style="text-align: center;">
            <br/><br/>

            <h1>NamelessMC v2</h1>

            <hr/>

            <div class="container">
                <div class="col-md-6 offset-md-3">
                    <?php
                    if (isset($_GET['route']) && $_GET['route'] == '/rewrite_test') {
                        echo '<div class="alert alert-success">Rewrite enabled!</div>';
                    } else {
                        echo '<div class="alert alert-danger">Rewrite disabled!</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
