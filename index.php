<?php
require_once('vendor/autoload.php');

# Inicializando
use Impuestos\Inss;

$Inss = new Inss(new DateTime( @$_GET['fechainss']) );
$Ir = new Impuestos\Ir;

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calcular Impuestos</title>

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/default.min.css">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">


</head>
<body>

    <div class="container">

        <div class="row">

            <div class="col-sm-12 text-center">

                <h1>Calculo de Impuestos</h1>
                <hr>
            </div>
        </div>


        <div class="row">

            <form class="form col-sm-4">

                <p class="text-bold lead">Calcular Inss</p>

                <div class="form-group">

                    <label class="form-text text-muted small">Salario Mensual:</label>
                    <input type="number" class="form-control" name="salarioinss" value="<?php echo $_GET['salarioinss'] ?>" placeholder="Salario Mensual">
                </div>

                <div class="form-group">

                    <label class="form-text text-muted small">Respecto a fecha:</label>
                    <input class="form-control" type="date" name="fechainss" value="<?php echo $_GET['fechainss'] ?>">
                </div>

                <div class="form-group">

                    <button type="submit" class="btn btn-primary">Calcular</button>
                </div>


                <table class="table">
                    <?php
                        if ($salario = @$_GET['salarioinss']):

                            $Inss->setSalario($salario);

                            echo
                                 "<tr><td>Salario Mensual: </td> <td> C$ <strong>". number_format( $Inss->getSalario(), 2) ."</strong></td></tr>"
                                ."<tr><td>Impuesto Empleado: </td> <td> C$ <strong>".number_format( $Inss->getEmpleado(), 2) ."</strong></td></tr>"
                                ."<tr><td>Impuesto Patronal: </td> <td> C$ <strong>".number_format( $Inss->getPatronal(), 2) ."</strong></td></tr>"
                                ."<tr><td>Salario Mínimo: </td> <td> <strong>". number_format( $Inss->getMinimo(), 2)."</strong></td></tr>"
                                ."<tr><td>Salario Máximo: </td> <td> <strong>". number_format( $Inss->getMaximo(), 2)."</strong></td></tr>"
                                ."<tr><td>Ciclo de Impuestos: </td> <td> <strong>". $Inss->getCiclo()->format('d/m/Y H:i:s')."</strong></td></tr>"
                            ;
                        endif;
                    ?>
                </table>
                <!--
                <pre><code style="background-color:#f5f5f5;">
                <?php var_dump($Inss) ?>
                </code></pre>
                -->
            </form>



            <form class="form col-sm-4 col-sm-offset-2">

                <p class="text-bold lead">Calcular Ir</p>

                <div class="form-group">

                    <label class="form-text text-muted small">Salario mensual</label>
                    <input type="number" class="form-control" name="salarioir" value="<?php echo $_GET['salarioir'] ?>" placeholder="Salario Mensual">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Calcular</button>
                </div>

                <table class="table">
                    <?php
                        if ($salario = @$_GET['salarioir']):

                        //     $Ir->setSalario($salario);

                        //     echo
                        //          "<tr><td>Salario Mensual: </td> <td> C$ <strong>". number_format( $Inss->getSalario(), 2) ."</strong></td></tr>"
                        //         ."<tr><td>Impuesto Empleado: </td> <td> C$ <strong>".number_format( $Inss->getEmpleado(), 2) ."</strong></td></tr>"
                        //         ."<tr><td>Impuesto Patronal: </td> <td> C$ <strong>".number_format( $Inss->getPatronal(), 2) ."</strong></td></tr>"
                        //         ."<tr><td>Salario Mínimo: </td> <td> <strong>". number_format( $Inss->getMinimo(), 2)."</strong></td></tr>"
                        //         ."<tr><td>Salario Máximo: </td> <td> <strong>". number_format( $Inss->getMaximo(), 2)."</strong></td></tr>"
                        //         ."<tr><td>Ciclo de Impuestos: </td> <td> <strong>". $Inss->getCiclo()->format('d/m/Y H:i:s')."</strong></td></tr>"
                        //     ;
                        endif;
                    ?>
                </table>

                <pre><code style="background-color:#f5f5f5;"><?php var_dump($Ir) ?></code></pre>
            </form>
        </div>


    <footer class="container text-center">

        <div class="col-sm-12">

            <hr class="divider">

            <p class="small"><strong>Bismarck Sevilla </strong> - <span style="unicode-bidi:bidi-override; direction: rtl">moc&period;liamg&commat;augaracin&period;latigid</span> | 05/2018</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</body>
</html>