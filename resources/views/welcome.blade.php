<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Xtend Systems - Sales Performance rankings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <style>
        @keyframes slideUpFade {
            0% {
                opacity: 0;
                transform: translateY(40px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-update {
            opacity: 0;
            animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
        }

        /* Ensure progress bars respect dynamic database width */
        .progress,
        .progress1,
        .progress2,
        .progress3,
        .progress4,
        .progress5,
        .progress6,
        .progress7,
        .progress8,
        .progress9,
        .progress10,
        .progress11 {
            height: 6px !important;
            background-color: rgba(255, 255, 255, 0.1) !important;
        }

        .progress-container {
            position: relative;
        }

        /* Custom styles to handle avatar fitting */
        .sales-table img.profile-img {
            width: 38px;
            height: 38px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .performer-box .imgsa img {
            height: 100% !important;
            object-fit: contain !important;
            width: 100% !important;
        }
    </style>
</head>

<body>

    <section class="sec-main">

        <header>
            <div class="container">
                <div class="row">
                <div class="col-md-12">
                    <div class="custom-nav">
                    <img src="{{ asset('images/logo.png') }}" class="img-fluid">
                    </div>
                </div>
                </div>
            </div>
        </header>

        <div class="container-fluid">
            <div class="row">

                <!-- Left Column: Teams & BU Heads -->
                <div class="col-md-3">
                    <div class="team-box-main">

                        @php
                            $boxClasses = ['', 'two', 'three', 'four'];
                            $progressClasses = ['progress', 'progress3', 'progress6', 'progress9'];
                        @endphp

                        @foreach ($departments as $index => $dept)
                            @php
                                $boxClass = $boxClasses[$index % 4];
                                $progressClass = $progressClasses[$index % 4];
                                $pct = $dept->dept_performance_percentage;
                            @endphp
                            <div class="team-box {{ $boxClass }} mb-3">
                                <h3>{{ strtoupper($dept->name) }}</h3>
                                <h4>{{ strtoupper($dept->head_name) }}</h4>
                                <div class="progress-container">
                                    <p>{{ $pct }}% <span>Achieved</span></p>
                                    <div class="{{ $progressClass }}"
                                         style="width: {{ min($pct, 100) }}%; background-color: #fff !important;">
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                <!-- Center Column: Leaderboards Swiper (Grouped by Roles) -->
                <div class="col-md-5">
                    <div class="swiper performer-slider2">
                        <div class="swiper-wrapper">

                            @foreach ($leaderboards as $lb)
                                <div class="swiper-slide">
                                    @foreach ($lb['tables'] as $tableIndex => $table)
                                        <div class="leaderboard {{ $tableIndex > 0 ? 'mt-3' : '' }}">
                                            <h4>{{ strtoupper($table['role']->name) }} (Benchmark {{ $lb['benchmark']->name }})</h4>
                                            <div class="table-responsive">
                                                <table class="table sales-table align-middle mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>NAME</th>
                                                            <th>TEAM</th>
                                                            <th>TARGET</th>
                                                            <th>SALES</th>
                                                            <th>ACHIEVED</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($table['salespersons'] as $sp)
                                                            @php
                                                                $avatarUrl = $sp->image_path
                                                                    ? asset($sp->image_path)
                                                                    : asset('images/default.jpg');
                                                                $barColor = 'bg-danger';
                                                                $percentClass = 'orange';
                                                                if ($sp->performance_percentage >= 90) {
                                                                    $barColor = 'bg-success';
                                                                    $percentClass = 'green';
                                                                } elseif ($sp->performance_percentage >= 75) {
                                                                    $barColor = 'bg-info';
                                                                    $percentClass = 'cyan';
                                                                } elseif ($sp->performance_percentage >= 50) {
                                                                    $barColor = 'bg-primary';
                                                                    $percentClass = 'blue';
                                                                } elseif ($sp->performance_percentage >= 35) {
                                                                    $barColor = 'bg-warning';
                                                                    $percentClass = 'yellow';
                                                                }
                                                            @endphp
                                                            <tr>
                                                                <td>
                                                                    <img src="{{ $avatarUrl }}" class="profile-img" alt="">
                                                                </td>
                                                                <td>{{ strtoupper($sp->name) }}</td>
                                                                <td>{{ strtoupper($sp->department->name ?? 'N/A') }}</td>
                                                                <td>${{ number_format($sp->total_target) }}</td>
                                                                <td>${{ number_format($sp->total_sales) }}</td>
                                                                <td>
                                                                    <div class="progress-wrapper">
                                                                        <span class="percent {{ $percentClass }}">{{ $sp->performance_percentage }}%</span>
                                                                        <div class="progress">
                                                                            <div class="progress-bar {{ $barColor }}" style="width: {{ min($sp->performance_percentage, 100) }}%;"></div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>

                <!-- Right Column: Star Performer & Giveaways -->
                <div class="col-md-4">
                    <div class="peroformer-main">
                        
                         

                        <div class="swiper performer-slider">
                            
                            <div class="swiper-wrapper">
                                

                                @forelse($starPerformers as $index => $performer)
                                
                                
                                    <div class="swiper-slide">
                                        
                                        <div id="particles-js-{{$index}}"></div> 
                                        
                                     
                                        <div class="performer-box">
                                            <div class="perform-con">
                                                <h2>{{ $performer->category_label }} <span>TEAM {{ strtoupper($performer->department->name ?? 'N/A') }}IANS</span></h2>
                                                <h3>{{ strtoupper($performer->name) }}
                                                    <span>{{ strtoupper($performer->role->name ?? 'Salesperson') }}</span>
                                                </h3>

                                                <div class="rating">
                                                    <span>
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                    </span>
                                                    <p>5.0 Rating</p>
                                                </div>

                                                <h3 class="green">${{ number_format($performer->total_target) }}
                                                    <span>BENCHMARK TARGET</span> </h3>
                                                <h3 class="bluew">${{ number_format($performer->total_sales) }}
                                                    <span>ACHIEVED SALES ({{ $performer->category_desc }})</span> </h3>
                                            </div>

                                            <div class="imgsa">
                                                @php
                                                    $performerImg = $performer->image_path
                                                        ? asset($performer->image_path)
                                                        : asset('images/bu-head.png');
                                                @endphp
                                                <img src="{{ $performerImg }}" class="img-fluid">
                                            </div>
                                        </div>
                                       
                                    </div>
                                @empty
                                    <div class="swiper-slide">
                                         <div id="particles-js"></div> 
                                         
                                       
                                        <div class="performer-box">
                                            <div class="perform-con">
                                                <h2>Star Performer <span>XTEND</span> </h2>
                                                <h3>NO RECORD <span>N/A</span> </h3>
                                            </div>
                                            <div class="imgsa">
                                                <img src="{{ asset('images/bu-head.png') }}" class="img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                @endforelse

                            </div>
                        </div>

                        <!-- Giveaways Swiper -->
                        <div class="swiper performer-slider7">
                            <div class="swiper-wrapper">
                                @php
                                    $noticeImages = $notices->filter(function($n) {
                                        return !empty($n->image_path);
                                    });
                                @endphp
                                @forelse($noticeImages as $n)
                                    <div class="swiper-slide">
                                        <div class="goft-box" style="background-image: url('{{ asset($n->image_path) }}');"></div>
                                    </div>
                                @empty
                                    <div class="swiper-slide">
                                        <div class="goft-box"></div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    
    
     
   
    <script src="http://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script> 
    <script src="http://threejs.org/examples/js/libs/stats.min.js"></script>

    <!-- Real-time WebSockets updates via Pusher -->
    <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>
    <script>
        var pusher = new Pusher('{{ env("PUSHER_APP_KEY", "f67540e46c0fa03762ae") }}', {
            cluster: '{{ env("PUSHER_APP_CLUSTER", "ap2") }}',
            forceTLS: true
        });

        var channel = pusher.subscribe('ranking-updates');
        channel.bind('ranking.updated', function(data) {
            console.log('Realtime ranking update received. Syncing view...');

            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    const oldMain = document.querySelector('.sec-main');
                    const newMain = doc.querySelector('.sec-main');
                    if (oldMain && newMain) {
                        oldMain.innerHTML = newMain.innerHTML;

                        // Apply a smooth staggered slide-up entry animation to all updated sections
                        const elementsToAnimate = oldMain.querySelectorAll('.team-box-main, .performer-slider3, .goft-box, .sales-table tbody tr, .leader-box');
                        elementsToAnimate.forEach((el, index) => {
                            el.style.opacity = '0';
                            setTimeout(() => {
                                el.classList.add('animate-update');
                            }, index * 50); // Stagger delay of 50ms per item
                        });

                        // Re-initialize swiper sliders using the globally registered lifecycle method
                        if (typeof window.initAllSliders === 'function') {
                            window.initAllSliders();
                        }
                    }
                })
                .catch(error => {
                    console.error('Realtime sync failed, falling back to full reload:', error);
                    window.location.reload();
                });
        });
    </script>
    
    
    @forelse($starPerformers as $index => $performer)
                                
<style>
    #particles-js-{{$index}} {
    position: absolute;
    z-index: 50;
    left: 0;
    right: 0;
}
</style>
                                 
                                        
                                        <script>
    particlesJS("particles-js-{{$index}}", {"particles":{"number":{"value":180,"density":{"enable":true,"value_area":552.4033491425909}},"color":{"value":"#ffffff"},"shape":{"type":"circle","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":1,"random":true,"anim":{"enable":true,"speed":1,"opacity_min":0,"sync":false}},"size":{"value":3.945738208161363,"random":true,"anim":{"enable":false,"speed":4,"size_min":0.3,"sync":false}},"line_linked":{"enable":false,"distance":150,"color":"#ffffff","opacity":0.4,"width":1},"move":{"enable":true,"speed":1,"direction":"none","random":true,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":600}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":true,"mode":"bubble"},"onclick":{"enable":true,"mode":"repulse"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":250,"size":0,"duration":2,"opacity":0,"speed":3},"repulse":{"distance":400,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true});var count_particles, stats, update; stats = new Stats; stats.setMode(0); stats.domElement.style.position = 'absolute'; stats.domElement.style.left = '0px'; stats.domElement.style.top = '0px'; document.body.appendChild(stats.domElement); count_particles = document.querySelector('.js-count-particles'); update = function() { stats.begin(); stats.end(); if (window.pJSDom[0].pJS.particles && window.pJSDom[0].pJS.particles.array) { count_particles.innerText = window.pJSDom[0].pJS.particles.array.length; } requestAnimationFrame(update); }; requestAnimationFrame(update);;
    </script>
                                    
                                       
                                   
                                @empty
                                  
                                         
                                         <script>
    particlesJS("particles-js", {"particles":{"number":{"value":180,"density":{"enable":true,"value_area":552.4033491425909}},"color":{"value":"#ffffff"},"shape":{"type":"circle","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":1,"random":true,"anim":{"enable":true,"speed":1,"opacity_min":0,"sync":false}},"size":{"value":3.945738208161363,"random":true,"anim":{"enable":false,"speed":4,"size_min":0.3,"sync":false}},"line_linked":{"enable":false,"distance":150,"color":"#ffffff","opacity":0.4,"width":1},"move":{"enable":true,"speed":1,"direction":"none","random":true,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":600}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":true,"mode":"bubble"},"onclick":{"enable":true,"mode":"repulse"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":250,"size":0,"duration":2,"opacity":0,"speed":3},"repulse":{"distance":400,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true});var count_particles, stats, update; stats = new Stats; stats.setMode(0); stats.domElement.style.position = 'absolute'; stats.domElement.style.left = '0px'; stats.domElement.style.top = '0px'; document.body.appendChild(stats.domElement); count_particles = document.querySelector('.js-count-particles'); update = function() { stats.begin(); stats.end(); if (window.pJSDom[0].pJS.particles && window.pJSDom[0].pJS.particles.array) { count_particles.innerText = window.pJSDom[0].pJS.particles.array.length; } requestAnimationFrame(update); }; requestAnimationFrame(update);;
    </script>
                                        
                                @endforelse

</body>

</html>
