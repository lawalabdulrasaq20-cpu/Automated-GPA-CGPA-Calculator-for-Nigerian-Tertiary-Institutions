// GPA Calculator Application JavaScript

class GPACalculator {
    constructor() {
        this.initializeEventListeners();
        this.initializeTooltips();
    }

    initializeEventListeners() {
        // Auto-hide flash messages
        setTimeout(() => {
            const flashMessages = document.querySelectorAll('.flash-message');
            flashMessages.forEach(msg => {
                msg.style.transition = 'opacity 0.5s ease-out';
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 500);
            });
        }, 5000);

        // Form validation
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', this.validateForm.bind(this));
        });

        // Real-time grade calculation
        const gradeSelects = document.querySelectorAll('select[name="grade"]');
        gradeSelects.forEach(select => {
            select.addEventListener('change', this.updateGradePoints.bind(this));
        });

        // Score to grade conversion
        const scoreInputs = document.querySelectorAll('input[name="score"]');
        scoreInputs.forEach(input => {
            input.addEventListener('input', this.convertScoreToGrade.bind(this));
        });

        // Modal handling
        this.initializeModals();
    }

    initializeTooltips() {
        // Add tooltips to grade badges
        const gradeBadges = document.querySelectorAll('[class*="grade-"]');
        gradeBadges.forEach(badge => {
            const grade = badge.className.match(/grade-([A-F])/)[1];
            const tooltip = this.getGradeTooltip(grade);
            badge.title = tooltip;
        });
    }

    getGradeTooltip(grade) {
        const tooltips = {
            'A': 'Excellent (70-100%) - 5 points',
            'B': 'Very Good (60-69%) - 4 points',
            'C': 'Good (50-59%) - 3 points',
            'D': 'Pass (45-49%) - 2 points',
            'E': 'Low Pass (40-44%) - 1 point',
            'F': 'Fail (0-39%) - 0 points'
        };
        return tooltips[grade] || '';
    }

    validateForm(event) {
        const form = event.target;
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                this.showFieldError(field, 'This field is required');
                isValid = false;
            } else {
                this.clearFieldError(field);
            }
        });

        // Special validation for course code
        const courseCodeField = form.querySelector('input[name="course_code"]');
        if (courseCodeField && courseCodeField.value) {
            const courseCode = courseCodeField.value.trim().toUpperCase();
            if (!/^[A-Z]{2,4}\s?\d{3,4}$/.test(courseCode)) {
                this.showFieldError(courseCodeField, 'Invalid course code format (e.g., MATH 101)');
                isValid = false;
            }
        }

        // Special validation for units
        const unitsField = form.querySelector('select[name="units"]');
        if (unitsField && unitsField.value) {
            const units = parseInt(unitsField.value);
            if (units < 1 || units > 6) {
                this.showFieldError(unitsField, 'Units must be between 1 and 6');
                isValid = false;
            }
        }

        if (!isValid) {
            event.preventDefault();
        }

        return isValid;
    }

    showFieldError(field, message) {
        this.clearFieldError(field);
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'text-red-500 text-xs mt-1 field-error';
        errorDiv.textContent = message;
        
        field.parentNode.appendChild(errorDiv);
        field.classList.add('border-red-500');
    }

    clearFieldError(field) {
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
        field.classList.remove('border-red-500');
    }

    updateGradePoints(event) {
        const gradeSelect = event.target;
        const unitsField = gradeSelect.closest('form').querySelector('select[name="units"]');
        const pointsField = gradeSelect.closest('form').querySelector('input[name="quality_points"]');
        
        if (gradeSelect.value && unitsField && unitsField.value) {
            const grade = gradeSelect.value;
            const units = parseInt(unitsField.value);
            const gradePoints = this.getGradePoints(grade);
            const qualityPoints = units * gradePoints;
            
            if (pointsField) {
                pointsField.value = qualityPoints;
                pointsField.classList.add('bg-green-50', 'border-green-500');
                setTimeout(() => {
                    pointsField.classList.remove('bg-green-50', 'border-green-500');
                }, 1000);
            }
        }
    }

    convertScoreToGrade(event) {
        const scoreInput = event.target;
        const gradeSelect = scoreInput.closest('form').querySelector('select[name="grade"]');
        
        if (scoreInput.value && gradeSelect) {
            const score = parseInt(scoreInput.value);
            const grade = this.getGradeFromScore(score);
            
            if (grade) {
                gradeSelect.value = grade;
                gradeSelect.classList.add('bg-green-50', 'border-green-500');
                setTimeout(() => {
                    gradeSelect.classList.remove('bg-green-50', 'border-green-500');
                }, 1000);
                
                // Trigger grade points update
                gradeSelect.dispatchEvent(new Event('change'));
            }
        }
    }

    getGradePoints(grade) {
        const gradePoints = {
            'A': 5, 'B': 4, 'C': 3, 'D': 2, 'E': 1, 'F': 0
        };
        return gradePoints[grade] || 0;
    }

    getGradeFromScore(score) {
        if (score >= 70) return 'A';
        if (score >= 60) return 'B';
        if (score >= 50) return 'C';
        if (score >= 45) return 'D';
        if (score >= 40) return 'E';
        return 'F';
    }

    initializeModals() {
        // Close modal when clicking outside
        document.addEventListener('click', (event) => {
            if (event.target.classList.contains('modal-backdrop')) {
                event.target.classList.add('hidden');
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                const openModals = document.querySelectorAll('.modal:not(.hidden)');
                openModals.forEach(modal => {
                    modal.classList.add('hidden');
                });
            }
        });
    }

    // Utility functions
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-md text-white z-50 ${
            type === 'success' ? 'bg-green-500' :
            type === 'error' ? 'bg-red-500' :
            type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    formatNumber(number, decimals = 2) {
        return parseFloat(number).toFixed(decimals);
    }

    // GPA Calculator utilities
    calculateGPA(courses) {
        if (!courses || courses.length === 0) return 0.00;
        
        let totalUnits = 0;
        let totalPoints = 0;
        
        courses.forEach(course => {
            totalUnits += course.units;
            totalPoints += course.quality_points;
        });
        
        if (totalUnits === 0) return 0.00;
        
        return (totalPoints / totalUnits).toFixed(2);
    }

    // Export functionality
    exportToCSV(data, filename) {
        const csv = this.convertToCSV(data);
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        
        if (link.download !== undefined) {
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', filename);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

    convertToCSV(data) {
        if (!data || data.length === 0) return '';
        
        const headers = Object.keys(data[0]);
        const csvHeaders = headers.join(',');
        
        const csvRows = data.map(row => {
            return headers.map(header => {
                const value = row[header];
                return typeof value === 'string' ? `"${value}"` : value;
            }).join(',');
        });
        
        return [csvHeaders, ...csvRows].join('\n');
    }
}

// Initialize the application when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.gpaCalculator = new GPACalculator();
});

// Global utility functions
window.showNotification = (message, type) => {
    window.gpaCalculator.showNotification(message, type);
};

window.calculateGPA = (courses) => {
    return window.gpaCalculator.calculateGPA(courses);
};

window.exportToCSV = (data, filename) => {
    window.gpaCalculator.exportToCSV(data, filename);
};