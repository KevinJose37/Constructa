<div class="col-xl-6">
    <div class="card">
        <div class="card-body">
            <h4 class="header-title">Nesting</h4>
            <p class="text-muted fs-14">
                Border styles, active styles, and table variants are not inherited by nested tables.
            </p>

            <div class="table-responsive-sm">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone Number</th>
                            <th>Date of Birth</th>
                            <th>Country</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Risa D. Pearson</td>
                            <td>336-508-2157</td>
                            <td>July 24, 1950</td>
                            <td>India</td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Phone Number</th>
                                            <th>Date of Birth</th>
                                            <th>Country</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Risa D. Pearson</td>
                                            <td>336-508-2157</td>
                                            <td>July 24, 1950</td>
                                            <td>Malaysia</td>
                                        </tr>
                                        <tr>
                                            <td>Ann C. Thompson</td>
                                            <td>646-473-2057</td>
                                            <td>January 25, 1959</td>
                                            <td>Canada</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>Linda G. Smith</td>
                            <td>606-253-1207</td>
                            <td>September 2, 1939</td>
                            <td>Belgium</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div><!-- end card body-->
    </div><!-- end card -->
</div><!-- end col -->