<?php require_once __DIR__ . '/../php/checksesion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta de Satisfacción - Hospital de Clínicas</title>
    <link rel="stylesheet" href="../css/elementosC.css">
    <link rel="stylesheet" href="../css/encuesta.css">
</head>

<body>
    <header class="Headerguia">
       <img src="../fotos-videos/logor.png" alt="Logo del hospital" class="logo">
        <h1>Encuesta de Satisfacción</h1>
        <nav class="navguia">
            <a href="../pages/guia.php">Inicio</a>
            <a href="../pages/tratamientos.php">Pacientes</a>
            <a href="../pages/traslados.php">Ambulancias</a>
            <a href="../pages/ajustes.php">Ajustes</a>
        </nav>
    </header>

    <main class="mainencuesta">
        <section class="sectionencuesta">
            <h2>Tu opinión nos ayuda mejorar</h2>
            <p>Responde libremente y de forma critica</p>
            <br>
            
            <form action="../php/guardar_encuesta.php" method="POST">
                
        
 <section class="row">

        <article class="col-md-4 mb-3">
            <h4>Encuesta de traslados</h4>
            <p>Evaluá el servicio de traslado en ambulancia.</p>
            <a href="https://docs.google.com/forms/d/18iFXTlWnNweCAD6Q01t4260O_zkcA7aHbj_ufHkwkk0/edit" class="btn btn-primary">  Completar encuesta
            </a>
        </article>
            
        

          <article class="col-md-4 mb-3">
            <h4>Encuesta del hospital</h4>
            <p>Evaluá el estado de las instalaciones.</p>
            <a href="https://docs.google.com/forms/d/13el__H0g7vz4br7v5IOwAgdHsVSYPr_7dy3zRLvy21M/edit?usp=forms_home&ouid=114919482076470136106&ths=true">
                Completar encuesta
            </a>
        </article>

        <article class="col-md-4 mb-3">
            <h4>Encuesta de funcionarios</h4>
            <a href="https://docs.google.com/forms/d/1jOph8sZjPPxW0phxF59Kb1n-klHsB2_wy8m9Ern5Fjw/edit" class="btn btn-primary">
                Completar encuesta
            </a>
        </article>

  </section> 
            
    </main>

        <footer class="footerguia">
        <p>Equipo 4 Espinas - Mateo Cáceres - Dominicke Alvarez - Emiliano Stagi - Thomas Zabala</p>
        <button class="logout"><a href="../php/logout.php">Cerrar Sesión</a></button>
    </footer>
</body>
</html>