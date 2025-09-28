<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a MediPro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/app.css')
    <style>
        /* Animación suave para burbujas flotando */
        @keyframes float {
            0% {
                transform: translate(0, 0) scale(1);
            }

            25% {
                transform: translate(30px, -20px) scale(1.05);
            }

            50% {
                transform: translate(-20px, 30px) scale(0.95);
            }

            75% {
                transform: translate(20px, 20px) scale(1.1);
            }

            100% {
                transform: translate(0, 0) scale(1);
            }
        }

        .bubble {
            animation: float 12s ease-in-out infinite;
        }

        .bubble:nth-child(2) {
            animation-duration: 15s;
            animation-delay: 3s;
        }

        .bubble:nth-child(3) {
            animation-duration: 18s;
            animation-delay: 6s;
        }

        .bubble:nth-child(4) {
            animation-duration: 20s;
            animation-delay: 1s;
        }
    </style>
</head>

<body class="relative bg-white text-gray-800 h-screen flex items-center justify-center overflow-hidden">

    <!-- Burbujas animadas de fondo -->
    <div class="absolute inset-0 -z-10 overflow-hidden">
        <div class="bubble absolute top-10 left-20 w-64 h-64 bg-pink-400/30 rounded-full blur-3xl"></div>
        <div class="bubble absolute bottom-20 right-32 w-72 h-72 bg-blue-400/30 rounded-full blur-3xl"></div>
        <div class="bubble absolute top-32 right-40 w-60 h-60 bg-purple-400/30 rounded-full blur-3xl"></div>
        <div class="bubble absolute bottom-16 left-28 w-52 h-52 bg-yellow-300/30 rounded-full blur-2xl"></div>
    </div>

    <!-- Contenido principal -->
    <div class="text-center max-w-md px-6">

        <!-- Texto principal -->
        <h1 class="text-5xl md:text-6xl font-extrabold tracking-wide text-blue-700 drop-shadow-sm">
            Bienvenido
        </h1>

        <!-- Subtítulo -->
        <p class="mt-4 text-xl md:text-2xl font-light text-gray-600">
            ¿Qué desea realizar hoy?
        </p>

        <div class="w-full items-center flex gap-3 p-2 justify-center">
            <a href="Ventana"
                class="cursor-pointer mt-6 bg-gradient-to-br from-white via-blue-50 to-blue-100 rounded-xl shadow-md hover:shadow-xl transition-all duration-500 hover:scale-105 p-4 flex flex-col items-center justify-center space-y-2 w-32 h-32">

                <!-- Icono -->
                <div
                    class="p-3 rounded-full bg-green-600 text-white shadow-md transform transition-transform duration-500 hover:rotate-6 hover:scale-110">
                    <img src="{{ asset('img/ventana.svg') }}" class="w-10 h-10" alt="Ventana">
                </div>

                <!-- Texto -->
                <span class="text-sm font-medium text-gray-700">Ventana</span>
            </a>

            <a disabled
                class="cursor-pointer mt-6 bg-gradient-to-br from-white via-blue-50 to-blue-100 rounded-xl shadow-md hover:shadow-xl transition-all duration-500 hover:scale-105 p-4 flex flex-col items-center justify-center space-y-2 w-32 h-32">

                <!-- Icono -->
                <div
                    class="p-3 rounded-full bg-orange-600 text-white shadow-md transform transition-transform duration-500 hover:rotate-6 hover:scale-110">
                    <img src="{{ asset('img/puerta.svg') }}" class="w-10 h-10" alt="Ventana">
                </div>

                <!-- Texto -->
                <span class="text-sm font-medium text-gray-700">Puerta</span>
            </a>

        </div>


        <!-- Pie de autor -->
        <p class="mt-12 text-xs text-gray-400">
            Desarrollado y creado por <span class="font-semibold text-gray-500">Jhon Rosales</span>
        </p>
    </div>

</body>

</html>
