<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <title>Laravel File Upload</title>
        <style>
            .container {
                max-width: 90%;
            }
            dl, ol, ul {
                margin: 0;
                padding: 0;
                list-style: none;
            }

            #files {
                font-family: Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }

            #files td, #customers th {
                border: 1px solid #ddd;
                padding: 8px;
            }

            #files tr:nth-child(even){background-color: #f2f2f2;}

            #files tr:hover {background-color: #ddd;}

            #files th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: left;
                background-color: #04AA6D;
                color: white;
            }
        </style>
    </head>

    <body>
        <h3 class="text-center mt-2 mb-5">Upload File in Laravel</h3>
        @csrf
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <strong>{{ $message }}</strong>
        </div>
        @endif

        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="row">
            <div class="container mt-2">
                <a href="/upload-file" class="btn btn-primary btn-block mt-4">
                    Add Files
                </a>
            </div>    
            <div class="container mt-2">
                <table id="files" >
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Url</th>
                            <th>Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($files as $file)
                        <tr>
                            <td>{{$file->name}}</td>
                            <td>{{$file->file_path}}</td>
                            <td>{{$file->updated_at}}</td>
                            <td> 
                                <a href="{{$file->file_path}}" target="_blank" >{{"View"}}</a>
                                <a href="#" onclick="deletefile({{$file->id}},'{{$file->name}}')">{{"Delete"}}</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
<script>
    function deletefile(id, name){
    if (confirm("Are you sure want to delete file : " + name + " ?")) {
    $.ajax({
    type: "GET",
            url: '/delete-file',
            dataType: 'json',
            data: {
            "_token": "{{ csrf_token() }}",
                    id: id
            },
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
            if (data){
            swal.fire({
            text: "Success! file " + name + " has been deleted", icon: "success"
            }).then(function () {
            window.location.href = "/";
            });
            } else{
            alert('Failed to Delete')
            }
            }

    });
    }
    }
</script>