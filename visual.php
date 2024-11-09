<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List API</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid 
#ccc;
            display: flex;
            justify-content: space-between;
        }
        button {
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <h1>To-Do List</h1>
    <input id="title" placeholder="Task Title" />
    <input id="description" placeholder="Task Description" />
    <button onclick="addTask()">Add Task</button>
    <ul id="taskList"></ul>

    <script>
        const apiUrl = 'http://localhost/anthony-aguirre-examen-final/index.php?q=tasks';

        // Function to fetch and display tasks
        async function fetchTasks() {
            const response = await fetch(apiUrl);
            const tasks = await response.json();
            const taskList = document.getElementById("taskList");
            taskList.innerHTML = ''; // Clear existing tasks
            tasks.forEach(task => {
                const listItem = document.createElement("li");
                listItem.innerHTML = `
                    <span>
                        <strong>${task.title}</strong>: ${task.description}
                    </span>
                    <span>
                        <button onclick="deleteTask(${task.id})">Delete</button>
                        <button onclick="openEditTask(${task.id}, '${task.title}', '${task.description}')">Edit</button>
                    </span>
                `;
                taskList.appendChild(listItem);
            });
        }

        // Function to add a new task
        async function addTask() {
            const title = document.getElementById("title").value;
            const description = document.getElementById("description").value;

            if (title.trim() === "") {
                alert("Titulo requerido");
                return;
            }

            await fetch(apiUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ title, description })
            });

            document.getElementById("title").value = '';
            document.getElementById("description").value = '';
            fetchTasks();
        }

        // Function to delete a task
        async function deleteTask(id) {
            if (confirm("deseas eliminar la task")) {
                await fetch(`${apiUrl}/${id}`, {
                    method: "DELETE"
                });
                fetchTasks();
            }
        }

        // Function to open edit task
        function openEditTask(id, title, description) {
            document.getElementById("title").value = title;
            document.getElementById("description").value = description;

            const addButton = document.querySelector("button[onclick='addTask()']");
            addButton.textContent = 'Task Actualizada';
            addButton.onclick = () => updateTask(id);
        }

        // Function to update a task
        async function updateTask(id) {
            const title = document.getElementById("title").value;
            const description = document.getElementById("description").value;

            await fetch(`${apiUrl}/${id}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ title, description })
            });

            const addButton = document.querySelector("button[onclick='addTask()']");
            addButton.textContent = 'Add Task';
            addButton.onclick = addTask;

            document.getElementById("title").value = '';
            document.getElementById("description").value = '';
            fetchTasks();
        }

        // Initial fetch to load tasks
        fetchTasks();
    </script>
</body>
</html>