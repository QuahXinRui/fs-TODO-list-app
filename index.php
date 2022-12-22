<?php

    $database = new PDO('mysql:host=devkinsta_db;dbname=to_do_list','root','DfLySbHg6Lx5lPAq');


    $query = $database->prepare('SELECT * FROM tasks');
    $query->execute();
    
    
    $tasks = $query->fetchAll();


    if (
      isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST'
      ) {
          if ($_POST['action'] === 'add'){
              $statement = $database->prepare(
                  "INSERT INTO tasks (`name`,`completed`) VALUES (:name,:completed)"     
              );
              $statement->execute([
               'name' => $_POST['task'],
               'completed' => 0
             ]);
     
              header('Location: /');
              exit;
          }

        if($_POST['action'] === 'delete') {
            $statement = $database->prepare(
                'DELETE FROM tasks WHERE id= :id'
            );
            $statement->execute([
                'id' => $_POST['task_id']
            ]);

            header('Location: /');
            exit;
        }

        if($_POST['action'] === 'update'){
          if ($_POST['completed'] === '0'){
            $statement = $database->prepare('UPDATE tasks SET completed = 1 WHERE id = :id');
          }else {
            $statement = $database->prepare('UPDATE tasks SET completed = 0 WHERE id = :id');
          }
          $statement->execute(['id' => $_POST['task_id']]);
    
    
          header('Location: /');
          exit;
        }

    }



?>
<!DOCTYPE html>
<html>
  <head>
    <title>TODO App</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"
    />
    <style type="text/css">
      body {
        background: #f1f1f1;
      }
    </style>
  </head>
  <body>
  <div class="card rounded shadow-sm mx-auto my-4" style="max-width: 500px;">
    <div class="card-body">
        <h3 class="card-title mb-3">My Todo List</h3>
        <div class="mt-4">
        <?php foreach ($tasks as $task): ?>
            <div class="mb-2 d-flex justify-content-between gap-3">
              <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
              <?php if($task['completed'] === '1') : ?>
                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="completed" value="<?php echo $task['completed']; ?>" />
                    <button class="btn btn-sm btn-success">
                        <i class="bi bi-check-square"></i>
                    </button>
                <?php else : ?>
                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>" />  
                    <input type="hidden" name="action" value="update" />
                    <input type="hidden" name="completed" value="<?php echo $task['completed']; ?>" />
                    <button class="btn btn-sm">
                      <i class="bi bi-square"></i>
                    </button>
                  <?php endif; ?>
                </form>
                <span class="mt-1" style="margin-left: -300px"><?php echo $task['name']; ?></span>
                <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                    <input type="hidden" name="action" value="delete">
                    <button class="btn btn-sm btn-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
        </div>

        <div class="mt-4 ">
          <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>" class="d-flex justify-content-between align-items-center">
            <input
                type="text"
                class="form-control"
                placeholder="Add new item..."
                name="task"
                required
            />
            <input type="hidden" name="action" value="add">
            <button class="btn btn-primary btn-sm rounded ms-2">Add</button>
          </form>
        </div>
      </div>
    </div>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
