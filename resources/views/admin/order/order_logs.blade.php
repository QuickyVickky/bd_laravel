<!----order logs Modal start---->
<div class="modal fade" id="logsModal" tabindex="-1" role="dialog" aria-labelledby="logsexampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="logsexampleModalLabel">Order Logs</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <table id="table_order_logs" class="table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th width="8%">#</th>
                            <th>Logs</th>
                            <th>DateTime</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!----order logs  Modal end---->
<script type="text/javascript">
    var table_order_logs;
    $.fn.dataTable.ext.errMode = 'none';

    function seeLogs(oid) {
		$("#logsModal").modal("show");
        table_order_logs = $('#table_order_logs').DataTable({
            processing: false,
            destroy: true,
            paging: true,
            searching: false,
            lengthMenu: [
                [10, 50, 100],
                [10, 50, 100]
            ],
            pageLength: 10,
            order: [
                [0, 'desc']
            ],
            serverSide: true,
            ajax: {
                "url": "{{ route('get-order-logs') }}",
                "type": "get",
                "data": function(data) {
                    data.oid = oid;
                },
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': [-1],
                }]
            },
        });
    }

</script>
