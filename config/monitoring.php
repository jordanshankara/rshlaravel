<?php

return [

    'days' => 7,

    'categories' => [
        'EMOSI' => [
            'label' => 'Kondisi Emosi & Pikiran',
            'questions' => [
                1 => [
                    'text' => 'Perasaan saya hari ini',
                    'options' => [
                        2 => ['label' => 'Tenang & nyaman',       'color' => 'green'],
                        1 => ['label' => 'Kadang gelisah',         'color' => 'yellow'],
                        0 => ['label' => 'Cemas / tidak tenang',   'color' => 'red'],
                    ],
                ],
                2 => [
                    'text' => 'Pikiran saya hari ini',
                    'options' => [
                        2 => ['label' => 'Ringan & jernih',        'color' => 'green'],
                        1 => ['label' => 'Banyak pikiran',         'color' => 'yellow'],
                        0 => ['label' => 'Berat & melelahkan',     'color' => 'red'],
                    ],
                ],
                3 => [
                    'text' => 'Tingkat stres saya hari ini',
                    'options' => [
                        2 => ['label' => 'Rendah / tidak stres',   'color' => 'green'],
                        1 => ['label' => 'Sedang',                 'color' => 'yellow'],
                        0 => ['label' => 'Tinggi / sangat stres',  'color' => 'red'],
                    ],
                ],
                4 => [
                    'text' => 'Tidur saya semalam',
                    'options' => [
                        2 => ['label' => 'Nyenyak & cukup',        'color' => 'green'],
                        1 => ['label' => 'Kurang / sering bangun',  'color' => 'yellow'],
                        0 => ['label' => 'Buruk / tidak tidur',    'color' => 'red'],
                    ],
                ],
                5 => [
                    'text' => 'Semangat dan motivasi saya',
                    'options' => [
                        2 => ['label' => 'Bersemangat',            'color' => 'green'],
                        1 => ['label' => 'Biasa saja',             'color' => 'yellow'],
                        0 => ['label' => 'Lesu / tidak bergairah', 'color' => 'red'],
                    ],
                ],
                6 => [
                    'text' => 'Hubungan dengan orang sekitar hari ini',
                    'options' => [
                        2 => ['label' => 'Harmonis & menyenangkan','color' => 'green'],
                        1 => ['label' => 'Biasa / netral',         'color' => 'yellow'],
                        0 => ['label' => 'Tegang / konflik',       'color' => 'red'],
                    ],
                ],
                7 => [
                    'text' => 'Kemampuan fokus saya hari ini',
                    'options' => [
                        2 => ['label' => 'Sangat fokus',           'color' => 'green'],
                        1 => ['label' => 'Agak sulit fokus',       'color' => 'yellow'],
                        0 => ['label' => 'Tidak bisa fokus',       'color' => 'red'],
                    ],
                ],
                8 => [
                    'text' => 'Secara keseluruhan kondisi batin saya hari ini',
                    'options' => [
                        2 => ['label' => 'Positif & damai',        'color' => 'green'],
                        1 => ['label' => 'Cukup baik',             'color' => 'yellow'],
                        0 => ['label' => 'Negatif / gelap',        'color' => 'red'],
                    ],
                ],
            ],
        ],

        'FISIK' => [
            'label' => 'Kondisi Fisik',
            'questions' => [
                1 => [
                    'text' => 'Kondisi badan saat bangun pagi',
                    'options' => [
                        2 => ['label' => 'Ringan & segar',              'color' => 'green'],
                        1 => ['label' => 'Agak lemas',                  'color' => 'yellow'],
                        0 => ['label' => 'Berat & tidak nyaman',        'color' => 'red'],
                    ],
                ],
                2 => [
                    'text' => 'Tingkat energi sepanjang hari',
                    'options' => [
                        2 => ['label' => 'Penuh energi',                'color' => 'green'],
                        1 => ['label' => 'Cukup / naik-turun',          'color' => 'yellow'],
                        0 => ['label' => 'Sangat lemah / lelah',        'color' => 'red'],
                    ],
                ],
                3 => [
                    'text' => 'Rasa nyeri atau ketidaknyamanan fisik',
                    'options' => [
                        2 => ['label' => 'Tidak ada nyeri',             'color' => 'green'],
                        1 => ['label' => 'Nyeri ringan',                'color' => 'yellow'],
                        0 => ['label' => 'Nyeri sedang–berat',          'color' => 'red'],
                    ],
                ],
                4 => [
                    'text' => 'Nafsu makan hari ini',
                    'options' => [
                        2 => ['label' => 'Baik & normal',               'color' => 'green'],
                        1 => ['label' => 'Kurang / berlebihan',         'color' => 'yellow'],
                        0 => ['label' => 'Tidak mau makan / sangat berlebih', 'color' => 'red'],
                    ],
                ],
                5 => [
                    'text' => 'Kondisi pencernaan hari ini',
                    'options' => [
                        2 => ['label' => 'Lancar & nyaman',             'color' => 'green'],
                        1 => ['label' => 'Agak terganggu',              'color' => 'yellow'],
                        0 => ['label' => 'Bermasalah (mual/diare/sembelit)', 'color' => 'red'],
                    ],
                ],
                6 => [
                    'text' => 'Kepala dan leher hari ini',
                    'options' => [
                        2 => ['label' => 'Bebas & nyaman',              'color' => 'green'],
                        1 => ['label' => 'Agak tegang / pusing ringan', 'color' => 'yellow'],
                        0 => ['label' => 'Sakit kepala / sangat tegang','color' => 'red'],
                    ],
                ],
                7 => [
                    'text' => 'Kemampuan bergerak dan beraktivitas',
                    'options' => [
                        2 => ['label' => 'Bebas & normal',              'color' => 'green'],
                        1 => ['label' => 'Sedikit terbatas',            'color' => 'yellow'],
                        0 => ['label' => 'Sangat terbatas',             'color' => 'red'],
                    ],
                ],
                8 => [
                    'text' => 'Secara keseluruhan kondisi fisik saya hari ini',
                    'options' => [
                        2 => ['label' => 'Sangat baik',                 'color' => 'green'],
                        1 => ['label' => 'Cukup baik',                  'color' => 'yellow'],
                        0 => ['label' => 'Buruk / tidak baik',          'color' => 'red'],
                    ],
                ],
            ],
        ],
    ],

    'scoring' => [
        'max_per_category' => 16, // 8 questions × max 2 points each
        'levels' => [
            [
                'min'        => 13,
                'max'        => 16,
                'label'      => 'Stabil',
                'color'      => 'green',
                'desc_emosi' => 'Kondisi mental & emosi sangat baik',
                'desc_fisik' => 'Kondisi fisik sangat baik',
            ],
            [
                'min'        => 8,
                'max'        => 12,
                'label'      => 'Perlu Perhatian',
                'color'      => 'yellow',
                'desc_emosi' => 'Perlu regulasi ringan — disarankan latihan Nafas Perut atau Reiki',
                'desc_fisik' => 'Perlu perhatian ekstra — istirahat cukup dan pola makan dijaga',
            ],
            [
                'min'        => 0,
                'max'        => 7,
                'label'      => 'Perlu Pendampingan',
                'color'      => 'red',
                'desc_emosi' => 'Perlu sesi khusus 1-on-1 dengan fasilitator',
                'desc_fisik' => 'Perlu konsultasi langsung dengan tim medis program',
            ],
        ],
    ],

];
