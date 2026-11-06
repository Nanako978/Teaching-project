// Validation de formulaire d'inscription
document.addEventListener('DOMContentLoaded', function() {
    
    // Validation du formulaire de register
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas.');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Le mot de passe doit contenir au moins 6 caractères.');
                return false;
            }
        });
    }

    // Afficher/cacher les champs selon le rôle sélectionné
    const roleSelect = document.getElementById('role');
    if (roleSelect) {
        roleSelect.addEventListener('change', function() {
            const studentFields = document.getElementById('student-fields');
            const teacherFields = document.getElementById('teacher-fields');
            
            if (this.value === 'student') {
                if (studentFields) studentFields.style.display = 'block';
                if (teacherFields) teacherFields.style.display = 'none';
            } else if (this.value === 'teacher') {
                if (studentFields) studentFields.style.display = 'none';
                if (teacherFields) teacherFields.style.display = 'block';
            }
        });
    }

    // Confirmation de suppression
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
                e.preventDefault();
            }
        });
    });

    // Auto-hide des alertes après 5 secondes
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // Validation de note (entre 0 et 20)
    const gradeInputs = document.querySelectorAll('input[name="note"]');
    gradeInputs.forEach(input => {
        input.addEventListener('blur', function() {
            const value = parseFloat(this.value);
            if (isNaN(value) || value < 0 || value > 20) {
                alert('La note doit être entre 0 et 20.');
                this.value = '';
            }
        });
    });
});