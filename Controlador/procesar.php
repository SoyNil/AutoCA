<?php
ini_set('memory_limit', '2G');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener la escala del formulario
    $escala = (float)$_POST["escala"] ?? 50;
    // Obtener las dimensiones del formulario
    $cantidadColumnas = (int)$_POST["cantidadColumnas"];
    $baseArray = [];
    $alturaArray = [];
    $tipoAceroEsquinasArray = [];
    $cantidadXArray = [];
    $tipoAcerosAdicionalesXArray = [];
    $cantidadYArray = [];
    $tipoAcerosAdicionalesYArray = [];
    for ($i = 0; $i < $cantidadColumnas; $i++) {
        $base = (float)$_POST["base"][$i] / $escala;
        $altura = (float)$_POST["altura"][$i] / $escala;
        $tipoAceroEsquinas = (float)$_POST["tiposaceroEsquinas"][$i] / $escala;
        $cantidadX = (int)$_POST["acerosX"][$i];
        $tipoAcerosAdicionalesX = (float)$_POST["tipoaceroX"][$i] / $escala;
        $cantidadY = (int)$_POST["acerosY"][$i];
        $tipoAcerosAdicionalesY = (float)$_POST["tipoaceroY"][$i] / $escala;
        $baseArray[] = $base;
        $alturaArray[] = $altura;
        $tipoAceroEsquinasArray[] = $tipoAceroEsquinas;
        $cantidadXArray[] = $cantidadX;
        $tipoAcerosAdicionalesXArray[] = $tipoAcerosAdicionalesX;
        $cantidadYArray[] = $cantidadY;
        $tipoAcerosAdicionalesYArray[] = $tipoAcerosAdicionalesY;
    }
    echo "<pre>";
    print_r($baseArray);
    print_r($alturaArray);
    print_r($tipoAceroEsquinasArray);
    print_r($cantidadXArray);
    print_r($tipoAcerosAdicionalesXArray);
    print_r($cantidadYArray);
    print_r($tipoAcerosAdicionalesYArray);
    echo "</pre>";
    $numPisosAdicionales = (int)$_POST["cantidadPisos"];
    $base = $baseArray[0];
    $altura = $alturaArray[0];
    $base2 = $baseArray[0];
    $altura2 = $alturaArray[0];
    $altura4 = $alturaArray[0];
    $contenido_dxf = "";
    $contenido_dxf .= "0\nSECTION\n2\nENTITIES\n";
    $contenido_dxf .= "0\nPOLYLINE\n8\n0\n66\n1\n70\n8\n62\n5\n";
    $vertices = array(
        array(3, 1.2),
        array($base + 3, 1.2),
        array($base + 3, $altura + 1.2),
        array(3, $altura + 1.2),
        array(3, 1.2),
    );
    foreach ($vertices as $vertex) {
        $x = $vertex[0];
        $y = $vertex[1];
        $contenido_dxf .= "0\nVERTEX\n8\n0\n10\n$x\n20\n$y\n";
    }
    $contenido_dxf .= "0\nSEQEND\n";
    //Cuadrado Exterior
    $yOffset = -0.05;
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n8\n10\n3\n20\n" . (1.2 + $yOffset) . "\n11\n" . ($base + 3) . "\n21\n" . (1.2 + $yOffset) . "\n";
    $texBasex = ($base * $escala);
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . (($base / 2) + 3) . "\n20\n" . ((1.2 + $yOffset - 0.07)) . "\n40\n0.05\n1\n$texBasex\n";
    $xOffset = ($base + 3) + 0.05;
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n8\n10\n$xOffset\n20\n1.2\n11\n$xOffset\n21\n" . ($altura + 1.2) . "\n";
    $texBasey = ($altura * $escala);
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($xOffset + 0.02) . "\n20\n" . (($altura / 2) + 1.2) . "\n40\n0.05\n1\n$texBasey\n";
    //CuadradoInteriorGrande
    $radio = 0.02;
    $p1 = array(3.08, 1.28);
    $p2 = array($base + 2.92, 1.28);
    $p3 = array($base + 2.92, $altura + 1.12);
    $p4 = array(3.08, $altura + 1.12);
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . ($p1[0] + $radio) . "\n20\n$p1[1]\n11\n" . ($p2[0] - $radio) . "\n21\n$p2[1]\n";
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . $p2[0] . "\n20\n" . ($p2[1] + $radio) . "\n11\n" . $p3[0] . "\n21\n" . ($p3[1] - $radio) . "\n";
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . ($p3[0] - $radio) . "\n20\n" . $p3[1] . "\n11\n" . ($p4[0] + $radio) . "\n21\n" . $p4[1] . "\n";
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . $p4[0] . "\n20\n" . ($p4[1] - $radio) . "\n11\n" . $p1[0] . "\n21\n" . ($p1[1] + $radio) . "\n";
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p1[0] + $radio) . "\n20\n" . ($p1[1] + $radio) . "\n40\n$radio\n50\n180\n51\n270\n";
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p2[0] - $radio) . "\n20\n" . ($p2[1] + $radio) . "\n40\n$radio\n50\n270\n51\n0\n";
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p3[0] - $radio) . "\n20\n" . ($p3[1] - $radio) . "\n40\n$radio\n50\n0\n51\n90\n";
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p4[0] + $radio) . "\n20\n" . ($p4[1] - $radio) . "\n40\n$radio\n50\n90\n51\n180\n";
    //CuadradoInteriorPequeño
    $radio = 0.02;
    $p1 = array(3.10, 1.30);
    $p2 = array($base + 2.90, 1.30);
    $p3 = array($base + 2.90, $altura + 1.10);
    $p4 = array(3.10, $altura + 1.10);
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . ($p1[0] + $radio) . "\n20\n$p1[1]\n11\n" . ($p2[0] - $radio) . "\n21\n$p2[1]\n";
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n$p2[0]\n20\n" . ($p2[1] + $radio) . "\n11\n$p3[0]\n21\n" . ($p3[1] - $radio) . "\n";
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . ($p3[0] - $radio) . "\n20\n$p3[1]\n11\n" . ($p4[0] + $radio) . "\n21\n$p4[1]\n";
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n$p4[0]\n20\n" . ($p4[1] - $radio) . "\n11\n$p1[0]\n21\n" . ($p1[1] + $radio) . "\n";
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p1[0] + $radio) . "\n20\n" . ($p1[1] + $radio) . "\n40\n$radio\n50\n180\n51\n270\n";
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p2[0] - $radio) . "\n20\n" . ($p2[1] + $radio) . "\n40\n$radio\n50\n270\n51\n0\n";
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p3[0] - $radio) . "\n20\n" . ($p3[1] - $radio) . "\n40\n$radio\n50\n0\n51\n90\n";
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p4[0] + $radio) . "\n20\n" . ($p4[1] - $radio) . "\n40\n$radio\n50\n90\n51\n180\n";
    //Aceros
    $verticesint = array(
        array(3.10, 1.30),
        array($base + 2.90, 1.30),
        array($base + 2.90, $altura + 1.10),
        array(3.10, $altura + 1.1),
        array(3.10, 1.30),
    );
    $vertices_gancho_izquierdo = [
        [($base - 0.04 - 0.424264686) + 0.2 + 2.96, ($altura - 0.424264686 + 0.2) + 1.08],
        [$base + 2.86, $altura + 1.12]
    ];
    $vertices_gancho_derecho = [
        [($base + 0.04 - 0.424264686) + 0.16 + 3.01, ($altura - 0.424264686 + 0.16) + 1.09],
        [$base + 2.92, $altura + 1.08]
    ];

    $dibujar_gancho = function ($vertices) use (&$contenido_dxf) {
        foreach ($vertices as $i => $vertex) {
            $x1 = $vertex[0];
            $y1 = $vertex[1];
            $x2 = $vertices[($i + 1) % count($vertices)][0];
            $y2 = $vertices[($i + 1) % count($vertices)][1];
            $contenido_dxf .= "0\nLINE\n8\n0\n10\n$x1\n20\n$y1\n11\n$x2\n21\n$y2\n62\n3\n";
        }
    };
    $dibujar_gancho($vertices_gancho_izquierdo);
    $dibujar_gancho($vertices_gancho_derecho);
    $vertices_gancho_izquierdo = [
        [($base - 0.04 - 0.424264686) + 0.2 + 2.98, ($altura - 0.424264686 + 0.2) + 1.08],
        [$base + 2.86, $altura + 1.1]
    ];
    $vertices_gancho_derecho = [
        [($base + 0.04 - 0.424264686) + 0.16 + 3, ($altura - 0.424264686 + 0.16) + 1.1],
        [$base + 2.9, $altura + 1.06]
    ];

    $dibujar_gancho($vertices_gancho_izquierdo);
    $dibujar_gancho($vertices_gancho_derecho);

    $Tipo_AcerosadicionalesX = $tipoAcerosAdicionalesXArray[0]; // 10
    $Tipo_AcerosadicionalesY = $tipoAcerosAdicionalesYArray[0]; // 5
    $Tipo_AceroEsquinas = $tipoAceroEsquinasArray[0];
    $cantidadX = $cantidadXArray[0];
    $cantidadY = $cantidadYArray[0];
    $radio_x = $Tipo_AcerosadicionalesX * $escala;
    $radio_y = $Tipo_AcerosadicionalesY * $escala;
    // Convertidor de medidas para Tipo_AceroEsquinas
    if ($Tipo_AceroEsquinas == 0.0127 / $escala) {
        $Tipo_AceroEsquinasc = "1/4";
    } elseif ($Tipo_AceroEsquinas == 0.019039999999999998 / $escala) {
        $Tipo_AceroEsquinasc = "3/8";
    } elseif ($Tipo_AceroEsquinas == 0.0254 / $escala) {
        $Tipo_AceroEsquinasc = "3/8";
    } elseif ($Tipo_AceroEsquinas == 0.03174 / $escala) {
        $Tipo_AceroEsquinasc = "3/8";
    } elseif ($Tipo_AceroEsquinas == 0.0381 / $escala) {
        $Tipo_AceroEsquinasc = "3/8";
    } elseif ($Tipo_AceroEsquinas == 0.0508 / $escala) {
        $Tipo_AceroEsquinasc = "3/8";
    }
    // Convertidor de medidas para Tipo_AcerosadicionalesX
    if ($Tipo_AcerosadicionalesX == 0.0127 / $escala) {
        $Tipo_AcerosadicionalesXc = "1/4";
    } elseif ($Tipo_AcerosadicionalesX == 0.019039999999999998 / $escala) {
        $Tipo_AcerosadicionalesXc = "3/8";
    } elseif ($Tipo_AcerosadicionalesX == 0.0254 / $escala) {
        $Tipo_AcerosadicionalesXc = "1/2";
    } elseif ($Tipo_AcerosadicionalesX == 0.03174 / $escala) {
        $Tipo_AcerosadicionalesXc = "5/8";
    } elseif ($Tipo_AcerosadicionalesX == 0.0381 / $escala) {
        $Tipo_AcerosadicionalesXc = "3/4";
    } elseif ($Tipo_AcerosadicionalesX == 0.0508 / $escala) {
        $Tipo_AcerosadicionalesXc = "1";
    }
    // Convertidor de medidas para Tipo_AcerosadicionalesY
    if ($Tipo_AcerosadicionalesY == 0.0127 / $escala) {
        $Tipo_AcerosadicionalesYc = "1/4";
    } elseif ($Tipo_AcerosadicionalesY == 0.019039999999999998 / $escala) {
        $Tipo_AcerosadicionalesYc = "3/8";
    } elseif ($Tipo_AcerosadicionalesY == 0.0254 / $escala) {
        $Tipo_AcerosadicionalesYc = "1/2";
    } elseif ($Tipo_AcerosadicionalesY == 0.03174 / $escala) {
        $Tipo_AcerosadicionalesYc = "5/8";
    } elseif ($Tipo_AcerosadicionalesY == 0.0381 / $escala) {
        $Tipo_AcerosadicionalesYc = "3/4";
    } elseif ($Tipo_AcerosadicionalesY == 0.0508 / $escala) {
        $Tipo_AcerosadicionalesYc = "1";
    }
    $cantidadtotalX = $cantidadX * 2;
    $cantidadtotalY = $cantidadY * 2;
    // Calcular y agregar círculos en el eje X dentro del cuadrado
    $ancho_x = $verticesint[1][0] - $verticesint[0][0];
    $espacio_x = $ancho_x / ($cantidadX + 1);
    // Definir el punto central en el eje X para el punto de convergencia en la parte inferior
    $punto_convergente_x = ($verticesint[0][0] + $verticesint[1][0]) / 2; // Punto medio en X
    $punto_convergente_y = $verticesint[3][1] + 0.15; // Coordenada Y en la parte inferior del cuadrado
    for ($i = 0; $i < $cantidadX; $i++) {
        $centro_x = $verticesint[0][0] + ($i + 1) * $espacio_x;
        $centro_y = $verticesint[0][1] + $radio_x; // Pegado al lado superior del cuadrado
        $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x\n20\n$centro_y\n40\n$radio_x\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n1\n10\n$centro_x\n20\n$centro_y\n11\n$punto_convergente_x\n21\n$punto_convergente_y\n";
    }
    $punto_convergente_xText = $punto_convergente_x + 0.05;
    $contenido_dxf .= "0\nTEXT\n8\n0\n62\n1\n10\n$punto_convergente_xText\n20\n$punto_convergente_y\n40\n0.05\n1\n$cantidadtotalX $Tipo_AcerosadicionalesXc\"\n";
    // Duplicar círculos en la otra cara del rectángulo (eje X)
    for ($i = 0; $i < $cantidadX; $i++) {
        $centro_x = $verticesint[0][0] + ($i + 1) * $espacio_x;
        $centro_y = $verticesint[3][1] - $radio_x; // Pegado al lado inferior del cuadrado
        $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x\n20\n$centro_y\n40\n$radio_x\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n1\n10\n$centro_x\n20\n$centro_y\n11\n$punto_convergente_x\n21\n$punto_convergente_y\n";
    }

    // Calcular y agregar círculos en el eje Y dentro del cuadrado
    $alto_y = $verticesint[3][1] - $verticesint[0][1];
    $espacio_y = $alto_y / ($cantidadY + 1);
    // Definir el punto central en el lado izquierdo y derecho donde convergen las líneas
    $punto_convergente_izquierdo_x = $verticesint[0][0] - 0.6; // Punto de convergencia en la izquierda
    $punto_convergente_izquierdo_y = ($verticesint[0][1] + $verticesint[3][1]) / 2; // Punto medio en Y
    for ($i = 0; $i < $cantidadY; $i++) {
        $centro_x = $verticesint[0][0] + $radio_y; // Pegado al lado izquierdo del cuadrado
        $centro_y = $verticesint[0][1] + ($i + 1) * $espacio_y;
        $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x\n20\n$centro_y\n40\n$radio_y\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n2\n10\n$centro_x\n20\n$centro_y\n11\n$punto_convergente_izquierdo_x\n21\n$punto_convergente_izquierdo_y\n";
    }
    // Duplicar círculos en la otra cara del rectángulo (eje Y)
    for ($i = 0; $i < $cantidadY; $i++) {
        $centro_x = $verticesint[1][0] - $radio_y; // Pegado al lado derecho del cuadrado
        $centro_y = $verticesint[0][1] + ($i + 1) * $espacio_y;
        $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x\n20\n$centro_y\n40\n$radio_y\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n2\n10\n$centro_x\n20\n$centro_y\n11\n$punto_convergente_izquierdo_x\n21\n$punto_convergente_izquierdo_y\n";
    }
    $punto_convergente_izquierdo_xText = $punto_convergente_izquierdo_x - 0.25;
    $contenido_dxf .= "0\nTEXT\n8\n0\n62\n2\n10\n$punto_convergente_izquierdo_xText\n20\n$punto_convergente_izquierdo_y\n40\n0.05\n1\n$cantidadtotalY $Tipo_AcerosadicionalesYc\"\n";

    // Cuatro Círculos
    $radio_esquinas = $Tipo_AceroEsquinas * $escala;
    $distancia_esquinas = $radio_esquinas;
    // Definir el punto central de convergencia (centro del rectángulo)
    $punto_convergente_x = ($verticesint[0][0] + $verticesint[1][0]) - 2.700;
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
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n6\n10\n$centro_x\n20\n$centro_y\n11\n$punto_convergente_x\n21\n$punto_convergente_y\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n62\n6\n10\n$punto_convergente_x\n20\n$punto_convergente_y\n40\n0.05\n1\n4 $Tipo_AceroEsquinasc\"\n";

    ///////////////////////////////////*CUADRO DE INFORMACION
    // TABLAS EXTERNAS INTERNAS//
    $baseTabla = $base + 4;
    // Suponiendo que $alturaArray ya está definido y contiene los valores de altura
    $altura = max($alturaArray); // Obtener el valor máximo de altura
    $alturaTabla = $altura + 2.5; // Ahora $alturaTabla se basa en el valor máximo

    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 3.8 - $base) . "\n20\n" . ($alturaTabla - 0.3) . "\n40\n0.1\n1\nESCALA:\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 3.8 - $base) . "\n20\n" . ($alturaTabla - 0.5) . "\n40\n0.1\n1\n 1/$escala\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 2 - $base) . "\n20\n" . ($alturaTabla - 0.4) . "\n40\n0.1\n1\nCUADRO DE COLUMNA\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 3.8 - $base) . "\n20\n" . ($alturaTabla - 0.8) . "\n40\n0.1\n1\nNIVEL\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 2.85 - $base) . "\n20\n" . ($alturaTabla - 0.8) . "\n40\n0.1\n1\nCONCRETO\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 3 - $base) . "\n20\n" . ($alturaTabla - 1) . "\n40\n0.1\n1\nfc'(Kg/cm2)\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 0.8 - $base) . "\n20\n" . ($alturaTabla - 0.8) . "\n40\n0.1\n1\nC1\n";
    // Altura de cada piso adicional (puedes ajustar esto según sea necesario)
    $alturaPisoAdicional = 0.2;
    for ($i = 1; $i <= $numPisosAdicionales; $i++) {
        $contenido_dxf .= "0\nTEXT\n8\n0\n";
        $contenido_dxf .= "10\n" . ($baseTabla - 3.8 - $base) . "\n20\n" . ($alturaTabla - 1.2 - ($i * $alturaPisoAdicional)) . "\n40\n0.1\n1\n" . ($i + 0) . "° PISO\n";
    }
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 2.7 - $base) . "\n20\n" . ($alturaTabla - 1.8) . "\n40\n0.1\n1\n210\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 3.5 - $base) . "\n20\n" . ($alturaTabla - 2 - $altura) . "\n40\n0.1\n1\nESFUERZO Y\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 3.5 - $base) . "\n20\n" . ($alturaTabla - 2.2 - $altura) . "\n40\n0.1\n1\nESTRIBAJE\n";
    //Fin
    $medidasFinalAlto = $altura4 * $escala;
    $mediadaFinalBase = $base * $escala;
    //Agregar el texto "medidas del grafico" dentro del rectángulo
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 1.3 - $base) . "\n20\n" . ($alturaTabla - 1.7 - $altura) . "\n40\n0.1\n1\n$mediadaFinalBase cm X $medidasFinalAlto cm\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 1.8 - $base) . "\n20\n" . ($alturaTabla - 1.9 - $altura) . "\n40\n0.1\n1\n4 $Tipo_AceroEsquinasc + $cantidadtotalX $Tipo_AcerosadicionalesXc + $cantidadtotalY $Tipo_AcerosadicionalesYc\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 1.7 - $base) . "\n20\n" . ($alturaTabla - 2.1 - $altura) . "\n40\n0.1\n1\n1 3/8∅: 1@0.05, 10@0.10,\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($baseTabla - 1.3 - $base) . "\n20\n" . ($alturaTabla - 2.3 - $altura) . "\n40\n0.1\n1\nRst. @0.20 C/E\n";

    $contenido_dxf .= "0\nPOLYLINE\n8\n0\n66\n1\n70\n8\n62\n1\n";
    $verticestabla = array(
        array(0, 0),
        array($baseTabla, 0),
        array($baseTabla, $alturaTabla),
        array(0, $alturaTabla),
        array(0, 0),
    );
    foreach ($verticestabla as $vertex) {
        $x = $vertex[0];
        $y = $vertex[1];
        $contenido_dxf .= "0\nVERTEX\n8\n0\n10\n$x\n20\n$y\n";
    }
    $contenido_dxf .= "0\nSEQEND\n";
    // HORIZONTALES
    $contenido_dxf .= "62\n1\n";
    $verticestablaH = array(
        array(1, 1),
        array(1, $alturaTabla),
    );
    foreach ($verticestablaH as $i => $vertex) {
        $x1 = $vertex[0];
        $y1 = $vertex[1];
        $x2 = $verticestablaH[($i + 1) % count($verticestablaH)][0];
        $y2 = $verticestablaH[($i + 1) % count($verticestablaH)][1];
        $contenido_dxf .= "0\nLINE\n8\n0\n10\n$x1\n20\n$y1\n11\n$x2\n21\n$y2\n62\n1\n";
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
        $contenido_dxf .= "0\nLINE\n8\n0\n10\n$x1\n20\n$y1\n11\n$x2\n21\n$y2\n62\n1\n";
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
        $contenido_dxf .= "62\n1\n";
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
    $contenido_dxf .= "62\n1\n";
    // Definir el espacio adicional hacia abajo en función de la altura del cuadrado
    $espacioAdicional = $altura + 0.3;
    // Nuevas dimensiones para el contorno extendido
    $alturaTablaExtendida = $alturaTabla + $espacioAdicional;
    // Dibujar el nuevo contorno extendido hacia abajo
    $contenido_dxf .= "0\nPOLYLINE\n8\n0\n66\n1\n70\n8\n62\n1\n";
    $verticestablaExtendida = array(
        array(0, 0),
        array($baseTabla, 0),
        array($baseTabla, -$espacioAdicional),
        array(0, -$espacioAdicional),
        array(0, 0),
    );
    foreach ($verticestablaExtendida as $vertex) {
        $x = $vertex[0];
        $y = $vertex[1];
        $contenido_dxf .= "0\nVERTEX\n8\n0\n10\n$x\n20\n$y\n";
    }
    $contenido_dxf .= "0\nSEQEND\n";
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n1\n10\n2\n20\n0\n11\n2\n21\n-" . $espacioAdicional . "\n";
    // Aquí empieza a agregar filas y columnas dependiendo de la cantidad de columnas
    $base3 = max($baseArray) + 2;
    $espacioAdicional = $base3;
    $offset = $baseTabla;
    $espacioAdicionaly1 = $altura + 0.3;
    for ($i = 1; $i < $cantidadColumnas; $i++) {
        $offset += $espacioAdicional;
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n1\n10\n$offset\n20\n0\n11\n$offset\n21\n-" . $espacioAdicionaly1 . "\n"; // Línea divisoria
    }
    // Finalizar polilínea de la tabla adicional (si es necesario)
    $contenido_dxf .= "0\nPOLYLINE\n8\n0\n66\n1\n70\n8\n62\n1\n";
    $verticestabla = array(
        array($baseTabla, 0),
        array($offset, 0),
        array($offset, -$espacioAdicionaly1),
        array($baseTabla, -$espacioAdicionaly1),
        array($baseTabla, 0),
    );
    foreach ($verticestabla as $vertex) {
        $x = $vertex[0];
        $y = $vertex[1];
        $contenido_dxf .= "0\nVERTEX\n8\n0\n10\n$x\n20\n$y\n";
    }
    $contenido_dxf .= "0\nSEQEND\n";
    $radio = 0.02;
    $nuevaBase = $base2;
    $nuevaAltura = $altura2;
    // Coordenadas de los puntos principales
    $p1 = array(3.08, 0.04 - $nuevaAltura); // Esquina inf izquierda
    $p2 = array($nuevaBase + 2.92, 0.04 - $nuevaAltura); // Esquina inf derecha
    $p3 = array($nuevaBase + 2.92, -0.12); // Esquina sup derecha
    $p4 = array(3.08, -0.12); // Esquina sup izquierda
    //Líneas
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . ($p1[0] + $radio) . "\n20\n" . $p1[1] . "\n11\n" . ($p2[0] - $radio) . "\n21\n" . $p2[1] . "\n";
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . $p2[0] . "\n20\n" . ($p2[1] + $radio) . "\n11\n" . $p3[0] . "\n21\n" . ($p3[1] - $radio) . "\n";
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . ($p3[0] - $radio) . "\n20\n" . $p3[1] . "\n11\n" . ($p4[0] + $radio) . "\n21\n" . $p4[1] . "\n";
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . $p4[0] . "\n20\n" . ($p4[1] - $radio) . "\n11\n" . $p1[0] . "\n21\n" . ($p1[1] + $radio) . "\n";
    //Arcos
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p1[0] + $radio) . "\n20\n" . ($p1[1] + $radio) . "\n40\n" . $radio . "\n50\n180\n51\n270\n";
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p2[0] - $radio) . "\n20\n" . ($p2[1] + $radio) . "\n40\n" . $radio . "\n50\n270\n51\n0\n";
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p3[0] - $radio) . "\n20\n" . ($p3[1] - $radio) . "\n40\n" . $radio . "\n50\n0\n51\n90\n";
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p4[0] + $radio) . "\n20\n" . ($p4[1] - $radio) . "\n40\n" . $radio . "\n50\n90\n51\n180\n";
    //Medidas
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n8\n10\n" . $p1[0] . "\n20\n" . ($p1[1] - 0.03) . "\n11\n" . $p2[0] . "\n21\n" . ($p2[1] - 0.03) . "\n";
    $texBasexV = (($p2[0] - $p1[0]) * $escala);
    $contenido_dxf .= "0\nTEXT\n8\n0\n62\n8\n10\n" . (($p1[0] + $p2[0]) / 2) . "\n20\n" . ($p1[1] - 0.15) . "\n40\n0.1\n1\n" . $texBasexV . "\n";
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n8\n10\n" . ($p2[0] + 0.03) . "\n20\n" . $p2[1] . "\n11\n" . ($p3[0] + 0.03) . "\n21\n" . $p3[1] . "\n";
    $texBaseyV = ($p2[1] - $p3[1]) * -$escala;
    $contenido_dxf .= "0\nTEXT\n8\n0\n62\n8\n10\n" . ($p3[0] + 0.04) . "\n20\n" . (($p2[1] + $p3[1]) / 2) . "\n40\n0.1\n1\n" . $texBaseyV . "\n";

    $radio = 0.02;
    $nuevaBase = $base2;
    $nuevaAltura = $altura2;
    // Coordenadas de los puntos principales
    $p1 = array(3.10, 0.06 - $nuevaAltura); // Esquina superior/inf izquierda
    $p2 = array($nuevaBase + 2.90, 0.06 - $nuevaAltura); // Esquina superior/inf derecha
    $p3 = array($nuevaBase + 2.90, -0.14); // Esquina inferior/sup derecha
    $p4 = array(3.10, -0.14); // Esquina inferior/sup izquierda

    //Líneas
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . ($p1[0] + $radio) . "\n20\n" . $p1[1] . "\n11\n" . ($p2[0] - $radio) . "\n21\n" . $p2[1] . "\n";
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . $p2[0] . "\n20\n" . ($p2[1] + $radio) . "\n11\n" . $p3[0] . "\n21\n" . ($p3[1] - $radio) . "\n";
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . ($p3[0] - $radio) . "\n20\n" . $p3[1] . "\n11\n" . ($p4[0] + $radio) . "\n21\n" . $p4[1] . "\n";
    $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . $p4[0] . "\n20\n" . ($p4[1] - $radio) . "\n11\n" . $p1[0] . "\n21\n" . ($p1[1] + $radio) . "\n";
    //Arcos
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p1[0] + $radio) . "\n20\n" . ($p1[1] + $radio) . "\n40\n" . $radio . "\n50\n180\n51\n270\n";
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p2[0] - $radio) . "\n20\n" . ($p2[1] + $radio) . "\n40\n" . $radio . "\n50\n270\n51\n0\n";
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p3[0] - $radio) . "\n20\n" . ($p3[1] - $radio) . "\n40\n" . $radio . "\n50\n0\n51\n90\n";
    $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p4[0] + $radio) . "\n20\n" . ($p4[1] - $radio) . "\n40\n" . $radio . "\n50\n90\n51\n180\n";
    //GANCHO ESTRIBO//}
    $vertices_gancho_izquierdo = [
        [($nuevaBase + 2.92) - 0.0590, -0.12],
        [($nuevaBase + 2.92) - ($nuevaBase / 3), -0.12 - ($nuevaBase / 3)],
    ];
    $vertices_gancho_derecho = [
        [($nuevaBase + 2.92), -0.12 - 0.0590],
        [($nuevaBase + 2.92) - ($nuevaBase / 3) + 0.0590, -0.12 - ($nuevaAltura / 3) - 0.0590],
    ];
    $dibujar_gancho = function ($vertices) use (&$contenido_dxf) {
        foreach ($vertices as $i => $vertex) {
            $x1 = $vertex[0];
            $y1 = $vertex[1];
            $x2 = $vertices[($i + 1) % count($vertices)][0];
            $y2 = $vertices[($i + 1) % count($vertices)][1];
            $contenido_dxf .= "0\nLINE\n8\n0\n10\n$x1\n20\n$y1\n11\n$x2\n21\n$y2\n62\n3\n"; // Color verde
        }
    };
    $dibujar_gancho($vertices_gancho_izquierdo);
    $dibujar_gancho($vertices_gancho_derecho);
    $vertices_gancho_izquierdo = [
        [($nuevaBase + 2.90) - 0.0390, -0.14],
        [($nuevaBase + 2.92) - ($nuevaBase / 3), -0.14 - ($nuevaBase / 3)],
    ];
    $vertices_gancho_derecho = [
        [($nuevaBase + 2.90), -0.14 - 0.0390],
        [($nuevaBase + 2.90) - ($nuevaBase / 3) + 0.0590, -0.12 - ($nuevaAltura / 3) - 0.0590],
    ];
    // Dibujar ganchos de estribo izquierdo y derecho
    $dibujar_gancho($vertices_gancho_izquierdo);
    $dibujar_gancho($vertices_gancho_derecho);
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . (0.5) . "\n20\n" . (-$espacioAdicionaly1 / 2) . "\n40\n0.1\n1\nDESPIECE DE\n";
    $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . (0.5) . "\n20\n" . ((-$espacioAdicionaly1 / 2) - 0.2) . "\n40\n0.1\n1\nESTRIBOS\n";
    // Aquí empieza a agregar filas y columnas dependiendo de la cantidad de columnas
    $espacioAdicional = $base3;
    $offset = $baseTabla;
    for ($i = 1; $i < $cantidadColumnas; $i++) {
        $offset += $espacioAdicional;
        $base1 = $baseArray[$i];
        $altura1 = $alturaArray[$i];
        $Tipo_AcerosadicionalesX1 = $tipoAcerosAdicionalesXArray[$i];
        $Tipo_AcerosadicionalesY1 = $tipoAcerosAdicionalesYArray[$i];
        $Tipo_AceroEsquinas1 = $tipoAceroEsquinasArray[$i];
        $cantidadX1 = $cantidadXArray[$i];
        $cantidadY1 = $cantidadYArray[$i];
        // Convertidor de medidas para Tipo_AceroEsquinas
        if ($Tipo_AceroEsquinas1 == 0.0127 / $escala) {
            $Tipo_AceroEsquinasc = "1/4";
        } elseif ($Tipo_AceroEsquinas1 == 0.019039999999999998 / $escala) {
            $Tipo_AceroEsquinasc = "3/8";
        } elseif ($Tipo_AceroEsquinas1 == 0.0254 / $escala) {
            $Tipo_AceroEsquinasc = "1/2";
        } elseif ($Tipo_AceroEsquinas1 == 0.03174 / $escala) {
            $Tipo_AceroEsquinasc = "5/8";
        } elseif ($Tipo_AceroEsquinas1 == 0.0381 / $escala) {
            $Tipo_AceroEsquinasc = "3/4";
        } elseif ($Tipo_AceroEsquinas1 == 0.0508 / $escala) {
            $Tipo_AceroEsquinasc = "1";
        }
        // Convertidor de medidas para Tipo_AcerosadicionalesX
        if ($Tipo_AcerosadicionalesX1 == 0.0127 / $escala) {
            $Tipo_AcerosadicionalesXc = "1/4";
        } elseif ($Tipo_AcerosadicionalesX1 == 0.019039999999999998 / $escala) {
            $Tipo_AcerosadicionalesXc = "3/8";
        } elseif ($Tipo_AcerosadicionalesX1 == 0.0254 / $escala) {
            $Tipo_AcerosadicionalesXc = "1/2";
        } elseif ($Tipo_AcerosadicionalesX1 == 0.03174 / $escala) {
            $Tipo_AcerosadicionalesXc = "5/8";
        } elseif ($Tipo_AcerosadicionalesX1 == 0.0381 / $escala) {
            $Tipo_AcerosadicionalesXc = "3/4";
        } elseif ($Tipo_AcerosadicionalesX1 == 0.0508 / $escala) {
            $Tipo_AcerosadicionalesXc = "1";
        }
        // Convertidor de medidas para Tipo_AcerosadicionalesY
        if ($Tipo_AcerosadicionalesY1 == 0.0127 / $escala) {
            $Tipo_AcerosadicionalesYc = "1/4";
        } elseif ($Tipo_AcerosadicionalesY1 == 0.019039999999999998 / $escala) {
            $Tipo_AcerosadicionalesYc = "3/8";
        } elseif ($Tipo_AcerosadicionalesY1 == 0.0254 / $escala) {
            $Tipo_AcerosadicionalesYc = "1/2";
        } elseif ($Tipo_AcerosadicionalesY1 == 0.03174 / $escala) {
            $Tipo_AcerosadicionalesYc = "5/8";
        } elseif ($Tipo_AcerosadicionalesY1 == 0.0381 / $escala) {
            $Tipo_AcerosadicionalesYc = "3/4";
        } elseif ($Tipo_AcerosadicionalesY1 == 0.0508 / $escala) {
            $Tipo_AcerosadicionalesYc = "1";
        }
        $radio_x1 = $Tipo_AcerosadicionalesX1 * $escala;
        $radio_y1 = $Tipo_AcerosadicionalesY1 * $escala;
        $contenido_dxf .= "0\nPOLYLINE\n8\n0\n66\n1\n70\n8\n62\n5\n";
        $vertices = array(
            array(($offset - $espacioAdicional) + 1, 1.2), //Esquina inferior izquierda
            array(($offset - $espacioAdicional) + 1 + $base1, 1.2), //Esquina inferior derecha
            array(($offset - $espacioAdicional) + 1 + $base1, 1.2 + $altura1), //Esquina superior derecha
            array(($offset - $espacioAdicional) + 1, 1.2 + $altura1), //Esquina superior izquierda
            array(($offset - $espacioAdicional) + 1, 1.2), //Esquina inferior izquierda
        );
        foreach ($vertices as $vertex) {
            $x = $vertex[0];
            $y = $vertex[1];
            $contenido_dxf .= "0\nVERTEX\n8\n0\n10\n$x\n20\n$y\n";
        }
        $contenido_dxf .= "0\nSEQEND\n";
        //Cuadrado Exterior
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n8\n10\n" . (($offset - $espacioAdicional) + 1) . "\n20\n" . (1.2 - 0.05) . "\n11\n" . (($offset - $espacioAdicional) + 1 + $base1) . "\n21\n" . (1.2 - 0.05) . "\n";
        $texBasex = ($base1 * $escala);
        $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . (($offset - $espacioAdicional) + 1 + ($base1 / 2)) . "\n20\n" . ((1.2 + $yOffset - 0.05) - 0.02) . "\n40\n0.05\n1\n$texBasex\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n8\n10\n" . ((($offset - $espacioAdicional) + 1) + $base1 + 0.05) . "\n20\n1.2\n11\n" . ((($offset - $espacioAdicional) + 1) + $base1 + 0.05) . "\n21\n" . ($altura1 + 1.2) . "\n";
        $texBasey = ($altura1 * $escala);
        $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ((($offset - $espacioAdicional) + 1) + $base1 + 0.05 + 0.02) . "\n20\n" . (($altura1 / 2) + 1.2) . "\n40\n0.05\n1\n$texBasey\n";
        //CuadradoInteriorGrande
        $radio = 0.02;
        $p1 = array(($offset - $espacioAdicional) + 1.08, 1.28); //Esquina Inferior Izquierda
        $p2 = array(($offset - $espacioAdicional) + 0.92 + $base1, 1.28); //Esquina inferior derecha
        $p3 = array(($offset - $espacioAdicional) + 0.92 + $base1, 1.12 + $altura1); //Esquina superior derecha
        $p4 = array(($offset - $espacioAdicional) + 1.08, 1.12 + $altura1); //Esquina superior izquierda
        //Líneas
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . ($p1[0] + $radio) . "\n20\n" . $p1[1] . "\n11\n" . ($p2[0] - $radio) . "\n21\n" . $p2[1] . "\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . $p2[0] . "\n20\n" . ($p2[1] + $radio) . "\n11\n" . $p3[0] . "\n21\n" . ($p3[1] - $radio) . "\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . ($p3[0] - $radio) . "\n20\n" . $p3[1] . "\n11\n" . ($p4[0] + $radio) . "\n21\n" . $p4[1] . "\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . $p4[0] . "\n20\n" . ($p4[1] - $radio) . "\n11\n" . $p1[0] . "\n21\n" . ($p1[1] + $radio) . "\n";
        //Arcos
        $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p1[0] + $radio) . "\n20\n" . ($p1[1] + $radio) . "\n40\n" . $radio . "\n50\n180\n51\n270\n";
        $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p2[0] - $radio) . "\n20\n" . ($p2[1] + $radio) . "\n40\n" . $radio . "\n50\n270\n51\n0\n";
        $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p3[0] - $radio) . "\n20\n" . ($p3[1] - $radio) . "\n40\n" . $radio . "\n50\n0\n51\n90\n";
        $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p4[0] + $radio) . "\n20\n" . ($p4[1] - $radio) . "\n40\n" . $radio . "\n50\n90\n51\n180\n";
        //CuadradoInteriorPequeño
        $radio = 0.02;
        $p1 = array(($offset - $espacioAdicional) + 1.10, 1.30);
        $p2 = array(($offset - $espacioAdicional) + 0.90 + $base1, 1.30);
        $p3 = array(($offset - $espacioAdicional) + 0.90 + $base1, 1.10 + $altura1);
        $p4 = array(($offset - $espacioAdicional) + 1.10, 1.10 + $altura1);
        //Líneas
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . ($p1[0] + $radio) . "\n20\n" . $p1[1] . "\n11\n" . ($p2[0] - $radio) . "\n21\n" . $p2[1] . "\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . $p2[0] . "\n20\n" . ($p2[1] + $radio) . "\n11\n" . $p3[0] . "\n21\n" . ($p3[1] - $radio) . "\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . ($p3[0] - $radio) . "\n20\n" . $p3[1] . "\n11\n" . ($p4[0] + $radio) . "\n21\n" . $p4[1] . "\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . $p4[0] . "\n20\n" . ($p4[1] - $radio) . "\n11\n" . $p1[0] . "\n21\n" . ($p1[1] + $radio) . "\n";
        //Arcos    
        $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p1[0] + $radio) . "\n20\n" . ($p1[1] + $radio) . "\n40\n" . $radio . "\n50\n180\n51\n270\n";
        $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p2[0] - $radio) . "\n20\n" . ($p2[1] + $radio) . "\n40\n" . $radio . "\n50\n270\n51\n0\n";
        $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p3[0] - $radio) . "\n20\n" . ($p3[1] - $radio) . "\n40\n" . $radio . "\n50\n0\n51\n90\n";
        $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p4[0] + $radio) . "\n20\n" . ($p4[1] - $radio) . "\n40\n" . $radio . "\n50\n90\n51\n180\n";
        $verticesint1 = array(
            array(($offset - $espacioAdicional) + 1.1, 1.3),
            array(($offset - $espacioAdicional) + 0.9 + $base1, 1.3),
            array(($offset - $espacioAdicional) + 0.9 + $base1, 1.1 + $altura1),
            array(($offset - $espacioAdicional) + 1.1, 1.1 + $altura1),
            array(($offset - $espacioAdicional) + 1.1, 1.3),
        );
        $cantidadtotalX1 = $cantidadX1 * 2;
        $cantidadtotalY1 = $cantidadY1 * 2;
        //Circulos en X
        $ancho_x1 = $verticesint1[1][0] - $verticesint1[0][0];
        $espacio_x1 = $ancho_x1 / ($cantidadX1 + 1);
        $punto_convergente_x1 = ($verticesint1[0][0] + $verticesint1[1][0]) / 2;
        $punto_convergente_y1 = $verticesint1[3][1] + 0.15;
        for ($i = 0; $i < $cantidadX1; $i++) {
            $centro_x1 = $verticesint1[0][0] + ($i + 1) * $espacio_x1;
            $centro_y1 = $verticesint1[0][1] + $radio_x1; // Pegado al lado superior del cuadrado
            $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x1\n20\n$centro_y1\n40\n$radio_x1\n";
            $contenido_dxf .= "0\nLINE\n8\n0\n62\n1\n10\n$centro_x1\n20\n$centro_y1\n11\n$punto_convergente_x1\n21\n$punto_convergente_y1\n";
        }
        $punto_convergente_xText1 = $punto_convergente_x1 + 0.05;
        $contenido_dxf .= "0\nTEXT\n8\n0\n62\n1\n10\n$punto_convergente_xText1\n20\n$punto_convergente_y1\n40\n0.05\n1\n$cantidadtotalX1 $Tipo_AcerosadicionalesXc\"\n";
        for ($i = 0; $i < $cantidadX1; $i++) {
            $centro_x1 = $verticesint1[0][0] + ($i + 1) * $espacio_x1;
            $centro_y1 = $verticesint1[3][1] - $radio_x; // Pegado al lado inferior del cuadrado
            $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x1\n20\n$centro_y1\n40\n$radio_x1\n";
            $contenido_dxf .= "0\nLINE\n8\n0\n62\n1\n10\n$centro_x1\n20\n$centro_y1\n11\n$punto_convergente_x1\n21\n$punto_convergente_y1\n";
        }
        //Circulos Y
        $alto_y1 = $verticesint1[3][1] - $verticesint1[0][1];
        $espacio_y1 = $alto_y1 / ($cantidadY1 + 1);
        $punto_convergente_izquierdo_x1 = $verticesint1[0][0] - 0.6;
        $punto_convergente_izquierdo_y1 = ($verticesint1[0][1] + $verticesint1[3][1]) / 2;
        for ($i = 0; $i < $cantidadY1; $i++) {
            $centro_x1 = $verticesint1[0][0] + $radio_y1; // Pegado al lado izquierdo del cuadrado
            $centro_y1 = $verticesint1[0][1] + ($i + 1) * $espacio_y1;
            $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x1\n20\n$centro_y1\n40\n$radio_y1\n";
            $contenido_dxf .= "0\nLINE\n8\n0\n62\n2\n10\n$centro_x1\n20\n$centro_y1\n11\n$punto_convergente_izquierdo_x1\n21\n$punto_convergente_izquierdo_y1\n";
        }
        for ($i = 0; $i < $cantidadY1; $i++) {
            $centro_x1 = $verticesint1[1][0] - $radio_y1; // Pegado al lado derecho del cuadrado
            $centro_y1 = $verticesint1[0][1] + ($i + 1) * $espacio_y1;
            $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x1\n20\n$centro_y1\n40\n$radio_y1\n";
            $contenido_dxf .= "0\nLINE\n8\n0\n62\n2\n10\n$centro_x1\n20\n$centro_y1\n11\n$punto_convergente_izquierdo_x1\n21\n$punto_convergente_izquierdo_y1\n";
        }
        $punto_convergente_izquierdo_xText1 = $punto_convergente_izquierdo_x1 - 0.25;
        $contenido_dxf .= "0\nTEXT\n8\n0\n62\n2\n10\n$punto_convergente_izquierdo_xText1\n20\n$punto_convergente_izquierdo_y1\n40\n0.05\n1\n$cantidadtotalY1 $Tipo_AcerosadicionalesYc\"\n";
        //Cuatro Círculos
        $radio_esquinas1 = $Tipo_AceroEsquinas1 * $escala;
        $distancia_esquinas1 = $radio_esquinas1;
        $punto_convergente_x1 = ($verticesint1[0][0] + $base1 + 0.2);
        $punto_convergente_y1 = ($verticesint1[0][1] + $verticesint1[3][1]) / 2;
        $centro_x1 = $verticesint1[0][0] + $distancia_esquinas1;
        $centro_y1 = $verticesint1[0][1] + $distancia_esquinas1;
        $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x1\n20\n$centro_y1\n40\n$radio_esquinas1\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n6\n10\n$centro_x1\n20\n$centro_y1\n11\n$punto_convergente_x1\n21\n$punto_convergente_y1\n";
        $centro_x1 = $verticesint1[1][0] - $distancia_esquinas1;
        $centro_y1 = $verticesint1[1][1] + $distancia_esquinas1;
        $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x1\n20\n$centro_y1\n40\n$radio_esquinas1\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n6\n10\n$centro_x1\n20\n$centro_y1\n11\n$punto_convergente_x1\n21\n$punto_convergente_y1\n";
        $centro_x1 = $verticesint1[2][0] - $distancia_esquinas1;
        $centro_y1 = $verticesint1[2][1] - $distancia_esquinas1;
        $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x1\n20\n$centro_y1\n40\n$radio_esquinas1\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n6\n10\n$centro_x1\n20\n$centro_y1\n11\n$punto_convergente_x1\n21\n$punto_convergente_y1\n";
        $centro_x1 = $verticesint1[3][0] + $distancia_esquinas1;
        $centro_y1 = $verticesint1[3][1] - $distancia_esquinas1;
        $contenido_dxf .= "0\nCIRCLE\n8\n0\n10\n$centro_x1\n20\n$centro_y1\n40\n$radio_esquinas1\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n6\n10\n$centro_x1\n20\n$centro_y1\n11\n$punto_convergente_x1\n21\n$punto_convergente_y1\n";
        $punto_convergente_x1text = $punto_convergente_x1 + 0.02;
        $contenido_dxf .= "0\nTEXT\n8\n0\n62\n6\n10\n$punto_convergente_x1text\n20\n$punto_convergente_y1\n40\n0.05\n1\n4 $Tipo_AceroEsquinasc\"\n";
        //GANCHO ESTRIBO//}
        // Definir los vértices del gancho de estriboext
        $vertices_gancho_izquierdo = array(
            array((($offset-$espacioAdicional)+0.92+$base1)-0.059, 1.12+$altura1),
            array(((($offset-$espacioAdicional)+0.92+$base1)-0.059)-($base1/3), (1.12+$altura1)-($altura1/3)),
        );

        // Gancho de estribos superior derecho
        $vertices_gancho_derecho = array(
            array((($offset-$espacioAdicional)+0.92+$base1), (1.12+$altura1)-0.059),
            array(((($offset-$espacioAdicional)+0.92+$base1)-($base1/3)), (1.12+$altura1)-($altura1/3)-0.059),
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
        // Definir los vértices del gancho de estriboext
        $vertices_gancho_izquierdo = array(
            array((($offset-$espacioAdicional)+0.90+$base1)-0.039, 1.10+$altura1),
            array(((($offset-$espacioAdicional)+0.90+$base1)-0.019)-($base1/3), (1.10+$altura1)-($altura1/3)+0.02),
        );

        // Gancho de estribos superior derecho
        $vertices_gancho_derecho = array(
            array((($offset-$espacioAdicional)+0.90+$base1), 1.10+$altura1-0.039),
            array(((($offset-$espacioAdicional)+0.90+$base1))-($base1/3)+0.02, (1.10+$altura1)-($altura1/3)-0.019),
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
        //CuadradoInteriorGrande
        $radio = 0.02;
        $p1 = array(($offset - $espacioAdicional) + 1.08, 0.04 - $altura1); //Esquina Inferior Izquierda
        $p2 = array(($offset - $espacioAdicional) + 0.92 + $base1, 0.04 - $altura1); //Esquina inferior derecha
        $p3 = array(($offset - $espacioAdicional) + 0.92 + $base1, -0.12); //Esquina superior derecha
        $p4 = array(($offset - $espacioAdicional) + 1.08, -0.12); //Esquina superior izquierda
        //Línea
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . ($p1[0] + $radio) . "\n20\n" . $p1[1] . "\n11\n" . ($p2[0] - $radio) . "\n21\n" . $p2[1] . "\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . $p2[0] . "\n20\n" . ($p2[1] + $radio) . "\n11\n" . $p3[0] . "\n21\n" . ($p3[1] - $radio) . "\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . ($p3[0] - $radio) . "\n20\n" . $p3[1] . "\n11\n" . ($p4[0] + $radio) . "\n21\n" . $p4[1] . "\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . $p4[0] . "\n20\n" . ($p4[1] - $radio) . "\n11\n" . $p1[0] . "\n21\n" . ($p1[1] + $radio) . "\n";
        //Arcos    
        $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p1[0] + $radio) . "\n20\n" . ($p1[1] + $radio) . "\n40\n" . $radio . "\n50\n180\n51\n270\n";
        $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p2[0] - $radio) . "\n20\n" . ($p2[1] + $radio) . "\n40\n" . $radio . "\n50\n270\n51\n0\n";
        $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p3[0] - $radio) . "\n20\n" . ($p3[1] - $radio) . "\n40\n" . $radio . "\n50\n0\n51\n90\n";
        $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p4[0] + $radio) . "\n20\n" . ($p4[1] - $radio) . "\n40\n" . $radio . "\n50\n90\n51\n180\n";
        //Medida
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n8\n10\n" . $p1[0] . "\n20\n" . ($p1[1] - 0.03) . "\n11\n" . $p2[0] . "\n21\n" . ($p2[1] - 0.03) . "\n";
        $texBasexV = (($p2[0] - $p1[0]) * $escala);
        $contenido_dxf .= "0\nTEXT\n8\n0\n62\n8\n10\n" . (($p1[0] + $p2[0]) / 2) . "\n20\n" . ($p1[1] - 0.15) . "\n40\n0.1\n1\n" . $texBasexV . "\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n8\n10\n" . ($p2[0] + 0.03) . "\n20\n" . $p2[1] . "\n11\n" . ($p3[0] + 0.03) . "\n21\n" . $p3[1] . "\n";
        $texBaseyV = ($p2[1] - $p3[1]) * -$escala;
        $contenido_dxf .= "0\nTEXT\n8\n0\n62\n8\n10\n" . ($p3[0] + 0.04) . "\n20\n" . (($p2[1] + $p3[1]) / 2) . "\n40\n0.1\n1\n" . $texBaseyV . "\n";
        //CuadradoInteriorPequeño
        $radio = 0.02;
        $p1 = array(($offset - $espacioAdicional) + 1.10, 0.06 - $altura1);
        $p2 = array(($offset - $espacioAdicional) + 0.90 + $base1, 0.06 - $altura1);
        $p3 = array(($offset - $espacioAdicional) + 0.90 + $base1, -0.14);
        $p4 = array(($offset - $espacioAdicional) + 1.10, -0.14);
        //Línea
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . ($p1[0] + $radio) . "\n20\n" . $p1[1] . "\n11\n" . ($p2[0] - $radio) . "\n21\n" . $p2[1] . "\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . $p2[0] . "\n20\n" . ($p2[1] + $radio) . "\n11\n" . $p3[0] . "\n21\n" . ($p3[1] - $radio) . "\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . ($p3[0] - $radio) . "\n20\n" . $p3[1] . "\n11\n" . ($p4[0] + $radio) . "\n21\n" . $p4[1] . "\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n62\n3\n10\n" . $p4[0] . "\n20\n" . ($p4[1] - $radio) . "\n11\n" . $p1[0] . "\n21\n" . ($p1[1] + $radio) . "\n";
        //Arcos
        $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p1[0] + $radio) . "\n20\n" . ($p1[1] + $radio) . "\n40\n" . $radio . "\n50\n180\n51\n270\n";
        $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p2[0] - $radio) . "\n20\n" . ($p2[1] + $radio) . "\n40\n" . $radio . "\n50\n270\n51\n0\n";
        $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p3[0] - $radio) . "\n20\n" . ($p3[1] - $radio) . "\n40\n" . $radio . "\n50\n0\n51\n90\n";
        $contenido_dxf .= "0\nARC\n8\n0\n62\n3\n10\n" . ($p4[0] + $radio) . "\n20\n" . ($p4[1] - $radio) . "\n40\n" . $radio . "\n50\n90\n51\n180\n";
        //GANCHO ESTRIBO//}
        // Definir los vértices del gancho de estriboext
        $vertices_gancho_izquierdo = array(
            array((($offset-$espacioAdicional)+0.92+$base1)-0.059, -0.12),
            array(((($offset-$espacioAdicional)+0.92+$base1)-0.059)-($base1/3), -0.12-($base1/3)),
        );

        // Gancho de estribos superior derecho
        $vertices_gancho_derecho = array(   
            array((($offset-$espacioAdicional)+0.92+$base1), -0.12-0.059),
            array(((($offset-$espacioAdicional)+0.92+$base1)-($base1/3)), -0.12-($altura1/3)-0.059),
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
        // Definir los vértices del gancho de estriboext
        $vertices_gancho_izquierdo = array(
            array((($offset-$espacioAdicional)+0.90+$base1)-0.039, -0.14),
            array(((($offset-$espacioAdicional)+0.90+$base1)-0.019)-($base1/3), -0.14-($base1/3)+0.02),
        );
        // Gancho de estribos superior derecho
        $vertices_gancho_derecho = array(
            array((($offset-$espacioAdicional)+0.90+$base1), -0.14-0.039),
            array(((($offset-$espacioAdicional)+0.90+$base1))-($base1/3)+0.02, -0.14-($altura1/3)-0.019),
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
        $medidasFinalAlto1 = $altura1 * $escala;
        $mediadaFinalBase1 = $base1 * $escala;
        $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($offset - 1.3 - $base1) . "\n20\n" . (0.8) . "\n40\n0.1\n1\n$mediadaFinalBase1 cm X $medidasFinalAlto1 cm\n";
        $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($offset - 1.8 - $base1) . "\n20\n" . (0.6) . "\n40\n0.1\n1\n4 $Tipo_AceroEsquinasc + $cantidadtotalX1 $Tipo_AcerosadicionalesXc + $cantidadtotalY1 $Tipo_AcerosadicionalesYc\n";
        $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($offset - 1.7 - $base1) . "\n20\n" . (0.4) . "\n40\n0.1\n1\n1 3/8∅: 1@0.05, 10@0.10,\n";
        $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($offset - 1.3 - $base1) . "\n20\n" . (0.2) . "\n40\n0.1\n1\nRst. @0.20 C/E\n";
        $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($offset - ($espacioAdicional / 2) - 1) . "\n20\n" . ($alturaTabla - 0.4) . "\n40\n0.1\n1\nCUADRO DE COLUMNA " . ($i + 1) . "\n";
        $contenido_dxf .= "0\nTEXT\n8\n0\n10\n" . ($offset - ($espacioAdicional / 2)) . "\n20\n" . ($alturaTabla - 0.8) . "\n40\n0.1\n1\nC" . ($i + 1) . "\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n10\n" . ($offset - $espacioAdicional) . "\n20\n" . ($alturaTabla - 0.6) . "\n11\n" . ($offset) . "\n21\n" . ($alturaTabla - 0.6) . "\n62\n1\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n10\n" . ($offset - $espacioAdicional) . "\n20\n1\n11\n" . ($offset) . "\n21\n1\n62\n1\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n10\n" . ($offset - $espacioAdicional) . "\n20\n" . ($alturaTabla - 1.1) . "\n11\n" . ($offset) . "\n21\n" . ($alturaTabla - 1.1) . "\n62\n1\n";
        $contenido_dxf .= "0\nLINE\n8\n0\n10\n" . ($offset) . "\n20\n0\n11\n" . ($offset) . "\n21\n" . ($alturaTabla) . "\n62\n1\n";
    }
    $contenido_dxf .= "0\nPOLYLINE\n8\n0\n66\n1\n70\n8\n62\n1\n";
    $verticestabla = array(
        array($baseTabla, 0), // Punto inicial de la nueva tabla
        array($offset, 0), // Punto derecho
        array($offset, $alturaTabla), // Punto alto derecho
        array($baseTabla, $alturaTabla), // Punto alto izquierdo
        array($baseTabla, 0), // Cierre la polilínea
    );
    foreach ($verticestabla as $vertex) {
        $x = $vertex[0];
        $y = $vertex[1];
        $contenido_dxf .= "0\nVERTEX\n8\n0\n10\n$x\n20\n$y\n";
    }
    $contenido_dxf .= "0\nSEQEND\n";
    $contenido_dxf .= "0\nENDSEC\n0\nEOF";
    $archivoDXF = 'Columna-rectangular-cuadrado.dxf';
    $rutaArchivo = __DIR__ . '/' . $archivoDXF;
    file_put_contents($rutaArchivo, $contenido_dxf);
    $rutaAutoCAD = 'C:\Program Files\Autodesk\AutoCAD 2021\acad.exe';
    $comando = 'start "" "' . $rutaAutoCAD . '" "' . $rutaArchivo . '"';
    // Ejecutar el comando
    shell_exec($comando);
    echo "AutoCAD iniciado con el archivo DXF basado en las dimensiones proporcionadas.";
}
