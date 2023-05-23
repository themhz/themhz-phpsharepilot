let tasks =[];


function loadCalendar(){
    // Fetch tasks from the API endpoint
    fetch("schedule/fetchtasks?format=raw")
        .then(response => response.json())
        .then(data => {
            tasks = data;
            //console.log(tasks);
            generateCalendar(); // Ensure the calendar is generated after tasks are fetched
        })
        .catch(error => console.error("Error fetching tasks:", error));
}
//loadCalendar();
// Generate calendar
const generateCalendar = () => {
    const calendarContainer = document.getElementById("calendar-container");
    const currentDate = new Date();
    const currentMonth = currentDate.getMonth();
    const currentYear = currentDate.getFullYear();

    const calendar = new Date(currentYear, currentMonth + 1, 0);
    const daysInMonth = calendar.getDate();

    let calendarHTML = "<table>";
    calendarHTML += "<tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>";
    calendarHTML += "<tr>";

    for (let i = 1; i <= daysInMonth; i++) {
        const day = new Date(currentYear, currentMonth, i);
        const dayOfWeek = day.getDay();
        const formattedDate = `${currentYear}-${String(currentMonth + 1).padStart(2, "0")}-${String(i).padStart(2, "0")}`;

        // Count the number of tasks for the current day
        const taskCount = tasks.filter(task => task.date === formattedDate).length;
        console.log("tasks:");
        console.log(formattedDate);
        console.log(tasks);

        const hasTasks = taskCount > 0;

        if (i === 1) {
            for (let j = 0; j < dayOfWeek; j++) {
                calendarHTML += "<td></td>";
            }
        }

        const today = new Date();
        const isCurrentDate = day.getDate() === today.getDate() && day.getMonth() === today.getMonth() && day.getFullYear() === today.getFullYear();

        // Check if the date is in the past
        const isPastDate = day < today;

        // Add the data-task-count attribute with the number of tasks for the current day
        calendarHTML += `<td data-date="${formattedDate}"${hasTasks ? ' data-tasks' : ''} data-task-count="${taskCount}"${isCurrentDate ? ' class="current-date"' : ''}${isPastDate ? ' class="past-date"' : ''}>${i}${taskCount > 0 ? ` (${taskCount})` : ''}</td>`;

        if (dayOfWeek === 6) {
            calendarHTML += "</tr><tr>";
        }
    }

    calendarHTML += "</tr></table>";
    calendarContainer.innerHTML = calendarHTML;
};

const tasksList = document.getElementById("tasks-list");
const maxTasks = 24;

tasks.slice(0, maxTasks).forEach(task => {
    const listItem = document.createElement("li");
    listItem.textContent = `${task.time} - ${task.task}`; // Update this line
    tasksList.appendChild(listItem);
});


document.getElementById("calendar-container").addEventListener("click", (event) => {
    const target = event.target;

    if (target.tagName.toLowerCase() === "td" && target.hasAttribute("data-date")) {
        const selectedDate = target.getAttribute("data-date");
        displayTasks(selectedDate);
    }


});

const displayTasks = (selectedDate) => {
    const filteredTasks = tasks.filter(task => task.date === selectedDate);
    tasksList.innerHTML = '';

    // Define the currentTime inside the function
    const currentDate = new Date();
    const currentTime = `${currentDate.getHours()}:${String(currentDate.getMinutes()).padStart(2, "0")}`;

    filteredTasks.slice(0, maxTasks).forEach(task => {
        const listItem = document.createElement("li");
        listItem.textContent = `${task.time} - ${task.task}`;

        // Check if the current task has the current time
        if (task.time === currentTime) {
            listItem.classList.add("current-time");
        }

        // Add the 'posted-task' class if the task is posted
        if (task.posted === '1') {
            listItem.classList.add("posted-task");
        }

        tasksList.appendChild(listItem);
    });
};