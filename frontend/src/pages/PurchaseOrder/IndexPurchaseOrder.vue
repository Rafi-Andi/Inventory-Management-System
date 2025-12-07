<template>
        <div class="container-fluid">
        <div class="page-header">
            <h1>Purchase Orders</h1>
            <a href="create.html" class="btn btn-primary">Create Request</a>
        </div>
        <hr>

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Supplier</th>
                    <th>Warehouse</th>
                    <th>Requester</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>PT Maju Jaya</td>
                    <td>Gudang Jakarta</td>
                    <td>John (Manager)</td>
                    <td>04 Dec 2024</td>
                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                    <td>
                        <a href="detail.html?id=1" class="btn btn-info btn-sm">Detail</a>
                        <button class="btn btn-success btn-sm">Approve</button>
                        <button class="btn btn-danger btn-sm">Reject</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>CV Berkah Sentosa</td>
                    <td>Gudang Bandung</td>
                    <td>Jane (Manager)</td>
                    <td>03 Dec 2024</td>
                    <td><span class="badge bg-primary">Approved</span></td>
                    <td>
                        <a href="detail.html?id=2" class="btn btn-info btn-sm">Detail</a>
                        <button class="btn btn-success btn-sm">Receive</button>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>UD Sumber Makmur</td>
                    <td>Gudang Surabaya</td>
                    <td>Bob (Manager)</td>
                    <td>02 Dec 2024</td>
                    <td><span class="badge bg-success">Received</span></td>
                    <td>
                        <a href="detail.html?id=3" class="btn btn-info btn-sm">Detail</a>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>PT Maju Jaya</td>
                    <td>Gudang Jakarta</td>
                    <td>John (Manager)</td>
                    <td>01 Dec 2024</td>
                    <td><span class="badge bg-danger">Rejected</span></td>
                    <td>
                        <a href="detail.html?id=4" class="btn btn-info btn-sm">Detail</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>