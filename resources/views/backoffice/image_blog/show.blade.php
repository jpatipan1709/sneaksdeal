<div class="modal" id="modal-default" style="">
    <div class="modal-dialog" style="width: 80%!important;max-width: 100%!important;">
        <div class="modal-content" style="width: 100%!important;">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">จัดการอัลบั้ม</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <label class="label label-success" id="labelAlertSuccess"></label>
                        <label class="label label-danger" id="labelAlertNot"></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="button" id="btnAdd" style="float: right" class="btn btn-primary"
                                onclick="AddAlbum()"><i
                                    class="fa fa-plus"></i> เพิ่มอัลบั้ม
                        </button>
                        <br>
                    </div>
                </div>
                <div class="row" id="divTable">
                    <h5>ตารางแสดงข้อมูลอัลบั้ม</h5>
                    <div class="col-12">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>ลำดับ</th>
                                <th>ชื่ออัลบั้ม</th>
                                <th>จัดการ</th>
                            </tr>
                            </thead>
                            <tbody id="rowData">
                            @foreach(@$getData AS $key => $r)
                                <tr>
                                    <td>{{($key +1)}}</td>
                                    <td>{{$r->name_album}}</td>
                                    <td>
                                        <button type="button" class="btn btn-outline-warning"
                                                onclick="editAlbum('{{$r->id}}','{{$r->name_album}}')"><i
                                                    class="fa fa-edit"></i></button>
                                        <button type="button" class="btn btn-outline-danger"
                                                onclick="delAlbum('{{$r->id}}')"><i
                                                    class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <form action="{!! url('backoffice/image_blog/album/create') !!}" id="fromCreate" style="display: none"
                      method="post">
                    <div class="row">
                        @csrf
                        {!! inputText('ชื่ออัลบั้ม ', 'name_album', 'name_album', '', 'md-12', 'required', '') !!}
                        <div class="col-md-12">
                            <center>
                                <button type="button" onclick="submitAlbum()" class="btn btn-success"><i
                                            class="fa fa-save"></i> Save
                                </button>
                            </center>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<input type="hidden" id="token" value="{{csrf_token()}}">
<script>
    function editAlbum(id, name) {
        AddAlbum(id, name)

    }


        
           
           
    
    function delAlbum(id) {
        swal({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover this imaginary album!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
    swal("Poof! Your imaginary album has been deleted!", {
      icon: "success",
    });
     var token = $('#token').val();
        $.ajax({
            url:'/backoffice/image_blog/album/delete/'+id,
              type: 'POST',
                data: {_token:token},
                success: function (res) {
                            var html_ = '';
                        var key_ = 1;
                        $.each(res.data ,function (i,v) {
                            html_ += '<tr>' +
                                '<td>'+(parseFloat(i)+parseFloat(key_))+'</td>' +
                                '<td>'+v.name_album+'</td>' +
                                '<td>  <button type="button" class="btn btn-outline-warning"\n' +
                                '                                                onclick="editAlbum(\''+v.id+'\',\''+v.name_album+'\')"><i\n' +
                                '                                                    class="fa fa-edit"></i></button>\n' +
                                '                                        <button type="button" class="btn btn-outline-danger"\n' +
                                '                                                onclick="delAlbum(\''+v.id+'\')"><i\n' +
                                '                                                    class="fa fa-trash"></i></button></td>' +
                                '</tr>';
                        })
                        $('#rowData').html(html_);
        


                }
        })
  } else {
  swal("Your imaginary album is safe!");
    }
    });
    }

    function AddAlbum(edit = '', name = '') {
        $('#divTable').fadeOut();
        $('#fromCreate').fadeIn();
        $('#btnAdd').html('<i class="fa fa-arrow-left"></i> ย้อนกลับ');
        $('#btnAdd').attr('onclick', 'HistoryBack()');
        if (edit != '') {
            $('#name_album').val(name);
            $('#fromCreate').attr('action', '/backoffice/image_blog/album/update/' + edit);
        }else{
            $('#fromCreate').attr('action', '/backoffice/image_blog/album/create');

            $('#name_album').val("");

        }
    }

    function HistoryBack() {
        $('#divTable').fadeIn();
        $('#fromCreate').fadeOut();
        $('#btnAdd').html('<i class="fa fa-plus"></i> เพิ่มอัลบั้ม');
        $('#btnAdd').attr('onclick', 'AddAlbum()');
    }

    function submitAlbum() {
        var name = $('#name_album').val();
        if (name == '') {
            $('#name_album').focus();
        } else {
            $.ajax({
                url: $('#fromCreate').attr('action'),
                type: 'POST',
                data: $('#fromCreate').serialize(),
                success: function (res) {
                    $('#labelAlertSuccess').html("");
                    $('#labelAlertNo').html("");
                    if (res.status == 'true') {
                        $('#labelAlertSuccess').html("เพิ่มอัลบั้มสำเร็จ");
                        $('#divTable').fadeIn();
                        $('#fromCreate').hide();
                        var html_ = '';
                        var key_ = 1;
                        $.each(res.data ,function (i,v) {
                            html_ += '<tr>' +
                                '<td>'+(parseFloat(i)+parseFloat(key_))+'</td>' +
                                '<td>'+v.name_album+'</td>' +
                                '<td>  <button type="button" class="btn btn-outline-warning"\n' +
                                '                                                onclick="editAlbum(\''+v.id+'\',\''+v.name_album+'\')"><i\n' +
                                '                                                    class="fa fa-edit"></i></button>\n' +
                                '                                        <button type="button" class="btn btn-outline-danger"\n' +
                                '                                                onclick="delAlbum(\''+v.id+'\')"><i\n' +
                                '                                                    class="fa fa-trash"></i></button></td>' +
                                '</tr>';
                        })
                        $('#rowData').html(html_);
                        $('#btnAdd').html('<i class="fa fa-plus"></i> เพิ่มอัลบั้ม');
                        $('#btnAdd').attr('onclick', 'AddAlbum()');
                    } else {
                        $('#labelAlertNo').html("อัลบั้มนี้มีชื่ออยู่แล้ว");
                    }
                    setTimeout(function () {
                        $('#labelAlertSuccess').hide();
                        $('#labelAlertNo').hide();
                    }, 3000);


                }
            })
        }
    }
</script>
