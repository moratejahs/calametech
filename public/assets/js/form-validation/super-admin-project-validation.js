document.addEventListener("DOMContentLoaded", function() {
    var storeForm = document.getElementById('storeProjectValidationForm');
    var viewForm = document.getElementById('viewProjectValidationForm');

    storeForm.addEventListener("submit", function(event) {
        var isValid = true;

        var taskName = document.querySelector('input[name="project_name"]');
        var taskNameFormGroup = taskName.closest('.form-group');

        var ownerName = document.querySelector('input[name="project_owner"]');
        var ownerNameFormGroup = ownerName.closest('.form-group');

        var dueDate = document.querySelector('input[name="due_date"]');
        var dueDateFormGroup = dueDate.closest('.form-group');

        var remark = document.querySelector('input[name="remarks"]');
        var remarkFormGroup = remark.closest('.form-group');

        var budget = document.querySelector('input[name="budget"]');
        var budgetFormGroup = budget.closest('.form-group');


        // TASK NAME
        if (!taskName.value.trim()) {
            displayError(taskNameFormGroup, "Task name is required.");
            isValid = false;

            taskName.classList.add('input-error'); // Define this class in your CSS
            taskName.classList.add('error-placeholder'); // New class for error state
        } else {
            hideError(taskNameFormGroup);

            // Remove the classes if the input is valid
            taskName.classList.remove('input-error');
            taskName.classList.remove('error-placeholder');
        }

        // CLIENT NAME
        if(!ownerName.value.trim()) {
            displayError(ownerNameFormGroup, "Client name is required.");
            isValid = false;

            ownerName.classList.add('input-error'); // Define this class in your CSS
            ownerName.classList.add('error-placeholder'); // New class for error state
        }
        else {
            hideError(ownerNameFormGroup);

            // Remove the classes if the input is valid
            ownerName.classList.remove('input-error');
            ownerName.classList.remove('error-placeholder');
        }

        // DUE DATE
        if(!dueDate.value.trim()) {
            displayError(dueDateFormGroup, "Due date is required.");
            isValid = false;

            dueDate.classList.add('input-error'); // Define this class in your CSS
            dueDate.classList.add('error-placeholder'); // New class for error state
        }
        else {
            hideError(dueDateFormGroup);

            // Remove the classes if the input is valid
            dueDate.classList.remove('input-error');
            dueDate.classList.remove('error-placeholder');
        }

        // REMARK
        if(!remark.value.trim()) {
            displayError(remarkFormGroup, "Remark is required.");
            isValid = false;

            remark.classList.add('input-error'); // Define this class in your CSS
            remark.classList.add('error-placeholder'); // New class for error state
        }
        else {
            hideError(remarkFormGroup);

            // Remove the classes if the input is valid
            remark.classList.remove('input-error');
            remark.classList.remove('error-placeholder');
        }

        // BUDGET
        if(!budget.value.trim()) {
            displayError(budgetFormGroup, "Budget is required.");
            isValid = false;

            budget.classList.add('input-error'); // Define this class in your CSS
            budget.classList.add('error-placeholder'); // New class for error state
        }
        else {
            hideError(budgetFormGroup);

            // Remove the classes if the input is valid
            budget.classList.remove('input-error');
            budget.classList.remove('error-placeholder');
        }

        if (!isValid) {
            event.preventDefault();
        }
    });

    viewForm.addEventListener("submit", function(event) {
        var isValid = true;

        var taskName = document.querySelector('input[name="vproject_name"]');
        var taskNameFormGroup = taskName.closest('.form-group');

        var ownerName = document.querySelector('input[name="vproject_owner"]');
        var ownerNameFormGroup = ownerName.closest('.form-group');

        var dueDate = document.querySelector('input[name="vdue_date"]');
        var dueDateFormGroup = dueDate.closest('.form-group');

        var remark = document.querySelector('input[name="vremarks"]');
        var remarkFormGroup = remark.closest('.form-group');

        var budget = document.querySelector('input[name="vbudget"]');
        var budgetFormGroup = budget.closest('.form-group');


        // TASK NAME
        if (!taskName.value.trim()) {
            displayError(taskNameFormGroup, "Task name is required.");
            isValid = false;

            taskName.classList.add('input-error'); // Define this class in your CSS
            taskName.classList.add('error-placeholder'); // New class for error state
        } else {
            hideError(taskNameFormGroup);

            // Remove the classes if the input is valid
            taskName.classList.remove('input-error');
            taskName.classList.remove('error-placeholder');
        }

        // CLIENT NAME
        if(!ownerName.value.trim()) {
            displayError(ownerNameFormGroup, "Client name is required.");
            isValid = false;

            ownerName.classList.add('input-error'); // Define this class in your CSS
            ownerName.classList.add('error-placeholder'); // New class for error state
        }
        else {
            hideError(ownerNameFormGroup);

            // Remove the classes if the input is valid
            ownerName.classList.remove('input-error');
            ownerName.classList.remove('error-placeholder');
        }

        // DUE DATE
        if(!dueDate.value.trim()) {
            displayError(dueDateFormGroup, "Due date is required.");
            isValid = false;

            dueDate.classList.add('input-error'); // Define this class in your CSS
            dueDate.classList.add('error-placeholder'); // New class for error state
        }
        else {
            hideError(dueDateFormGroup);

            // Remove the classes if the input is valid
            dueDate.classList.remove('input-error');
            dueDate.classList.remove('error-placeholder');
        }

        // REMARK
        if(!remark.value.trim()) {
            displayError(remarkFormGroup, "Remark is required.");
            isValid = false;

            remark.classList.add('input-error'); // Define this class in your CSS
            remark.classList.add('error-placeholder'); // New class for error state
        }
        else {
            hideError(remarkFormGroup);

            // Remove the classes if the input is valid
            remark.classList.remove('input-error');
            remark.classList.remove('error-placeholder');
        }

        // BUDGET
        if(!budget.value.trim()) {
            displayError(budgetFormGroup, "Budget is required.");
            isValid = false;

            budget.classList.add('input-error'); // Define this class in your CSS
            budget.classList.add('error-placeholder'); // New class for error state
        }
        else {
            hideError(budgetFormGroup);

            // Remove the classes if the input is valid
            budget.classList.remove('input-error');
            budget.classList.remove('error-placeholder');
        }

        if (!isValid) {
            event.preventDefault();
        }
    });

    function displayError(formGroup, message) {
        var errorDiv = formGroup.querySelector('.error-message');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message text-danger';
            formGroup.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
    }

    function hideError(formGroup) {
        var errorDiv = formGroup.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.textContent = '';
        }
    }
});
