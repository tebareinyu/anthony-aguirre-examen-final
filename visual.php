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
            background-color: 
#f4f4f9;
            color: 
#333;
        }
        h1 {
            text-align: center;
            color: 
#4CAF50;
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
            border: 1px solid 
#ccc;
        }
        button {
            padding: 10px 15px;
            background-color: 
#4CAF50;
            color: 
white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: 
#45a049;
        }
        table {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid 
#ccc;
        }
        th {
            background-color: 
#4CAF50;
            color: 
white;
        }
        tr:hover {
            background-color: 
#f1f1f1;
        }
        .edit {
            background-color: 
#008CBA;
            color: 
white;
        }
        .delete {
            background-color: 
#f44336;
            color: 
white;
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

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="taskList"></tbody>
    </table>

    <script>
        const apiUrl = 'http://localhost/anthony-aguirre-examen-final/index.php?q=tasks';

        // Function to fetch and display tasks
        async function fetchTasks() {
            try {
                const response = await fetch(apiUrl);
                if (!response.ok) throw new Error("Network response was not ok");
                const tasks = await response.json();
                const taskList = document.getElementById("taskList");
                taskList.innerHTML = ''; // Clear existing tasks
                tasks.forEach(task => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${task.id}</td>
                        <td>${task.title}</td>
                        <td>${task.description}</td>
                        <td>${task.status}</td>
                        <td>${new Date(task.created_at).toLocaleString()}</td>
                        <td>
                            <button class="edit" onclick="openEditTask(${task.id}, '${task.title}', '${task.description}')">Edit</button>
                            <button class="delete" onclick="deleteTask(${task.id})">Delete</button>
                        </td>
                    `;
                    taskList.appendChild(row);
                });
            } catch (error) {
                console.error('Error fetching tasks:', error);
            }
        }

        // Function to add a new task
        async function addTask() {
            const title = document.getElementById("title").value.trim();
            const description = document.getElementById("description").value.trim();

            if (title === "" || description === "") {
                alert("Both Title and Description are required!");
                return;
            }

            try {
                const response = await fetch(apiUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ title, description })
                });

                if (!response.ok) throw new Error("Error adding task");

                document.getElementById("title").value = '';
                document.getElementById("description").value = '';
                fetchTasks();
            } catch (error) {
                console.error('Error adding task:', error);
                alert("An error occurred while adding the task.");
            }
        }

        // Function to delete a task
        async function deleteTask(id) {
            if (confirm("Do you want to delete this task?")) {
                try {
                    await fetch(`${apiUrl}/${id}`, {
                        method: "DELETE"
                    });
                    fetchTasks();
                } catch (error) {
                    console.error('Error deleting task:', error);
                    alert("An error occurred while deleting the task.");
                }
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

            try {
                const response = await fetch(`${apiUrl}/${id}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ title, description })
                });

                if (!response.ok) throw new Error("Error updating task");

                const addButton = document.getElementById("addTaskBtn");
                addButton.textContent = 'Add Task';
                addButton.onclick = addTask;

                document.getElementById("title").value = '';
                document.getElementById("description").value = '';
                fetchTasks();
            } catch (error) {
                console.error('Error updating task:', error);
                alert("An error occurred while updating the task.");
            }
        }

        // Initial fetch to load tasks
        fetchTasks();
    </script>

</body>
</html>