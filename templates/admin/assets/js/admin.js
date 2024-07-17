$(document).ready(function () {
// Adiciona o evento de clique no corpo da página
    $('body').on('click', function (e) {
// Verifica se o clique não foi dentro do menu
        if (!$(e.target).closest('.menu').length) {
// Desmarca o input checkbox do menu
            $('#button-menu').prop('checked', false);
        }
    });

    $('#summernote').summernote({
        height: 300, // set editor height
        minHeight: null, // set minimum height of editor
        maxHeight: null, // set maximum height of editor
        focus: true, // set focus to editable area after initializing summernote
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
        ]
    });

    Fancybox.bind("[data-fancybox]", {
        hideScrollbar: false
    });




});






$(document).ready(function () {
    var url = $('table').attr('url');

    $.extend($.fn.dataTable.defaults, {
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json'
        }
    });

    $('#CategoriaDtTable').DataTable({

        order: [[1, 'asc']],
        layout: {
            top2End: {
                buttons: ['excelHtml5', 'csvHtml5', 'pdfHtml5']
            }
        }
    });


    $('#PostDtTable').DataTable({
        searching: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: url + 'admin/posts/datatable',
            type: 'POST'
        },
        order: [[0, 'desc']],
        columns: [
            null,
            {
                data: 'null',
                render: function (data, type, row) {
                    if (row[1]) {
                        return '<a data-fancybox data-caption ="Capa" class="overflow zoom" href="' + url + 'uploads/imagens/' + row[1] + '"><img src="' + url + 'uploads/imagens/' + row[1] + '" style="width:150px;"></a>';
                    } else {
                        return '<i class="fa-regular fa-images fs-1 text-secondary" style="color:#ff0000;"></i>';
                    }
                }
            },

            null,
            null,
            null,

            {
                data: 'null',
                render: function (data, type, row) {
                    if (row[5] === 1) {
                        return '<i class="fa-solid fa-circle" style="color: #63E6BE;"></i>';
                    } else {
                        return '<i class="fa-solid fa-circle" style="color: #ff0000;"></i>';
                    }
                }
            },

            {
                data: 'null',
                render: function (data, type, row) {
                    var html = '';

                    html += '<a class="btn" href="' + url + 'admin/posts/editar/' + row[0] + '"><i class="fa-regular fa-pen-to-square"></i></a> ';
                    html += '<a class="btn" href="' + url + 'admin/posts/deletar/' + row[0] + '"><i class="fa-regular fa-trash-can"></i></a> ';

                    return html;
                }
            }
        ]
    });
});