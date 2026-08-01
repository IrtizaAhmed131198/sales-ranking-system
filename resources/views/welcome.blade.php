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

        /*new css titan*/

        .ranking-num {
            display: flex;
            gap: 19px;
        }

        .ranking-num h3 {
            color: white;
            font-size: 35px;
        }

        .ranking-num p {
            color: white;
            font-size: 15px;
            font-family: 'Lufga-light';
            margin-bottom: 0px;
        }



        .rankunf-con hr {
            color: white;
        }

        .ranking-num h4 {
            color: white;
            font-size: 22px;
            font-family: 'Lufga-light';
            background: transparent;
            margin-bottom: 0px;
            padding: 0px;
        }



        .ranking-num h1 {
            color: #ff8500;
            font-size: 59px;
            margin: 5px 0px 0px;
            font-family: 'Lufga-Regular';
        }



        p.text-cont {
            font-size: 18px;
            margin-top: -3px;
        }

        .leaderboard.titan {
            background: url(../images/titanbg.png);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            padding: 53px 27px;
        }

        .ranking-num img {
            width: 227px;
            height: auto;
            object-fit: contain;
        }

        .rank-medal {
            font-size: 20px;
            margin-right: 10px;
        }

        .gold-rank {
            background: linear-gradient(90deg, #FFD700, #FFF6BF);
            border-left: 6px solid #d4af37;
            font-weight: 700;
        }

        .silver-rank {
            background: linear-gradient(90deg, #C0C0C0, #F5F5F5);
            border-left: 6px solid #9e9e9e;
            font-weight: 700;
        }

        .bronze-rank {
            background: linear-gradient(90deg, #CD7F32, #F6D3B0);
            border-left: 6px solid #a65a2d;
            font-weight: 700;
        }

        .gold-rank .profile-img {
            border: 3px solid #FFD700;
            box-shadow: 0 0 15px rgba(255, 215, 0, .7);
        }

        .silver-rank .profile-img {
            border: 3px solid #C0C0C0;
            box-shadow: 0 0 15px rgba(192, 192, 192, .7);
        }

        .bronze-rank .profile-img {
            border: 3px solid #CD7F32;
            box-shadow: 0 0 15px rgba(205, 127, 50, .7);
        }

        .gold-rank td,
        .silver-rank td,
        .bronze-rank td {
            vertical-align: middle;
        }

        #achievement-popup {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .75);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 999999;
        }

        .achievement-content {
            background: #28a745;
            color: #fff;
            padding: 40px 70px;
            border-radius: 20px;
            text-align: center;
            animation: popupScale .5s ease;
            box-shadow: 0 0 30px rgba(0, 0, 0, .4);
        }

        .achievement-content h1 {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .achievement-content h2 {
            font-size: 36px;
            margin: 0;
        }

        @keyframes popupScale {
            from {
                transform: scale(.4);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        div[id^="particles-js"],
        #particles-js {
            position: absolute;
            z-index: 50;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
        }

        #achievement-popup {
            position: fixed;
            inset: 0;
            display: none;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, .55);
            backdrop-filter: blur(10px);
            z-index: 999999;
            animation: fadeIn .4s ease;
        }

        .achievement-card {
            width: 520px;
            max-width: 90%;
            padding: 40px 30px;
            text-align: center;
            border-radius: 25px;
            background: linear-gradient(90deg, #6b0187 38%, #24066e 100%);
            border: 2px solid rgba(255, 215, 0, .5);
            box-shadow: 0 0 25px rgba(255, 215, 0, .35), 0 0 80px rgba(0, 180, 255, .15);
            animation: popupScale .5s ease;
        }

        .achievement-icon {

            width: 110px;
            height: 110px;
            margin: auto;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 60px;

            background: linear-gradient(#FFD700, #ffb300);

            box-shadow:
                0 0 30px gold,
                inset 0 0 20px rgba(255, 255, 255, .4);

            animation: rotateGlow 3s infinite linear;
        }

        .achievement-title {

            margin-top: 25px;
            font-size: 34px;
            font-weight: 900;
            color: #FFD700;
            letter-spacing: 3px;
            text-transform: uppercase;

            text-shadow:
                0 0 15px gold,
                0 0 25px gold;
        }

        .achievement-subtitle {

            margin-top: 10px;
            font-size: 20px;
            color: #ffffff;
            opacity: .9;
        }

        .achievement-name {

            margin-top: 30px;
            font-size: 32px;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.5;

            text-shadow:
                0 0 12px #ffffff;
        }

        .achievement-name small {

            display: block;
            margin-top: 10px;
            color: #ffffff;
            font-size: 18px;
            opacity: .85;
        }

        .achievement-footer {

            margin-top: 35px;
            font-size: 22px;
            font-weight: 700;
            color: #FFD700;
        }

        @keyframes popupScale {

            0% {
                transform: scale(.6);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }

        }

        @keyframes rotateGlow {

            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }

        }

        @keyframes fadeIn {

            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }

        }

        .achievement-popup {
            position: fixed;
            inset: 0;
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 999999;
            background: rgba(0, 0, 0, .55);
            backdrop-filter: blur(8px);
        }

        .popup-card {

            display: flex;
            align-items: center;
            gap: 25px;

            min-width: 520px;

            padding: 30px 35px;

            border-radius: 24px;

            background: linear-gradient(135deg, #0d1b2a, #1b263b, #415a77);

            border: 3px solid gold;

            box-shadow:
                0 0 25px rgba(255, 215, 0, .6),
                0 20px 80px rgba(0, 0, 0, .5);

            animation: popupScale .45s ease;
        }

        .achievement-avatar {

            width: 120px;
            height: 120px;

            border-radius: 50%;
            object-fit: cover;

            border: 5px solid gold;

            box-shadow: 0 0 25px rgba(255, 215, 0, .8);
        }

        .popup-content {

            display: flex;
            flex-direction: column;
        }

        .popup-title {

            color: #FFD700;

            font-size: 18px;

            font-weight: 700;

            letter-spacing: 3px;

            margin-bottom: 10px;
        }

        #achievement-name {

            color: #fff;

            font-size: 34px;

            font-weight: 800;

            line-height: 1.2;
        }

        #achievement-name small {

            display: block;

            margin-top: 12px;

            color: #d6d6d6;

            font-size: 20px;

            font-weight: 500;
        }

        @keyframes popupScale {

            from {

                opacity: 0;
                transform: scale(.75);

            }

            to {

                opacity: 1;
                transform: scale(1);

            }

        }

        #achievement-popup {
            position: fixed;
            inset: 0;
            display: none;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, .55);
            backdrop-filter: blur(8px);
            z-index: 999999;
        }

        .achievement-card {
            width: 650px;
            border-radius: 24px;
            overflow: hidden;
            background: linear-gradient(135deg, #0c1528, #22324b);
            border: 2px solid #f7c600;
            box-shadow:
                0 0 25px rgba(247, 198, 0, .45),
                inset 0 0 0 1px rgba(255, 255, 255, .08);
            animation: popupScale .45s ease;
        }

        .achievement-header {
            height: 8px;
            background: linear-gradient(90deg, #f7c600, #ffea7a, #f7c600);
        }

        .achievement-body {
            display: flex;
            align-items: center;
            gap: 25px;
            padding: 28px;
        }

        .achievement-avatar {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 5px solid #f7c600;
            object-fit: cover;
            box-shadow: 0 0 25px rgba(247, 198, 0, .7);
        }

        .achievement-title {
            color: #f7c600;
            text-transform: uppercase;
            letter-spacing: 4px;
            font-weight: 700;
            font-size: 20px;
        }

        .achievement-name {
            color: #fff;
            font-size: 42px;
            font-weight: 800;
            margin: 8px 0;
        }

        .achievement-role {
            color: #d4d9e4;
            font-size: 20px;
            font-weight: 500;
        }

        .achievement-badge {
            display: inline-block;
            margin-top: 18px;
            background: linear-gradient(90deg, #f7c600, #ffe36a);
            color: #111;
            padding: 8px 18px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 14px;
        }

        .progress-wrap {
            margin-top: 20px;
            width: 100%;
            height: 8px;
            border-radius: 20px;
            background: #1a2740;
            overflow: hidden;
        }

        .progress-wrap span {
            display: block;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #f7c600, #ffe36a);
        }

        @keyframes popupScale {
            from {
                transform: scale(.75);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        /*new css titan*/


        /* marquee css */
        .top-marquee {
            width: 100%;
            height: 48px;
            overflow: hidden;
            position: relative;
            background: linear-gradient(90deg, #6b0187, #24066e, #6b0187);
            border-top: 2px solid #d4af37;
            border-bottom: 2px solid #d4af37;
            box-shadow:
                0 0 15px rgba(212, 175, 55, .35),
                inset 0 0 15px rgba(212, 175, 55, .15);
            display: flex;
            align-items: center;
        }

        .top-marquee-content {
            white-space: nowrap;
            display: inline-block;
            padding-left: 100%;
            animation: marqueeMove 35s linear infinite;

            font-size: 18px;
            font-weight: 700;
            color: #FFD700;
            text-transform: uppercase;
            letter-spacing: 1px;

            text-shadow:
                0 0 6px rgba(255, 215, 0, .7),
                0 0 15px rgba(255, 215, 0, .4);
        }

        .top-marquee:hover .top-marquee-content {
            animation-play-state: paused;
        }

        @keyframes marqueeMove {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-100%);
            }
        }
    </style>
</head>

<body>

    <section class="sec-main">
        <div class="top-marquee">
            <div class="top-marquee-content">
                🏆 WELCOME TO THE SALES PERFORMANCE DASHBOARD &nbsp;&nbsp; • &nbsp;&nbsp;
                🎯 ACHIEVE 100% TARGET TO UNLOCK YOUR ACHIEVEMENT &nbsp;&nbsp; • &nbsp;&nbsp;
                👑 TITAN • LEGEND • CHAMPION LEAGUES &nbsp;&nbsp; • &nbsp;&nbsp;
                ⭐ LIVE LEADERBOARD UPDATES &nbsp;&nbsp; • &nbsp;&nbsp;
                🚀 KEEP CLOSING • KEEP CLIMBING • KEEP WINNING &nbsp;&nbsp; • &nbsp;&nbsp;
                🎉 EVERY SALE BRINGS YOU CLOSER TO THE TOP
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/stats.js/r17/Stats.min.js"></script>
    <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>

    <!-- Global App Configuration — uses config() not env() to support cached deployments -->
    <script>
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
