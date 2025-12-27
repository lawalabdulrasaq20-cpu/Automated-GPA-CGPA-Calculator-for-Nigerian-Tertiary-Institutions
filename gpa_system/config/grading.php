<?php
// Nigerian 5-Point Grading System Configuration

return [
    // Grade scale mapping
    'grade_scale' => [
        'A' => [
            'min' => 70,
            'max' => 100,
            'points' => 5,
            'description' => 'Excellent'
        ],
        'B' => [
            'min' => 60,
            'max' => 69,
            'points' => 4,
            'description' => 'Very Good'
        ],
        'C' => [
            'min' => 50,
            'max' => 59,
            'points' => 3,
            'description' => 'Good'
        ],
        'D' => [
            'min' => 45,
            'max' => 49,
            'points' => 2,
            'description' => 'Pass'
        ],
        'E' => [
            'min' => 40,
            'max' => 44,
            'points' => 1,
            'description' => 'Low Pass'
        ],
        'F' => [
            'min' => 0,
            'max' => 39,
            'points' => 0,
            'description' => 'Fail'
        ]
    ],
    
    // Maximum possible GPA
    'max_gpa' => 5.0,
    
    // Minimum passing grade
    'passing_grade' => 'D',
    
    // Academic standing thresholds
    'standing' => [
        'first_class' => 4.5,
        'second_class_upper' => 3.5,
        'second_class_lower' => 2.5,
        'third_class' => 1.5,
        'pass' => 1.0
    ]
];