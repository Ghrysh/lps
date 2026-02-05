@extends('layouts.visitor')

@section('title', 'Peta Lokasi')

@section('content')

    @php
        $coordinates = [
            ['x' => 16, 'y' => 27],
            ['x' => 23, 'y' => 50], 
            ['x' => 35, 'y' => 100], 
            ['x' => 16,  'y' => -15],  

            ['x' => 29, 'y' => 28], 
            ['x' => 63, 'y' => 90], 

            ['x' => 63, 'y' => 39], 
            ['x' => 90, 'y' => -10], 
            ['x' => 72, 'y' => 17], 
            ['x' => 75, 'y' => 55], 
            
            ['x' => 85, 'y' => 43], 
        ];
    @endphp

    <div class="flex flex-col h-full">
        
        <div class="mb-4 shrink-0">
            <h1 class="text-xl font-bold text-slate-800">Peta Eksplorasi</h1>
            <p class="text-slate-500 text-xs">Scan 10 titik & menangkan Minigame (Total 150 Poin).</p>
        </div>

        <div class="relative w-full min-h-[20vh] bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden flex items-center justify-center p-4">
            <div class="relative w-full max-w-[800px]">
                
                <img src="{{ asset('assets/images/peta.png') }}" alt="Peta Labirin" class="w-full h-auto object-contain">

                @for ($i = 1; $i <= 11; $i++)
                    @php
                        if ($i < 11) {
                            $isActive = $totalPoints >= ($i * 10);
                        } else {
                            $isActive = $totalPoints >= 150; 
                        }

                        $isMinigame = ($i == 11);
                    @endphp

                    <div class="absolute transform -translate-x-1/2 -translate-y-1/2 transition-all duration-500 flex flex-col items-center"
                         style="left: {{ $coordinates[$i-1]['x'] }}%; top: {{ $coordinates[$i-1]['y'] }}%; 
                                width: {{ $isMinigame ? '8%' : '5%' }}; aspect-ratio: 1/1;">
                        
                        @if($isMinigame)
                            
                            @if(!$isActive)
                                <div class="absolute inset-0 bg-yellow-400 rounded-full animate-ping opacity-50"></div>
                            @endif

                            <div class="w-full h-full rounded-full border-2 shadow-lg z-10 flex items-center justify-center transition-colors duration-300
                                {{ $isActive 
                                    ? 'bg-green-500 border-white text-white shadow-green-200' 
                                    : 'bg-yellow-400 border-yellow-200 text-white shadow-yellow-200 animate-pulse' 
                                }}">
                                <i class="fas {{ $isActive ? 'fa-check' : 'fa-gamepad' }} text-[2.5vw] md:text-sm"></i>
                            </div>

                            <div class="absolute top-full mt-1 whitespace-nowrap bg-yellow-100 text-yellow-700 text-[8px] px-2 py-0.5 rounded-full font-bold border border-yellow-300 shadow-sm z-20">
                                MINIGAME
                            </div>

                        @else
                            <div class="w-full h-full rounded-full border border-white shadow-sm z-10 flex items-center justify-center transition-colors duration-300
                                {{ $isActive ? 'bg-green-500' : 'bg-slate-300' }}">
                                
                                @if($isActive)
                                    <i class="fas fa-check text-white text-[2vw] md:text-[8px]"></i>
                                @endif
                            </div>
                        @endif

                    </div>
                @endfor
            </div>
        </div>

        <div class="mt-4 bg-white p-5 rounded-3xl shadow-lg border border-slate-100 shrink-0 relative overflow-hidden">
            
            @if(!$isFinished)
                <div class="flex justify-between items-end mb-2">
                    <div>
                        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Progress</span>
                        <div class="text-2xl font-bold text-slate-800">
                            {{ $totalPoints }} <span class="text-sm text-slate-400 font-medium">/ {{ $maxPoints }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-orange-600 font-bold">{{ round(($totalPoints / $maxPoints) * 100) }}%</span>
                    </div>
                </div>

                <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-orange-400 to-orange-600 rounded-full transition-all duration-1000 ease-out"
                         style="width: {{ ($totalPoints / $maxPoints) * 100 }}%"></div>
                </div>
                
                @if($totalPoints >= 100 && $totalPoints < 150)
                    <div class="mt-3 bg-yellow-50 text-yellow-700 text-xs p-2 rounded-lg text-center animate-pulse">
                        <i class="fas fa-exclamation-circle mr-1"></i> Scan selesai! Sekarang mainkan <strong>Minigame</strong>.
                    </div>
                @endif

            @else
                <div class="text-center py-2 animate-fade-in-up">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-3 text-xl shadow-sm">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    
                    <h3 class="font-bold text-lg text-slate-800 mb-2">Selamat! Misi Selesai</h3>
                    
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                        <p class="text-sm text-slate-700 leading-relaxed">
                            Poin sudah mencapai <strong>150</strong>. <br>
                            Pergilah ke mesin <strong>Photobooth</strong> dan dapatkan code untuk penukaran kopi gratis.
                        </p>
                    </div>
                    
                    <div class="mt-3">
                         <a href="{{ route('photobooth') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold shadow hover:bg-blue-700">
                            Buka Photobooth
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
