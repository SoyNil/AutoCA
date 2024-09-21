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

    $radio = 0.02;  // Radio de redondeo (empalme)
    // Coordenadas de los puntos principales
    $p1 = array(3.08, 1.28); // Esquina superior izquierda
    $p2 = array($base + 2.92, 1.28); // Esquina superior derecha
    $p3 = array($base + 2.92, $altura + 1.12); // Esquina inferior derecha
    $p4 = array(3.08, $altura + 1.12); // Esquina inferior izquierda

    // Dibuja las líneas entre los puntos, ajustando para el redondeo
    // Línea desde P1 a P2 (pero ajustada para el empalme en los extremos)
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n";  // Color verde
    $contenido_dxf .= "10\n" . ($p1[0] + $radio) . "\n20\n" . $p1[1] . "\n";
    $contenido_dxf .= "11\n" . ($p2[0] - $radio) . "\n21\n" . $p2[1] . "\n";

    // Línea desde P2 a P3
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n";  // Color verde
    $contenido_dxf .= "10\n" . $p2[0] . "\n20\n" . ($p2[1] + $radio) . "\n";
    $contenido_dxf .= "11\n" . $p3[0] . "\n21\n" . ($p3[1] - $radio) . "\n";

    // Línea desde P3 a P4
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n";  // Color verde
    $contenido_dxf .= "10\n" . ($p3[0] - $radio) . "\n20\n" . $p3[1] . "\n";
    $contenido_dxf .= "11\n" . ($p4[0] + $radio) . "\n21\n" . $p4[1] . "\n";

    // Línea desde P4 a P1
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n";  // Color verde
    $contenido_dxf .= "10\n" . $p4[0] . "\n20\n" . ($p4[1] - $radio) . "\n";
    $contenido_dxf .= "11\n" . $p1[0] . "\n21\n" . ($p1[1] + $radio) . "\n";

    // Añadir arcos para las esquinas redondeadas
    // Arco entre P1 y P2 (esquina superior izquierda)
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n";  // Color verde
    $contenido_dxf .= "10\n" . ($p1[0] + $radio) . "\n20\n" . ($p1[1] + $radio) . "\n";  // Centro del arco
    $contenido_dxf .= "40\n" . $radio . "\n";  // Radio del arco
    $contenido_dxf .= "50\n180\n51\n270\n"; // Ángulo inicial y final del arco (en grados)

    // Arco entre P2 y P3 (esquina superior derecha)
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n";  // Color verde
    $contenido_dxf .= "10\n" . ($p2[0] - $radio) . "\n20\n" . ($p2[1] + $radio) . "\n";  // Centro del arco
    $contenido_dxf .= "40\n" . $radio . "\n";
    $contenido_dxf .= "50\n270\n51\n0\n"; // Ángulo inicial y final

    // Arco entre P3 y P4 (esquina inferior derecha)
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n";  // Color verde
    $contenido_dxf .= "10\n" . ($p3[0] - $radio) . "\n20\n" . ($p3[1] - $radio) . "\n";
    $contenido_dxf .= "40\n" . $radio . "\n";
    $contenido_dxf .= "50\n0\n51\n90\n"; // Ángulo inicial y final

    // Arco entre P4 y P1 (esquina inferior izquierda)
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n";  // Color verde
    $contenido_dxf .= "10\n" . ($p4[0] + $radio) . "\n20\n" . ($p4[1] - $radio) . "\n";
    $contenido_dxf .= "40\n" . $radio . "\n";
    $contenido_dxf .= "50\n90\n51\n180\n"; // Ángulo inicial y final

    $radio = 0.02;  // Radio de redondeo (empalme)
    // Coordenadas de los puntos principales
    $p1 = array(3.10, 1.30); // Esquina superior izquierda
    $p2 = array($base + 2.90, 1.30); // Esquina superior derecha
    $p3 = array($base + 2.90, $altura + 1.10); // Esquina inferior derecha
    $p4 = array(3.10, $altura + 1.10); // Esquina inferior izquierda

    // Dibuja las líneas entre los puntos, ajustando para el redondeo
    // Línea desde P1 a P2 (pero ajustada para el empalme en los extremos)
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n";  // Color verde
    $contenido_dxf .= "10\n" . ($p1[0] + $radio) . "\n20\n" . $p1[1] . "\n";
    $contenido_dxf .= "11\n" . ($p2[0] - $radio) . "\n21\n" . $p2[1] . "\n";

    // Línea desde P2 a P3
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n";  // Color verde
    $contenido_dxf .= "10\n" . $p2[0] . "\n20\n" . ($p2[1] + $radio) . "\n";
    $contenido_dxf .= "11\n" . $p3[0] . "\n21\n" . ($p3[1] - $radio) . "\n";

    // Línea desde P3 a P4
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n";  // Color verde
    $contenido_dxf .= "10\n" . ($p3[0] - $radio) . "\n20\n" . $p3[1] . "\n";
    $contenido_dxf .= "11\n" . ($p4[0] + $radio) . "\n21\n" . $p4[1] . "\n";

    // Línea desde P4 a P1
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n";  // Color verde
    $contenido_dxf .= "10\n" . $p4[0] . "\n20\n" . ($p4[1] - $radio) . "\n";
    $contenido_dxf .= "11\n" . $p1[0] . "\n21\n" . ($p1[1] + $radio) . "\n";

    // Añadir arcos para las esquinas redondeadas
    // Arco entre P1 y P2 (esquina superior izquierda)
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n";  // Color verde
    $contenido_dxf .= "10\n" . ($p1[0] + $radio) . "\n20\n" . ($p1[1] + $radio) . "\n";  // Centro del arco
    $contenido_dxf .= "40\n" . $radio . "\n";  // Radio del arco
    $contenido_dxf .= "50\n180\n51\n270\n"; // Ángulo inicial y final del arco (en grados)

    // Arco entre P2 y P3 (esquina superior derecha)
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n";  // Color verde
    $contenido_dxf .= "10\n" . ($p2[0] - $radio) . "\n20\n" . ($p2[1] + $radio) . "\n";  // Centro del arco
    $contenido_dxf .= "40\n" . $radio . "\n";
    $contenido_dxf .= "50\n270\n51\n0\n"; // Ángulo inicial y final

    // Arco entre P3 y P4 (esquina inferior derecha)
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n";  // Color verde
    $contenido_dxf .= "10\n" . ($p3[0] - $radio) . "\n20\n" . ($p3[1] - $radio) . "\n";
    $contenido_dxf .= "40\n" . $radio . "\n";
    $contenido_dxf .= "50\n0\n51\n90\n"; // Ángulo inicial y final

    // Arco entre P4 y P1 (esquina inferior izquierda)
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n";  // Color verde
    $contenido_dxf .= "10\n" . ($p4[0] + $radio) . "\n20\n" . ($p4[1] - $radio) . "\n";
    $contenido_dxf .= "40\n" . $radio . "\n";
    $contenido_dxf .= "50\n90\n51\n180\n"; // Ángulo inicial y final

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
    if ($Tipo_AcerosadicionalesY == 0.0127/ $escala) {
        $Tipo_AcerosadicionalesYc = "1/4";
    } elseif ($Tipo_AcerosadicionalesY == 0.019039999999999998/ $escala) {
        $Tipo_AcerosadicionalesYc = "3/8";
    } elseif ($Tipo_AcerosadicionalesY == 0.0254/ $escala) {
        $Tipo_AcerosadicionalesYc = "1/2";
    } elseif ($Tipo_AcerosadicionalesY == 0.03174/ $escala) {
        $Tipo_AcerosadicionalesYc = "5/8";
    } elseif ($Tipo_AcerosadicionalesY == 0.0381/ $escala) {
        $Tipo_AcerosadicionalesYc = "3/4";
    } elseif ($Tipo_AcerosadicionalesY == 0.0508/ $escala) {
        $Tipo_AcerosadicionalesYc = "1";
    }
    $cantidadtotalX = $cantidadX * 2;
    $cantidadtotalY = $cantidadY * 2;

    $ancho_x = $verticesint[1][0] - $verticesint[0][0];
    $espacio_x = $ancho_x / ($cantidadX + 1); // Espacio entre los círculos en el eje X
    // Definir el punto central en el eje X para el punto de convergencia en la parte inferior
    $punto_convergente_x = ($verticesint[0][0] + $verticesint[1][0]) / 2; // Punto medio en X
    $punto_convergente_y = $verticesint[3][1]+0.15; // Coordenada Y en la parte inferior del cuadrado
    
    for ($i = 0; $i < $cantidadX; $i++) {
        $centro_x = $verticesint[0][0] + ($i + 1) * $espacio_x;
        $centro_y = $verticesint[0][1] + $radio_x; // Pegado al lado superior del cuadrado
    
        // Crear el círculo
        $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x\n20\n$centro_y\n40\n$radio_x\n";
        
        // Crear la línea que conecta el centro del círculo con el punto superior
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n1\n10\n$centro_x\n20\n$centro_y\n11\n$punto_convergente_x\n21\n$punto_convergente_y\n";
    }
    
    $punto_convergente_xText = $punto_convergente_x + 0.05;
    // Añadir un texto en el punto de convergencia
    $contenido_dxf .= "0\nTEXT\n8\n0\n62\n1\n10\n$punto_convergente_xText\n20\n$punto_convergente_y\n40\n0.05\n1\n$cantidadtotalX $Tipo_AcerosadicionalesXc\"\n";
    
    // Duplicar círculos en la otra cara del rectángulo (eje X)
    for ($i = 0; $i < $cantidadX; $i++) {
        $centro_x = $verticesint[0][0] + ($i + 1) * $espacio_x;
        $centro_y = $verticesint[3][1] - $radio_x; // Pegado al lado inferior del cuadrado
    
        // Crear el círculo
        $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x\n20\n$centro_y\n40\n$radio_x\n";
        
        // Crear la línea que conecta el centro del círculo con el punto superior
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n1\n10\n$centro_x\n20\n$centro_y\n11\n$punto_convergente_x\n21\n$punto_convergente_y\n";
    }

    // Calcular y agregar círculos en el eje Y dentro del cuadrado
    $alto_y = $verticesint[3][1] - $verticesint[0][1];
    $espacio_y = $alto_y / ($cantidadY + 1); // Espacio entre los círculos en el eje Y

    // Definir el punto central en el lado izquierdo y derecho donde convergen las líneas
    $punto_convergente_izquierdo_x = $verticesint[0][0] - 0.6; // Punto de convergencia en la izquierda
    $punto_convergente_izquierdo_y = ($verticesint[0][1] + $verticesint[3][1]) / 2; // Punto medio en Y

    for ($i = 0; $i < $cantidadY; $i++) {
        $centro_x = $verticesint[0][0] + $radio_y; // Pegado al lado izquierdo del cuadrado
        $centro_y = $verticesint[0][1] + ($i + 1) * $espacio_y;

        // Crear el círculo
        $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x\n20\n$centro_y\n40\n$radio_y\n";

        // Crear la línea que conecta el centro del círculo con el punto izquierdo de convergencia
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n2\n10\n$centro_x\n20\n$centro_y\n11\n$punto_convergente_izquierdo_x\n21\n$punto_convergente_izquierdo_y\n";
    }

    // Duplicar círculos en la otra cara del rectángulo (eje Y)
    for ($i = 0; $i < $cantidadY; $i++) {
        $centro_x = $verticesint[1][0] - $radio_y; // Pegado al lado derecho del cuadrado
        $centro_y = $verticesint[0][1] + ($i + 1) * $espacio_y;

        // Crear el círculo
        $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x\n20\n$centro_y\n40\n$radio_y\n";

        $contenido_dxf .= "0\nLINE\n8\n0\n62\n2\n10\n$centro_x\n20\n$centro_y\n11\n$punto_convergente_izquierdo_x\n21\n$punto_convergente_izquierdo_y\n";
    }
    $punto_convergente_izquierdo_xText=$punto_convergente_izquierdo_x-0.25;
    // Añadir un texto en el punto de convergencia derecho
    $contenido_dxf .= "0\nTEXT\n8\n0\n62\n2\n10\n$punto_convergente_izquierdo_xText\n20\n$punto_convergente_izquierdo_y\n40\n0.05\n1\n$cantidadtotalY $Tipo_AcerosadicionalesYc\"\n";

    // Cuatro Círculos
    $radio_esquinas = $Tipo_AceroEsquinas * $escala;
    $distancia_esquinas = $radio_esquinas;

    // Convertidor de medidas para Tipo_AceroEsquinas
    if ($Tipo_AceroEsquinas == 0.0127 / $escala) {
        $numeracion = "(2)";
    } elseif ($Tipo_AceroEsquinas == 0.019039999999999998 / $escala) {
        $numeracion = "(3)";
    } elseif ($Tipo_AceroEsquinas == 0.0254 / $escala) {
        $numeracion = "(4)";
    } elseif ($Tipo_AceroEsquinas == 0.03174 / $escala) {
        $numeracion = "(5)";
    } elseif ($Tipo_AceroEsquinas == 0.0381 / $escala) {
        $numeracion = "(6)";
    } elseif ($Tipo_AceroEsquinas == 0.0508 / $escala) {
        $numeracion = "(8)";
    }

    // Definir el punto central de convergencia (centro del rectángulo)
    $punto_convergente_x = ($verticesint[0][0] + $verticesint[1][0]) -2.700;
    $punto_convergente_y = ($verticesint[0][1] + $verticesint[3][1]) / 2;

    // Esquina superior izquierda
    $centro_x = $verticesint[0][0] + $distancia_esquinas;
    $centro_y = $verticesint[0][1] + $distancia_esquinas;

    $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x\n20\n$centro_y\n40\n$radio_esquinas\n";

    $contenido_dxf .= "0\nLINE\n8\n0\n62\n6\n10\n$centro_x\n20\n$centro_y\n11\n$punto_convergente_x\n21\n$punto_convergente_y\n";

    // Esquina superior derecha
    $centro_x = $verticesint[1][0] - $distancia_esquinas;
    $centro_y = $verticesint[1][1] + $distancia_esquinas;

    $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x\n20\n$centro_y\n40\n$radio_esquinas\n";

    $contenido_dxf .= "0\nLINE\n8\n0\n62\n6\n10\n$centro_x\n20\n$centro_y\n11\n$punto_convergente_x\n21\n$punto_convergente_y\n";

    // Esquina inferior derecha
    $centro_x = $verticesint[2][0] - $distancia_esquinas;
    $centro_y = $verticesint[2][1] - $distancia_esquinas;

    $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x\n20\n$centro_y\n40\n$radio_esquinas\n";

    $contenido_dxf .= "0\nLINE\n8\n0\n62\n6\n10\n$centro_x\n20\n$centro_y\n11\n$punto_convergente_x\n21\n$punto_convergente_y\n";

    // Esquina inferior izquierda
    $centro_x = $verticesint[3][0] + $distancia_esquinas;
    $centro_y = $verticesint[3][1] - $distancia_esquinas;

    $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x\n20\n$centro_y\n40\n$radio_esquinas\n";

    // Crear la línea que conecta el círculo con el punto central
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n6\n10\n$centro_x\n20\n$centro_y\n11\n$punto_convergente_x\n21\n$punto_convergente_y\n";

    // Añadir un texto en el punto de convergencia central
    $contenido_dxf .= "0\nTEXT\n8\n0\n62\n6\n10\n$punto_convergente_x\n20\n$punto_convergente_y\n40\n0.05\n1\n4 \"$Tipo_AceroEsquinasc\"\n";





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

    
    //Fin
    $medidasFinalAlto = $altura*$escala ;
    $mediadaFinalBase = $base*$escala;
    //Agregar el texto "medidas del grafico" dentro del rectángulo
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 1.3 - $base) . "\n20\n" . ($alturaTabla - 1.7 - $altura) . "\n40\n0.1\n1\n$mediadaFinalBase cm X $medidasFinalAlto cm\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 1.8 - $base) . "\n20\n" . ($alturaTabla - 1.9 - $altura) . "\n40\n0.1\n1\n4 $Tipo_AceroEsquinasc + $cantidadtotalX $Tipo_AcerosadicionalesXc + $cantidadtotalY $Tipo_AcerosadicionalesYc\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 1.7 - $base) . "\n20\n" . ($alturaTabla - 2.1 - $altura) . "\n40\n0.1\n1\n1 3/8∅: 1@0.05, 10@0.10,\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 1.3 - $base) . "\n20\n" . ($alturaTabla - 2.3 - $altura) . "\n40\n0.1\n1\nRst. @0.20 C/E\n";


