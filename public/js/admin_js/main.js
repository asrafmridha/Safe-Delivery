const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});


$(function(){
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#dataTable').DataTable();

    $('#dataTableOrder').DataTable({
        "paging"        : true,
        "lengthChange"  : false,
        "searching"     : false,
        "ordering"      : true,
        "info"          : true,
        "autoWidth"     : false,
    });

    $('.alert').delay(5000).slideUp('slow', function(){
        $(this).alert('close');
    });

    if ($(".select2").length > 0) $('.select2').select2();

    $(document).on("wheel", "input[type=number]", function (e) {
        $(this).blur();
    });

    if ($("input[data-bootstrap-switch]").length > 0) {
        $("input[data-bootstrap-switch]").each(function(){
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });
    }


    $(document).on('click', '.setNavBarPushMenu', function(){
        if($('.sidebar-collapse').length){
            localStorage.setItem('sidebar-collapse', '1');
        }else{
            localStorage.setItem('sidebar-collapse', '0');
        }
    });

    var sidebarCollapse = localStorage.getItem('sidebar-collapse');
    if(sidebarCollapse){
        if(sidebarCollapse == '1'){
            $(".sidebar-mini" ).addClass( "sidebar-collapse")
        }else{
            $(".sidebar-mini" ).removeClass( "sidebar-collapse");
        }
    }

});

function returnNumber(value){
    value = parseFloat(value);
    return !isNaN(value) ?  value : 0;
}
