<!DOCTYPE html>
<html>

<head>
    <title>PDF feltöltő form</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <style>
        .success-modal .modal-content {
            background-color: #5cb85c;
            color: #fff;
        }

        .success-modal .modal-header,
        .success-modal .modal-footer {
            border-color: #4cae4c;
        }

        .success-modal .modal-header h5 {
            color: #fff;
        }

        .fail-modal .modal-content {
            background-color: #d9534f;
            color: #fff;
        }

        .fail-modal .modal-header,
        .fail-modal .modal-footer {
            border-color: #d43f3a;
        }

        .fail-modal .modal-header h5 {
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <form id="pdf-upload-form" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="pdf-file" class="form-label">Válasszon egy PDF fájlt</label>
                        <input type="file" class="form-control" id="pdf-file" name="pdf-file" accept=".pdf" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Feltöltés</button>
                </form>
            </div>
        </div>
    </div>

    <div id="loader" style="display:none;" class="text-center">
        <div class="spinner-border" role="status"></div>
    </div>
    <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modal cím</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tartalom</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezárás</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {

            $("#pdf-upload-form").on("submit", function(e) {
                e.preventDefault();
                var fileInput = document.getElementById('pdf-file');
                var filePath = fileInput.value;
                var allowedExtensions = /(\.pdf)$/i;
                if (!allowedExtensions.exec(filePath)) {
                    alert('Hiba: csak PDF fájlokat tölthet fel!');
                    fileInput.value = '';
                    return false;
                }
                var form_data = new FormData();
                form_data.append('pdf-file', fileInput.files[0]);
                $.ajax({
                    url: 'pdf_upload.php',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(response) {
                        var json = $.parseJSON(response)
                        $('#loader').hide();
                        $('#myModal').modal('show').addClass('success-modal')
                        $('#myModal .modal-title').html('Sikeres üzenet küldés');
                        $('#myModal .modal-body').html(json.message);
                        $('#pdf-file').val('');
                    },
                    error: function(xhr, status, error) {
                        $('#loader').hide();
                        $('#myModal').modal('show').addClass('fail-modal')
                        $('#myModal .modal-title').html('Sikeretlen üzenet küldés');
                        $('#myModal .modal-body').html('Hiba történt a fájl feltöltése során!');
                    }
                });
            });
        });
    </script>
</body>

</html>