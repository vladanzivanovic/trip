<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Company name</a>
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <a class="nav-link" href="<?php echo $route->generate('logout'); ?>">Sign out</a>
        </li>
    </ul>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="/trips">
                            <span data-feather="home"></span>
                            Trips <span class="sr-only">(current)</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Trips</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#exampleModalCenter">
                        Add Trip
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Trip Name</th>
                        <th>Type</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($trips as $trip): ?>
                    <tr>
                        <td><?=$trip['id'] ?></td>
                        <td><?=$trip['name']?></td>
                        <td><?=$tripTypes[$trip['type']]; ?></td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-secondary view-route" data-id="<?=$trip['id'] ?>">View trip</button>
                                <button type="button" class="btn btn-secondary remove-trip" data-id="<?=$trip['id'] ?>">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<div class="modal fade trip-modal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add Trip</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form  id="add-trip-form" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="col">
                            <input type="text" class="form-control" name="name" placeholder="Trip name">
                            <span id="name-error" class="errors"></span>
                        </div>
                        <div class="col">
                            <select class="custom-select" name="type">
                                <option selected>Select trip type</option>
                                <?php foreach ($tripTypes as $value => $name): ?>
                                    <option value="<?=$value; ?>"><?=$name; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span id="type-error" class="errors"></span>
                        </div>
                    </div>
                    <div class="form-row mt-4">
                        <div class="col">
                            <div class="custom-file">
                                <input type="file" name="gpx" class="custom-file-input" id="customFile" accept="text/xml">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                                <span id="gpx-error" class="errors"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary add-trip">Add  Trip</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade route-modal" id="routeViewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add Trip</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body route-modal__body">
                <div id="map"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    window.tripTypes = JSON.parse('<?php echo json_encode($tripTypes); ?>');
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDkf4t3fqjti40Y4Y3PfBnmepts7Qbk4GM">
</script>

