<?php include_once './header.php'; ?>

    <div class="container">
        <div class="row">
            <div class="col">

                <h2 class="text-center">Munkaidő lista</h2>

                <table id="workTimeAjax" class="table table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>Dátum</th>
                        <th>Dolgozó neve</th>
                        <th class="nosort">Munkaidő kezdete</th>
                        <th class="nosort">Munkaidő vége</th>
                        <th class="nosort">Ledolgozott idő</th>
                        <th class="nosort">Vasárnapi pótlék</th>
                        <th class="nosort">Műveletek</th>
                    </tr>
                    </thead>
                    <tbody></tbody>

                </table>

            </div>
        </div>

        <div class="row">
            <div id="showErrorMessage" class="text-center alert"></div>
        </div>

        <div class="row">
            <div class="col">

                <h2 class="text-center">Munkaidő adatok felvitele</h2>

                <form id="workTimeForm" class="needs-validation" novalidate >

                    <div class="form-group row">
                        <label for="employee_id" class="col-sm-2 col-form-label">Dolgozó: </label>
                        <div class="col-sm-10">
                            <select class="form-control" id="employee_id" name="employee" required>
                                <option value="101">Kis Béla</option>
                                <option value="102">Nagy Sándor</option>
                                <option value="103">Farkas Béla</option>
                                <option value="104">Virág Hajnalka</option>
                                <option value="105">Piros Ilona</option>
                            </select>
                            <div class="invalid-tooltip">
                                Válassz dolgozót!
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="date" class="col-sm-2 col-form-label">Dátum: </label>
                        <div class="input-group col-sm-10 date" id="">
                            <input id="date" name="date" type="text" class="form-control" placeholder="2018-06-29" required />
                            <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                            </div>
                            <div class="invalid-tooltip">
                                Add meg a dátumot!
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col offset-2">
                            <div class="form-group">
                                <label for="start_time" class="col-form-label">Munkaidő kezdete: </label>
                                <div class="input-group date">
                                    <input id="start_time" type="text" name="start_time" class="form-control" placeholder="08:00" required />
                                    <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clock"></i>
                                            </span>
                                    </div>
                                    <div class="invalid-tooltip">
                                        Add meg a munkaidő kezdetét!
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="end_time" class="col-form-label">Munkaidő vége: </label>
                                <div class="input-group date">
                                    <input id="end_time" type="text" name="end_time" class="form-control" placeholder="16:00" required />
                                    <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clock"></i>
                                            </span>
                                    </div>
                                    <div class="invalid-tooltip">
                                        Add meg a munkaidő végét!
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="total_work_time" class="col-sm-2 col-form-label">Ledolgozott idő: </label>
                        <div class="col-sm-10">
                            <input id="total_work_time" class="form-control" type="text" name="total_work_time" readonly />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="sunday_bonus" class="col-sm-2 col-form-label">Vasárnapi pótlék: </label>
                        <div class="col-sm-10">
                            <input id="sunday_bonus" class="form-control" type="text" name="sunday_bonus" readonly />
                        </div>
                    </div>

                    <div class="text-right">
                        <button id="workTimeSubmit" type="submit" class="btn btn-primary">Mentés</button>
                    </div>

                    <div id="calculatedTime" class="alert alert-info"></div>

                </form>

            </div>
        </div>
    </div>

<?php include_once './footer.php'; ?>