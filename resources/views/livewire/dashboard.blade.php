<div>
    <div class="mt-2">
        <select wire:model="selectedProjectId" wire:change="loadMessages"
            class="form-select form-select-sm w-25 d-inline-block mb-3">
            <option value="">Proyección general</option>
            <option value="1">Proyecto 1</option>
            <option value="2">Proyecto 2</option>
            <option value="3">Proyecto 3</option>
        </select>
    </div>
    <div class="row row-cols-1 row-cols-xxl-6 row-cols-lg-3 row-cols-md-2">
        <div class="col">
            <div class="card widget-icon-box">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="flex-grow-1 overflow-hidden">
                            <h5 class="text-muted text-uppercase fs-13 mt-0" title="Number of Customers">Usuarios</h5>
                            <h3 class="my-3">54,214</h3>
                            <p class="mb-0 text-muted text-truncate">
                                <span class="badge bg-success me-1"><i class="ri-arrow-up-line"></i> 2,541</span>
                                <span>Since last month</span>
                            </p>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span
                                class="avatar-title text-bg-success rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                <i class="ri-group-line"></i>
                            </span>
                        </div>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->


        <div class="col">
            <div class="card widget-icon-box">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="flex-grow-1 overflow-hidden">
                            <h5 class="text-muted text-uppercase fs-13 mt-0" title="Number of Orders">Ordenes de compra
                            </h5>
                            <h3 class="my-3">7,543</h3>
                            <p class="mb-0 text-muted text-truncate">
                                <span class="badge bg-danger me-1"><i class="ri-arrow-down-line"></i> 1.08%</span>
                                <span>Since last month</span>
                            </p>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span
                                class="avatar-title text-bg-info rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                <i class="ri-shopping-basket-2-line"></i>
                            </span>
                        </div>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->

        <div class="col">
            <div class="card widget-icon-box">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="flex-grow-1 overflow-hidden">
                            <h5 class="text-muted text-uppercase fs-13 mt-0" title="Average Revenue">Presupuesto
                                asignado</h5>
                            <h3 class="my-3">$9,254</h3>
                            <p class="mb-0 text-muted text-truncate">
                                <span class="badge bg-danger me-1"><i class="ri-arrow-down-line"></i> 7.00%</span>
                                <span>Since last month</span>
                            </p>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span
                                class="avatar-title text-bg-danger rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                <i class="ri-money-dollar-circle-line"></i>
                            </span>
                        </div>
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->


        <div class="col">
            <div class="card widget-icon-box">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="flex-grow-1 overflow-hidden">
                            <h5 class="text-muted text-uppercase fs-12 mt-0" title="Conversation Ration">Gastos ordenes
                                de compra</h5>
                            <h3 class="my-3">$168.5k</h3>
                            <p class="mb-0 text-muted text-truncate">
                                <span class="badge bg-success me-1"><i class="ri-arrow-up-line"></i> 18.34%</span>
                                <span>Since last month</span>
                            </p>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title text-bg-dark rounded rounded-3 fs-3 widget-icon-box-avatar">
                                <i class="ri-wallet-3-line"></i>
                            </span>
                        </div>
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div> <!-- end row -->


    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Total Sales</h4>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="ri-more-2-fill"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-animated dropdown-menu-end">
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">Profit</a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">Action</a>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div id="average-sales" class="apex-charts mb-3 mt-n5" data-colors="#4254ba"></div>

                    <h5 class="mb-1 mt-0 fw-normal">Brooklyn, New York</h5>
                    <div class="progress-w-percent">
                        <span class="progress-value fw-bold">72k </span>
                        <div class="progress progress-sm">
                            <div class="progress-bar" role="progressbar" style="width: 72%;" aria-valuenow="72"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <h5 class="mb-1 mt-0 fw-normal">The Castro, San Francisco</h5>
                    <div class="progress-w-percent">
                        <span class="progress-value fw-bold">39k </span>
                        <div class="progress progress-sm">
                            <div class="progress-bar" role="progressbar" style="width: 39%;" aria-valuenow="39"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <h5 class="mb-1 mt-0 fw-normal">Kovan, Singapore</h5>
                    <div class="progress-w-percent mb-0">
                        <span class="progress-value fw-bold">61k </span>
                        <div class="progress progress-sm">
                            <div class="progress-bar" role="progressbar" style="width: 61%;" aria-valuenow="61"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->
        <div class="col-lg-8">
            <div class="card">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Revenue</h4>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="ri-more-2-fill"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-animated dropdown-menu-end">
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">Profit</a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">Action</a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="bg-light-subtle border-top border-bottom border-light">
                        <div class="row text-center">
                            <div class="col">
                                <p class="text-muted mt-3"><i class="ri-donut-chart-fill"></i> Current Week</p>
                                <h3 class="fw-normal mb-3">
                                    <span>$1705.54</span>
                                </h3>
                            </div>
                            <div class="col">
                                <p class="text-muted mt-3"><i class="ri-donut-chart-fill"></i> Previous Week</p>
                                <h3 class="fw-normal mb-3">
                                    <span>$6,523.25 <i class="ri-corner-right-up-fill text-success"></i></span>
                                </h3>
                            </div>
                            <div class="col">
                                <p class="text-muted mt-3"><i class="ri-donut-chart-fill"></i> Conversation</p>
                                <h3 class="fw-normal mb-3">
                                    <span>8.27%</span>
                                </h3>
                            </div>
                            <div class="col">
                                <p class="text-muted mt-3"><i class="ri-donut-chart-fill"></i> Customers</p>
                                <h3 class="fw-normal mb-3">
                                    <span>69k <i class="ri-corner-right-down-line text-danger"></i></span>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div dir="ltr">
                        <div id="revenue-chart" class="apex-charts mt-1" data-colors="#4254ba,#17a497"></div>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div>
    <!-- end row -->

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- Daterangepicker js -->
    <script src="assets/vendor/daterangepicker/moment.min.js"></script>
    <script src="assets/vendor/daterangepicker/daterangepicker.js"></script>

    <!-- Apex Charts js -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>

    <!-- Vector Map js -->
    <script src="assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="assets/vendor/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>

    <!-- Dashboard App js -->
    <script src="assets/js/pages/demo.dashboard.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>


</div>
