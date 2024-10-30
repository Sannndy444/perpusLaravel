<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Publisher</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <x-navbar></x-navbar>

    <div class="container p-5 my-5 border">
        <div class="row">
            <div class="col">
                <h1>Add Publisher</h1>
            </div>
        </div>
        <div class="r">
            <div class="col">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <form action="{{ route('publishers.store') }}" method="POST">
                    @csrf
                        <div class="input-group mb-3">
                            <button class="btn btn-outline-success" type="submit" id="button-addon1">Add</button>
                            <input type="text" class="form-control" placeholder="Publisher Name" name="publisher_name" aria-label="Example text with button addon" aria-describedby="button-addon1">
                        </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
