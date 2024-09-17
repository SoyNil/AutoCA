<?php

//NOTA:
//Arreglar los aceros en eje X como en eje Y; la distancia debe ser desde el acero en las esquinas
//Agregar Cotas al cuadrado
//Cambiar de letras y fuente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener la escala del formulario
    $escala = (float)$_POST["escala"] ?? 50;

    // Obtener las dimensiones del formulario
    $base = (float)$_POST["base"] / $escala;
    $altura = (float)$_POST["altura"] / $escala;
    $Tipo_AceroEsquinas = (float)$_POST["tiposaceroEsquinas"] / $escala;

    $cantidadX = (int)$_POST["acerosX"];
    $Tipo_AcerosadicionalesX = (float)$_POST["tipoaceroX"]/ $escala;

    $cantidadY = (int)$_POST["acerosy"];    
    $Tipo_AcerosadicionalesY = (float)$_POST["tipoaceroY"] / $escala;


    // Contenido DXF
    $contenido_dxf = "0\nSECTION\n2\nENTITIES\n";

    // Definir los vértices del cuadro exterior como una polilínea independiente
    $contenido_dxf .= "0\nPOLYLINE\n8\n0\n";
    $contenido_dxf .= "66\n1\n70\n8\n";
    $contenido_dxf .= "62\n5\n"; // Establecer el color azul

    $vertices = array(
        array(3, 1.2),
        array($base + 3, 1.2),
        array($base + 3, $altura + 1.2),
        array(3, $altura + 1.2),
        array(3, 1.2), // Cierre la polilínea volviendo al punto inicial
    );

    foreach ($vertices as $vertex) {
        $x = $vertex[0];
        $y = $vertex[1];
        $contenido_dxf .= "0\nVERTEX\n8\n0\n10\n$x\n20\n$y\n";
    }
    $contenido_dxf .= "0\nSEQEND\n"; // Finalizar la primera polilínea

    $contenido_dxf .= "0\nLINE\n8\n0\n";
    $contenido_dxf .= "62\n8\n"; // Establecer el color azul
    // Definir los puntos de la línea adicional
    $yOffset = -0.05; // Desplazamiento hacia abajo
    $contenido_dxf .= "10\n3\n20\n" . (1.2 + $yOffset) . "\n"; // Punto de inicio
    $contenido_dxf .= "11\n" . ($base + 3) . "\n21\n" . (1.2 + $yOffset) . "\n"; // Punto final
    $texBasex=($base*$escala);
    // Agregar texto debajo de la línea
    $contenido_dxf .= "0\nTEXT\n8\n0\n"; // Entidad TEXT
    $contenido_dxf .= "10\n" . ($base/2)+3 . "\n"; // Coordenada X centrada en la línea
    $contenido_dxf .= "20\n" . ((1.2 + $yOffset-0.05)-0.02) . "\n"; // Coordenada Y justo debajo de la línea
    $contenido_dxf .= "40\n0.05\n"; // Tamaño del texto
    $contenido_dxf .= "1\n$texBasex\n"; // Contenido del texto

    // Agregar una línea vertical en el lado derecho del cuadrado
    $contenido_dxf .= "0\nLINE\n8\n0\n";
    $contenido_dxf .= "62\n8\n"; // Establecer el color rojo
    $xOffset = ($base + 3)+0.05; // Coordenada X para la línea vertical
    $contenido_dxf .= "10\n" . ($xOffset) . "\n20\n1.2\n"; // Punto de inicio (lado derecho)
    $contenido_dxf .= "11\n" . ($xOffset) . "\n21\n" . ($altura + 1.2) . "\n"; // Punto final (lado derecho)
    $texBasey=($altura*$escala);
    // Agregar texto al costado de la línea vertical
    $contenido_dxf .= "0\nTEXT\n8\n0\n"; // Entidad TEXT
    $contenido_dxf .= "10\n" . ($xOffset)+0.02 . "\n"; // Coordenada X justo al costado de la línea vertical
    $contenido_dxf .= "20\n" . ($altura / 2 + 1.2) . "\n"; // Coordenada Y centrada verticalmente
    $contenido_dxf .= "40\n0.05\n"; // Tamaño del texto
    $contenido_dxf .= "1\n$texBasey\n"; // Contenido del texto

    // Cuadrado reducido dentro del cuadro exterior (4 unidades de separación)
    $contenido_dxf .= "0\nPOLYLINE\n8\n0\n";
    $contenido_dxf .= "66\n1\n70\n8\n";
    $contenido_dxf .= "62\n3\n"; // Establecer el color verde

    $verticesin = array(
        array(3.08, 1.28), // Esquina superior izquierda
        array($base + 2.92, 1.28), // Esquina superior derecha
        array($base + 2.92, $altura + 1.12), // Esquina inferior derecha
        array(3.08, $altura + 1.12), // Esquina inferior izquierda
        array(3.08, 1.28), // Cierre la polilínea volviendo al punto inicial
    );

    foreach ($verticesin as $vertex) {
        $x = $vertex[0];
        $y = $vertex[1];
        $contenido_dxf .= "0\nVERTEX\n8\n0\n10\n$x\n20\n$y\n";
    }
    $contenido_dxf .= "0\nSEQEND\n"; // Finalizar la segunda polilínea


    // Cuadrado reducido dentro del cuadro exterior (4 unidades de separación)
    $contenido_dxf .= "0\nPOLYLINE\n8\n0\n";
    $contenido_dxf .= "66\n1\n70\n8\n";
    $contenido_dxf .= "62\n3\n"; // Establecer el color verde
    $verticesin = array(
        array(3.10, 1.30), // Esquina superior izquierda
        array($base + 2.90, 1.30), // Esquina superior derecha
        array($base + 2.90, $altura + 1.10), // Esquina inferior derecha
        array(3.10, $altura + 1.1), // Esquina inferior izquierda
        array(3.10, 1.30), // Cierre la polilínea volviendo al punto inicial
    );

    foreach ($verticesin as $vertex) {
        $x = $vertex[0];
        $y = $vertex[1];
        $contenido_dxf .= "0\nVERTEX\n8\n0\n10\n$x\n20\n$y\n";
    }

    $contenido_dxf .= "0\nSEQEND\n"; // Finalizar la segunda polilínea

    //Aceros*---------------------------------------------------------------------
    $verticesint = array(
        array(3.10, 1.30), // Esquina superior izquierda
        array($base + 2.90, 1.30), // Esquina superior derecha
        array($base + 2.90, $altura + 1.10), // Esquina inferior derecha
        array(3.10, $altura + 1.1), // Esquina inferior izquierda
        array(3.10, 1.30),  // Cierre la polilínea volviendo al punto inicial
    );

    // Radio de los círculos en el eje X proporcionado por el usuario
    $radio_x = $Tipo_AcerosadicionalesX*$escala;

    // Radio de los círculos en el eje Y proporcionado por el usuario
    $radio_y = $Tipo_AcerosadicionalesY*$escala;

    //GANCHO ESTRIBO//}

    // Definir los vértices del gancho de estriboext
    $vertices_gancho_izquierdo = array(
        array((($base - 0.04 - 0.424264686) + 0.2) + 2.96, ($altura - 0.424264686 + 0.2) + 1.08),
        array($base + 2.86, $altura + 1.12),
    );

    // Gancho de estribos superior derecho
    $vertices_gancho_derecho = array(
        array((($base + 0.04 - 0.424264686) + 0.16) + 3.01, ($altura - 0.424264686 + 0.16) + 1.09),
        array($base + 2.92, $altura + 1.08),
    );
    // Agregar líneas para el gancho de estribos izquierdo
    foreach ($vertices_gancho_izquierdo as $i => $vertex) {
        $x1 = $vertex[0];
        $y1 = $vertex[1];
        $x2 = $vertices_gancho_izquierdo[($i + 1) % count($vertices_gancho_izquierdo)][0];
        $y2 = $vertices_gancho_izquierdo[($i + 1) % count($vertices_gancho_izquierdo)][1];

        $contenido_dxf .= "0\nLINE\n8\n0\n10\n$x1\n20\n$y1\n11\n$x2\n21\n$y2\n";
        $contenido_dxf .= "62\n3\n"; // Establecer el color verde
    }

    // Agregar líneas para el gancho de estribos derecho
    foreach ($vertices_gancho_derecho as $i => $vertex) {
        $x1 = $vertex[0];
        $y1 = $vertex[1];
        $x2 = $vertices_gancho_derecho[($i + 1) % count($vertices_gancho_derecho)][0];
        $y2 = $vertices_gancho_derecho[($i + 1) % count($vertices_gancho_derecho)][1];

        $contenido_dxf .= "0\nLINE\n8\n0\n10\n$x1\n20\n$y1\n11\n$x2\n21\n$y2\n";
        $contenido_dxf .= "62\n3\n"; // Establecer el color verde
    }

    // Definir los vértices del gancho de estribo Interno
    $vertices_gancho_izquierdo = array(
        array((($base - 0.04 - 0.424264686) + 0.2) + 2.98, ($altura - 0.424264686 + 0.2) + 1.08),
        array($base + 2.86, $altura + 1.1),
    );

    // Gancho de estribos superior derecho
    $vertices_gancho_derecho = array(
        array((($base + 0.04 - 0.424264686) + 0.16) + 3, ($altura - 0.424264686 + 0.16) + 1.1),
        array($base + 2.9, $altura + 1.06),
    );
    // Agregar líneas para el gancho de estribos izquierdo
    foreach ($vertices_gancho_izquierdo as $i => $vertex) {
        $x1 = $vertex[0];
        $y1 = $vertex[1];
        $x2 = $vertices_gancho_izquierdo[($i + 1) % count($vertices_gancho_izquierdo)][0];
        $y2 = $vertices_gancho_izquierdo[($i + 1) % count($vertices_gancho_izquierdo)][1];

        $contenido_dxf .= "0\nLINE\n8\n0\n10\n$x1\n20\n$y1\n11\n$x2\n21\n$y2\n";
        $contenido_dxf .= "62\n3\n"; // Establecer el color verde
    }

    // Agregar líneas para el gancho de estribos derecho
    foreach ($vertices_gancho_derecho as $i => $vertex) {
        $x1 = $vertex[0];
        $y1 = $vertex[1];
        $x2 = $vertices_gancho_derecho[($i + 1) % count($vertices_gancho_derecho)][0];
        $y2 = $vertices_gancho_derecho[($i + 1) % count($vertices_gancho_derecho)][1];

        $contenido_dxf .= "0\nLINE\n8\n0\n10\n$x1\n20\n$y1\n11\n$x2\n21\n$y2\n";
        $contenido_dxf .= "62\n3\n"; // Establecer el color verde
    }
    //Fin del GANCHOO

    // Convertidor de medidas para Tipo_AceroEsquinas
    if ($Tipo_AcerosadicionalesX == 0.0127/ $escala) {
        $numeracionx = "(2)";
    } elseif ($Tipo_AcerosadicionalesX == 0.019039999999999998/ $escala) {
        $numeracionx = "(3)";
    } elseif ($Tipo_AcerosadicionalesX == 0.0254/ $escala) {
        $numeracionx = "(4)";
    } elseif ($Tipo_AcerosadicionalesX == 0.03174/ $escala) {
        $numeracionx = "(5)";
    } elseif ($Tipo_AcerosadicionalesX == 0.0381/ $escala) {
        $numeracionx = "(6)";
    } elseif ($Tipo_AcerosadicionalesX == 0.0508/ $escala) {
        $numeracionx = "(8)";
    }

    //$numeracionx = 3;

    // Convertidor de medidas para Tipo_AcerosadicionalesY
    if ($Tipo_AcerosadicionalesY == 0.0127/ $escala) {
        $numeracionesy = "(2)";
    } elseif ($Tipo_AcerosadicionalesY == 0.019039999999999998/ $escala) {
        $numeracionesy = "(3)";
    } elseif ($Tipo_AcerosadicionalesY == 0.0254/ $escala) {
        $numeracionesy = "(4)";
    } elseif ($Tipo_AcerosadicionalesY == 0.03174/ $escala) {
        $numeracionesy = "(5)";
    } elseif ($Tipo_AcerosadicionalesY == 0.0381/ $escala) {
        $numeracionesy = "(6)";
    } elseif ($Tipo_AcerosadicionalesY == 0.0508/ $escala) {
        $numeracionesy = "(8)";
    }

    $ancho_x = $verticesint[1][0] - $verticesint[0][0];
    $espacio_x = $ancho_x / ($cantidadX + 1); // Espacio entre los círculos en el eje X
    for ($i = 0; $i < $cantidadX; $i++) {
        $centro_x = $verticesint[0][0] + ($i + 1) * $espacio_x;
        $centro_y = $verticesint[0][1] + $radio_x; // Pegado al lado superior del cuadrado

        $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x\n20\n$centro_y\n40\n$radio_x\n";
        // Agregar la numeración en forma de texto
        $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($centro_x - 0.03) . "\n20\n" . ($centro_y - 0.08) . "\n40\n0.03\n1\n$numeracionx\n";
    }

    // Calcular y agregar círculos en el eje Y dentro del cuadrado
    $alto_y = $verticesint[3][1] - $verticesint[0][1];
    $espacio_y = $alto_y / ($cantidadY + 1); // Espacio entre los círculos en el eje Y

    for ($i = 0; $i < $cantidadY; $i++) {
        $centro_x = $verticesint[0][0] + $radio_y; // Pegado al lado izquierdo del cuadrado
        $centro_y = $verticesint[0][1] + ($i + 1) * $espacio_y;

        $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x\n20\n$centro_y\n40\n$radio_y\n";
        // Agregar la numeración en forma de texto
        $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($centro_x - 0.1) . "\n20\n" . ($centro_y - 0.01) . "\n40\n0.03\n1\n$numeracionesy\n";
    }

    // Duplicar círculos en la otra cara del rectángulo (eje X)
    for ($i = 0; $i < $cantidadX; $i++) {
        $centro_x = $verticesint[0][0] + ($i + 1) * $espacio_x;
        $centro_y = $verticesint[3][1] - $radio_x; // Pegado al lado inferior del cuadrado

        $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x\n20\n$centro_y\n40\n$radio_x\n";
        // Agregar la numeración en forma de texto
        $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($centro_x - 0.03) . "\n20\n" . ($centro_y + 0.05) . "\n40\n0.03\n1\n$numeracionx\n";
    }

    // Duplicar círculos en la otra cara del rectángulo (eje Y)
    for ($i = 0; $i < $cantidadY; $i++) {
        $centro_x = $verticesint[1][0] - $radio_y; // Pegado al lado derecho del cuadrado
        $centro_y = $verticesint[0][1] + ($i + 1) * $espacio_y;

        $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x\n20\n$centro_y\n40\n$radio_y\n";
        // Agregar la numeración en forma de texto
        $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($centro_x + 0.04) . "\n20\n" . ($centro_y - 0.02) . "\n40\n0.03\n1\n$numeracionesy\n";
    }
    // Cuatro Círculos
    $radio_esquinas = $Tipo_AceroEsquinas* $escala; // Puedes ajustar este valor según tus necesidades
    $distancia_esquinas = $radio_esquinas;
    // Convertidor de medidas para Tipo_AceroEsquinas
    if ($Tipo_AceroEsquinas == 0.0127/ $escala) {
        $numeracion = "(2)";
    } elseif ($Tipo_AceroEsquinas == 0.019039999999999998/ $escala) {
        $numeracion = "(3)";
    } elseif ($Tipo_AceroEsquinas == 0.0254/ $escala) {
        $numeracion = "(4)";
    } elseif ($Tipo_AceroEsquinas == 0.03174/ $escala) {
        $numeracion = "(5)";
    } elseif ($Tipo_AceroEsquinas == 0.0381/ $escala) {
        $numeracion = "(6)";
    } elseif ($Tipo_AceroEsquinas == 0.0508/ $escala) {
        $numeracion = "(8)";
    }

    // Esquina superior izquierda
    $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n" . ($verticesint[0][0] + $distancia_esquinas) . "\n20\n" . ($verticesint[0][1] + $distancia_esquinas) . "\n40\n$radio_esquinas\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($verticesint[0][0] + $distancia_esquinas) - 0.03 . "\n20\n" . ($verticesint[0][1] - $distancia_esquinas - 0.03) . "\n40\n0.03\n1\n$numeracion\n";

    // Esquina superior derecha
    $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n" . ($verticesint[1][0] - $distancia_esquinas) . "\n20\n" . ($verticesint[1][1] + $distancia_esquinas) . "\n40\n$radio_esquinas\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($verticesint[1][0] - $distancia_esquinas) - 0.03 . "\n20\n" . ($verticesint[1][1] - $distancia_esquinas - 0.03) . "\n40\n0.03\n1\n$numeracion\n";

    // Esquina inferior derecha
    $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n" . ($verticesint[2][0] - $distancia_esquinas) . "\n20\n" . ($verticesint[2][1] - $distancia_esquinas) . "\n40\n$radio_esquinas\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($verticesint[2][0] - $distancia_esquinas) - 0.03 . "\n20\n" . ($verticesint[2][1] + $distancia_esquinas) + 0.01 . "\n40\n0.03\n1\n$numeracion\n";

    // Esquina inferior izquierda
    $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n" . ($verticesint[3][0] + $distancia_esquinas) . "\n20\n" . ($verticesint[3][1] - $distancia_esquinas) . "\n40\n$radio_esquinas\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($verticesint[3][0] + $distancia_esquinas) - 0.03 . "\n20\n" . ($verticesint[3][1] + $distancia_esquinas) + 0.01 . "\n40\n0.03\n1\n$numeracion\n";





    ///////////////////////////////////*CUADRO DE INFORMACION
    // TABLAS EXTERNAS INTERNAS//

    $baseTabla = $base + 4;
    $alturaTabla = $altura + 2.5;

    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 3.8 - $base) . "\n20\n" . ($alturaTabla - 0.3) . "\n40\n0.1\n1\nESCALA:\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 3.8 - $base) . "\n20\n" . ($alturaTabla - 0.5) . "\n40\n0.1\n1\n 1/$escala\n";
    // Agregar el texto "titulo del cuadro" dentro del rectángulo
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 2 - $base) . "\n20\n" . ($alturaTabla - 0.4) . "\n40\n0.1\n1\nCUADRO DE COLUMNA\n";
    // Agregar el texto "nivel" dentro del rectángulo
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 3.8 - $base) . "\n20\n" . ($alturaTabla - 0.8) . "\n40\n0.1\n1\nNIVEL\n";

    // Agregar el texto "concreto" dentro del rectángulo
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 2.85 - $base) . "\n20\n" . ($alturaTabla - 0.8) . "\n40\n0.1\n1\nCONCRETO\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 3 - $base) . "\n20\n" . ($alturaTabla - 1) . "\n40\n0.1\n1\nfc'(Kg/cm2)\n";

    //Agregar el texto "titulo del grafico" dentro del rectángulo
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 0.8 - $base) . "\n20\n" . ($alturaTabla - 0.8) . "\n40\n0.1\n1\nC1\n";

    //Agregar el texto "pisos" dentro del rectángulo
    $numPisosAdicionales = 4;

    // Altura de cada piso adicional (puedes ajustar esto según sea necesario)
    $alturaPisoAdicional = 0.2; // Cambia la altura como sea necesario

    for ($i = 1; $i <= $numPisosAdicionales; $i++) {
        $contenido_dxf .= "0\nTEXT\n8\n0\n";
        $contenido_dxf .= "10\n" . ($baseTabla - 3.8 - $base) . "\n20\n" . ($alturaTabla - 1.2 - ($i * $alturaPisoAdicional)) . "\n40\n0.1\n1\n" . ($i + 0) . "° PISO\n";
    }

    //Agregar el texto "espesor de la mescla" dentro del rectángulo
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 2.7 - $base) . "\n20\n" . ($alturaTabla - 1.8) . "\n40\n0.1\n1\n210\n";

    //Agregar el texto "Esfuerzo y estiaje" dentro del rectángulo
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 3.5 - $base) . "\n20\n" . ($alturaTabla - 2 - $altura) . "\n40\n0.1\n1\nESFUERZO Y\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 3.5 - $base) . "\n20\n" . ($alturaTabla - 2.2 - $altura) . "\n40\n0.1\n1\nESTRIBAJE\n";

    $cantidadtotalX = $cantidadX * 2;
    $cantidadtotalY = $cantidadY * 2;
    //Fin

    // Convertidor de medidas para Tipo_AceroEsquinas
    if ($Tipo_AceroEsquinas == 0.0127/ $escala) {
        $Tipo_AceroEsquinasc = "1/4";
    } elseif ($Tipo_AceroEsquinas == 0.019039999999999998/ $escala) {
        $Tipo_AceroEsquinasc = "3/8";
    } elseif ($Tipo_AceroEsquinas == 0.0254/ $escala) {
        $Tipo_AceroEsquinasc = "3/8";
    } elseif ($Tipo_AceroEsquinas == 0.03174/ $escala) {
        $Tipo_AceroEsquinasc = "3/8";
    } elseif ($Tipo_AceroEsquinas == 0.0381/ $escala) {
        $Tipo_AceroEsquinasc = "3/8";
    } elseif ($Tipo_AceroEsquinas == 0.0508/ $escala) {
        $Tipo_AceroEsquinasc = "3/8";
    }

    // Convertidor de medidas para Tipo_AcerosadicionalesX
    if ($Tipo_AcerosadicionalesX == 0.0127/ $escala) {
        $Tipo_AcerosadicionalesXc = "1/4";
    } elseif ($Tipo_AcerosadicionalesX == 0.019039999999999998/ $escala) {
        $Tipo_AcerosadicionalesXc = "3/8";
    } elseif ($Tipo_AcerosadicionalesX == 0.0254/ $escala) {
        $Tipo_AcerosadicionalesXc = "1/2";
    } elseif ($Tipo_AcerosadicionalesX == 0.03174/ $escala) {
        $Tipo_AcerosadicionalesXc = "5/8";
    } elseif ($Tipo_AcerosadicionalesX == 0.0381/ $escala) {
        $Tipo_AcerosadicionalesXc = "3/4";
    } elseif ($Tipo_AcerosadicionalesX == 0.0508/ $escala) {
        $Tipo_AcerosadicionalesXc = "1";
    }

    // Convertidor de medidas para Tipo_AcerosadicionalesY
    if ($Tipo_AcerosadicionalesY == 0.0127) {
        $Tipo_AcerosadicionalesY = "1/4";
    } elseif ($Tipo_AcerosadicionalesY == 0.019039999999999998) {
        $Tipo_AcerosadicionalesY = "3/8";
    } elseif ($Tipo_AcerosadicionalesY == 0.0254) {
        $Tipo_AcerosadicionalesY = "1/2";
    } elseif ($Tipo_AcerosadicionalesY == 0.03174) {
        $Tipo_AcerosadicionalesY = "5/8";
    } elseif ($Tipo_AcerosadicionalesY == 0.0381) {
        $Tipo_AcerosadicionalesY = "3/4";
    } elseif ($Tipo_AcerosadicionalesY == 0.0508) {
        $Tipo_AcerosadicionalesY = "1";
    }
    //Fin

    $medidasFinalAlto = $altura*$escala ;
    $mediadaFinalBase = $base*$escala;

    //Agregar el texto "medidas del grafico" dentro del rectángulo
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 1.3 - $base) . "\n20\n" . ($alturaTabla - 1.7 - $altura) . "\n40\n0.1\n1\n$mediadaFinalBase cm X $medidasFinalAlto cm\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 1.8 - $base) . "\n20\n" . ($alturaTabla - 1.9 - $altura) . "\n40\n0.1\n1\n40 $Tipo_AceroEsquinasc + $cantidadtotalX 0$Tipo_AcerosadicionalesXc + $cantidadtotalY 0$Tipo_AcerosadicionalesY\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 1.7 - $base) . "\n20\n" . ($alturaTabla - 2.1 - $altura) . "\n40\n0.1\n1\n1 3/8∅: 1@0.05, 10@0.10,\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 1.3 - $base) . "\n20\n" . ($alturaTabla - 2.3 - $altura) . "\n40\n0.1\n1\nRst. @0.20 C/E\n";





    $contenido_dxf .= "0\nPOLYLINE\n8\n0\n";
    $contenido_dxf .= "66\n1\n70\n8\n";
    $contenido_dxf .= "62\n1\n"; // Establecer el color rojo

    $verticestabla = array(
        array(0, 0),
        array($baseTabla, 0),
        array($baseTabla, $alturaTabla),
        array(0, $alturaTabla),
        array(0, 0), // Cierre la polilínea volviendo al punto inicial
    );

    foreach ($verticestabla as $vertex) {
        $x = $vertex[0];
        $y = $vertex[1];
        $contenido_dxf .= "0\nVERTEX\n8\n0\n10\n$x\n20\n$y\n";
    }

    $contenido_dxf .= "0\nSEQEND\n"; // Finalizar la tercera polilínea

    // Agregar líneas de las tablas internas
    // HORIZONTALES

    $contenido_dxf .= "62\n1\n"; // Establecer el color rojo
    $verticestablaH = array(
        array(1, 1),
        array(1, $alturaTabla),
    );

    foreach ($verticestablaH as $i => $vertex) {
        $x1 = $vertex[0];
        $y1 = $vertex[1];
        $x2 = $verticestablaH[($i + 1) % count($verticestablaH)][0];
        $y2 = $verticestablaH[($i + 1) % count($verticestablaH)][1];

        $contenido_dxf .= "0\nLINE\n8\n0\n10\n$x1\n20\n$y1\n11\n$x2\n21\n$y2\n";
        $contenido_dxf .= "62\n1\n"; // Establecer el color rojo
    }


    $verticestablaH = array(
        array(2, 0),
        array(2, $alturaTabla - 0.6),
    );

    foreach ($verticestablaH as $i => $vertex) {
        $x1 = $vertex[0];
        $y1 = $vertex[1];
        $x2 = $verticestablaH[($i + 1) % count($verticestablaH)][0];
        $y2 = $verticestablaH[($i + 1) % count($verticestablaH)][1];

        $contenido_dxf .= "0\nLINE\n8\n0\n10\n$x1\n20\n$y1\n11\n$x2\n21\n$y2\n";
        $contenido_dxf .= "62\n1\n"; // Establecer el color rojo
    }


    // VERTICALES
    $verticesVE = array(
        array(0, $alturaTabla - 0.6),
        array($baseTabla, $alturaTabla - 0.6),
    );

    foreach ($verticesVE as $i => $vertex) {
        $x1 = $vertex[0];
        $y1 = $vertex[1];
        $x2 = $verticesVE[($i + 1) % count($verticesVE)][0];
        $y2 = $verticesVE[($i + 1) % count($verticesVE)][1];

        $contenido_dxf .= "0\nLINE\n8\n0\n10\n$x1\n20\n$y1\n11\n$x2\n21\n$y2\n";
        $contenido_dxf .= "62\n1\n"; // Establecer el color rojo
    }


    $verticesVE = array(
        array(0, $alturaTabla - 1.1),
        array($baseTabla, $alturaTabla - 1.1),
    );

    foreach ($verticesVE as $i => $vertex) {
        $x1 = $vertex[0];
        $y1 = $vertex[1];
        $x2 = $verticesVE[($i + 1) % count($verticesVE)][0];
        $y2 = $verticesVE[($i + 1) % count($verticesVE)][1];

        $contenido_dxf .= "0\nLINE\n8\n0\n10\n$x1\n20\n$y1\n11\n$x2\n21\n$y2\n";
    }

    $contenido_dxf .= "62\n1\n"; // Establecer el color rojo
    $verticesVE = array(
        array(0, 1),
        array($baseTabla, 1),
    );

    foreach ($verticesVE as $i => $vertex) {
        $x1 = $vertex[0];
        $y1 = $vertex[1];
        $x2 = $verticesVE[($i + 1) % count($verticesVE)][0];
        $y2 = $verticesVE[($i + 1) % count($verticesVE)][1];

        $contenido_dxf .= "0\nLINE\n8\n0\n10\n$x1\n20\n$y1\n11\n$x2\n21\n$y2\n";
    }
    $contenido_dxf .= "62\n1\n"; // Establecer el color rojo


    $contenido_dxf .= "0\nENDSEC\n0\nEOF";

    // Nombre del archivo DXF
    $archivoDXF = 'Columna-rectangular-cuadrado.dxf';

    // Ruta completa del archivo
    $rutaArchivo = __DIR__ . '/' . $archivoDXF;

    // Guardar el contenido DXF en el archivo
    file_put_contents($rutaArchivo, $contenido_dxf);
    // Ruta de AutoCAD (ajustar según la instalación)
    $rutaAutoCAD = 'C:\Program Files\Autodesk\AutoCAD 2021\acad.exe';

    // Comando para abrir AutoCAD con el archivo DXF
    $comando = 'start "" "' . $rutaAutoCAD . '" "' . $rutaArchivo . '"';

    // Ejecutar el comando
    shell_exec($comando);

    echo "AutoCAD iniciado con el archivo DXF basado en las dimensiones proporcionadas.";
    }