// Definir el espacio adicional hacia abajo en función de la altura del cuadrado
/*$espacioAdicional = $altura+0.3; // Ajusta este valor según tus necesidades

// Nuevas dimensiones para el contorno extendido
$alturaTablaExtendida = $alturaTabla + $espacioAdicional;

// Dibujar el contorno original
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
$contenido_dxf .= "0\nSEQEND\n"; // Finalizar la polilínea original

// Dibujar el nuevo contorno extendido hacia abajo
$contenido_dxf .= "0\nPOLYLINE\n8\n0\n";
$contenido_dxf .= "66\n1\n70\n8\n";
$contenido_dxf .= "62\n1\n"; // Establecer el color rojo
$verticestablaExtendida = array(
    array(0, 0),
    array($baseTabla, 0),
    array($baseTabla, - $espacioAdicional), // Extender hacia abajo
    array(0, - $espacioAdicional), // Extender hacia abajo
    array(0, 0), // Cierre la polilínea volviendo al punto inicial
);
foreach ($verticestablaExtendida as $vertex) {
    $x = $vertex[0];
    $y = $vertex[1];
    $contenido_dxf .= "0\nVERTEX\n8\n0\n10\n$x\n20\n$y\n";
}
$contenido_dxf .= "0\nSEQEND\n"; // Finalizar la polilínea extendida

// Agregar una línea vertical de división
$contenido_dxf .= "0\nLINE\n8\n0\n";
$contenido_dxf .= "62\n1\n"; // Establecer el color rojo
$contenido_dxf .= "10\n" . (2) . "\n20\n0\n11\n" . (2) . "\n21\n-" . ( $espacioAdicional) . "\n"; // Línea vertical en el centro del nuevo rectángulo

$radio = 0.02;  // Radio de redondeo (empalme)

// Nuevas dimensiones
$nuevaBase = $base;
$nuevaAltura = $altura;

// Coordenadas de los puntos principales
$p1 = array(3.08, 0.04-$nuevaAltura); // Esquina superior/inf izquierda
$p2 = array($nuevaBase+2.92, 0.04-$nuevaAltura); // Esquina superior/inf derecha
$p3 = array($nuevaBase+2.92, -0.12); // Esquina inferior/sup derecha
$p4 = array(3.08, -0.12); // Esquina inferior/sup izquierda

// Dibuja las líneas entre los puntos, ajustando para el redondeo
// Línea desde P1 a P2
$contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n";  // Color verde
$contenido_dxf .= "10\n" . ($p1[0] + $radio) . "\n20\n" . $p1[1] . "\n";
$contenido_dxf .= "11\n" . ($p2[0] - $radio) . "\n21\n" . $p2[1] . "\n";

// Línea desde P2 a P3
$contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n";  // Color verde
$contenido_dxf .= "10\n" . $p2[0] . "\n20\n" . ($p2[1] + $radio) . "\n";
$contenido_dxf .= "11\n" . $p3[0] . "\n21\n" . ($p3[1] - $radio) . "\n";

// Línea desde P3 a P4
$contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n";  // Color verde
$contenido_dxf .= "10\n" . ($p3[0] - $radio) . "\n20\n" . $p3[1] . "\n";
$contenido_dxf .= "11\n" . ($p4[0] + $radio) . "\n21\n" . $p4[1] . "\n";

// Línea desde P4 a P1
$contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n";  // Color verde
$contenido_dxf .= "10\n" . $p4[0] . "\n20\n" . ($p4[1] - $radio) . "\n";
$contenido_dxf .= "11\n" . $p1[0] . "\n21\n" . ($p1[1] + $radio) . "\n";

// Añadir arcos para las esquinas redondeadas
// Arco entre P1 y P2 (esquina superior izquierda)
$contenido_dxf .= "0\nARC\n8\n0\n62\n3\n";  // Color verde
$contenido_dxf .= "10\n" . ($p1[0] + $radio) . "\n20\n" . ($p1[1] + $radio) . "\n";  // Centro del arco
$contenido_dxf .= "40\n" . $radio . "\n";  // Radio del arco
$contenido_dxf .= "50\n180\n51\n270\n"; // Ángulo inicial y final del arco (en grados)

// Arco entre P2 y P3 (esquina superior derecha)
$contenido_dxf .= "0\nARC\n8\n0\n62\n3\n";  // Color verde
$contenido_dxf .= "10\n" . ($p2[0] - $radio) . "\n20\n" . ($p2[1] + $radio) . "\n";  // Centro del arco
$contenido_dxf .= "40\n" . $radio . "\n";
$contenido_dxf .= "50\n270\n51\n0\n"; // Ángulo inicial y final

// Arco entre P3 y P4 (esquina inferior derecha)
$contenido_dxf .= "0\nARC\n8\n0\n62\n3\n";  // Color verde
$contenido_dxf .= "10\n" . ($p3[0] - $radio) . "\n20\n" . ($p3[1] - $radio) . "\n";
$contenido_dxf .= "40\n" . $radio . "\n";
$contenido_dxf .= "50\n0\n51\n90\n"; // Ángulo inicial y final

// Arco entre P4 y P1 (esquina inferior izquierda)
$contenido_dxf .= "0\nARC\n8\n0\n62\n3\n";  // Color verde
$contenido_dxf .= "10\n" . ($p4[0] + $radio) . "\n20\n" . ($p4[1] - $radio) . "\n";
$contenido_dxf .= "40\n" . $radio . "\n";
$contenido_dxf .= "50\n90\n51\n180\n"; // Ángulo inicial y final

$radio = 0.02;  // Radio de redondeo (empalme)

// Nuevas dimensiones
$nuevaBase = $base;
$nuevaAltura = $altura;

// Coordenadas de los puntos principales
$p1 = array(3.10, 0.06-$nuevaAltura); // Esquina superior/inf izquierda
$p2 = array($nuevaBase+2.90, 0.06-$nuevaAltura); // Esquina superior/inf derecha
$p3 = array($nuevaBase+2.90, -0.14); // Esquina inferior/sup derecha
$p4 = array(3.10, -0.14); // Esquina inferior/sup izquierda

// Dibuja las líneas entre los puntos, ajustando para el redondeo
// Línea desde P1 a P2
$contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n";  // Color verde
$contenido_dxf .= "10\n" . ($p1[0] + $radio) . "\n20\n" . $p1[1] . "\n";
$contenido_dxf .= "11\n" . ($p2[0] - $radio) . "\n21\n" . $p2[1] . "\n";

// Línea desde P2 a P3
$contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n";  // Color verde
$contenido_dxf .= "10\n" . $p2[0] . "\n20\n" . ($p2[1] + $radio) . "\n";
$contenido_dxf .= "11\n" . $p3[0] . "\n21\n" . ($p3[1] - $radio) . "\n";

// Línea desde P3 a P4
$contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n";  // Color verde
$contenido_dxf .= "10\n" . ($p3[0] - $radio) . "\n20\n" . $p3[1] . "\n";
$contenido_dxf .= "11\n" . ($p4[0] + $radio) . "\n21\n" . $p4[1] . "\n";

// Línea desde P4 a P1
$contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n";  // Color verde
$contenido_dxf .= "10\n" . $p4[0] . "\n20\n" . ($p4[1] - $radio) . "\n";
$contenido_dxf .= "11\n" . $p1[0] . "\n21\n" . ($p1[1] + $radio) . "\n";

// Añadir arcos para las esquinas redondeadas
// Arco entre P1 y P2 (esquina superior izquierda)
$contenido_dxf .= "0\nARC\n8\n0\n62\n3\n";  // Color verde
$contenido_dxf .= "10\n" . ($p1[0] + $radio) . "\n20\n" . ($p1[1] + $radio) . "\n";  // Centro del arco
$contenido_dxf .= "40\n" . $radio . "\n";  // Radio del arco
$contenido_dxf .= "50\n180\n51\n270\n"; // Ángulo inicial y final del arco (en grados)

// Arco entre P2 y P3 (esquina superior derecha)
$contenido_dxf .= "0\nARC\n8\n0\n62\n3\n";  // Color verde
$contenido_dxf .= "10\n" . ($p2[0] - $radio) . "\n20\n" . ($p2[1] + $radio) . "\n";  // Centro del arco
$contenido_dxf .= "40\n" . $radio . "\n";
$contenido_dxf .= "50\n270\n51\n0\n"; // Ángulo inicial y final

// Arco entre P3 y P4 (esquina inferior derecha)
$contenido_dxf .= "0\nARC\n8\n0\n62\n3\n";  // Color verde
$contenido_dxf .= "10\n" . ($p3[0] - $radio) . "\n20\n" . ($p3[1] - $radio) . "\n";
$contenido_dxf .= "40\n" . $radio . "\n";
$contenido_dxf .= "50\n0\n51\n90\n"; // Ángulo inicial y final

// Arco entre P4 y P1 (esquina inferior izquierda)
$contenido_dxf .= "0\nARC\n8\n0\n62\n3\n";  // Color verde
$contenido_dxf .= "10\n" . ($p4[0] + $radio) . "\n20\n" . ($p4[1] - $radio) . "\n";
$contenido_dxf .= "40\n" . $radio . "\n";
$contenido_dxf .= "50\n90\n51\n180\n"; // Ángulo inicial y final

//GANCHO ESTRIBO//}

// Definir los vértices del gancho de estribo externo
$vertices_gancho_izquierdo = array(
    array($p1[0] + 0.2, $p1[1] + 1.08),
    array($p3[0] - 0.16, $p3[1] + 1.12),
);

$vertices_gancho_derecho = array(
    array($p2[0] + 0.2, $p2[1] + 1.08),
    array($p3[0] - 0.16, $p3[1] + 1.12),
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

// Definir los vértices del gancho de estribo para el cuadrado interno
$vertices_gancho_izquierdo = array(
    array(3.10 + 0.05, 0.06 - $nuevaAltura + 0.05),
    array(3.10 + 0.15, 0.06 - $nuevaAltura + 0.06),
);

$vertices_gancho_derecho = array(
    array($nuevaBase + 2.90 - 0.05, 0.06 - $nuevaAltura + 0.05),
    array($nuevaBase + 2.90 - 0.15, 0.06 - $nuevaAltura + 0.06),
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
}*/


$contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . (0.5) . "\n20\n" . (-$espacioAdicional / 2) . "\n40\n0.1\n1\nDESPIECE DE\n";
$contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . (0.5) . "\n20\n" . ((-$espacioAdicional / 2) - 0.2) . "\n40\n0.1\n1\nESTRIBOS\n";

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
?>