
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redmine Manager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css" type="text/css">
</head>
<body>
    
    <div class="container-fluid">
        <div class="row" id="toolBar">
            <button type="button" id="logOut">Logout</button>
            <span class="d-block ml-auto"><b id="welcome">Logged in as 
            </b></span>
            
        </div>
        <div class="row form-group">
            <div class="col-12">
                <h6 class="d-block my-3">Search in projects by category</h6>
            </div>
            <div class="col-12">
                <select class="form-control" id="projectTypeSelect">
                    <option value="-1">All</option>
                </select>
            </div>
        </div>
    </div>

    <hr class="w-100">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <div class="container-fluid">
                    <div class="row form-group">
                        <div class="col-12 text-center">
                            <h3 class="d-block mx-auto mt-3">RedMine Application</h3>
                        </div>
                        <div class="col-12">
                            <h6 class="d-block my-3">Projects in progress in the selected category</h6>
                        </div>
                        <div class="col-12">
                            <select class="form-control" id="projectSelect">
                                <option value="-1"></option>
                            </select>
                        </div>
                        <div class="col-12" id="buttonView">
            
                        </div>
            
                        <div class="col-12" id="taskView">
            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3" id="managerTaskView">
                <h4 class="text-center">Tasks that you created</h4>
            </div>
        </div>
    </div>
    

    <!-- The Modal -->
    <div class="modal" id="taskModal">
        <div class="modal-dialog">
        <div class="modal-content">
    
            <!-- Modal Header -->
            <div class="modal-header">
            <h4 class="modal-title">Add new task</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
    
            <!-- Modal body -->
            <div class="modal-body">
            
                <form>
                    <div class="form-group">
                        <label for="name">Task name:</label>
                        <input type="text" id="name" class="my-5"><br>
                        <div class="input-group">
                            <span class="input-group-text">Task description:</span>
                            <textarea class="form-control" placeholder="Enter your task here" id="desc"></textarea>
                        </div>

                        </textarea>
                        <label for="date" class="d-block mt-5">Deadline:</label>
                        <input type="date" id="date">
                    </div>
                </form>

            </div>
    
            <!-- Modal footer -->
            <div class="modal-footer">
            <button type="button" class="btn btn-success" data-dismiss="modal" id="taskSave">Save</button>
            </div>
    
        </div>
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal" id="devModal">
        <div class="modal-dialog">
        <div class="modal-content">
    
            <!-- Modal Header -->
            <div class="modal-header">
            <h4 class="modal-title">Add new developer to the project</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
    
            <!-- Modal body -->
            <div class="modal-body">
            
                <form>
                    <select id="developers" class="form-control">
                        
                    </select>
                </form>

            </div>
    
            <!-- Modal footer -->
            <div class="modal-footer">
            <button type="button" class="btn btn-success" data-dismiss="modal" id="devSave">Save</button>
            </div>
    
        </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="js/index.js"></script>
</body>
</html>