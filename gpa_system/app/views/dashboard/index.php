<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-shadow { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .table-shadow { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-graduation-cap text-2xl text-purple-600 mr-3"></i>
                        <h1 class="text-xl font-bold text-gray-900">GPA Calculator</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-user-circle text-gray-500"></i>
                        <span class="text-gray-700 font-medium"><?php echo e($user['name']); ?></span>
                    </div>
                    <a href="/gpa_system/public/logout.php" class="bg-red-50 text-red-600 px-3 py-2 rounded-md text-sm font-medium hover:bg-red-100 transition-colors">
                        <i class="fas fa-sign-out-alt mr-1"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if ($message = flash('success')): ?>
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 max-w-7xl mx-auto mt-4">
        <div class="flex">
            <i class="fas fa-check-circle mt-1 mr-2"></i>
            <p><?php echo e($message); ?></p>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($message = flash('error')): ?>
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 max-w-7xl mx-auto mt-4">
        <div class="flex">
            <i class="fas fa-exclamation-circle mt-1 mr-2"></i>
            <p><?php echo e($message); ?></p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg card-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-chart-line text-purple-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Current CGPA</dt>
                            <dd class="text-lg font-medium text-gray-900"><?php echo number_format($cgpaResult['cgpa'], 2); ?></dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg card-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-award text-green-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Academic Standing</dt>
                            <dd class="text-lg font-medium text-gray-900"><?php echo e($academicStanding); ?></dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg card-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-book text-blue-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Semesters</dt>
                            <dd class="text-lg font-medium text-gray-900"><?php echo count($semesters); ?></dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg card-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-100 rounded-md flex items-center justify-center">
                            <i class="fas fa-coins text-orange-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Units</dt>
                            <dd class="text-lg font-medium text-gray-900"><?php echo $cgpaResult['total_units']; ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Semester Management -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg card-shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Semesters</h3>
                    </div>
                    
                    <div class="p-6">
                        <!-- Add New Semester Form -->
                        <form method="POST" action="/gpa_system/public/index.php">
                            <input type="hidden" name="action" value="add_semester">
                            <div class="mb-4">
                                <label for="semester_name" class="block text-sm font-medium text-gray-700 mb-2">Semester Name</label>
                                <input type="text" id="semester_name" name="semester_name" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                       placeholder="e.g., First Semester">
                            </div>
                            <div class="mb-4">
                                <label for="session" class="block text-sm font-medium text-gray-700 mb-2">Academic Session (Optional)</label>
                                <input type="text" id="session" name="session"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                       placeholder="e.g., 2023/2024">
                            </div>
                            <button type="submit" class="w-full bg-purple-600 text-white py-2 px-4 rounded-md font-medium hover:bg-purple-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i>Add Semester
                            </button>
                        </form>

                        <!-- Semester List -->
                        <?php if (!empty($semesters)): ?>
                        <div class="space-y-2">
                            <?php foreach ($semesters as $semester): ?>
                            <a href="/gpa_system/public/index.php?semester=<?php echo $semester['id']; ?>" 
                               class="block p-3 rounded-md border <?php echo $selectedSemesterId == $semester['id'] ? 'bg-purple-50 border-purple-300' : 'bg-gray-50 border-gray-200'; ?> hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-900"><?php echo e($semester['semester_name']); ?></p>
                                        <?php if ($semester['session']): ?>
                                        <p class="text-sm text-gray-500"><?php echo e($semester['session']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">GPA: <?php echo number_format($semester['gpa'], 2); ?></p>
                                        <p class="text-xs text-gray-500"><?php echo $semester['total_units']; ?> units</p>
                                    </div>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-calendar-alt text-3xl mb-3"></i>
                            <p>No semesters yet.</p>
                            <p class="text-sm">Add your first semester above.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right Column: Course Management -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg card-shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <?php if ($selectedSemesterId && !empty($semesters)): ?>
                                    Courses for <?php echo e($semesters[array_search($selectedSemesterId, array_column($semesters, 'id'))]['semester_name'] ?? 'Selected Semester'); ?>
                                <?php else: ?>
                                    Select a Semester
                                <?php endif; ?>
                            </h3>
                            <?php if ($selectedSemesterId): ?>
                            <button onclick="document.getElementById('addCourseModal').classList.remove('hidden')" 
                                    class="bg-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-purple-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i>Add Course
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="p-6">
                        <?php if ($selectedSemesterId): ?>
                            <?php if (!empty($courses)): ?>
                            <!-- Course Table -->
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 table-shadow">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Code</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Title</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php foreach ($courses as $course): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($course['course_code']); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($course['course_title']); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $course['units']; ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo 'grade-' . $course['grade']; ?>">
                                                    <?php echo $course['grade']; ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $course['quality_points']; ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button onclick="editCourse(<?php echo $course['id']; ?>)" class="text-purple-600 hover:text-purple-900 mr-3">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button onclick="deleteCourse(<?php echo $course['id']; ?>)" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Semester Summary -->
                            <div class="mt-6 grid grid-cols-3 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg text-center">
                                    <p class="text-sm text-gray-600">Total Units</p>
                                    <p class="text-2xl font-bold text-gray-900"><?php echo $semesterGPA['total_units']; ?></p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg text-center">
                                    <p class="text-sm text-gray-600">Quality Points</p>
                                    <p class="text-2xl font-bold text-gray-900"><?php echo $semesterGPA['total_points']; ?></p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg text-center">
                                    <p class="text-sm text-gray-600">GPA</p>
                                    <p class="text-2xl font-bold text-purple-600"><?php echo number_format($semesterGPA['gpa'], 2); ?></p>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="text-center py-12 text-gray-500">
                                <i class="fas fa-book-open text-4xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No courses yet</h3>
                                <p class="text-gray-500 mb-4">Add your first course to start calculating your GPA</p>
                                <button onclick="document.getElementById('addCourseModal').classList.remove('hidden')" 
                                        class="bg-purple-600 text-white px-6 py-2 rounded-md font-medium hover:bg-purple-700 transition-colors">
                                    <i class="fas fa-plus mr-2"></i>Add Course
                                </button>
                            </div>
                            <?php endif; ?>
                        <?php else: ?>
                        <div class="text-center py-12 text-gray-500">
                            <i class="fas fa-calendar-check text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Select a Semester</h3>
                            <p class="text-gray-500">Choose a semester from the left panel to view and manage courses</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Course Modal -->
    <?php if ($selectedSemesterId): ?>
    <div id="addCourseModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Add New Course</h3>
                    <button onclick="document.getElementById('addCourseModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form method="POST" action="/gpa_system/public/index.php">

                    <input type="hidden" name="action" value="add_course">
                    <input type="hidden" name="semester_id" value="<?php echo $selectedSemesterId; ?>">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Course Code</label>
                            <input type="text" name="course_code" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   placeholder="e.g., MATH 101">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Course Title</label>
                            <input type="text" name="course_title" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   placeholder="e.g., Calculus I">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Units</label>
                                <select name="units" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="">Select</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Grade</label>
                                <select name="grade" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="">Select</option>
                                    <option value="A">A (70-100)</option>
                                    <option value="B">B (60-69)</option>
                                    <option value="C">C (50-59)</option>
                                    <option value="D">D (45-49)</option>
                                    <option value="E">E (40-44)</option>
                                    <option value="F">F (0-39)</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Score (Optional)</label>
                            <input type="number" name="score" min="0" max="100"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   placeholder="e.g., 75">
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="document.getElementById('addCourseModal').classList.add('hidden')" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-md hover:bg-purple-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Add Course
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="text-center text-sm text-gray-500">
                <p>&copy; <?php echo date('Y'); ?> GPA & CGPA Calculator. Built for Nigerian Tertiary Institutions.</p>
            </div>
        </div>
    </footer>

    <script>
        // Auto-hide flash messages after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.bg-green-50, .bg-red-50').forEach(el => {
                el.style.display = 'none';
            });
        }, 5000);

        // Edit course function
        function editCourse(courseId) {
            alert('Edit functionality coming soon!');
        }

        // Delete course function
        function deleteCourse(courseId) {
            if (confirm('Are you sure you want to delete this course?')) {
                // Will implement delete functionality
                alert('Delete functionality coming soon!');
            }
        }

        // Close modal when clicking outside
        document.getElementById('addCourseModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    </script>
</body>
</html>