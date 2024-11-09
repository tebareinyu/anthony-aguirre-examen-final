<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGREGAR ELEMENTOS A LA LISTA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #4CAF50;
        }
        .form-container {
            margin-bottom: 20px;
            text-align: center;
        }
        input {
            padding: 10px;
            margin: 5px;
            width: 200px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        ul {
            list-style-type: none;
            padding: 0;
            max-width: 600px;
            margin: 0 auto;
        }
        li {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            display: flex;
            justify-content: space-between;
            background-color: #fff;
            border-radius: 4px;
        }
        li span {
            display: flex;
            align-items: center;
        }
        li span button {
            margin-left: 10px;
            background-color: #f44336;
        }
        li span button.edit {
            background-color: #008CBA;
        }
    </style>
</head>
<body>

    <h1>To-Do List</h1>

    <div class="form-container">
        <input id="title" placeholder="Task Title" aria-label="Task Title" />
        <input id="description" placeholder="Task Description" aria-label="Task Description" />
        <button id="addTaskBtn" onclick="addTask()">Add Task</button>
    </div>

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
                        <button class="edit" onclick="openEditTask(${task.id}, '${task.title}', '${task.description}')">Edit</button>
                        <button onclick="deleteTask(${task.id})">Delete</button>
                    </span>
                `;
                taskList.appendChild(listItem);
            });
        }

        // Function to add a new task
        async function addTask() {
            const title = document.getElementById("title").value.trim();
            const description = document.getElementById("description").value.trim();

            if (title === "" || description === "") {
                alert("Both Title and Description are required!");
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
            if (confirm("Do you want to delete this task?")) {
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

            const addButton = document.getElementById("addTaskBtn");
            addButton.textContent = 'Update Task';
            addButton.onclick = () => updateTask(id);
        }

        // Function to update a task
        async function updateTask(id) {
            const title = document.getElementById("title").value.trim();
            const description = document.getElementById("description").value.trim();

            if (title === "" || description === "") {
                alert("Both Title and Description are required!");
                return;
            }

            await fetch(`${apiUrl}/${id}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ title, description })
            });

            const addButton = document.getElementById("addTaskBtn");
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
