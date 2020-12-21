<style>
    tr th:only-child {
        width: 100%;
    }

    .table-result {
        max-height: 450px;
    }

    .table-result {
        overflow-y: auto
    }

    .table-result thead th {
        position: sticky;
        background-color: #e9ecef;
        top: -1%;
    }

    td .form-control {
        width: 120px !important;
    }

    .swal2-container {
        z-index: 1110 !important;
    }

</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<div class="modal" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">ข้อมูลโรงแรม</h4>

                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="table-responsive table-result">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                        </tr>
                        </thead>
                        <tbody class="top-10  t-body">

                        @foreach($data AS $key => $v)
                            <tr class="tr-row{{($key +1)}}">
                                <td>{{($key +1)}}</td>
                                <td>{{$v->name_main}}</td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
</div>
