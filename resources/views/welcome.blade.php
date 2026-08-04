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
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
</head>

<body>

    <section class="sec-main">
        <div class="top-marquee">
            <div class="top-marquee-content" id="dynamic-marquee">
                ⭐ WAITING FOR LIVE UPDATES &nbsp;&nbsp; • &nbsp;&nbsp; 🚀 KEEP CLOSING
            </div>
        </div>
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

                                {{-- Benchmark (Titan/Legend/etc) Slide --}}
                                <div class="swiper-slide">

                                    {{-- Front Sale --}}
                                    <div class="leaderboard titan"
                                        style="background: url('{{ asset($lb['benchmark']->front_sale_background) }}')">

                                        <div class="ranking-num">

                                            <img src="{{ asset($lb['benchmark']->front_sale_logo) }}" class="img-fluid">

                                            <div class="rankunf-con">

                                                <h3>{{ $lb['benchmark']->name }}</h3>

                                                <p>{{ $lb['benchmark']->front_sale_text }}</p>

                                                <hr>

                                                <h4>Front Sale</h4>

                                                {{-- <h1>${{ number_format($lb['benchmark']->front_sale_value) }}</h1> --}}
                                                <h1>${{ rtrim(rtrim(number_format($lb['benchmark']->front_sale_value / 1000, 1), '0'), '.') }}K
                                                </h1>

                                                <p class="text-cont">Monthly Target</p>

                                            </div>

                                        </div>

                                    </div>

                                    {{-- Upsell --}}
                                    <div class="leaderboard titan mt-3"
                                        style="background: url('{{ asset($lb['benchmark']->upsell_background) }}')">

                                        <div class="ranking-num">

                                            <img src="{{ asset($lb['benchmark']->upsell_logo) }}" class="img-fluid">

                                            <div class="rankunf-con">

                                                <h3>{{ $lb['benchmark']->name }}</h3>

                                                <p>{{ $lb['benchmark']->upsell_text }}</p>

                                                <hr>

                                                <h4>Upsell</h4>

                                                {{-- <h1>${{ number_format($lb['benchmark']->upsell_value) }}</h1> --}}
                                                <h1>${{ rtrim(rtrim(number_format($lb['benchmark']->upsell_value / 1000, 1), '0'), '.') }}K
                                                </h1>

                                                <p class="text-cont">Monthly Target</p>

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                {{-- Leaderboard Slide --}}
                                <div class="swiper-slide">

                                    @foreach ($lb['tables'] as $tableIndex => $table)
                                        <div class="leaderboard {{ $tableIndex > 0 ? 'mt-3' : '' }}">

                                            <h4>
                                                {{ strtoupper($table['role']->name) }}
                                                ({{ $lb['benchmark']->name }} -
                                                ${{ number_format($table['target_value']) }})
                                            </h4>

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

                                                        @foreach ($table['salespersons'] as $index => $sp)
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

                                                                // Top 3 Rank
                                                                $rankClass = '';

                                                                if ($index == 0) {
                                                                    $rankClass = 'gold-rank';
                                                                } elseif ($index == 1) {
                                                                    $rankClass = 'silver-rank';
                                                                } elseif ($index == 2) {
                                                                    $rankClass = 'bronze-rank';
                                                                }
                                                            @endphp

                                                            <tr class="{{ $rankClass }}"
                                                                data-id="{{ $sp->id }}"
                                                                data-name="{{ strtoupper($sp->name) }}"
                                                                data-league="{{ $lb['benchmark']->name }}"
                                                                data-contest="{{ strtoupper($table['role']->name) }}"
                                                                data-percent="{{ $sp->performance_percentage }}"
                                                                data-sales="{{ $sp->total_sales }}"
                                                                data-achieved="{{ $sp->performance_percentage >= 100 ? 1 : 0 }}"
                                                                data-image="{{ $sp->image ? asset($sp->image_path) : asset('images/default.jpg') }}">

                                                                <td>
                                                                    <div class="d-flex align-items-center">

                                                                        <img src="{{ $avatarUrl }}"
                                                                            class="profile-img" alt="">

                                                                        @if ($index == 0)
                                                                            <span class="rank-medal">🥇</span>
                                                                        @elseif ($index == 1)
                                                                            <span class="rank-medal">🥈</span>
                                                                        @elseif ($index == 2)
                                                                            <span class="rank-medal">🥉</span>
                                                                        @endif
                                                                    </div>
                                                                </td>

                                                                <td>{{ strtoupper($sp->name) }}</td>

                                                                <td>{{ strtoupper($sp->department->name ?? 'N/A') }}
                                                                </td>

                                                                <td>${{ number_format($sp->total_target) }}</td>

                                                                <td>${{ number_format($sp->total_sales) }}</td>

                                                                <td>

                                                                    <div class="progress-wrapper">

                                                                        <span class="percent {{ $percentClass }}">
                                                                            {{ $sp->performance_percentage }}<b>%</b>
                                                                        </span>

                                                                        <div class="progress">
                                                                            <div class="progress-bar {{ $barColor }}"
                                                                                style="width: {{ min($sp->performance_percentage, 100) }}%;">
                                                                            </div>
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

                                        <div id="particles-js-{{ $index }}"></div>


                                        <div class="performer-box">
                                            <div class="perform-con">
                                                <h2>{{ $performer->category_label }} <span>TEAM
                                                        {{ strtoupper($performer->department->name ?? 'N/A') }}IANS</span>
                                                </h2>
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
                                                    <span>BENCHMARK TARGET</span>
                                                </h3>
                                                <h3 class="bluew">${{ number_format($performer->total_sales) }}
                                                    <span>ACHIEVED SALES ({{ $performer->category_desc }})</span>
                                                </h3>
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
                                    $noticeImages = $notices->filter(function ($n) {
                                        return !empty($n->image_path);
                                    });
                                @endphp
                                @forelse($noticeImages as $n)
                                    <div class="swiper-slide">
                                        <div class="goft-box"
                                            style="background-image: url('{{ asset($n->image_path) }}');"></div>
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
    @php
        $messages = array_filter([
            $salesText,
            $targetCompletedText,
            $topPerformerText,
            $topDeptText
        ]);
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/stats.js/r17/Stats.min.js"></script>
    <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>

    <!-- Global App Configuration — uses config() not env() to support cached deployments -->
    <script>
        window.SoundPaths = {
            leaderboard: "{{ asset('sounds/sale-update.mp3') }}",
            milestone: "{{ asset('sounds/universfield-achievement-unlock-243762.mp3') }}"
        };

        window.AppConfig = {
            pusherKey: '{{ config('broadcasting.connections.pusher.key', 'f67540e46c0fa03762ae') }}',
            pusherCluster: '{{ config('broadcasting.connections.pusher.options.cluster', 'ap2') }}'
        };

        Pusher.logToConsole = true;

        const pusher = new Pusher(window.AppConfig.pusherKey, {
            cluster: window.AppConfig.pusherCluster,
            forceTLS: true
        });

        const channel = pusher.subscribe('ranking-updates');

        channel.bind('pusher:subscription_succeeded', () => {
            console.log("Subscribed");
        });

        channel.bind('ranking.updated', (data) => {
            console.log("Event Received", data);
        });

        window.InitialMarqueeMessages = @json($messages);
    </script>
    <script src="{{ asset('js/script.js') }}"></script>
    <div id="achievement-popup">

        <div class="achievement-card">

            <div class="achievement-header"></div>

            <div class="achievement-body">

                <img id="achievement-image" class="achievement-avatar" src="/images/default.jpg">

                <div>

                    <div class="achievement-title">
                        🏆 Achievement Unlocked
                    </div>

                    <div id="achievement-name" class="achievement-name">
                    </div>

                    <div id="achievement-role" class="achievement-role">
                    </div>

                    <div class="achievement-badge">
                        TARGET ACHIEVED • 100%
                    </div>

                    <div class="progress-wrap">
                        <span></span>
                    </div>

                </div>

            </div>

        </div>

    </div>
    <audio id="achievement-sound" preload="auto">
        <source src="{{ asset('sounds/achievement.mp3') }}" type="audio/mpeg">
    </audio>
</body>

</html>
