<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SIK PonPes (Sistem Informasi Keuangan Pondok Pesantren)</title>

    {{-- favicon --}}
    <link rel="icon" href="/images/favicon.svg" type="image/svg+xml">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">

    {{-- navigation --}}
    <nav class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 left-0 border-b border-gray-200 dark:border-gray-600">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="https://flowbite.com/" class="flex items-center">
                <span class="self-center text-2xl font-extrabold whitespace-nowrap dark:text-white text-green-700">SIK
                    Ponpes</span>
            </a>
            <div class="flex md:order-2">
                @auth('web')
                    <a href="/dashboard/login"
                        class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 text-center mr-3 md:mr-0 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Dashboard</a>
                @endauth
                @guest
                    <a href="/dashboard/login"
                        class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 text-center mr-3 md:mr-0 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Login</a>
                @endguest
                <button data-collapse-toggle="navbar-sticky" type="button"
                    class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                    aria-controls="navbar-sticky" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                </button>
            </div>
            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
                <ul
                    class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                    <li>
                        <a href="#"
                            class="block py-2 pl-3 pr-4 text-white bg-green-700 rounded md:bg-transparent md:text-green-700 md:p-0 md:dark:text-green-500"
                            aria-current="page">Home</a>
                    </li>
                    <li>
                        <a href="#about"
                            class="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 md:p-0 md:dark:hover:text-green-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">About</a>
                    </li>
                    <li>
                        <a href="#features"
                            class="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 md:p-0 md:dark:hover:text-green-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Features</a>
                    </li>
                    <li>
                        <a href="#testimoni"
                            class="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 md:p-0 md:dark:hover:text-green-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Testimonials</a>
                    </li>
                    <li>
                        <a href="#faqs"
                            class="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 md:p-0 md:dark:hover:text-green-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Faqs</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- hero --}}
    <section
        class="bg-center bg-no-repeat bg-[url('https://gcdnb.pbrd.co/images/HaBxvOKdwPnN.webp')] bg-green-900 bg-blend-multiply">
        <div class="px-4 mx-auto max-w-screen-xl text-center py-24 lg:py-56">
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-white md:text-5xl lg:text-6xl">
                Kelola Keuangan Pondok Pesantren dengan Mudah</h1>
            <p class="mb-8 text-lg font-normal text-gray-300 lg:text-xl sm:px-16 lg:px-48">Tidak Perlu Khawatir untuk
                manajemen dan pengelolaan keuangan Pondok Pesantren, dengan SIK PonPes semua jadi Mudah.</p>
            <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
                <a href="/dashboard/login"
                    class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 dark:focus:ring-green-900">
                    Mulai Sekarang
                    <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 5h12m0 0L9 1m4 4L9 9" />
                    </svg>
                </a>
                <a href="/#about"
                    class="inline-flex justify-center hover:text-gray-900 items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg border border-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-400">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </section>

    {{-- about --}}

    <section class="bg-gradient-to-b from-green-950 to-green-500" id="about">
        <div class="px-4 mx-auto max-w-screen-xl py-24 sm:px-6 lg:px-8">
            {{-- <h2 class="text-center text-5xl font-extrabold mb-24 text-white">Tentang SIK PonPes</h2> --}}
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-2">
                <div class="space-y-4">
                    <h2 class="text-4xl font-extrabold tracking-tight text-white sm:text-4xl">Sistem Informasi Keuangan
                        Pondok Pesantren</h2>
                    <p class="text-xl text-gray-100">SIK PonPes (Sistem Informasi Keuangan Pondok Pesantren) merupakan
                        Platform pengelolaan dan pembuatan laporan keuangan yang dikhususkan untuk Pondok Pesantren.</p>
                </div>
                <div class="space-y-4">
                    <img src="{{ asset('images/about.png') }}" alt="">
                </div>
            </div>
        </div>
    </section>

    {{-- benefits --}}



    {{-- features --}}
    <section class="bg-gradient-to-b from-green-500 to-gray-50 pt-24" id="features">
        <h2 class="text-4xl font-extrabold text-center text-white">Fitur - fitur SIK Ponpes</h2>
        <div class="text-center">
            <span class="inline-block w-1 h-1 rounded-full bg-white ml-1"></span>
            <span class="inline-block w-32 h-1 rounded-full bg-white ml-1"></span>
            <span class="inline-block w-4 h-1 rounded-full bg-white"></span>
            <span class="inline-block w-3 h-1 rounded-full bg-white ml-1"></span>
            <span class="inline-block w-1 h-1 rounded-full bg-white ml-1"></span>
        </div>
        <div class="px-4 mx-auto max-w-screen-xl py-24 sm:px-6 lg:px-8">
            <div class="-mx-4 flex flex-wrap">
                <div class="w-full px-4 md:w-1/2 lg:w-1/3">
                    <div
                        class="mb-9 bg-white rounded-xl py-8 px-7 shadow-md transition-all hover:shadow-lg sm:p-9 lg:px-6 xl:px-9">
                        <div class="mx-auto mb-3 inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="green" class="w-16 h-16">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="mb-4 text-xl font-bold text-black sm:text-2xl lg:text-xl xl:text-2xl">Kelola
                                Akun
                            </h3>
                            <p class="text-base font-medium text-body-color">Pengelolaan akun utama atau parent
                                account, classification account, dan account keuangan</p>
                        </div>
                    </div>
                </div>



                <div class="w-full px-4 md:w-1/2 lg:w-1/3">
                    <div
                        class="mb-9 bg-white rounded-xl py-8 px-7 shadow-md transition-all hover:shadow-lg sm:p-9 lg:px-6 xl:px-9">
                        <div class="mx-auto mb-3 inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="green" class="w-16 h-16">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="mb-4 text-xl font-bold text-black sm:text-2xl lg:text-xl xl:text-2xl">
                                Buku Besar
                            </h3>
                            <p class="text-base font-medium text-body-color">Pengelolaan Buku Besar keuangan Pondok
                                Pesantren berdasarkan akun dan waktu.</p>
                        </div>
                    </div>
                </div>



                <div class="w-full px-4 md:w-1/2 lg:w-1/3">
                    <div
                        class="mb-9 bg-white rounded-xl py-8 px-7 shadow-md transition-all hover:shadow-lg sm:p-9 lg:px-6 xl:px-9">
                        <div class="mx-auto mb-3 inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="green" class="w-16 h-16">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>

                        </div>
                        <div>
                            <h3 class="mb-4 text-xl font-bold text-black sm:text-2xl lg:text-xl xl:text-2xl">Jurnal
                                Umum
                            </h3>
                            <p class="text-base font-medium text-body-color">Pencatatan transaksi dengan Jurnal Umum
                                yang terintegrasi dengan akun</p>
                        </div>
                    </div>
                </div>



                <div class="w-full px-4 md:w-1/2 lg:w-1/3">
                    <div
                        class="mb-9 bg-white rounded-xl py-8 px-7 shadow-md transition-all hover:shadow-lg sm:p-9 lg:px-6 xl:px-9">
                        <div class="mx-auto mb-3 inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="green" class="w-16 h-16">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0012 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 01-2.031.352 5.988 5.988 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 01-2.031.352 5.989 5.989 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="mb-4 text-xl font-bold text-black sm:text-2xl lg:text-xl xl:text-2xl">Neraca
                            </h3>
                            <p class="text-base font-medium text-body-color">Manajemen Neraca Awal, Neraca Lajur, dan
                                Neraca berdasarkan waktu</p>
                        </div>
                    </div>
                </div>


                <div class="w-full px-4 md:w-1/2 lg:w-1/3">
                    <div
                        class="mb-9 bg-white rounded-xl py-8 px-7 shadow-md transition-all hover:shadow-lg sm:p-9 lg:px-6 xl:px-9">
                        <div class="mx-auto mb-7 inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="green" class="w-16 h-16">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="mb-4 text-xl font-bold text-black sm:text-2xl lg:text-xl xl:text-2xl">
                                Laporan Keuangan</h3>
                            <p class="text-base font-medium text-body-color">Laporan Aktivitas, Laporan Posisi
                                keuangan, Laporan Perubahan ekuitas, dan Laporan arus kas</p>
                        </div>
                    </div>
                </div>


                <div class="w-full px-4 md:w-1/2 lg:w-1/3">
                    <div
                        class="mb-9 bg-white rounded-xl py-8 px-7 shadow-md transition-all hover:shadow-lg sm:p-9 lg:px-6 xl:px-9">
                        <div class="mx-auto mb-3 inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="green" class="w-16 h-16">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="mb-4 text-xl font-bold text-black sm:text-2xl lg:text-xl xl:text-2xl">Manajemen
                                Internal
                            </h3>
                            <p class="text-base font-medium text-body-color">Manajemen data profil multi-pesantren dan
                                multi-karyawan untuk tiap pesantren.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- testimoni --}}
    <section id="testimoni">
        <div class="min-w-screen min-h-screen bg-gradient-to-b from-gray-50 to-white flex items-center justify-center">
            <div class="w-full px-5 py-16 md:py-24">
                <div class="w-full max-w-6xl mx-auto">
                    <div class="text-center max-w-xl mx-auto">
                        <h2 class="text-5xl font-extrabold mb-5 ">Testimoni</h2>
                        <h3 class="text-lg mb-5">Pendapat para pengguna setelah menggunakan SIK PonPes</h3>
                        <div class="text-center mb-10">
                            <span class="inline-block w-1 h-1 rounded-full bg-green-500 ml-1"></span>
                            <span class="inline-block w-3 h-1 rounded-full bg-green-500 ml-1"></span>
                            <span class="inline-block w-40 h-1 rounded-full bg-green-500"></span>
                            <span class="inline-block w-3 h-1 rounded-full bg-green-500 ml-1"></span>
                            <span class="inline-block w-1 h-1 rounded-full bg-green-500 ml-1"></span>
                        </div>
                    </div>
                    <div class="-mx-3 md:flex items-start">
                        <div class="px-3 md:w-1/3">
                            <div
                                class="w-full mx-auto rounded-lg bg-white border border-gray-200 p-5 text-gray-800 font-light mb-6">
                                <div class="w-full flex mb-4 items-center">
                                    <div
                                        class="overflow-hidden rounded-full w-10 h-10 bg-gray-50 border border-gray-200">
                                        <img src="https://i.pravatar.cc/100?img=1" alt="">
                                    </div>
                                    <div class="flex-grow pl-3">
                                        <h6 class="font-bold text-sm">Kenzie Edgar.</h6>
                                        <p class="font-semibold text-xs">Mudir Pesantren Gontor</p>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <p class="text-sm leading-tight"><span
                                            class="text-lg leading-none italic font-bold text-gray-400 mr-1">"</span>Lorem
                                        ipsum dolor sit amet consectetur adipisicing elit. Quos sunt ratione dolor
                                        exercitationem minima quas itaque saepe quasi architecto vel! Accusantium, vero
                                        sint recusandae cum tempora nemo commodi soluta deleniti.<span
                                            class="text-lg leading-none italic font-bold text-gray-400 ml-1">"</span>
                                    </p>
                                </div>
                            </div>
                            <div
                                class="w-full mx-auto rounded-lg bg-white border border-gray-200 p-5 text-gray-800 font-light mb-6">
                                <div class="w-full flex mb-4 items-center">
                                    <div
                                        class="overflow-hidden rounded-full w-10 h-10 bg-gray-50 border border-gray-200">
                                        <img src="https://i.pravatar.cc/100?img=2" alt="">
                                    </div>
                                    <div class="flex-grow pl-3">
                                        <h6 class="font-bold text-sm  text-gray-600">Stevie Tifft.</h6>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <p class="text-sm leading-tight"><span
                                            class="text-lg leading-none italic font-bold text-gray-400 mr-1">"</span>Lorem
                                        ipsum, dolor sit amet, consectetur adipisicing elit. Dolore quod necessitatibus,
                                        labore sapiente, est, dignissimos ullam error ipsam sint quam tempora vel.<span
                                            class="text-lg leading-none italic font-bold text-gray-400 ml-1">"</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="px-3 md:w-1/3">
                            <div
                                class="w-full mx-auto rounded-lg bg-white border border-gray-200 p-5 text-gray-800 font-light mb-6">
                                <div class="w-full flex mb-4 items-center">
                                    <div
                                        class="overflow-hidden rounded-full w-10 h-10 bg-gray-50 border border-gray-200">
                                        <img src="https://i.pravatar.cc/100?img=3" alt="">
                                    </div>
                                    <div class="flex-grow pl-3">
                                        <h6 class="font-bold text-sm  text-gray-600">Tommie Ewart.</h6>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <p class="text-sm leading-tight"><span
                                            class="text-lg leading-none italic font-bold text-gray-400 mr-1">"</span>Lorem
                                        ipsum dolor sit amet, consectetur adipisicing elit. Vitae, obcaecati ullam
                                        excepturi dicta error deleniti sequi.<span
                                            class="text-lg leading-none italic font-bold text-gray-400 ml-1">"</span>
                                    </p>
                                </div>
                            </div>
                            <div
                                class="w-full mx-auto rounded-lg bg-white border border-gray-200 p-5 text-gray-800 font-light mb-6">
                                <div class="w-full flex mb-4 items-center">
                                    <div
                                        class="overflow-hidden rounded-full w-10 h-10 bg-gray-50 border border-gray-200">
                                        <img src="https://i.pravatar.cc/100?img=4" alt="">
                                    </div>
                                    <div class="flex-grow pl-3">
                                        <h6 class="font-bold text-sm  text-gray-600">Charlie Howse.</h6>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <p class="text-sm leading-tight"><span
                                            class="text-lg leading-none italic font-bold text-gray-400 mr-1">"</span>Lorem
                                        ipsum dolor sit amet consectetur adipisicing elit. Architecto inventore
                                        voluptatum nostrum atque, corrupti, vitae esse id accusamus dignissimos neque
                                        reprehenderit natus, hic sequi itaque dicta nisi voluptatem! Culpa, iusto.<span
                                            class="text-lg leading-none italic font-bold text-gray-400 ml-1">"</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="px-3 md:w-1/3">
                            <div
                                class="w-full mx-auto rounded-lg bg-white border border-gray-200 p-5 text-gray-800 font-light mb-6">
                                <div class="w-full flex mb-4 items-center">
                                    <div
                                        class="overflow-hidden rounded-full w-10 h-10 bg-gray-50 border border-gray-200">
                                        <img src="https://i.pravatar.cc/100?img=5" alt="">
                                    </div>
                                    <div class="flex-grow pl-3">
                                        <h6 class="font-bold text-sm  text-gray-600">Nevada Herbertson.</h6>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <p class="text-sm leading-tight"><span
                                            class="text-lg leading-none italic font-bold text-gray-400 mr-1">"</span>Lorem
                                        ipsum dolor sit amet consectetur adipisicing elit. Nobis, voluptatem porro
                                        obcaecati dicta, quibusdam sunt ipsum, laboriosam nostrum facere exercitationem
                                        pariatur deserunt tempora molestiae assumenda nesciunt alias eius? Illo,
                                        autem!<span
                                            class="text-lg leading-none italic font-bold text-gray-400 ml-1">"</span>
                                    </p>
                                </div>
                            </div>
                            <div
                                class="w-full mx-auto rounded-lg bg-white border border-gray-200 p-5 text-gray-800 font-light mb-6">
                                <div class="w-full flex mb-4 items-center">
                                    <div
                                        class="overflow-hidden rounded-full w-10 h-10 bg-gray-50 border border-gray-200">
                                        <img src="https://i.pravatar.cc/100?img=6" alt="">
                                    </div>
                                    <div class="flex-grow pl-3">
                                        <h6 class="font-bold text-sm  text-gray-600">Kris Stanton.</h6>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <p class="text-sm leading-tight"><span
                                            class="text-lg leading-none italic font-bold text-gray-400 mr-1">"</span>Lorem
                                        ipsum dolor sit amet consectetur adipisicing elit. Voluptatem iusto, explicabo,
                                        cupiditate quas totam!<span
                                            class="text-lg leading-none italic font-bold text-gray-400 ml-1">"</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- faq --}}
    <section id="faqs" class="bg-white">
        <div class="px-4 mx-auto max-w-screen-xl py-24 sm:px-6 lg:px-8">
            <h1 class="text-center text-4xl font-extrabold mb-12">Sering Ditanyakan</h1>
            <div id="accordion-flush" data-accordion="collapse"
                data-active-classes="bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                data-inactive-classes="text-gray-500 dark:text-gray-400">
                <h2 id="accordion-flush-heading-1">
                    <button type="button"
                        class="flex items-center justify-between w-full py-5 font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400"
                        data-accordion-target="#accordion-flush-body-1" aria-expanded="true"
                        aria-controls="accordion-flush-body-1">
                        <span>Apakah SIK Ponpes Gratis?</span>
                        <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M9 5 5 1 1 5" />
                        </svg>
                    </button>
                </h2>
                <div id="accordion-flush-body-1" class="hidden" aria-labelledby="accordion-flush-heading-1">
                    <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                        <p class="mb-2 text-gray-500 dark:text-gray-400">Sampai saat ini SIK Ponpes masih gratis dan
                            bebas untuk digunakan untuk kepentingan pondok pesantren</p>
                        {{-- <p class="text-gray-500 dark:text-gray-400">Check out this guide to learn how to <a
                                href="/docs/getting-started/introduction/"
                                class="text-green-600 dark:text-green-500 hover:underline">get started</a> and start
                            developing websites even faster with components on top of Tailwind CSS.</p> --}}
                    </div>
                </div>
                <h2 id="accordion-flush-heading-2">
                    <button type="button"
                        class="flex items-center justify-between w-full py-5 font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400"
                        data-accordion-target="#accordion-flush-body-2" aria-expanded="false"
                        aria-controls="accordion-flush-body-2">
                        <span>Kenapa harus pakai SIK Ponpes?</span>
                        <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M9 5 5 1 1 5" />
                        </svg>
                    </button>
                </h2>
                <div id="accordion-flush-body-2" class="hidden" aria-labelledby="accordion-flush-heading-2">
                    <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                        <p class="mb-2 text-gray-500 dark:text-gray-400">
                            Dengan menggunakan SIK Ponpes maka akan membuat pengelolaan keuangan pesantren anda semakin
                            mudah karena sudah terdigitalisasi</p>
                        {{-- <p class="text-gray-500 dark:text-gray-400">Check out the <a
                                href="https://flowbite.com/figma/"
                                class="text-green-600 dark:text-green-500 hover:underline">Figma design system</a>
                            based
                            on the utility classes from Tailwind CSS and components from Flowbite.</p> --}}
                    </div>
                </div>
                <h2 id="accordion-flush-heading-3">
                    <button type="button"
                        class="flex items-center justify-between w-full py-5 font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400"
                        data-accordion-target="#accordion-flush-body-3" aria-expanded="false"
                        aria-controls="accordion-flush-body-3">
                        <span>Apakah data saya aman di dalam SIK Ponpes?</span>
                        <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M9 5 5 1 1 5" />
                        </svg>
                    </button>
                </h2>
                <div id="accordion-flush-body-3" class="hidden" aria-labelledby="accordion-flush-heading-3">
                    <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                        <p class="mb-2 text-gray-500 dark:text-gray-400">Tentunya data anda aman tersimpan di database
                            SIK Ponpes yang sudah terproteksi di layanan hosting yang terpercaya.</p>
                        {{-- <p class="mb-2 text-gray-500 dark:text-gray-400">However, we actually recommend using both
                            Flowbite, Flowbite Pro, and even Tailwind UI as there is no technical reason stopping you
                            from using the best of two worlds.</p>
                        <p class="mb-2 text-gray-500 dark:text-gray-400">Learn more about these technologies:</p>
                        <ul class="pl-5 text-gray-500 list-disc dark:text-gray-400">
                            <li><a href="https://flowbite.com/pro/"
                                    class="text-green-600 dark:text-green-500 hover:underline">Flowbite Pro</a></li>
                            <li><a href="https://tailwindui.com/" rel="nofollow"
                                    class="text-green-600 dark:text-green-500 hover:underline">Tailwind UI</a></li>
                        </ul> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>




    {{-- footer --}}

    <footer class="bg-green-600 dark:bg-gray-900">
        <div class="mx-auto w-full max-w-screen-xl p-4 py-6 lg:py-8">
            <div class="md:flex justify-between">
                <div>
                    <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">SIKPonPes</span>
                </div>
                <div class="my-12 md:my-0">
                    <h2 class="mb-4 text-lg font-bold text-white">Alamat Kami</h2>
                    <p class="text-white leading-9">Gedung SV UGM, Sekip Unit <br> 1 Jl. Persatuan, Caturtunggal
                        <br>Daerah
                        Istimewa Yogyakarta
                    </p>
                </div>
                <div class="my-12 md:my-0">
                    <h2 class="mb-4 text-lg font-bold text-white">Kontak Kami</h2>
                    <ul class="text-white font-medium">
                        <li class="mb-4">
                            <a href="mailto:irkham@ugm.ac.id" class="hover:underline ">irkham@ugm.ac.id</a>
                        </li>
                        <li>
                            <a href="mailto:faiz@ugm.ac.id" class="hover:underline">faiz@ugm.ac.id</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="md:flex md:justify-end">
                <div class="mb-6 md:mb-0">
                    <a href="https://flowbite.com/" class="flex items-center">

                    </a>
                </div>
                <div class="grid grid-cols-1 gap-8 sm:gap-6 sm:grid-cols-2">



                </div>
            </div>
            <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
            <div class="sm:flex sm:items-center sm:justify-between">
                <span class="text-sm text-white sm:text-center">© 2023 <a href="https://sikponpes.com/"
                        class="hover:underline">Tim Pengembang SikPonPes™</a>. All Rights Reserved.
                </span>
            </div>
        </div>
    </footer>





</body>

</html>
