<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGREGAR ELEMENTOS A LA LISTA</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: 
#e8f0f2;
            color: 
#333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: 
#007bbd;
            margin-bottom: 30px;
        }
        .form-container {
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px 
rgba(0, 0, 0, 0.2);
            padding: 20px;
            background-color: 
#ffffff;
            border-radius: 8px;
        }
        input {
            padding: 10px;
            margin: 5px;
            width: 250px;
            border-radius: 5px;
            border: 1px solid 
#007bbd;
            transition: border-color 0.3s;
        }
        input:focus {
            border-color: 
#005a8b;
            outline: none;
        }
        button {
            padding: 10px 20px;
            background-color: 
#007bbd;
            color: 
white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: 
#005a8b;
        }
        table {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            border-collapse: collapse;
            border: 1px solid 
#007bbd;
            border-radius: 5px;
            overflow: hidden;
        }
        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid 
#ccc;
        }
        th {
            background-color: 
#007bbd;
            color: 
white;
        }
        tr:nth-child(even) {
            background-color: 
#f2f2f2;
        }
        tr:hover {
            background-color: 
#d1e7fd;
        }
        .edit, .delete {
            background-color: 
#28a745; /* Green */
            border: none;
            padding: 8px 12px;
            color: 
white;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete {
            background-color: 
#dc3545; /* Red */
        }
    </style>
</head>
<body>

    <h1>LISTA DE ACTIVIDADES</h1>

    <div class="form-container">
        <input id="title" placeholder="Task Title" aria-label="Task Title" />
        <input id="description" placeholder="Task Description" aria-label="Task Description" />
        <button id="addTaskBtn" onclick="addTask()">Add Task</button>
    </div>

    <table>
        <thead>
            <tr>
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