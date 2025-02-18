document.addEventListener("DOMContentLoaded", function() {
    var form = document.getElementById('showProjectValidation');

    form.addEventListener("submit", function(event) {
        var isValid = true;

        var taskName = document.querySelector('input[name="taskName"]');
        var taskNameFormGroup = taskName.closest('.form-group');

        var ownerName = document.querySelector('input[name="projectOwner"]');
        var ownerNameFormGroup = ownerName.closest('.form-group');

        var dueDate = document.querySelector('input[name="dueDate"]');
        var dueDateFormGroup = dueDate.closest('.form-group');

        var remark = document.querySelector('input[name="userRemark"]');
        var remarkFormGroup = remark.closest('.form-group');

        var budget = document.querySelector('input[name="userBudget"]');
        var budgetFormGroup = budget.closest('.form-group');
        var budgetValue = budget.value.trim();
        var budgetRegex = /^\d+$/;


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
        if (budget.disabled && budgetValue.toLowerCase() === 'unauthorized') {
            hideError(budgetFormGroup);
            budget.classList.remove('input-error');
            budget.classList.remove('error-placeholder');
        } else {
            // Perform validation only if the budget is enabled or not "Unauthorized"
            if (!budgetRegex.test(budgetValue)) {
                displayError(budgetFormGroup, "Budget should be a number.");
                isValid = false;

                budget.classList.add('input-error');
                budget.classList.add('error-placeholder');
            } else {
                hideError(budgetFormGroup);

                budget.classList.remove('input-error');
                budget.classList.remove('error-placeholder');
            }
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
