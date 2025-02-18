document.addEventListener('DOMContentLoaded', function () {
    // Redirect to project status pages
    let notStartedProject = document.getElementById('notStartedProject');
    let completedProject = document.getElementById('completedProject');
    let inProgressProject = document.getElementById('inProgressProject');
    let behindScheduleProject = document.getElementById('behindScheduleProject');

    notStartedProject.addEventListener('click', function () {
        window.location.href = notStartedProjectUrl;
    });
    completedProject.addEventListener('click', function () {
        window.location.href = completedProjectUrl;
    });
    inProgressProject.addEventListener('click', function () {
        window.location.href = inProgressProjectUrl;
    });
    behindScheduleProject.addEventListener('click', function () {
        window.location.href = behindScheduleProjectUrl;
    });
});

