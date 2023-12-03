
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VetTalavera</title>
    <link rel="stylesheet" href="./css/menu.css">
    <link rel="stylesheet" href="./css/service.css">
    <link rel="stylesheet" href="./css/breakpoint.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>

<body>
    <header class="header_main header_short">
        <nav class="nav container" id="mininav">
            <h2 class="nav__logo">Tala<span class="char char--logo">V</span>et</h2>

            <ul class="nav__links">

                <li class="nav__item">
                    <a href="index.php" class="nav__link"><span class="char">I</span>nicio</a>
                </li>
                <li class="nav__item">
                    <a href="./pages/login.php" class="nav__link"><span class="char">A</span>cceso</a>
                </li>
                <li class="nav__item">
                    <a href="#" class="nav__link"><span class="char">S</span>ervicios</a>
                </li>
                <li class="nav__item">
                    <a href="#" class="nav__link"><span class="char">C</span>ontacto</a>
                </li>

            </ul>

            <!--Tiene que ser un boton que haga referencia al id puesto en el nav-->
            <a href="#mininav" class="nav__hamburguer">
                <img src="./images/menu.svg" class="nav__icon">
            </a>

            <a href="" class="nav__close">
                <img src="./images/close.svg" class="nav__icon">
            </a>

        </nav>

    </header>
    <main class="service">
        <section class="consulta">
            <header class="consulta__main">
                <h1 class="consulta__title">Donde las patas están en las mejores manos.</h1>
                <h2 class="consulta__subtitle">Equipo siempre listo para un tratamiento.</h2>
                <button class="btn-consulta" id="btn-consulta"><span>Pide una consulta</span></button>
            </header>

            <div>
                <form id="consulta-form" class="consulta-form" name="consulta-form" action="#" method="POST">
                    <div class="consulta__nombre">
                        <label for="name" class="label">Nombre</label>
                        <input required type="text" id="name" name="name" class="form-input">
                    </div>
                    <div class="consulta__mail">
                        <label for="email" class="label">Email</label>
                        <input required type="text" id="email" name="email" class="form-input">

                    </div>
                    <div class="consulta__telefono">
                        <label for="tele" class="label">Teléfono</label>
                        <input required type="text" id="tele" name="tele" class="form-input">
                    </div>
                    <div class="consulta__fecha">
                        <label for="date" class="label">Fecha</label>
                        <input required type="text" id="date" name="date" class="form-input ">
                    </div>
                    <div class="consulta__textarea">
                        <label for="message" class="label">Motivo de la consulta</label>
                        <textarea required id="message" name="message" class="form-textarea"></textarea>
                    </div>
                    <button type="submit" class="btn-consulta">Enviar Consulta</button>
                </form>
            </div>
        </section>
        <section>
            <h2 class="clinica__title">La Clínica</h2>
            <div class="clinica">
                <img alt="clinica" class="clinica__img" src="./images/clinica.jpg">
                <p class="text-xl">Con la más moderna tecnología veterinaria</p>
            </div>
            <div class="clinica__text-container">
                <p class="clinica__text">TalaVet fue establecida con orgullo en <b>noviembre de 2023</b> con la misión
                    firme de
                    proporcionar servicios veterinarios excepcionales y personalizados a los residentes de la pintoresca
                    provincia de Toledo, centrándonos especialmente en la cálida comunidad de Talavera de la Reina.</p>

                <p class="clinica__text">Este ambicioso proyecto se destaca por su visión innovadora, brindando
                    servicios a domicilio
                    respaldados por equipos de última generación, colocando a disposición de nuestros clientes y sus
                    adorables mascotas. Nuestra aspiración se ha convertido en el sello distintivo de la calidad que
                    ofrecemos, una calidad que ha evolucionado y mejorado constantemente desde el día de nuestra
                    apertura en el vibrante corazón de Talavera.</p>

                <p class="clinica__text">La creación de esta institución marca la realización de un sueño compartido
                    entre tres
                    entrañables amigos y compañeros de la universidad: Antonio Martínez, Carmen Sánchez y Javier López.
                    Su dedicación y pasión son la fuerza motriz detrás de nuestro compromiso continuo de brindar
                    atención veterinaria de primera clase a lo largo de los años.</p>
            </div>
            <div class="mascotas">
                <h3 class="mascotas__title">Nuestras Mascotas</h3>
                <div class="mascotas-imagenes">
                    <div class="mascotas-img-container">
                        <img src="./images/masc_1.jpg" alt="Mascota 1" class=" mascotas-img">
                        <div class="content">
                            <p class="content_text content_text--white">Manolo</p>
                        </div>
                    </div>
                    <div class="mascotas-img-container">
                        <img src="./images/masc_2.jpg" alt="Mascota 2" class=" mascotas-img">
                        <div class="content">
                            <p class="content_text content_text--black">Titán</p>
                        </div>
                    </div>
                    <div class="mascotas-img-container">
                        <img src="./images/masc_3.jpg" alt="Mascota 3" class=" mascotas-img">
                        <div class="content">
                            <p class="content_text content_text--white">Chispas</p>
                        </div>
                    </div>
                    <div class="mascotas-img-container">
                        <img src="./images/masc_4.jpg" alt="Mascota 4" class=" mascotas-img">
                        <div class="content">
                            <p class="content_text content_text--black">Paquito</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="servicios">
            <div class="servicios__container-title">
                <h2 class="servicios__title">
                    Servicios:</h2>
            </div>
            <ul class="servicios__list">
                <li>
                    <i class="bi bi-prescription2"></i>
                    <h5 class="text-icon">Consulta General</h5>
                </li>
                <li>
                    <i class="bi bi-heart-pulse-fill"></i>
                    <h5 class="text-icon">Cardiología</h5>
                </li>
                <li>
                    <i class="bi bi-eye-fill"></i>
                    <h5 class="text-icon">Oftalmología</h5>
                </li>
                <li>
                    <i class="bi bi-tree-fill"></i>
                    <h5 class="text-icon">Zona de Paseo</h5>
                </li>
            </ul>
            <div class="servicios__container-distincion">
                <div class="">
                    <h4 class="">Lo que nos distingue:</h4>
                    <hr>
                </div>
                <div class="">
                    <div class="img_container">
                        <img src="./images/clini.jpg" class="img img_s" alt="mesa de operaciones" />
                        <img src="./images/operacion.jpg" class="img img_s" alt=" operando" />
                        <img src="./images/clini2.jpg" class="img img_s" alt="mesa de operaciones2" />
                    </div>
                </div>
                <div>
                    <div class="space">
                        <h5>Flexibilidad</h5>
                        <p class="text">Contamos con un equipo formado, especializado y flexible.
                            Acompañamiento exclusivo para su mascota.</p>
                    </div>
                    <div class="space">
                        <h5>Rapidez</h5>
                        <p class="text">Brindamos atención rápida, crucial en la mayoría de los casos más
                            graves de su mascota.</p>
                    </div>
                    <div class="space">
                        <h5>Equipamientos</h5>
                        <p class="text">Disponemos de tecnología de punta para el tratamiento de su mascota.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="team-container-title">
                <h2 class="team-title">Equipo:
                </h2>
            </div>
            <hr>
            <div class="container__text">
                <p class="text">
                    Contamos con un equipo multidisciplinar, con profesionales capacitados en el campo de
                    veterinaria en toda su extensión. Un equipo siempre listo para un tratamiento profesional de su
                    mascota.</p>
            </div>
            <div class="team-container">
                <!-- Card 1 -->
                <div class="team-member flip-card">
                    <div class="card">
                        <div class="card-front">
                            <img src="./images/veter.jpg" alt="Team member image">
                            <div class="avatar">
                                <img src="./images/avatar1.jpg" alt="Avatar image">
                            </div>
                            <div class="card-body">
                                <h5 class="member-name">Dra. Ana pinchazos</h5>
                                <p class="menber-role">Médico Veterinária</p>
                                <p class="member-info">Medicina Interna,
                                    Radiología y Urgencias/Trauma</p>
                                <i class="fas fa-redo-alt"></i>
                                <p>Saber
                                    mas...</p>
                            </div>
                        </div>
                        <div class="face back">
                            <div class="card-body card-team">
                                <h6 class="">Grado en Veterinaria</h6>
                                <h6 class="">UCLM </h6>
                                <ul class="">
                                    <li class="">Hospital Veterinario de Madrid </li>
                                    <li class="">Carrera profesional en Clínica Vet.
                                        Moncloa (Oct/2011 - Mar/2020)</li>
                                    <li class="">Curso práctico de "Ecografia Abdominal" -
                                        Univ. Autónoma de Madrid</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="team-member flip-card">
                    <div class="card">
                        <div class="card-front">
                            <img src="./images/veter.jpg" alt="Team member image">
                            <div class="avatar">
                                <img src="./images/avatar1.jpg" alt="Avatar image">
                            </div>
                            <div class="card-body">
                                <h5 class="member-name">Dr. Paco Peligros</h5>
                                <p class="menber-role">Médico Veterinária</p>
                                <p class="member-info">Medicina Interna,
                                    Radiología y Urgencias/Trauma</p>
                                <i class="fas fa-redo"></i>
                                <p> Saber
                                    mas...</p>
                            </div>
                        </div>
                        <div class="face back">
                            <div class="card-body card-team">
                                <h6 class="">Grado en Veterinaria</h6>
                                <h6 class="">UCLM </h6>
                                <ul class="">
                                    <li class="">Hospital Veterinario de Madrid </li>
                                    <li class="">Carrera profesional en Clínica Vet.
                                        Moncloa (Oct/2011 - Mar/2020)</li>
                                    <li class="">Curso práctico de "Ecografia Abdominal" -
                                        Univ. Autónoma de Madrid</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="team-member flip-card">
                    <div class="card">
                        <div class="card-front">
                            <img src="./images/vet1.jpg" alt="Team member image">
                            <div class="avatar">
                                <img src="./images/avatar1.jpg" alt="Avatar image">
                            </div>
                            <div class="card-body">
                                <h5 class="member-name">Dr. Luis Metepatas</h5>
                                <p class="menber-role">Médico Veterinário</p>
                                <p class="member-info">Medicina Interna,
                                    Radiología y Urgencias/Trauma</p>
                                <i class="fas fa-redo"></i>
                                <p>Saber
                                    mas...</p>
                            </div>
                        </div>
                        <div class="face back">
                            <div class="card-body card-team">
                                <h6 class="">Grado en Veterinaria</h6>
                                <h6 class="">UCLM </h6>
                                <ul class="">
                                    <li class="">Hospital Veterinario de Madrid </li>
                                    <li class="">Carrera profesional en Clínica Vet.
                                        Moncloa (Oct/2011 - Mar/2020)</li>
                                    <li class="">Curso práctico de "Ecografia Abdominal" -
                                        Univ. Autónoma de Madrid</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Card 4 -->

                </div>
                <!-- Card 4 -->
                <div class="team-member flip-card">
                    <div class="card">
                        <div class="card-front">
                            <img src="./images/vet1.jpg" alt="Team member image">
                            <div class="avatar">
                                <img src="./images/avatar1.jpg" alt="Avatar image">
                            </div>
                            <div class="card-body">
                                <h5 class="member-name">Dr. Paco Mataperros</h5>
                                <p class="menber-role">Médico Veterinário</p>
                                <p class="member-info">Medicina Interna,
                                    Radiología y Urgencias/Trauma</p>
                                <i class="fas fa-redo"></i>
                                <p>Saber
                                    mas...</p>
                            </div>
                        </div>
                        <div class="face back">
                            <div class="card-body card-team">
                                <h6 class="">Grado en Veterinaria</h6>
                                <h6 class="">UCLM </h6>
                                <ul class="">
                                    <li class="">Hospital Veterinario de Madrid </li>
                                    <li class="">Carrera profesional en Clínica Vet.
                                        Moncloa (Oct/2011 - Mar/2020)</li>
                                    <li class="">Curso práctico de "Ecografia Abdominal" -
                                        Univ. Autónoma de Madrid</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
        <section class="efecto_img">
            <img alt="picture" src="./images/posibles/pelu400x300.jpg" class="img_fresh">
            <img alt="picture" src="./images/posibles/radiologia-ecografia-400x300.jpg" class="img_fresh">
            <img alt="picture" src="./images/perro_collar.jpg" class="img_fresh">
            <img alt="picture" src="./images/cachorroperro.jpg" class="img_fresh">
            <img alt="picture" src="./images/veter.jpg" class="img_fresh">
            <img alt="picture" src="./images/clini2.jpg" class="img_fresh">
            <img alt="picture" src="./images/jugando.jpg" class="img_fresh">
            <img alt="picture" src="./images/sala.jpg" class="img_fresh">
        </section>
    </main>
</body>

</html>