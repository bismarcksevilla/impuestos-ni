<?php
require_once('vendor/autoload.php');

date_default_timezone_set('America/Managua');

use Impuestos\Inss;

$Inss = new Inss(new DateTime(@$_GET['fechainss']));

$Ir = new Impuestos\Ir([
    @$_GET['ir1'],
    @$_GET['ir2'],
    @$_GET['ir3'],
    @$_GET['ir4'],
    @$_GET['ir5'],
    @$_GET['ir6'],
    @$_GET['ir7'],
    @$_GET['ir8'],
    @$_GET['ir9'],
    @$_GET['ir10'],
    @$_GET['ir11'],
    @$_GET['ir12'],
 ]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calcular Impuestos</title>

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/default.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>

    <div class="container">

        <div class="row">
            <div class="col-sm-12 text-center">
                <h4>Calculo de Impuestos</h4>
                <hr>
            </div>
        </div>


        <div class="row">

            <form class="form col-sm-4 card card-body">

                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="text-center">Calcular Inss:</h4>
                    </div>
                </div>

                <div class="form-group">

                    <label class="form-text text-muted small">Salario Mensual:</label>
                    <div class="input-group">
                        <div class="input-group-prepend" >
                            <div class="input-group-text" style="background-color:white;">C$</div>
                        </div>
                        <input type="number" class="form-control" name="salarioinss" value="<?php echo $_GET['salarioinss'] ?>" placeholder="Salario Mensual">
                    </div>
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

                            echo '<tr><td colspan="2"><p class="lead m-0"><strong>Resultado:</strong></p></td></tr>';
                            echo
                                 "<tr><td><small>Salario Mensual: </small></td> <td> C$ <strong>". number_format($Inss->getSalario(), 2) ."</strong></td></tr>"
                                ."<tr style='background-color:#ddd;'><td><small>Impuesto Empleado:</small></td> <td> C$ <strong>".number_format($Inss->getInssEmpleado(), 2) ."</strong></td></tr>"
                                ."<tr style='background-color:#f5f5f5;'><td><small>Impuesto Patronal:</small></td> <td> C$ <strong>".number_format($Inss->getInssPatronal(), 2) ."</strong></td></tr>"
                                ."<tr style=''><td><small>Ciclo de Impuestos:</small></td> <td> <strong>". $Inss->getCiclo()->format('d/m/Y')."</strong></td></tr>"
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


            <form class="form col-sm-8">
                <div class="card card-body">

                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="text-center">Calcular Ir Anual:</h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6  form-inline align-items-center">

                            <p class="form-text text-muted small col-sm-12">Escriba el salario Mensual de los ultimos pagos:</p>

                            <?php for ($i=1; $i < 13 ; $i++): ?>
                                <label class="form-text small col-sm-2 col-form-label col-form-label-sm">Mes<?php echo $i ?>:</label>
                                <div class="input-group col-sm-10 mt-2">
                                    <div class="input-group-prepend" >
                                        <div class="input-group-text" style="background-color:white;">C$</div>
                                    </div>
                                    <input type="number"  name="ir<?php echo $i ?>" value="<?php echo @$_GET['ir'.$i]?>" placeholder="0.00" class="form-control form-control-sm">
                                </div>
                            <?php endfor ?>

                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <button type="submit" class="btn btn-primary">Calcular</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <p class="m-0 lead">Tabla de referencia</p>
                            <p class="m-0 small">Establecida en el Arto.52 de la Ley 822</p>
                            <div class="table-responsive-sm">
                                <table class="table table-sm table-dark">
                                    <thead>
                                        <tr>
                                            <th scope="col"><p class="m-0 small text-center"><strong>Desde</strong></p></th>
                                            <th scope="col"><p class="m-0 small text-center"><strong>Exceso</strong></p></th>
                                            <th scope="col"><p class="m-0 small text-center"><strong>Base</strong></p></th>
                                            <th scope="col"><p class="m-0 small text-center"><strong>Porcentaje</strong></p></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach ($Ir->getTabla() as $key => $value) {
                                                echo "
                                                    <tr class='list'>
                                                        <td class='text-right'><p class='m-0 small'>C$".number_format($value->Desde,2)."</p></td>
                                                        <td class='text-right'><p class='m-0 small'>C$".number_format($value->Exceso,2)."</p></td>
                                                        <td class='text-right'><p class='m-0 small'>".number_format($value->Base,2)."</p></td>
                                                        <td class='text-center'><p class='m-0 small'>$value->Porcentaje%</p></td>
                                                    </tr>"
                                                ;
                                            }
                                        ?>
                                    <tbody>
                                </table>
                            </div>

                            <?php if (@$_GET['ir1']): ?>
                                <table class="table">
                                    <tr>
                                        <td><p class="m-0 small">Pagos ingresados:</p></td>
                                        <td>
                                            <p class="m-0"><?php echo number_format($Ir->getCantidadPagos(), 0) .'/'. $Ir->getModo() ?></p>
                                            <pre><code>$Ir->getCantidadPagos()<br>$Ir->getModo()</code></pre>
                                        </td>
                                    </tr>
                                    <tr class="table-secondary">
                                        <td><p class="m-0 small">Ultimo Salario:</p></td>
                                        <td>
                                            <p class="m-0"><strong>C$ <?php echo number_format($Ir->getSalario(), 2) ?></strong></p>
                                            <pre><code>$Ir->getSalario()</code></pre>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><p class="m-0 small">Salario Promedio:</p></td>
                                        <td>
                                            <p class="m-0"><strong>C$ <?php echo number_format($Ir->getPromedio(), 2) ?></strong></p>
                                            <pre><code>$Ir->getPromedio()</code></pre>
                                        </td>
                                    </tr>
                                    <tr class="table-secondary">
                                        <td><p class="m-0 small">Inss Empleado:</p></td>
                                        <td>
                                            <p class="m-0"><strong>C$ <?php echo number_format($Ir->getInssEmpleado(), 2) ?></strong></p>
                                            <pre><code>$Ir->getInssEmpleado()</code></pre>
                                        </td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td><p class="m-0 small">IR:</p></td>
                                        <td>
                                            <p class="m-0"><?php echo number_format($Ir->getIr(), 2) ?></p>
                                            <pre><code>$Ir->getIr()</code></pre>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><p class="m-0 small">Salario Proyectado:</p></td>
                                        <td>
                                            <p class="m-0"><strong>C$ <?php echo number_format($Ir->getProyeccion(), 2) ?></strong></p>
                                            <pre><code>$Ir->getProyeccion()</code></pre>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><p class="m-0 small">Total Pagado:</p></td>
                                        <td>
                                            <p class="m-0"><?php echo number_format($Ir->getTotalPagado(), 2) ?></p>
                                            <pre><code>$Ir->getTotalPagado()</code></pre>
                                        </td>
                                    </tr>

                                </table>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!--
                        <pre><code style="background-color:#f5f5f5;"><?php var_dump($Ir) ?></code></pre>
                    -->
                </div>
            </form>
        </div>


    <footer class="container text-center">

        <div class="col-sm-12">

            <hr class="divider">

            <p class="small"><strong>Bismarck Sevilla </strong> - <span style="unicode-bidi:bidi-override; direction: rtl">moc&period;liamg&commat;augaracin&period;latigid</span> | 05/2018</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</body>
</html>